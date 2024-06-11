$(document).ready(function() {
    $('#crearAsistenciaBtn').click(function() {
        var fecha = $('#fechaAsistencia').val();
        if (fecha) {
            //llamada AJAX para crear la asistencia en la base de datos pero no se, ando con  la mente ida
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
        } else {
            alert('Por favor, selecciona una fecha.');
        }
    });

    // Mostrar formulario de toma de asistencia depues que se agregue y se le de clicj a tomar asistencia
    $('#listaAsistencias').on('click', '.btn-tomar-asistencia', function() {
        var fecha = $(this).data('fecha');
        $('#fechaAsistenciaLabel').text(fecha);
        $('#formTomarAsistencia').show();

        // Simulacion carga de atletas
        var atletas = [
            {nombre: 'Juan', apellido: 'Pérez'},
            {nombre: 'María', apellido: 'Gómez'},
            {nombre: 'Carlos', apellido: 'Ramírez'}
        ];

        $('#listaAtletas').empty();
        atletas.forEach(function(atleta) {
            var fila = `
                <tr>
                    <td>${atleta.nombre}</td>
                    <td>${atleta.apellido}</td>
                    <td>
                        <input type="checkbox" class="form-check-input" data-nombre="${atleta.nombre}" data-apellido="${atleta.apellido}">
                    </td>
                </tr>`;
            $('#listaAtletas').append(fila);
        });
    });

    // Guardar la asistencia
    $('#guardarAsistenciaBtn').click(function() {
        var asistencias = [];
        $('#listaAtletas input:checked').each(function() {
            asistencias.push({
                nombre: $(this).data('nombre'),
                apellido: $(this).data('apellido')
            });
        });
        //  llamada AJAX para guardar las asistencias en la base de datos pero nohay
        console.log(asistencias);
        alert('Asistencia guardada exitosamente.');
        $('#formTomarAsistencia').hide();
    });
});
