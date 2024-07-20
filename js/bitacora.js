$(document).ready(function () {
    function cargaListadoBitacora() {
        const datos = new FormData();
        datos.append("accion", "listado_bitacora");
        enviaAjax(datos);
    }

    cargaListadoBitacora();
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
        beforeSend: function () { },
        timeout: 10000,
        success: function (respuesta) {
            try {
                const lee = JSON.parse(respuesta);
                if (lee.ok) {
                    console.log(lee.respuesta)
                    actualizarListadoBitacora(lee.respuesta);
                } else if (lee.ok) {
                    Swal.fire("Éxito", "Operación realizada con éxito", "success");
                    cargaListadoBitacora();
                    $('#modalModificar').modal('hide');
                } else {
                    Swal.fire("Error", lee.mensaje, "error");
                }
            } catch (error) {
                Swal.fire("Error", "Algo salió mal", "error");
            }
        },
        error: function (request, status, err) {
            const errorMsg = status === "timeout" ? "Servidor ocupado, Intente de nuevo" : "Error al procesar la solicitud";
            Swal.fire("Error", errorMsg, "error");
        },
        complete: function () { },
    });
}

function actualizarListadoBitacora(bitacora) {
    let listadoBitacora = "";
    if ($.fn.DataTable.isDataTable("#tablabitacora")) {
        $("#tablabitacora").DataTable().destroy();
    }
    bitacora.forEach(elemento => {
        listadoBitacora += `
                <tr>
                <td class='align-middle text-capitalize'>${elemento.id_usuario}</td>
                    <td class='align-middle text-capitalize'>${elemento.accion}</td>
                    <td class='align-middle text-capitalize'>${elemento.fecha}</td>
                    ${elemento.usuario_modificado !== null ? `<td class='align-middle text-capitalize'>${elemento.usuario_modificado}</td>`
                :
                `<td class='align-middle text-capitalize'><span class='badge bg-secondary'>No</span></td>`}
                ${elemento.valor_cambiado !== null ? `<td class='align-middle text-capitalize'>${elemento.valor_cambiado}</td>`
                :
                `<td class='align-middle text-capitalize'><span class='badge bg-secondary'>No</span></td>`}                    
                </tr>
            `;
    });

    $("#listado").html(listadoBitacora);
    $("#tablabitacora").DataTable({
        language: {
            lengthMenu: "Mostrar _MENU_ por página",
            zeroRecords: "No se encontraron acciones",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay roles disponibles",
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