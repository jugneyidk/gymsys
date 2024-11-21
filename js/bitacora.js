import { enviaAjax, obtenerNotificaciones } from "./comunes.js";
$(document).ready(function () {
  function cargaListadoBitacora() {
    const datos = new FormData();
    datos.append("accion", "listado_bitacora");
    enviaAjax(datos, "").then((respuesta) => {
      actualizarListadoBitacora(respuesta.respuesta);
    });
  }
  obtenerNotificaciones(idUsuario);
  setInterval(() => obtenerNotificaciones(idUsuario), 35000);
  cargaListadoBitacora();
});
window.consultarBitacora = function (id) {
  const datos = new FormData();
  datos.append("accion", "consultar_accion");
  datos.append("id_accion", id);
  enviaAjax(datos, "").then((respuesta) => {
    llenarDetallesAccion(respuesta.respuesta);
  });
};
function llenarDetallesAccion(respuesta) {
  var detalles = "";
  if (respuesta.detalles) {
    const detalleArray = respuesta.detalles
      .split(";")
      .filter((item) => item.trim() !== "");
    detalleArray.forEach((detalle) => {
      detalles = detalles + `<li>${detalle.trim()}</li>`;
    });
  }
  var contenido = `
            <div class="container">
                    <div class="row my-3">
                        <div class="col text-center">
                            <span class="fw-bold">Usuario</span>
                            <span class="d-block">${respuesta.id_usuario}</span>
                        </div>
                        <div class="col text-center">
                            <span class="fw-bold">Fecha</span>
                            <span class="d-block">${respuesta.fecha}</span>
                        </div>
                        <div class="col text-center">
                            <span class="fw-bold">Accion</span>
                            <span class="d-block">${respuesta.accion}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col text-center">
                            <span class="fw-bold">Registro afectado</span>
                            <span class="d-block">${
                              respuesta.usuario_modificado
                                ? respuesta.usuario_modificado
                                : "No"
                            }</span>
                        </div>
                        <div class="col text-center">
                            <span class="fw-bold">Modulo</span>
                            <span class="d-block">${respuesta.modulo}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col text-center">
                            <span class="fw-bold">Detalles</span>
                            <ol class="text-start">
                                ${detalles}
                            </ol>
                        </div>
                    </div>
                </div>            
            `;
  $("#modalBody").html(contenido);
  $("#modal").modal("show");
}

function actualizarListadoBitacora(bitacora) {
  let listadoBitacora = "";
  if ($.fn.DataTable.isDataTable("#tablabitacora")) {
    $("#tablabitacora").DataTable().destroy();
  }
  bitacora.forEach((elemento) => {
    listadoBitacora += `
                <tr>
                    <td class='align-middle text-capitalize d-none'>${
                      elemento.id_accion
                    }</td>
                    <td class='align-middle text-capitalize'>${
                      elemento.id_usuario
                    }</td>
                    <td class='text-center align-middle text-capitalize'><span class='w-100 badge rounded-4 bg-${
                      elemento.accion === "Incluir"
                        ? "primary"
                        : elemento.accion === "Modificar"
                        ? "warning"
                        : "danger"
                    }'>${elemento.accion}</span></td>
                    <td class='align-middle text-capitalize'>${
                      elemento.modulo
                    }</td>
                    <td class='align-middle text-capitalize d-none d-md-table-cell'>${
                      elemento.fecha
                    }</td>
                    ${
                      elemento.usuario_modificado !== null
                        ? `<td class='align-middle text-capitalize d-none d-md-table-cell'>${elemento.usuario_modificado}</td>`
                        : `<td class='align-middle text-capitalize d-none d-md-table-cell'><span class='badge bg-secondary'>No</span></td>`
                    }    
                <td class='align-middle text-capitalize'><button class='btn btn-sm btn-warning' onclick="consultarBitacora(
                  ${elemento.id_accion}
                )">Ver</button></td>                    
                </tr>
            `;
  });

  $("#listado").html(listadoBitacora);
  $("#tablabitacora").DataTable({
    columnDefs: [{ targets: [6], orderable: false, searchable: false }],
    language: {
      lengthMenu: "Mostrar _MENU_ por página",
      zeroRecords: "No se encontraron acciones",
      info: "Mostrando página _PAGE_ de _PAGES_",
      infoEmpty: "No hay roles disponibles",
      infoFiltered: "(filtrado de _MAX_ registros totales)",
      search: "Buscar:",
      paginate: {
        next: "Siguiente",
        previous: "Anterior",
      },
    },
    autoWidth: false,
    order: [[0, "desc"]],
    dom: '<"top"f>rt<"bottom"lp><"clear">',
  });
}
