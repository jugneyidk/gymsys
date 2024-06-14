$(document).ready(function () {
  function carga_listado_atleta() {
    var datos = new FormData();
    datos.append("accion", "listado_atleta");
    enviaAjax(datos);
  }
  carga_listado_atleta();
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
      /^\d{7,9}$/,
      $("#cedula"),
      $("#scedula"),
      "La cédula debe tener al menos 8 números"
    );
    esValido &= validarKeyUp(
      /^04\d{9}$/,
      $("#telefono"),
      $("#stelefono"),
      "El formato del teléfono debe ser 04XXXXXXXXX"
    );
    esValido &= validarKeyUp(
      /^04\d{9}$/,
      $("#telefono_representante"),
      $("#stelefono_representante"),
      "El formato del teléfono debe ser 04XXXXXXXXX"
    );
    esValido &= validarKeyUp(
      /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
      $("#correo"),
      $("#scorreo"),
      "Correo inválido"
    );
    esValido &= validarKeyUp(
      /^\d+(\.\d{1,2})?$/,
      $("#peso"),
      $("#speso"),
      "Solo números y puntos decimales"
    );
    esValido &= validarKeyUp(
      /^\d+(\.\d{1,2})?$/,
      $("#altura"),
      $("#saltura"),
      "Solo números y puntos decimales"
    );
    esValido &= validarKeyUp(
      /^\d{7,9}$/,
      $("#entrenador_asignado"),
      $("#sentrenador_asignado"),
      "Solo numeros (7-9 caracteres)"
    );
    esValido &= verificarFecha();
    esValido &= validarKeyUp(
      /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
      $("#lugar_nacimiento"),
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
      case "entrenador_asignado":
      case "edad":
      case "telefono":
      case "telefono_representante":
        validarKeyPress(e, /^\d*$/);
        break;
      case "correo":
        validarKeyPress(e, /^[a-zA-Z0-9@._-]*$/);
        break;
      case "peso":
      case "altura":
        validarKeyPress(e, /^\d*\.?\d*$/);
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
          /^\d{7,9}$/,
          $(this),
          $("#scedula"),
          "La cédula debe tener al menos 8 números"
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
      case "telefono_representante":
        validarKeyUp(
          /^04\d{9}$/,
          $(this),
          $("#stelefono_representante"),
          "El formato del teléfono debe ser 04XXXXXXXXX"
        );
        break;
      case "correo":
        validarKeyUp(
          /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
          $(this),
          $("#scorreo"),
          "Correo inválido"
        );
        break;
      case "peso":
        validarKeyUp(
          /^\d+(\.\d{1,2})?$/,
          $(this),
          $("#speso"),
          "Solo números y puntos decimales"
        );
        break;
      case "altura":
        validarKeyUp(
          /^\d+(\.\d{1,2})?$/,
          $(this),
          $("#saltura"),
          "Solo números y puntos decimales"
        );
        break;
      case "entrenador_asignado":
        validarKeyUp(
          /^\d{7,9}$/,
          $(this),
          $("#scedula"),
          "La cédula debe tener al menos 8 números"
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
    }
  });

  $("#fecha_nacimiento").on("change", function () {
    verificarFecha();
    var edad = calcularEdad($(this).val());
    $("#edad").val(edad);
    if (edad < 18) {
      $("#representanteInfo").show();
    } else {
      $("#representanteInfo").hide();
    }
  });

  $("#f").on("submit", function (e) {
    e.preventDefault();
  });
  $("#incluir, #modificar, #eliminar").on("click", function () {
    var action = $(this).attr("id");
    if (validarEnvio()) {
      $("#accion").val(action);
      var datos = new FormData($("#f")[0]);
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
          if (lee.devol == "listado_atletas") {
            var listado_atleta = "";
            if ($.fn.DataTable.isDataTable("#tablaatleta")) {
              $("#tablaatleta").DataTable().destroy();
            }
            lee.respuesta.forEach((atleta) => {
              listado_atleta +=
                "<tr><td class='align-middle'>" + atleta.cedula + "</td>";
              listado_atleta +=
                "<td class='align-middle'>" + atleta.id_entrenador + "</td>";
              listado_atleta +=
                "<td class='align-middle'>" + atleta.nombre + "</td>";
              listado_atleta +=
                "<td class='align-middle'>" + atleta.apellido + "</td>";
              listado_atleta +=
                "<td class='align-middle'>" + atleta.tipo_atleta + "</td>";
              listado_atleta +=
                "<td class='align-middle'>" + atleta.genero + "</td>";
              listado_atleta +=
                "<td class='align-middle'>" + atleta.fecha_nacimiento + "</td>";
              listado_atleta +=
                "<td class='align-middle'><button class='btn btn-block btn-warning me-2'>Modificar</button><button class='btn btn-block btn-danger'>Eliminar</button></td>";
              listado_atleta += "</tr>";
            });
            $("#listado").html(listado_atleta);
            if (!$.fn.DataTable.isDataTable("#tablaatleta")) {
              $("#tablaatleta").DataTable({
                columnDefs: [
                  { targets: [7], orderable: false, searchable: false },
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
                autoWidth: true,
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
