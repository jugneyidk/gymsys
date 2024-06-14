$(document).ready(function () {
  function carga_listado_entrenadores() {
    var datos = new FormData();
    datos.append("accion", "listado_entrenadores");
    enviaAjax(datos);
  }
  carga_listado_entrenadores();
  function validarKeyPress(e, er) {
    var key = e.key;
    if (!er.test(key)) {
      e.preventDefault();
    }
  }

  function validarKeyUp(er, input, mensaje, textoError) {
    if (er.test(input.val())) {
      input.removeClass("is-invalid").addClass("is-valid");
      mensaje.text("");
      return true;
    } else {
      input.removeClass("is-valid").addClass("is-invalid");
      mensaje.text(textoError);
      return false;
    }
  }
  function verificarFecha() {
    var fecha = $("#fecha_nacimiento").val();
    if (!fecha) {
      $("#fecha_nacimiento").removeClass("is-valid").addClass("is-invalid");
      $("#sfecha_nacimiento").text("La fecha de nacimiento es obligatoria");
      return false;
    }
    var hoy = new Date();
    var fechaNac = new Date(fecha);
    if (fechaNac > hoy) {
      $("#fecha_nacimiento").removeClass("is-valid").addClass("is-invalid");
      $("#sfecha_nacimiento").text("La fecha debe ser anterior al día actual");
      return false;
    } else {
      $("#fecha_nacimiento").removeClass("is-invalid").addClass("is-valid");
      $("#sfecha_nacimiento").text("");
      return true;
    }
  }
  function validarEnvio() {
    var esValido = true;
    esValido &= validarKeyUp(
      /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
      $("#nombres"),
      $("#snombres"),
      "Solo letras y espacios (1-50 caracteres)"
    );
    esValido &= validarKeyUp(
      /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
      $("#apellidos"),
      $("#sapellidos"),
      "Solo letras y espacios (1-50 caracteres)"
    );
    esValido &= validarKeyUp(
      /^[0-9]{7,}$/,
      $("#cedula"),
      $("#scedula"),
      "La cédula debe tener al menos 7 números"
    );
    esValido &= validarKeyUp(
      /^04\d{9}$/,
      $("#telefono"),
      $("#stelefono"),
      "El formato del teléfono debe ser 04XXXXXXXXX"
    );
    esValido &= validarKeyUp(
      /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
      $("#correo_electronico"),
      $("#scorreo"),
      "Correo inválido"
    );
    esValido &= verificarFecha();
    esValido &= validarKeyUp(
      /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
      $("#lugar_nacimiento"),
      $("#slugarnacimiento"),
      "El lugar de nacimiento no puede estar vacío"
    );
    esValido &= validarKeyUp(
      /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
      $("#grado_instruccion"),
      $("#slugarnacimiento"),
      "El lugar de nacimiento no puede estar vacío"
    );
    return esValido;
  }

  $("input").on("keypress", function (e) {
    var id = $(this).attr("id");
    switch (id) {
      case "nombres":
      case "apellidos":
      case "lugar_nacimiento":
        validarKeyPress(e, /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/);
        break;
      case "cedula":
      case "edad":
      case "telefono":
      case "correo":
        validarKeyPress(e, /^[a-zA-Z0-9@._-]*$/);
        break;
      default:
        break;
    }
  });

  $("input").on("keyup", function () {
    var id = $(this).attr("id");
    switch (id) {
      case "nombres":
        validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
          $(this),
          $("#snombres"),
          "Solo letras y espacios (1-50 caracteres)"
        );
        break;
      case "apellidos":
        validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
          $(this),
          $("#sapellidos"),
          "Solo letras y espacios (1-50 caracteres)"
        );
        break;
      case "cedula":
        validarKeyUp(
          /^[0-9]{8,}$/,
          $(this),
          $("#scedula"),
          "La cédula debe tener al menos 7 números"
        );
        break;
      case "telefono":
        validarKeyUp(
          /^04\d{9}$/,
          $(this),
          $("#stelefono"),
          "El formato del teléfono debe ser 04XXXXXXXXX"
        );
        break;
      case "correo_electronico":
        validarKeyUp(
          /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
          $(this),
          $("#scorreo"),
          "Correo inválido"
        );
        break;
      case "lugar_nacimiento":
        validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
          $(this),
          $("#slugarnacimiento"),
          "El lugar de nacimiento no puede estar vacío"
        );
        break;
      case "grado_instruccion":
        validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
          $(this),
          $("#sgrado_instruccion"),
          "El lugar de nacimiento no puede estar vacío"
        );
        break;
    }
  });

  $("#fecha_nacimiento").on("change", function () {
    verificarFecha();
  });

  $("#f").on("submit", function (e) {
    e.preventDefault();
  });

  $("#incluir, #modificar, #eliminar").on("click", function () {
    var accion = $(this).attr("id");
    if (validarEnvio()) {
      var datos = new FormData($("#f")[0]);
      datos.append("accion", accion);
      enviaAjax(datos);
    }
  });

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
          var lee = JSON.parse(respuesta);
          if (lee.devol == "listado_entrenador") {
            console.log(lee.respuesta)
            var listado_entrenador = "";
            if ($.fn.DataTable.isDataTable("#tablaentrenador")) {
              $("#tablaentrenador").DataTable().destroy();
            }
            lee.respuesta.forEach((entrenador) => {
              listado_entrenador +=
                "<tr><td class='align-middle'>" + entrenador.cedula + "</td>";
              listado_entrenador +=
                "<td class='align-middle'>" + entrenador.nombre + "</td>";
              listado_entrenador +=
                "<td class='align-middle'>" + entrenador.apellido + "</td>";
              listado_entrenador +=
                "<td class='align-middle'>" + entrenador.genero + "</td>";
              listado_entrenador +=
                "<td class='align-middle'>" +
                entrenador.fecha_nacimiento +
                "</td>";
              listado_entrenador +=
                "<td class='align-middle'>" +
                entrenador.correo_electronico +
                "</td>";
              listado_entrenador +=
                "<td class='align-middle'><button class='btn btn-block btn-warning me-2'>Modificar</button><button class='btn btn-block btn-danger'>Eliminar</button></td>";
              listado_entrenador += "</tr>";
            });
            $("#listado").html(listado_entrenador);
            if (!$.fn.DataTable.isDataTable("#tablaentrenador")) {
              $("#tablaentrenador").DataTable({
                columnDefs: [
                  { targets: [6], orderable: false, searchable: false },
                ],
                language: {
                  lengthMenu: "Mostrar _MENU_ por página",
                  zeroRecords: "No se encontraron atletas",
                  info: "Mostrando página _PAGE_ de _PAGES_",
                  infoEmpty: "No hay atletas disponibles",
                  infoFiltered: "(filtrado de _MAX_ registros totales)",
                  search: "Buscar:",
                  paginate: {
                    first: "Primera",
                    last: "Última",
                    next: "Siguiente",
                    previous: "Anterior",
                  },
                },
                autoWidth: false,
                order: [[0, "desc"]],
              });
            } else {
              Swal.fire("Éxito", "Operación realizada con éxito", "success");
              $("#f")[0].reset();
            }
          }
        } catch {
          Swal.fire("Error", "algo salio mal", "error");
        }
      },
      error: function (request, status, err) {
        if (status === "timeout") {
          Swal.fire("Servidor ocupado", "Intente de nuevo", "error");
        } else {
          Swal.fire("Error", "Error al procesar la solicitud", "error");
        }
      },
      complete: function () {},
    });
  }
});
