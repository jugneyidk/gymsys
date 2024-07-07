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

  function validarEnvio(formId) {
      var esValido = true;
      var form = $(formId);
      esValido &= validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
          form.find("#nombres"),
          form.find("#snombres"),
          "Solo letras y espacios (1-50 caracteres)"
      );
      esValido &= validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
          form.find("#apellidos"),
          form.find("#sapellidos"),
          "Solo letras y espacios (1-50 caracteres)"
      );
      esValido &= validarKeyUp(
          /^\d{7,9}$/,
          form.find("#cedula"),
          form.find("#scedula"),
          "La cédula debe tener al menos 7 números"
      );
      esValido &= validarKeyUp(
          /^04\d{9}$/,
          form.find("#telefono"),
          form.find("#stelefono"),
          "El formato del teléfono debe ser 04XXXXXXXXX"
      );
      esValido &= validarKeyUp(
          /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
          form.find("#correo"),
          form.find("#scorreo"),
          "Correo inválido"
      );
      esValido &= verificarFecha(form.find("#fecha_nacimiento"), form.find("#sfecha_nacimiento"));
      esValido &= validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
          form.find("#lugar_nacimiento"),
          form.find("#slugarnacimiento"),
          "El lugar de nacimiento no puede estar vacío"
      );
      esValido &= validarKeyUp(
          /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
          form.find("#grado_instruccion"),
          form.find("#sgrado_instruccion"),
          "El grado de instrucción no puede estar vacío"
      );

      return esValido;
  }

  $("#f1, #f2").on("submit", function (e) {
      e.preventDefault();
      var formId = $(this).attr('id');
      var action = $(this).find('input[name="accion"]').val();
      if (validarEnvio("#" + formId)) {
          var datos = new FormData($(this)[0]);
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
          beforeSend: function () { },
          timeout: 10000,
          success: function (respuesta) {
              try {
                  var lee = JSON.parse(respuesta);
                  if (lee.devol == "listado_entrenadores") {
                      var listado_entrenador = "";
                      if ($.fn.DataTable.isDataTable("#tablaentrenador")) {
                          $("#tablaentrenador").DataTable().destroy();
                      }
                      lee.respuesta.forEach((entrenador) => {
                          listado_entrenador +=
                              "<tr><td class='align-middle'>" + entrenador.cedula + "</td>";
                          listado_entrenador +=
                              "<td class='align-middle'>" + entrenador.nombres + "</td>";
                          listado_entrenador +=
                              "<td class='align-middle'>" + entrenador.apellidos + "</td>";
                          listado_entrenador +=
                              "<td class='align-middle'>" + entrenador.genero + "</td>";
                          listado_entrenador +=
                              "<td class='align-middle'>" + entrenador.fecha_nacimiento + "</td>";
                          listado_entrenador +=
                              "<td class='align-middle'>" + entrenador.correo_electronico + "</td>";
                          listado_entrenador +=
                              "<td class='align-middle'><button class='btn btn-block btn-warning me-2' data-bs-toggle='modal' data-bs-target='#modalModificar' onclick='cargarDatosEntrenador(" + JSON.stringify(entrenador.cedula) + ")'>Modificar</button><button class='btn btn-block btn-danger' onclick='eliminarEntrenador(" + entrenador.cedula + ")'>Eliminar</button></td>";
                          listado_entrenador += "</tr>";
                      });
                      $("#listado").html(listado_entrenador);
                      $("#tablaentrenador").DataTable({
                          columnDefs: [
                              { targets: [6], orderable: false, searchable: false },
                          ],
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
                    Swal.fire("Éxito", "Operación realizada con éxito", "success");
                } catch {
                    Swal.fire("Error", "Algo salió mal", "error");
                }
            },
            error: function (request, status, err) {
                if (status === "timeout") {
                    Swal.fire("Servidor ocupado", "Intente de nuevo", "error");
                } else {
                    Swal.fire("Error", "Error al procesar la solicitud", "error");
                }
            },
            complete: function () { },
        });
    }

    function cargarDatosEntrenador(cedula) {
        var datos = new FormData();
        datos.append("accion", "obtener_entrenador");
        datos.append("cedula", cedula);

        $.ajax({
            async: true,
            url: "",
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.ok) {
                        var entrenador = lee.entrenador;
                        $("#f2 #nombres_modificar").val(entrenador.nombres);
                        $("#f2 #apellidos_modificar").val(entrenador.apellidos);
                        $("#f2 #cedula_modificar").val(entrenador.cedula);
                        $("#f2 #genero_modificar").val(entrenador.genero);
                        $("#f2 #fecha_nacimiento_modificar").val(entrenador.fecha_nacimiento);
                        $("#f2 #lugar_nacimiento_modificar").val(entrenador.lugar_nacimiento);
                        $("#f2 #estado_civil_modificar").val(entrenador.estado_civil);
                        $("#f2 #telefono_modificar").val(entrenador.telefono);
                        $("#f2 #correo_modificar").val(entrenador.correo_electronico);
                        $("#f2 #grado_instruccion_modificar").val(entrenador.grado_instruccion);

                        // Mostrar el modal de modificación
                        $("#modalModificar").modal('show');
                    } else {
                        Swal.fire("Error", lee.mensaje, "error");
                    }
                } catch {
                    Swal.fire("Error", "Algo salió mal", "error");
                }
            },
            error: function (request, status, err) {
                Swal.fire("Error", "Error al procesar la solicitud", "error");
            }
        });
    }

    function eliminarEntrenador(cedula) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData();
                datos.append("accion", "eliminar");
                datos.append("cedula", cedula);
                enviaAjax(datos);
            }
        })
    }

    $("#tablaentrenador").on("click", ".btn-warning", function () {
        var cedula = $(this).closest("tr").find("td:first").text();
        cargarDatosEntrenador(cedula);
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
            case "correo":
            case "correo_modificar":
                validarKeyPress(e, /^[a-zA-Z0-9@._-]*$/);
                break;
        }
    });

    $("input").on("keyup", function () {
        var id = $(this).attr("id");
        switch (id) {
            case "nombres":
            case "nombres_modificar":
                validarKeyUp(
                    /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
                    $(this),
                    $("#snombres"),
                    "Solo letras y espacios (1-50 caracteres)"
                );
                break;
            case "apellidos":
            case "apellidos_modificar":
                validarKeyUp(
                    /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
                    $(this),
                    $("#sapellidos"),
                    "Solo letras y espacios (1-50 caracteres)"
                );
                break;
            case "cedula":
            case "cedula_modificar":
                validarKeyUp(
                    /^\d{7,9}$/,
                    $(this),
                    $("#scedula"),
                    "La cédula debe tener al menos 7 números"
                );
                break;
            case "telefono":
            case "telefono_modificar":
                validarKeyUp(
                    /^04\d{9}$/,
                    $(this),
                    $("#stelefono"),
                    "El formato del teléfono debe ser 04XXXXXXXXX"
                );
                break;
            case "correo":
            case "correo_modificar":
                validarKeyUp(
                    /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                    $(this),
                    $("#scorreo"),
                    "Correo inválido"
                );
                break;
            case "lugar_nacimiento":
            case "lugar_nacimiento_modificar":
                validarKeyUp(
                    /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
                    $(this),
                    $("#slugarnacimiento"),
                    "El lugar de nacimiento no puede estar vacío"
                );
                break;
            case "grado_instruccion":
            case "grado_instruccion_modificar":
                validarKeyUp(
                    /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
                    $(this),
                    $("#sgrado_instruccion"),
                    "El grado de instrucción no puede estar vacío"
                );
                break;
        }
    });

    $("#fecha_nacimiento, #fecha_nacimiento_modificar").on("change", function () {
        var form = $(this).closest("form");
        verificarFecha($(this), form.find("#sfecha_nacimiento"));
    });
});
