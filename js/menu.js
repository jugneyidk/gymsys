$(document).ready(function () {
    // Realizamos la solicitud AJAX al cargar la página
    $.ajax({
        url: '', // Coloca la URL correcta de tu controlador
        method: 'POST',
        data: { accion: 'estadisticas' },
        success: function (response) {
            // Parseamos los datos recibidos desde el servidor
            var data = JSON.parse(response);

            // Actualizamos el gráfico de medallas en competencias
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels_medallas, // etiquetas dinámicas
                    datasets: [{
                        label: '# medallas en competencias',
                        data: data.medallas_por_mes, // datos dinámicos
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Actualizamos el gráfico de Medallas por mes
            var ctx2 = document.getElementById('myChart1').getContext('2d');
            var myChart1 = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: data.labels_medallas, // etiquetas dinámicas
                    datasets: [{
                        label: 'Medallas por mes',
                        data: data.medallas_por_mes, // datos dinámicos
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Actualizamos el gráfico de progreso semanal
            var ctxProgress = document.getElementById('progressChart').getContext('2d');
            var progressChart = new Chart(ctxProgress, {
                type: 'line',
                data: {
                    labels: data.labels_progreso, // etiquetas dinámicas
                    datasets: [{
                        label: 'Progreso de levantamientos (kg)',
                        data: data.progreso_semanal, // datos dinámicos
                        fill: false,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Kilogramos'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Semanas'
                            }
                        }
                    }
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
});
