$(document).ready(function () {
    function carga_listado_wada() {
        var datos = new FormData();
        datos.append("accion", "listado_");
        enviaAjax(datos);
    }
    //carga_listado_();

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

    $("#incluir").on("click", function() {
        if (validarEnvio()) {
            var datos = new FormData($("#f")[0]);
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
            success: function(respuesta){
                try{
                    var lee = JSON.parse(respuesta);
                    if (lee.devol == 'listado_atletas') {
                        if ($.fn.DataTable.isDataTable("#tablaatleta")) {
                            $("#tablahabitantes").DataTable().destroy();
                        }
                        $("#listado").html(lee.mensaje);
                        if (!$.fn.DataTable.isDataTable("#tablaatleta")) {
                            $("#tablaatleta").DataTable({
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
                                order: [[1, "asc"]],
                            });
                        }
                    } else {
                        Swal.fire("Éxito", "Operación realizada con éxito", "success");
                        $("#f")[0].reset();
                    }
                } catch {
                    Swal.fire("Error", "algo salió mal", "error");
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

