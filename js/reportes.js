$(document).ready(function () {
    // Ocultar todos los filtros inicialmente
    $('#filtrosAtletas, #filtrosEntrenadores, #filtrosMensualidades, #filtrosWada, #filtrosEventos, #filtrosAsistencias').hide();

    // Mostrar filtros basados en el tipo de reporte seleccionado
    $('#tipoReporte').change(function () {
        $('#filtrosAtletas, #filtrosEntrenadores, #filtrosMensualidades, #filtrosWada, #filtrosEventos, #filtrosAsistencias').hide();
        switch ($(this).val()) {
            case 'atletas':
                $('#filtrosAtletas').show();
                break;
            case 'entrenadores':
                $('#filtrosEntrenadores').show();
                break;
            case 'mensualidades':
                $('#filtrosMensualidades').show();
                break;
            case 'wada':
                $('#filtrosWada').show();
                break;
            case 'eventos':
                $('#filtrosEventos').show();
                break;
            case 'asistencias':
                $('#filtrosAsistencias').show();
                break;
        }
    });

    // Manejo del evento al hacer clic en "Generar Reporte"
    $('#btnGenerarReporte').on('click', function () {
        var datos = new FormData($('#formReportes')[0]);
        datos.append('accion', 'obtener_reportes');
        enviaAjax(datos);
    });

    // Función para manejar la solicitud AJAX
    function enviaAjax(datos) {
        $.ajax({
            async: true,
            url: "", // Asegúrate de configurar correctamente la URL
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.ok) {
                        var listado_reportes = "";
                        // Destruir DataTable si ya existe
                        if ($.fn.DataTable.isDataTable("#tablaReportes")) {
                            $("#tablaReportes").DataTable().destroy();
                        }
                        // Crear nuevo contenido para la tabla
                        lee.reportes.forEach((reporte) => {
                            listado_reportes += "<tr>";
                            listado_reportes += "<td>" + reporte.id + "</td>";
                            listado_reportes += "<td>" + reporte.nombre + "</td>";
                            listado_reportes += "<td>" + reporte.detalles + "</td>";
                            listado_reportes += "<td>" + reporte.fecha + "</td>";
                            listado_reportes += "</tr>";
                        });
                        // Insertar contenido en la tabla
                        $("#listadoReportes").html(listado_reportes);
                        // Inicializar DataTable
                        $("#tablaReportes").DataTable({
                            language: {
                                lengthMenu: "Mostrar _MENU_ por página",
                                zeroRecords: "No se encontraron reportes",
                                info: "Mostrando página _PAGE_ de _PAGES_",
                                infoEmpty: "No hay reportes disponibles",
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
                    } else {
                        Swal.fire("Error", lee.mensaje, "error");
                    }
                } catch (error) {
                    Swal.fire("Error", "Algo salió mal", "error");
                }
            },
            error: function (request, status, err) {
                Swal.fire("Error", "Error al procesar la solicitud", "error");
            }
        });
    }

    $('#btnDescargarPDF').on('click', function () {
        var datos = $('#formReportes').serialize();
        var popup = window.open('', 'popup', 'width=800,height=600');
        $.ajax({
            url: 'reportes_pdf.php',
            type: 'POST',
            data: datos,
            xhrFields: {
                responseType: 'blob'
            },
            success: function (data) {
                var url = window.URL.createObjectURL(data);
                popup.location.href = url;
            }
        });
    });
});
