$(document).ready(function() {
    function enviarAjax(datos, successCallback) {
        $.ajax({
            async: true,
            url: '',
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function(respuesta) {
                var lee = JSON.parse(respuesta);
                if (lee.ok) {
                    successCallback(lee);
                } else {
                    Swal.fire("Error", lee.mensaje, "error");
                }
            },
            error: function(request, status, err) {
                Swal.fire("Error", "Error al procesar la solicitud", "error");
            }
        });
    }

    $('#crearAsistenciaBtn').click(function() {
        var fecha = $('#fechaAsistencia').val();
        if (fecha) {
            var datos = new FormData();
            datos.append("accion", "crear");
            datos.append("fecha", fecha);

            enviarAjax(datos, function(lee) {
                var nuevaTarjeta = `
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">${fecha}</h5>
                                <p class="card-text">Estado: <span class="badge bg-warning">Pendiente</span></p>
                            </div>
                            <button class="btn btn-success btn-tomar-asistencia" data-fecha="${fecha}">Tomar asistencia</button>
                        </div>
                    </div>`;
                $('#listaAsistencias').append(nuevaTarjeta);
            });
        } else {
            alert('Por favor, selecciona una fecha.');
        }
    });

    $('#listaAsistencias').on('click', '.btn-tomar-asistencia', function() {
        var fecha = $(this).data('fecha');
        $('#fechaAsistenciaLabel').text(fecha);
        $('#formTomarAsistencia').show();

        var datos = new FormData();
        datos.append("accion", "listar_atletas");

        enviarAjax(datos, function(lee) {
            $('#listaAtletas').empty();
            lee.respuesta.forEach(function(atleta) {
                var fila = `
                    <tr>
                        <td>${atleta.nombre}</td>
                        <td>${atleta.apellido}</td>
                        <td>
                            <input type="checkbox" class="form-check-input" data-id="${atleta.id}">
                        </td>
                    </tr>`;
                $('#listaAtletas').append(fila);
            });
        });
    });

    $('#guardarAsistenciaBtn').click(function() {
        var asistencias = [];
        $('#listaAtletas input:checked').each(function() {
            asistencias.push($(this).data('id'));
        });

        var fecha = $('#fechaAsistenciaLabel').text();
        var datos = new FormData();
        datos.append("accion", "guardar");
        datos.append("fecha", fecha);
        datos.append("asistencias", JSON.stringify(asistencias));

        enviarAjax(datos, function() {
            Swal.fire("Éxito", "Asistencia guardada exitosamente.", "success");
            $('#formTomarAsistencia').hide();
        });
    });

    function cargarListadoAsistencias() {
        var datos = new FormData();
        datos.append("accion", "listado");

        enviarAjax(datos, function(lee) {
            $('#listaAsistencias').empty();
            lee.respuesta.forEach(function(asistencia) {
                var tarjeta = `
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">${asistencia.fecha}</h5>
                                <p class="card-text">Estado: <span class="badge bg-success">Completado</span></p>
                            </div>
                            <button class="btn btn-success btn-tomar-asistencia" data-fecha="${asistencia.fecha}">Tomar asistencia</button>
                        </div>
                    </div>`;
                $('#listaAsistencias').append(tarjeta);
            });
        });
    }

    // Cargar el listado de asistencias al cargar la página
    cargarListadoAsistencias();
});
