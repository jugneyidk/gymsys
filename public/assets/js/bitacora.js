import { enviaAjax, obtenerNotificaciones, modalCarga } from "./comunes.js";
$(document).ready(function () {
   $("#tablabitacora").DataTable({
      serverSide: true,
      ajax: {
         url: '?p=bitacora&accion=listadoBitacora',
         type: 'POST',
         dataSrc: function (json) {
            json.draw = json.data.draw;
            json.recordsTotal = json.data.recordsTotal;
            json.recordsFiltered = json.data.recordsFiltered;
            return json.data.data; // aquí va el array de filas
         },
         beforeSend: function () {
            modalCarga(true);
         },
         complete: function () {
            modalCarga(false);
         }
      },
      columns: [
         { data: 'id_usuario' },
         {
            data: 'accion',
            render: function (data, type, row) {
               return `<span class='w-100 badge rounded-2 text-body bg-${row.accion === "Incluir"
                  ? "primary"
                  : row.accion === "Modificar"
                     ? "warning"
                     : "danger"
                  }-subtle'>${row.accion}</span>`;
            }
         },
         { data: 'modulo' },
         {
            data: 'registro_modificado',
            render: function (data, type, row) {
               return row.registro_modificado !== null
                  ? row.registro_modificado
                  : `<span class='badge bg-secondary'>No</span>`;
            }
         },
         { data: 'fecha' },
         {
            data: null, // la data completa del registro
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
               return `<button class='btn btn-sm btn-warning ver-detalles' data-id='${row.id_accion}'>Ver</button>`;
            }
         }
      ],
      language: {
         lengthMenu: "Mostrar _MENU_ por página",
         loadingRecords: "Cargando...",
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
      order: [[4, "desc"]],
   });
   obtenerNotificaciones();
   setInterval(() => obtenerNotificaciones(), 35000);
});
$("#tablabitacora").on("click", ".btn-warning", function () {
   const idAccion = $(this).data('id');
   enviaAjax("", `?p=bitacora&accion=obtenerAccion&id=${idAccion}`, "GET").then((respuesta) => {
      llenarDetallesAccion(respuesta.accion);
   });
});

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
   var contenido = `<div class="container">
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
                            <span class="d-block">${respuesta.registro_modificado
         ? respuesta.registro_modificado
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
                </div>`;
   $("#modalBody").html(contenido);
   $("#modal").modal("show");
}