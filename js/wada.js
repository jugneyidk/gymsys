$(document).ready(function() {
    // Simular carga de atletas
    var atletas = [
        {id: 1, nombre: 'Juan Pérez'},
        {id: 2, nombre: 'María Gómez'},
        {id: 3, nombre: 'Carlos Ramírez'}
    ];

    var registrosWada = [];

    atletas.forEach(function(atleta) {
        var opcion = `<option value="${atleta.id}">${atleta.nombre}</option>`;
        $('#atleta').append(opcion);
    });

    function actualizarEstadisticas() {
        var totalCumplen = registrosWada.filter(registro => registro.status === 'cumple').length;
        var totalNoCumplen = registrosWada.filter(registro => registro.status === 'no_cumple').length;
        var totalAtletas = registrosWada.length;

        $('#totalCumplen').text(totalCumplen);
        $('#totalNoCumplen').text(totalNoCumplen);
        $('#totalAtletas').text(totalAtletas);
    }

    function actualizarUltimosRegistros() {
        $('#listaUltimosRegistros').empty();
        registrosWada.slice(-5).forEach(function(registro) {
            var item = `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${registro.nombre} - ${registro.status}
                    <span class="badge bg-primary rounded-pill">${registro.fecha}</span>
                </li>`;
            $('#listaUltimosRegistros').append(item);
        });
    }

    $('#wadaForm').on('submit', function(e) {
        e.preventDefault();
        
        var atletaId = $('#atleta').val();
        var status = $('#status').val();
        var inscrito = $('#inscrito').val();
        var ultima_actualizacion = $('#ultima_actualizacion').val();
        var vencimiento = $('#vencimiento').val();

        if (atletaId && status && inscrito && ultima_actualizacion && vencimiento) {
            var atletaNombre = $('#atleta option:selected').text();

            var nuevoRegistro = {
                id: atletaId,
                nombre: atletaNombre,
                status: status,
                inscrito: inscrito,
                ultima_actualizacion: ultima_actualizacion,
                vencimiento: vencimiento,
                fecha: new Date().toLocaleDateString()
            };

            registrosWada.push(nuevoRegistro);

            actualizarEstadisticas();
            actualizarUltimosRegistros();

            $('#mensaje').html('<div class="alert alert-success">Status WADA registrado exitosamente.</div>');
            $('#wadaForm')[0].reset();
        } else {
            $('#mensaje').html('<div class="alert alert-danger">Por favor, complete todos los campos.</div>');
        }
    });
});
