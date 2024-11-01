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
    beforeSend: function () {},
    timeout: 10000,
    success: function (respuesta) {
      try {
        const lee = JSON.parse(respuesta);
        if (lee.ok) {
          if (lee.devol === "listado_bitacora") {
            actualizarListadoBitacora(lee.respuesta);
          } else if (lee.devol === "consultar_accion") {
            accion = lee.respuesta;
            detalles = "";
            if (accion.detalles) {
              const detalleArray = accion.detalles
                .split(";")
                .filter((item) => item.trim() !== "");
              detalleArray.forEach((detalle) => {
                detalles = detalles + `<li>${detalle.trim()}</li>`;
              });
            }
            contenido = `
            <div class="container">
                    <div class="row my-3">
                        <div class="col text-center">
                            <span class="fw-bold">Usuario</span>
                            <span class="d-block">${accion.id_usuario}</span>
                        </div>
                        <div class="col text-center">
                            <span class="fw-bold">Fecha</span>
                            <span class="d-block">${accion.fecha}</span>
                        </div>
                        <div class="col text-center">
                            <span class="fw-bold">Accion</span>
                            <span class="d-block">${accion.accion}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col text-center">
                            <span class="fw-bold">Registro afectado</span>
                            <span class="d-block">${
                              accion.usuario_modificado
                                ? accion.usuario_modificado
                                : "No"
                            }</span>
                        </div>
                        <div class="col text-center">
                            <span class="fw-bold">Modulo</span>
                            <span class="d-block">${accion.modulo}</span>
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
            console.log(lee.respuesta);
          }
        } else {
          Swal.fire("Error", lee.mensaje, "error");
        }
      } catch (error) {
        Swal.fire("Error", "Algo salió mal", "error");
        console.log(error);
      }
    },
    error: function (request, status, err) {
      const errorMsg =
        status === "timeout"
          ? "Servidor ocupado, Intente de nuevo"
          : "Error al procesar la solicitud";
      Swal.fire("Error", errorMsg, "error");
    },
    complete: function () {},
  });
}
function consultarBitacora(id) {
  const datos = new FormData();
  datos.append("accion", "consultar_accion");
  datos.append("id_accion", id);
  enviaAjax(datos);
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
                    <td class='align-middle text-capitalize'>${
                      elemento.accion
                    }</td>
                    <td class='align-middle text-capitalize'>${
                      elemento.modulo
                    }</td>
                    <td class='align-middle text-capitalize'>${
                      elemento.fecha
                    }</td>
                    ${
                      elemento.usuario_modificado !== null
                        ? `<td class='align-middle text-capitalize'>${elemento.usuario_modificado}</td>`
                        : `<td class='align-middle text-capitalize'><span class='badge bg-secondary'>No</span></td>`
                    }    
                <td class='align-middle text-capitalize'><button class='btn btn-sm btn-warning' onclick="consultarBitacora(${
                  elemento.id_accion
                })">Ver</button></td>                    
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
