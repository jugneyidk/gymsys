import { validarKeyPress, validarKeyUp, enviaAjax, muestraMensaje } from "./comunes.js";
$(document).ready(function () {
    function cargarListadoAtletas() {
        var datos = new FormData();
        datos.append('accion', 'obtener_atletas');
        enviaAjax(datos, "").then((respuesta) => {
            actualizarListadoAtletas(respuesta.atletas);
        });
    }

    function enviarAsistencias() {
        var fecha = $('#fechaAsistencia').val();
        if (!fecha) {
            muestraMensaje("Error", "Debe seleccionar una fecha", "error");
            return;
        }

        var asistencias = [];
        $('#listadoAsistencias tr').each(function () {
            var id = $(this).find('input[type="checkbox"]').data('id');
            var asistio = $(this).find('input[type="checkbox"]').is(':checked') ? 1 : 0;
            var comentario = $(this).find('input[type="text"]').val();

            asistencias.push({
                id_atleta: id,
                asistio: asistio,
                comentario: comentario
            });
        });

        var datos = new FormData();
        datos.append('accion', 'guardar_asistencias');
        datos.append('fecha', fecha);
        datos.append('asistencias', JSON.stringify(asistencias));
        enviaAjax(datos, "").then((respuesta) => {
            muestraMensaje("Éxito", "Asistencias guardadas correctamente.", "success");
        });
    }
    function actualizarListadoAtletas(atletas) {
        var listado = '';
        atletas.forEach(function (atleta) {
            listado += `
                        <tr>
                            <td>${atleta.cedula}</td>
                            <td>${atleta.nombre}</td>
                            <td>${atleta.apellido}</td>
                            <td><input type="checkbox" class="form-check-input" data-id="${atleta.cedula}" /></td>
                            <td><input type="text" class="form-control" data-id="${atleta.cedula}" /></td>
                        </tr>
                    `;
        });
        $('#listadoAsistencias').html(listado);
        $('#tablaAsistencias').DataTable({
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
            autoWidth: true,
            order: [[0, "desc"]],
            dom: '<"top"f>rt<"bottom"lp><"clear">',
        });
    }
    function obtenerAsistencias(fecha) {
        var datos = new FormData();
        datos.append('accion', 'obtener_asistencias');
        datos.append('fecha', fecha);
        enviaAjax(datos, "").then((respuesta) => {
            actualizarListadoAsistencias(respuesta.asistencias);
        });
    }
    function actualizarListadoAsistencias(asistencias, fecha) {
        var fechaSeleccionada = new Date(fecha);
        var fechaActual = new Date();
        var unDia = 24 * 60 * 60 * 1000; // Un día en milisegundos

        var deshabilitar = (fechaActual - fechaSeleccionada) >= unDia;

        $('#listadoAsistencias tr').each(function () {
            var id = $(this).find('input[type="checkbox"]').data('id');
            var asistencia = asistencias.find(function (asistencia) {
                return asistencia.id_atleta == id;
            });

            if (asistencia) {
                $(this).find('input[type="checkbox"]').prop('checked', asistencia.asistio == 1);
                $(this).find('input[type="text"]').val(asistencia.comentario);
            } else {
                $(this).find('input[type="checkbox"]').prop('checked', false);
                $(this).find('input[type="text"]').val('');
            }
            if (deshabilitar) {
                $(this).find('input[type="checkbox"]').prop('disabled', true);
                $(this).find('input[type="text"]').prop('disabled', true);
            } else {
                $(this).find('input[type="checkbox"]').prop('disabled', false);
                $(this).find('input[type="text"]').prop('disabled', false);
            }
        });
    }
    $('#btnGuardarAsistencias').on('click', function () {
        enviarAsistencias();
    });

    $('#fechaAsistencia').on('change', function () {
        var fecha = $(this).val();
        obtenerAsistencias(fecha);
    });

    cargarListadoAtletas();
});
