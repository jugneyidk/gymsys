$(document).ready(function () {
  function cargaListadoRoles() {
    const datos = new FormData();
    datos.append("accion", "listado_roles");
    enviaAjax(datos);
  }

  cargaListadoRoles();

  function validarKeyPress(e, regex) {
    if (!regex.test(e.key)) {
      e.preventDefault();
    }
  }

  function validarKeyUp(regex, input, mensaje, textoError) {
    const isValid = regex.test(input.val());
    input.toggleClass("is-invalid", !isValid).toggleClass("is-valid", isValid);
    mensaje.text(isValid ? "" : textoError);
    return isValid;
  }

  function validarEnvio(formId) {
    let esValido = true;
    const form = $(formId);
    const sufijo = formId === "#f2" ? "_modificar" : "";

    const validaciones = [
      {
        regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
        id: "nombre",
        errorMsg: "Solo letras y espacios (1-50 caracteres)",
      },
    ];

    validaciones.forEach(({ regex, id, errorMsg }) => {
      esValido &= validarKeyUp(
        regex,
        form.find(`#${id}${sufijo}`),
        form.find(`#s${id}${sufijo}`),
        errorMsg
      );
    });

    return esValido;
  }

  $("#btnCrearRol").on("click", function () {
    $("#modalCrearLabel").text("Nuevo Rol");
  });
  $("#btnSubmit").on("click", function (event) {
    event.preventDefault();
  });

  $("#btnSubmit").on("click", function () {
    if (!$("#id_rol").val()) {
      if (validarEnvio("#form_incluir")) {
        const datos = new FormData($("#form_incluir")[0]);
        datos.append("accion", "incluir");
        enviaAjax(datos);
        $("#modalCrear").modal("hide");
      }
    } else {
      if (validarEnvio("#form_incluir")) {
        const datos = new FormData($("#form_incluir")[0]);
        datos.append("accion", "modificar");
        enviaAjax(datos);
        $("#modalCrear").modal("hide");
      }
    }
  });

  $("#btnModificar").on("click", function () {
    if (validarEnvio("#f2")) {
      const datos = new FormData($("#f2")[0]);
      enviaAjax(datos);
    }
  });
  function llenarFormularioModificar(permisos) {
    $("#nombre").val(permisos[0].nombre_rol);
    permisos.forEach((modulo) => {
      let pantalla = modulo.nombre_modulo;
      $(`${"#c" + pantalla}`).prop(
        "checked",
        modulo.crear === 1 ? true : false
      );
      $(`${"#r" + pantalla}`).prop("checked", modulo.leer === 1 ? true : false);
      $(`${"#u" + pantalla}`).prop(
        "checked",
        modulo.actualizar === 1 ? true : false
      );
      $(`${"#d" + pantalla}`).prop(
        "checked",
        modulo.eliminar === 1 ? true : false
      );
    });
  }
  function enviaAjax(datos) {
    $.ajax({
      async: true,
      url: "",
      type: "POST",
      contentType: false,
      data: datos,
      processData: false,
      cache: false,
      beforeSend: function () {},
      timeout: 10000,
      success: function (respuesta) {
        try {
          const lee = JSON.parse(respuesta);
          if (lee.devol === "listado_roles") {
            console.log(lee.respuesta);
            actualizarListadoRoles(lee.respuesta);
          } else if (lee.devol === "consultar_rol") {
            llenarFormularioModificar(lee.respuesta);
            $("#modalCrearLabel").text("Modificar Rol");
            $("#modalCrear").modal("show");
            console.log(lee.respuesta);
          } else if (lee.ok) {
            Swal.fire("Éxito", "Operación realizada con éxito", "success");
            cargaListadoRoles();
          } else {
            Swal.fire("Error", lee.mensaje, "error");
          }
        } catch (error) {
          Swal.fire("Error", "Algo salió mal", "error");
          console.log(error);
        }
      },
      error: function (request, status, err) {
        const errorMsg =
          status === "timeout"
            ? "Servidor ocupado, Intente de nuevo"
            : "Error al procesar la solicitud";
        Swal.fire("Error", errorMsg, "error");
      },
      complete: function () {},
    });
  }
  function cargarDatosRol(id_rol) {
    const datos = new FormData();
    datos.append("accion", "consultar_rol");
    datos.append("id_rol", id_rol);
    enviaAjax(datos);
  }
  function eliminarRol(id_rol) {
    Swal.fire({
      title: "¿Estás seguro que deseas eliminar este rol?",
      text: "No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, eliminar!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        const datos = new FormData();
        datos.append("accion", "eliminar_rol");
        datos.append("id_rol", id_rol);
        enviaAjax(datos);
      }
    });
  }
  function actualizarListadoRoles(roles) {
    let listadoRoles = "";
    if ($.fn.DataTable.isDataTable("#tablaroles")) {
      $("#tablaroles").DataTable().destroy();
    }
    roles.forEach((rol) => {
      listadoRoles += `
                <tr>
                    <td class='d-none'>${rol.id_rol}</td>
                    <td class='align-middle text-capitalize'>${rol.nombre}</td>
                    <td class='align-middle'>
                        <button class='btn btn-block btn-warning me-2' data-bs-toggle='modal'><i class="fa-regular fa-pen-to-square"></i></button>
                        <button class='btn btn-block btn-danger'><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
            `;
    });

    $("#listado").html(listadoRoles);
    $("#tablaroles").DataTable({
      columnDefs: [{ targets: [2], orderable: false, searchable: false }],
      language: {
        lengthMenu: "Mostrar _MENU_ por página",
        zeroRecords: "No se encontraron roles",
        info: "Mostrando página _PAGE_ de _PAGES_",
        infoEmpty: "No hay roles disponibles",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        search: "Buscar:",
        paginate: {
          first: "Primera",
          last: "Última",
          next: "Siguiente",
          previous: "Anterior",
        },
      },
      autoWidth: true,
      order: [[0, "desc"]],
      dom: '<"top"f>rt<"bottom"lp><"clear">',
    });
  }
  $("input").on("keypress", function (e) {
    const id = $(this).attr("id");
    const regexMap = {
      nombre: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
    };
    if (regexMap[id]) {
      validarKeyPress(e, regexMap[id]);
    }
  });

  $("input").on("keyup", function () {
    const id = $(this).attr("id");
    const formId = $(this).closest("form").attr("id");
    const sufijo = formId === "f2" ? "_modificar" : "";
    const regexMap = {
      nombre: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
    };

    if (regexMap[id.replace(sufijo, "")]) {
      validarKeyUp(
        regexMap[id.replace(sufijo, "")],
        $(this),
        $(`#s${id}`),
        $(`#${id.replace(sufijo, "")}_error`).text()
      );
    }
  });

  $("#tablaroles").on("click", ".btn-warning", function () {
    const id_rol = $(this).closest("tr").find("td:first").text();
    $("#id_rol").val(id_rol);
    cargarDatosRol(id_rol);
  });

  $("#tablaroles").on("click", ".btn-danger", function () {
    const id_rol = $(this).closest("tr").find("td:first").text();
    eliminarRol(id_rol);
  });

  function limpiarFormulario() {
    const formulario = document.getElementById("form_incluir");
    formulario.reset();
  }
  $("#modalCrear").on("hidden.bs.modal", function () {
    limpiarFormulario();
  });
});
