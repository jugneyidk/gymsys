import {
  validarKeyPress,
  validarKeyUp,
  enviaAjax,
  muestraMensaje,
  REGEX,
  obtenerNotificaciones,
} from "./comunes.js";
$(document).ready(function () {
  function cargaListadoRoles() {
    const datos = new FormData();
    datos.append("accion", "listado_roles");
    enviaAjax(datos, "").then((respuesta) => {
      actualizarListadoRoles(respuesta.roles);
    });
  }
  obtenerNotificaciones(idUsuario);
  setInterval(() => obtenerNotificaciones(idUsuario), 35000);
  cargaListadoRoles();

  function validarEnvio(formId) {
    let esValido = true;
    const form = $(formId);
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
        form.find(`#${id}`),
        form.find(`#s${id}`),
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
  $("#f1").on("submit", function (e) {
    e.preventDefault();
    let regexCedula = /^\d{7,9}$/;
    let regexId = /^\d{1,50}$/;
    if (
      !regexCedula.test($("#cedula").val()) ||
      !regexId.test($("#id_rol_asignar").val())
    ) {
      muestraMensaje("Error", "Los valores ingresados no son validos", "error");
    } else {
      const datos = new FormData($("#f1")[0]);
      datos.append("accion", "asignar_rol");
      enviaAjax(datos, "").then((respuesta) => {
        muestraMensaje(
          "Éxito",
          "El rol se ha asignado exitosamente.",
          "success"
        );
        $("#modalAsignarRol").modal("hide");
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
    let selectRoles = "";
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
                        ? `<button class='btn btn-block btn-warning me-2 w-auto' data-bs-toggle='modal' aria-label='Modificar rol ${rol.nombre}' data-tooltip="tooltip" title="Modificar rol"><i class='fa-regular fa-pen-to-square'></i></button>`
                        : ""
                    }
                    ${
                      eliminar === 1
                        ? `<button class='btn btn-block btn-danger w-auto' aria-label='Eliminar rol ${rol.nombre}' data-tooltip="tooltip" title="Eliminar rol"><i class='fa-solid fa-trash-can'></i></button>`
                        : ""
                    }                        
                    </td>
                </tr>
            `;
      selectRoles += `<option value="${rol.id_rol}">${rol.nombre}</option>`;
    });
    $("#listado").html(listadoRoles);
    $("#id_rol_asignar").html(selectRoles);
    $("#id_rol_asignar").val(0);
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
      cedula: /^\d*$/,
    };
    if (regexMap[id]) {
      validarKeyPress(e, regexMap[id]);
    }
  });
  function consultarUsuario(datos, url) {
    return new Promise((resolve, reject) => {
      $.ajax({
        async: true,
        url: url, // URL pasada como argumento
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        timeout: 10000,
        beforeSend: function () {
          $("#cedula").removeClass("is-valid");
          $("#cedula").removeClass("is-invalid");
          $("#spinner-usuario").removeClass("d-none");
          $("#id_rol_asignar").val(0);
          $("#nombreUsuario").addClass("bg-secondary");
          $("#nombreUsuario").removeClass("bg-primary");
          $("#nombreUsuario").text("No seleccionado");
        },
        success: function (respuesta) {
          try {
            const datosParseados = JSON.parse(respuesta);
            resolve(datosParseados);
          } catch (error) {
            reject("Error al parsear la respuesta JSON");
          }
        },
        error: function (request, status) {
          const errorMsg =
            status === "timeout"
              ? "Servidor ocupado, intente de nuevo"
              : "Error al procesar la solicitud";
          muestraMensaje("Error", errorMsg, "error");
          reject(errorMsg);
        },
        complete: function () {
          $("#spinner-usuario").addClass("d-none");
        },
      });
    });
  }
  $("input").on("keyup", function () {
    const id = $(this).attr("id");
    const regexMap = {
      nombre_rol: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}$/,
      cedula: /^\d{7,9}$/,
    };
    if (regexMap[id]) {
      validarKeyUp(
        regexMap[id],
        $(this),
        $(`#s${id}`),
        id == "nombre_rol"
          ? "El nombre del rol debe ser entre 3 y 50 caracteres"
          : "La cedula cédula debe tener al menos 7 números"
      );
    }
  });
  $("#cedula").on("keyup", function () {
    let regex = /^\d{7,9}$/;
    if (regex.test($(this).val())) {
      const id = $(this).attr("id");
      const datos = new FormData($("#f1")[0]);
      datos.append("accion", "consultar_rol_usuario");
      consultarUsuario(datos, "").then((respuesta) => {
        if (!respuesta.ok && respuesta.mensaje) {
          muestraMensaje("Error", respuesta.mensaje, "error");
        }
        if (respuesta.ok) {
          $("#nombreUsuario").text(
            `${respuesta.usuario.nombre} ${respuesta.usuario.apellido}`
          );
          $("#nombreUsuario").removeClass("bg-secondary");
          $("#nombreUsuario").addClass("bg-primary");
          $("#id_rol_asignar").val(respuesta.usuario.id_rol);
          $("#cedula").removeClass("is-invalid");
          $("#cedula").addClass("is-valid");
        } else {
          $("#cedula").addClass("is-invalid");
        }
      });
    } else {
      $("#id_rol_asignar").val(0);
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
