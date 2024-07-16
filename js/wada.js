$(document).ready(function () {
    function carga_listado_wada() {
        var datos = new FormData();
        datos.append("accion", "listado_wada");
        enviaAjax(datos);
    }
    carga_listado_wada();

    function validarKeyPress(e, er) {
        var key = e.key;
        if (!er.test(key)) {
            e.preventDefault();
        }
    }

    function validarKeyUp(er, input, mensaje, textoError) {
        if (er.test(input.val())) {
            input.removeClass('is-invalid').addClass('is-valid');
            mensaje.text('');
            return true;
        } else {
            input.removeClass('is-valid').addClass('is-invalid');
            mensaje.text(textoError);
            return false;
        }
    }

    function verificarCampoVacio(input, mensaje, textoError) {
        if (input.val().trim() === '') {
            input.removeClass('is-valid').addClass('is-invalid');
            mensaje.text(textoError);
            return false;
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
            mensaje.text('');
            return true;
        }
    }

    function validarEnvio() {
        var esValido = true;
        esValido &= verificarCampoVacio($('#atleta'), $('#satleta'), 'Seleccione un atleta');
        esValido &= verificarCampoVacio($('#status'), $('#sstatus'), 'Seleccione un status');
        esValido &= verificarCampoVacio($('#inscrito'), $('#sinscrito'), 'Seleccione una fecha de inscripción');
        esValido &= verificarCampoVacio($('#ultima_actualizacion'), $('#sultima_actualizacion'), 'Seleccione la última actualización');
        esValido &= verificarCampoVacio($('#vencimiento'), $('#svencimiento'), 'Seleccione la fecha de vencimiento');
        return esValido;
    }

    $("#incluir, #modificar, #eliminar").on("click", function () {
        var action = $(this).attr("id");
        if (validarEnvio()) {
            $("#accion").val(action);
            var datos = new FormData($("#f1")[0]);
            enviaAjax(datos);
        }
    });

    function enviaAjax(datos) {
        $.ajax({
            async: true,
            url: '',
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
                    if (lee.devol == 'listado_wada') {
                        if ($.fn.DataTable.isDataTable("#tablaWada")) {
                            $("#tablaWada").DataTable().destroy();
                        }
                        let listado_wada = "";
                        lee.respuesta.forEach((registro) => {
                            listado_wada += `
                                <tr>
                                    <td class='align-middle'>${registro.id_atleta}</td>
                                    <td class='align-middle'>${registro.estado == 1 ? 'Cumple' : 'No Cumple'}</td>
                                    <td class='align-middle'>${registro.inscrito}</td>
                                    <td class='align-middle'>${registro.ultima_actualizacion}</td>
                                    <td class='align-middle'>${registro.vencimiento}</td>
                                    <td class='align-middle'>
                                        <button class='btn btn-block btn-warning me-2' onclick="obtenerWada('${registro.id_atleta}')">Modificar</button>
                                        <button class='btn btn-block btn-danger' onclick="eliminarWada('${registro.id_atleta}')">Eliminar</button>
                                    </td>
                                </tr>`;
                        });
                        $("#listado").html(listado_wada);
                        if (!$.fn.DataTable.isDataTable("#tablaWada")) {
                            $("#tablaWada").DataTable({
                                columnDefs: [
                                    { targets: [5], orderable: false, searchable: false },
                                ],
                                language: {
                                    lengthMenu: "Mostrar _MENU_ por página",
                                    zeroRecords: "No se encontraron registros",
                                    info: "Mostrando página _PAGE_ de _PAGES_",
                                    infoEmpty: "No hay registros disponibles",
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
                        }
                    } else {
                        Swal.fire("Éxito", "Operación realizada con éxito", "success");
                        $("#f1")[0].reset();
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
            complete: function () {}
        });
    }

    function obtenerWada(id_atleta) {
        var datos = new FormData();
        datos.append("accion", "obtener_wada");
        datos.append("atleta", id_atleta);
        $.ajax({
            async: true,
            url: '',
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
                    if (lee.ok) {
                        var wada = lee.wada;
                        $("#atleta_modificar").val(wada.id_atleta);
                        $("#status_modificar").val(wada.estado);
                        $("#inscrito_modificar").val(wada.inscrito);
                        $("#ultima_actualizacion_modificar").val(wada.ultima_actualizacion);
                        $("#vencimiento_modificar").val(wada.vencimiento);
                        $("#modalModificar").modal("show");
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
            complete: function () {}
        });
    }

    function eliminarWada(id_atleta) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "No podrás revertir esto",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar"
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData();
                datos.append("accion", "eliminar");
                datos.append("atleta", id_atleta);
                $.ajax({
                    async: true,
                    url: '',
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
                            if (lee.ok) {
                                Swal.fire("Eliminado", "El registro ha sido eliminado", "success");
                                carga_listado_wada();
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
                    complete: function () {}
                });
            }
        });
    }

    $('input, select').on('keyup change', function() {
        var id = $(this).attr('id');
        switch(id) {
            case 'atleta':
                verificarCampoVacio($(this), $('#satleta'), 'Seleccione un atleta');
                break;
            case 'status':
                verificarCampoVacio($(this), $('#sstatus'), 'Seleccione un status');
                break;
            case 'inscrito':
                verificarCampoVacio($(this), $('#sinscrito'), 'Seleccione una fecha de inscripción');
                break;
            case 'ultima_actualizacion':
                verificarCampoVacio($(this), $('#sultima_actualizacion'), 'Seleccione la última actualización');
                break;
            case 'vencimiento':
                verificarCampoVacio($(this), $('#svencimiento'), 'Seleccione la fecha de vencimiento');
                break;
        }
    });
});
