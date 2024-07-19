$(document).ready(function () {
    $('#btnGenerarReporte').on('click', function () {
        var datos = new FormData($('#formReportes')[0]);
        datos.append('accion', 'obtener_reportes');
        enviaAjax(datos);
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
            success: function (respuesta) {
                try {
                    var lee = JSON.parse(respuesta);
                    if (lee.ok) {
                        var listado_reportes = "";
                        if ($.fn.DataTable.isDataTable("#tablaReportes")) {
                            $("#tablaReportes").DataTable().destroy();
                        }
                        lee.reportes.forEach((reporte) => {
                            listado_reportes += "<tr>";
                            listado_reportes += "<td>" + reporte.id + "</td>";
                            listado_reportes += "<td>" + reporte.nombre + "</td>";
                            listado_reportes += "<td>" + reporte.detalles + "</td>";
                            listado_reportes += "<td>" + reporte.fecha + "</td>";
                            listado_reportes += "</tr>";
                        });
                        $("#listadoReportes").html(listado_reportes);
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
        var tipoReporte = $('#tipoReporte').val();
        var fechaInicio = $('#fechaInicio').val();
        var fechaFin = $('#fechaFin').val();
        window.location.href = 'reportes_pdf.php?tipoReporte=' + tipoReporte + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin;
    });
});
