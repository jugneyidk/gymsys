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
            form.find("#correo" + sufijo),
            form.find("#scorreo" + sufijo),
            "Correo inválido"
        );
        esValido &= verificarFecha(form.find("#fecha_nacimiento" + sufijo), form.find("#sfecha_nacimiento" + sufijo));
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
                    if (lee.devol === "listado_entrenadores") {
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
                    } else if (lee.ok) {
                        Swal.fire("Éxito", "Operación realizada con éxito", "success");
                        carga_listado_entrenadores();
                        // Cerrar los modales
                        $('#modalInscripcion').modal('hide');
                        $('#modalModificar').modal('hide');
                    } else {
                        Swal.fire("Error", lee.mensaje, "error");
                    }
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
                        $("#f2 #nombres_modificar").val(entrenador.nombre);
                        $("#f2 #apellidos_modificar").val(entrenador.apellido);
                       
