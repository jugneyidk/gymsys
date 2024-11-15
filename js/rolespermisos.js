import {
  validarKeyPress,
  validarKeyUp,
  enviaAjax,
  muestraMensaje,
  REGEX,
} from "./comunes.js";
$(document).ready(function () {
  function cargaListadoRoles() {
    const datos = new FormData();
    datos.append("accion", "listado_roles");
    enviaAjax(datos, "").then((respuesta) => {
      actualizarListadoRoles(respuesta.roles);
    });
  }

  cargaListadoRoles();

  function validarEnvio(formId) {
    let esValido = true;
    const form = $(formId);
    const sufijo = formId === "#f2" ? "_modificar" : "";

    const validaciones = [
      {
        regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
        id: "nombre_rol",
        errorMsg: "Solo letras y espacios (3-50 caracteres)",
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
    $("#modalTitulo").text("Nuevo Rol");
    $("#btnSubmit").text("Registrar Rol");
  });
  $("#btnSubmit").on("click", function (event) {
    event.preventDefault();
  });

  $("#btnSubmit").on("click", function () {
    if (!$("#id_rol").val()) {
      if (validarEnvio("#form_incluir")) {
        const datos = new FormData($("#form_incluir")[0]);
        datos.append("accion", "incluir");
        enviaAjax(datos, "").then((respuesta) => {
          muestraMensaje(
            "Éxito",
            "El rol se ha agregado exitosamente.",
            "success"
          );
          $("#modal").modal("hide");
          cargaListadoRoles();
        });
      }
    } else if (validarEnvio("#form_incluir")) {
      const datos = new FormData($("#form_incluir")[0]);
      datos.append("accion", "modificar");
      enviaAjax(datos, "").then((respuesta) => {
        muestraMensaje(
          "Éxito",
          "El rol se ha modificado exitosamente.",
          "success"
        );
        $("#modal").modal("hide");
        cargaListadoRoles();
      });
    }
  });

  function llenarFormularioModificar(permisos) {
    $("#nombre_rol").val(permisos[0].nombre_rol);
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
  function cargarDatosRol(id_rol) {
    const datos = new FormData();
    datos.append("accion", "consultar_rol");
    datos.append("id_rol", id_rol);
    enviaAjax(datos, "").then((respuesta) => {
      llenarFormularioModificar(respuesta.permisos);
      $("#modal").modal("show");
      $("#modalTitulo").text("Modificar Rol");
      $("#btnSubmit").text("Modificar Rol");
    });
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
        enviaAjax(datos, "").then((respuesta) => {
          muestraMensaje(
            "Éxito",
            "El rol se ha eliminado exitosamente.",
            "success"
          );
          $("#modalModificar").modal("hide");
          cargaListadoRoles();
        });
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
                    ${
                      actualizar === 1
                        ? "<button class='btn btn-block btn-warning me-2 w-auto' data-bs-toggle='modal'><i class='fa-regular fa-pen-to-square'></i></button>"
                        : ""
                    }
                    ${
                      eliminar === 1
                        ? "<button class='btn btn-block btn-danger w-auto'><i class='fa-solid fa-trash-can'></i></button>"
                        : ""
                    }                        
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
          next: "Siguiente",
          previous: "Anterior",
        },
      },
      autoWidth: false,
      order: [[0, "desc"]],
      dom: '<"top"f>rt<"bottom"lp><"clear">',
    });
  }
  $("input").on("keypress", function (e) {
    const id = $(this).attr("id");
    const regexMap = {
      nombre_rol: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
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
      nombre_rol: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}$/,
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
  $("#modal").on("hidden.bs.modal", function () {
    limpiarFormulario();
    $("#nombre_rol").removeClass("is-valid");
  });
});
