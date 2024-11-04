import {
  validarKeyPress,
  validarKeyUp,
  enviaAjax,
  muestraMensaje,
} from "./comunes.js";
$(document).ready(function () {
  function carga_listado_entrenadores() {
    var datos = new FormData();
    datos.append("accion", "listado_entrenadores");
    enviaAjax(datos, "").then((respuesta) => {
      actualizarListadoEntrenadores(respuesta.respuesta);
    });
  }

  carga_listado_entrenadores();

  function verificarFecha(fechaInput, mensaje) {
    var fecha = fechaInput.val();
    if (!fecha) {
      fechaInput.removeClass("is-valid").addClass("is-invalid");
      mensaje.text("La fecha de nacimiento es obligatoria");
      return false;
    }
    var hoy = new Date();
    var fechaNac = new Date(fecha);
    if (fechaNac > hoy) {
      fechaInput.removeClass("is-valid").addClass("is-invalid");
      mensaje.text("La fecha debe ser anterior al día actual");
      return false;
    } else {
      fechaInput.removeClass("is-invalid").addClass("is-valid");
      mensaje.text("");
      return true;
    }
  }

  function calcularEdad(fechaNacimiento) {
    var hoy = new Date();
    var fechaNac = new Date(fechaNacimiento);
    var edad = hoy.getFullYear() - fechaNac.getFullYear();
    var mes = hoy.getMonth() - fechaNac.getMonth();
    if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
      edad--;
    }
    return edad;
  }

  function validarEnvio(formId) {
    var esValido = true;
    var form = $(formId);

    var sufijo = formId === "#f2" ? "_modificar" : "";

    esValido &= validarKeyUp(
      /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
      form.find("#nombres" + sufijo),
      form.find("#snombres" + sufijo),
      "Solo letras y espacios (1-50 caracteres)"
    );
    esValido &= validarKeyUp(
      /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
      form.find("#apellidos" + sufijo),
      form.find("#sapellidos" + sufijo),
      "Solo letras y espacios (1-50 caracteres)"
    );
    esValido &= validarKeyUp(
      /^\d{7,9}$/,
      form.find("#cedula" + sufijo),
      form.find("#scedula" + sufijo),
      "La cédula debe tener al menos 7 números"
    );
    esValido &= validarKeyUp(
      /^04\d{9}$/,
      form.find("#telefono" + sufijo),
      form.find("#stelefono" + sufijo),
      "El formato del teléfono debe ser 04XXXXXXXXX"
    );
    esValido &= validarKeyUp(
      /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
      form.find("#correo_electronico" + sufijo),
      form.find("#scorreo_electronico" + sufijo),
      "Correo inválido"
    );
    esValido &= verificarFecha(
      form.find("#fecha_nacimiento" + sufijo),
      form.find("#sfecha_nacimiento" + sufijo)
    );
    esValido &= validarKeyUp(
      /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
      form.find("#lugar_nacimiento" + sufijo),
      form.find("#slugarnacimiento" + sufijo),
      "El lugar de nacimiento no puede estar vacío"
    );
    esValido &= validarKeyUp(
      /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
      form.find("#grado_instruccion" + sufijo),
      form.find("#sgrado_instruccion" + sufijo),
      "El grado de instrucción no puede estar vacío"
    );

    if (formId === "#f2" && $("#modificar_contraseña").is(":checked")) {
      esValido &= validarKeyUp(
        /^[a-zA-Z0-9@._-]{6,20}$/,
        form.find("#password_modificar"),
        form.find("#spassword_modificar"),
        "La contraseña debe tener entre 6 y 20 caracteres"
      );
    }
    console.log(esValido);
    return esValido;
  }

  $("#f1, #f2").on("submit", function (e) {
    e.preventDefault();
    var formId = $(this).attr("id");
    if (validarEnvio("#" + formId)) {
      var datos = new FormData($(this)[0]);
      enviaAjax(datos, "").then((respuesta) => {
        muestraMensaje("Exito", "Operación realizada exitosamente.", "success");
        carga_listado_entrenadores();
        $("#modalInscripcion").modal("hide");
        $("#modalModificar").modal("hide");
      });
    }
  });
  function actualizarListadoEntrenadores(entrenadores) {
    var listado_entrenador = "";
    if ($.fn.DataTable.isDataTable("#tablaentrenador")) {
      $("#tablaentrenador").DataTable().destroy();
    }
    entrenadores.forEach((entrenador) => {
      listado_entrenador +=
        "<tr><td class='align-middle'>" + entrenador.cedula + "</td>";
      listado_entrenador +=
        "<td class='align-middle'>" +
        entrenador.nombre +
        " " +
        entrenador.apellido +
        "</td>";
      listado_entrenador +=
        "<td class='align-middle'>" + entrenador.telefono + "</td>";
      listado_entrenador += `<td>${
        actualizar === 1
          ? "<button class='btn btn-block btn-warning me-2' data-bs-toggle='modal'><i class='fa-regular fa-pen-to-square'></i></button>"
          : ""
      }
                                    ${
                                      eliminar === 1
                                        ? "<button class='btn btn-block btn-danger'><i class='fa-solid fa-trash-can'></i></button>"
                                        : ""
                                    } </td>`;
      listado_entrenador += "</tr>";
    });
    $("#listado").html(listado_entrenador);
    $("#tablaentrenador").DataTable({
      columnDefs: [{ targets: [3], orderable: false, searchable: false }],
      language: {
        lengthMenu: "Mostrar _MENU_ por página",
        zeroRecords: "No se encontraron entrenadores",
        info: "Mostrando página _PAGE_ de _PAGES_",
        infoEmpty: "No hay entrenadores disponibles",
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
  function llenarFormularioModificar(entrenador) {
    $("#f2 #nombres_modificar").val(entrenador.nombre);
    $("#f2 #apellidos_modificar").val(entrenador.apellido);
    $("#f2 #cedula_modificar").val(entrenador.cedula);
    $("#f2 #genero_modificar").val(entrenador.genero);
    $("#f2 #fecha_nacimiento_modificar").val(entrenador.fecha_nacimiento);
    $("#f2 #lugar_nacimiento_modificar").val(entrenador.lugar_nacimiento);
    $("#f2 #estado_civil_modificar").val(entrenador.estado_civil);
    $("#f2 #telefono_modificar").val(entrenador.telefono);
    $("#f2 #correo_modificar").val(entrenador.correo_electronico);
    $("#f2 #grado_instruccion_modificar").val(entrenador.grado_instruccion);
    $("#f2 #password_modificar").val("");
    $("#modificar_contraseña").prop("checked", false);
  }
  function cargarDatosEntrenador(cedula) {
    var datos = new FormData();
    datos.append("accion", "obtener_entrenador");
    datos.append("cedula", cedula);
    enviaAjax(datos, "").then((respuesta) => {
      llenarFormularioModificar(respuesta.entrenador);
      $("#modalModificar").modal("show");
    });
  }

  function eliminarEntrenador(cedula) {
    Swal.fire({
      title: "¿Estás seguro?",
      text: "No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, eliminar!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData();
        datos.append("accion", "eliminar");
        datos.append("cedula", cedula);
        enviaAjax(datos, "").then((respuesta) => {
          muestraMensaje(
            "Éxito",
            "El entrenador fue eliminado exitosamente.",
            "success"
          );
          carga_listado_entrenadores();
        });
      }
    });
  }

  $("#modificar_contraseña").on("change", function () {
    if ($(this).is(":checked")) {
      $("#password_modificar").prop("disabled", false);
    } else {
      $("#password_modificar").prop("disabled", true).val("");
      $("#password_modificar").removeClass("is-valid is-invalid");
      $("#spassword_modificar").text("");
    }
  });

  $("input").on("keypress", function (e) {
    var id = $(this).attr("id");
    switch (id) {
      case "nombres":
      case "apellidos":
      case "lugar_nacimiento":
      case "grado_instruccion":
      case "nombres_modificar":
      case "apellidos_modificar":
      case "lugar_nacimiento_modificar":
      case "grado_instruccion_modificar":
        validarKeyPress(e, /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/);
        break;
      case "cedula":
      case "cedula_modificar":
      case "telefono":
      case "telefono_modificar":
        validarKeyPress(e, /^\d*$/);
        break;
      case "correo_electronico":
      case "correo_modificar":
        validarKeyPress(e, /^[a-zA-Z0-9@._-]*$/);
        break;
      case "password_modificar":
        validarKeyPress(e, /^[a-zA-Z0-9@._-]*$/);
        break;
    }
  });

  $("input").on("keyup", function () {
    var id = $(this).attr("id");
    var formId = $(this).closest("form").attr("id");
    var sufijo = formId === "f2" ? "_modificar" : "";
    switch (id) {
      case "nombres" + sufijo:
        validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
          $(this),
          $("#snombres" + sufijo),
          "Solo letras y espacios (1-50 caracteres)"
        );
        break;
      case "apellidos" + sufijo:
        validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
          $(this),
          $("#sapellidos" + sufijo),
          "Solo letras y espacios (1-50 caracteres)"
        );
        break;
      case "cedula" + sufijo:
        validarKeyUp(
          /^\d{7,9}$/,
          $(this),
          $("#scedula" + sufijo),
          "La cédula debe tener al menos 7 números"
        );
        break;
      case "telefono" + sufijo:
        validarKeyUp(
          /^04\d{9}$/,
          $(this),
          $("#stelefono" + sufijo),
          "El formato del teléfono debe ser 04XXXXXXXXX"
        );
        break;
      case "correo_electronico" + sufijo:
        validarKeyUp(
          /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
          $(this),
          $("#scorreo_electronico" + sufijo),
          "Correo inválido"
        );
        break;
      case "lugar_nacimiento" + sufijo:
        validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
          $(this),
          $("#slugarnacimiento" + sufijo),
          "El lugar de nacimiento no puede estar vacío"
        );
        break;
      case "grado_instruccion" + sufijo:
        validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
          $(this),
          $("#sgrado_instruccion" + sufijo),
          "El grado de instrucción no puede estar vacío"
        );
        break;
      case "password_modificar":
        if ($("#modificar_contraseña").is(":checked")) {
          validarKeyUp(
            /^[a-zA-Z0-9@._-]{6,20}$/,
            $(this),
            $("#spassword_modificar"),
            "La contraseña debe tener entre 6 y 20 caracteres"
          );
        }
        break;
    }
  });

  $("#fecha_nacimiento, #fecha_nacimiento_modificar").on("change", function () {
    var form = $(this).closest("form");
    var sufijo = form.attr("id") === "f2" ? "_modificar" : "";
    verificarFecha($(this), form.find("#sfecha_nacimiento" + sufijo));
    var edad = calcularEdad($(this).val());
    form.find("#edad" + sufijo).val(edad);
    if (edad < 18) {
      form.find("#representanteInfo" + sufijo).show();
    } else {
      form.find("#representanteInfo" + sufijo).hide();
    }
  });

  $("#tablaentrenador").on("click", ".btn-warning", function () {
    var cedula = $(this).closest("tr").find("td:first").text();
    cargarDatosEntrenador(cedula);
  });

  $("#tablaentrenador").on("click", ".btn-danger", function () {
    var cedula = $(this).closest("tr").find("td:first").text();
    eliminarEntrenador(cedula);
  });
});
