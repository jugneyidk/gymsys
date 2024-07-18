$(document).ready(function () {
    carga_listado_wada();
    cargaProximosVencer();

    function carga_listado_wada() {
        var datos = new FormData();
        datos.append("accion", "listado_wada");
        enviaAjax(datos, actualizarTablaWada);
    }

    function enviaAjax(datos, callback) {
        $.ajax({
            async: true,
            url: '',
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                var lee = JSON.parse(respuesta);
                if (lee.ok) {
                    callback(lee.respuesta);
                } else {
                    Swal.fire("Error", lee.mensaje, "error");
                }
            },
            error: function (xhr, status, error) {
                Swal.fire("Error", "Hubo un problema con la petición: " + error, "error");
            }
        });
    }

    function actualizarTablaWada(data) {
        var html = "";
        data.forEach(function (registro) {
            html += `<tr>
                        <td>${registro.nombre} ${registro.apellido}</td>
                        <td>${registro.estado === '1' ? 'Cumple' : 'No Cumple'}</td>
                        <td>${registro.inscrito}</td>
                        <td>${registro.ultima_actualizacion}</td>
                        <td>${registro.vencimiento}</td>
                        <td>
                            <button onclick="editarWada('${registro.cedula}')" class="btn btn-warning">Editar</button>
                            <button onclick="eliminarWada('${registro.cedula}')" class="btn btn-danger">Eliminar</button>
                        </td>
                    </tr>`;
        });
        $('#tablaWada tbody').html(html);

        if ($.fn.DataTable.isDataTable("#tablaWada")) {
            $('#tablaWada').DataTable().clear().destroy();
        }
        $('#tablaWada').DataTable();
    }

    function actualizarTablaProximosVencer(data) {
        var html = "";
        data.forEach(function (registro) {
            html += `<tr>
                        <td>${registro.nombre} ${registro.apellido}</td>
                        <td>${registro.cedula}</td>
                        <td>${registro.vencimiento}</td>
                        <td>
                            <button onclick="editarWada('${registro.cedula}')" class="btn btn-warning">Actualizar</button>
                        </td>
                    </tr>`;
        });
        $('#tablaProximosVencer tbody').html(html);

        if ($.fn.DataTable.isDataTable("#tablaProximosVencer")) {
            $('#tablaProximosVencer').DataTable().clear().destroy();
        }
        $('#tablaProximosVencer').DataTable();
    }

    function cargaProximosVencer() {
        var datos = new FormData();
        datos.append("accion", "obtener_proximos_vencer");
        enviaAjax(datos, actualizarTablaProximosVencer);
    }

    $("#f1").submit(function (e) {
        e.preventDefault();
        var datos = new FormData(this);
        datos.append("accion", "incluir");
        enviaAjax(datos, function() {
            Swal.fire("Éxito", "Registro añadido correctamente", "success");
            $('#modalInscripcion').modal('hide');
            carga_listado_wada();
            cargaProximosVencer();
        });
    });

    $("#f2").submit(function (e) {
        e.preventDefault();
        var datos = new FormData(this);
        datos.append("accion", "modificar");
        enviaAjax(datos, function() {
            Swal.fire("Éxito", "Registro modificado correctamente", "success");
            $('#modalModificar').modal('hide');
            carga_listado_wada();
            cargaProximosVencer();
        });
    });

    window.editarWada = function(cedula) {
        var datos = new FormData();
        datos.append("accion", "obtener_wada");
        datos.append("atleta", cedula);
        $.ajax({
            async: true,
            url: '',
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                var lee = JSON.parse(respuesta);
                if (lee.ok) {
                    var wada = lee.wada;
                    $("#atleta_modificar").val(wada.cedula);
                    $("#status_modificar").val(wada.estado);
                    $("#inscrito_modificar").val(wada.inscrito);
                    $("#ultima_actualizacion_modificar").val(wada.ultima_actualizacion);
                    $("#vencimiento_modificar").val(wada.vencimiento);
                    $("#modalModificar").modal("show");
                } else {
                    Swal.fire("Error", lee.mensaje, "error");
                }
            },
            error: function (request, status, err) {
                Swal.fire("Error", "Error al procesar la solicitud", "error");
            }
        });
    };

    window.eliminarWada = function(cedula) {
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
                datos.append("atleta", cedula);
                enviaAjax(datos, function() {
                    Swal.fire("Eliminado", "El registro ha sido eliminado", "success");
                    carga_listado_wada();
                    cargaProximosVencer();
                });
            }
        });
    };

    function carga_atletas() {
        var datos = new FormData();
        datos.append("accion", "listado_atletas");
        $.ajax({
            async: true,
            url: '',
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            success: function (respuesta) {
                var lee = JSON.parse(respuesta);
                if (lee.ok) {
                    var opciones = "<option value=''>Seleccione un atleta</option>";
                    lee.respuesta.forEach(function (atleta) {
                        opciones += `<option value='${atleta.cedula}'>${atleta.nombre} ${atleta.apellido}</option>`;
                    });
                    $("#atleta").html(opciones);
                    $("#atleta_modificar").html(opciones);
                } else {
                    Swal.fire("Error", lee.mensaje, "error");
                }
            },
            error: function (request, status, err) {
                Swal.fire("Error", "Error al procesar la solicitud", "error");
            }
        });
    }

    carga_atletas();
});
