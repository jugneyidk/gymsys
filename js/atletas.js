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
        esValido &= validarKeyUp(
            /^\d+(\.\d{1,2})?$/,
            form.find("#peso"),
            form.find("#speso"),
            "Solo números y puntos decimales"
        );
        esValido &= validarKeyUp(
            /^\d+(\.\d{1,2})?$/,
            form.find("#altura"),
            form.find("#saltura"),
            "Solo números y puntos decimales"
        );
        esValido &= validarKeyUp(
            /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
            form.find("#lugar_nacimiento"),
            form.find("#slugarnacimiento"),
            "El lugar de nacimiento no puede estar vacío"
        );
        esValido &= verificarFecha(form.find("#fecha_nacimiento"), form.find("#sfecha_nacimiento"));

        return esValido;
    }

    $('#btnIncluir', 'btnModificar').on('click', function(event) {
        event.preventDefault();
    });

    $("#btnIncluir").on("click", function () {
        if (validarEnvio("#f1")) {
            var datos = new FormData($("#f1")[0]);
            enviaAjax(datos);
        }
    });

    
    $("#btnModificar").on("click", function () {
        if (validarEnvio("#f2")) {
            var datos = new FormData($("#f2")[0]);
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
                    if (lee.devol === "listado_atletas") {
                        var listado_atleta = "";
                        if ($.fn.DataTable.isDataTable("#tablaatleta")) {
                            $("#tablaatleta").DataTable().destroy();
                        }
                        lee.respuesta.forEach((atleta) => {
                            listado_atleta +=
                                "<tr><td class='align-middle'>" + atleta.cedula + "</td>";
                            listado_atleta +=
                                "<td class='align-middle'>" + atleta.entrenador + "</td>";
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
                                "<td class='align-middle'><button class='btn btn-block btn-warning me-2' data-bs-toggle='modal' data-bs-target='#modalModificar' onclick='cargarDatosAtleta(" + JSON.stringify(atleta.cedula) + ")'>Modificar</button><button class='btn btn-block btn-danger' onclick='eliminarAtleta(" + atleta.cedula + ")'>Eliminar</button></td>";
                            listado_atleta += "</tr>";
                        });
                        $("#listado").html(listado_atleta);
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
                            dom: '<"top"f>rt<"bottom"lp><"clear">',
                        });
                    } else if (lee.ok) {
                        Swal.fire("Éxito", "Operación realizada con éxito", "success");
                        carga_listado_atleta();
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

    function cargarDatosAtleta(cedula) {
        var datos = new FormData();
        datos.append("accion", "obtener_atleta");
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
                        var atleta = lee.atleta;
                        $("#f2 #nombres_modificar").val(atleta.nombre);
                        $("#f2 #apellidos_modificar").val(atleta.apellido);
                        $("#f2 #cedula_modificar").val(atleta.cedula);
                        $("#f2 #genero_modificar").val(atleta.genero);
                        $("#f2 #fecha_nacimiento_modificar").val(atleta.fecha_nacimiento);
                        $("#f2 #lugar_nacimiento_modificar").val(atleta.lugar_nacimiento);
                        $("#f2 #peso_modificar").val(atleta.peso);
                        $("#f2 #altura_modificar").val(atleta.altura);
                        $("#f2 #tipo_atleta_modificar").val(atleta.tipo_atleta);
                        $("#f2 #estado_civil_modificar").val(atleta.estado_civil);
                        $("#f2 #telefono_modificar").val(atleta.telefono);
                        $("#f2 #correo_modificar").val(atleta.correo_electronico);
                        $("#f2 #entrenador_asignado_modificar").val(atleta.entrenador);

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

    function eliminarAtleta(cedula) {
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

    $("#tablaatleta").on("click", ".btn-warning", function() {
        var cedula = $(this).closest("tr").find("td:first").text();
        cargarDatosAtleta(cedula);
    });

    $("input").on("keypress", function (e) {
        var id = $(this).attr("id");
        switch (id) {
            case "nombres":
            case "apellidos":
            case "lugar_nacimiento":
            case "nombres_modificar":
            case "apellidos_modificar":
            case "lugar_nacimiento_modificar":
                validarKeyPress(e, /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/);
                break;
            case "cedula":
            case "entrenador_asignado":
            case "edad":
            case "telefono":
            case "telefono_representante":
            case "cedula_modificar":
            case "entrenador_asignado_modificar":
            case "edad_modificar":
            case "telefono_modificar":
            case "telefono_representante_modificar":
                validarKeyPress(e, /^\d*$/);
                break;
            case "correo":
            case "correo_modificar":
                validarKeyPress(e, /^[a-zA-Z0-9@._-]*$/);
                break;
            case "peso":
            case "altura":
            case "peso_modificar":
            case "altura_modificar":
                validarKeyPress(e, /^\d*\.?\d*$/);
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
            case "telefono_representante":
            case "telefono_representante_modificar":
                validarKeyUp(
                    /^04\d{9}$/,
                    $(this),
                    $("#stelefono_representante"),
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
            case "peso":
            case "peso_modificar":
                validarKeyUp(
                    /^\d+(\.\d{1,2})?$/,
                    $(this),
                    $("#speso"),
                    "Solo números y puntos decimales"
                );
                break;
            case "altura":
            case "altura_modificar":
                validarKeyUp(
                    /^\d+(\.\d{1,2})?$/,
                    $(this),
                    $("#saltura"),
                    "Solo números y puntos decimales"
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
        }
    });

    $("#fecha_nacimiento, #fecha_nacimiento_modificar").on("change", function () {
        var form = $(this).closest("form");
        verificarFecha($(this), form.find("#sfecha_nacimiento"));
        var edad = calcularEdad($(this).val());
        form.find("#edad").val(edad);
        if (edad < 18) {
            form.find("#representanteInfo").show();
        } else {
            form.find("#representanteInfo").hide();
        }
    });
});
