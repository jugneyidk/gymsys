import { enviaAjax, muestraMensaje, modalListener, REGEX, obtenerNotificaciones } from "./comunes.js";
$(document).ready(function () {
   cargaListadoWada();
   cargarListadoPorVencer();
   cargarAtletas();
   obtenerNotificaciones();
   setInterval(() => obtenerNotificaciones(), 35000);
   modalListener("WADA");

   function cargaListadoWada() {
      enviaAjax("", "?p=wada&accion=listadoWada", "GET").then((respuesta) => {
         actualizarTablaWada(respuesta.wada);
      });
   }

   function actualizarTablaWada(data) {
      var html = "";
      data.forEach(function (registro) {
         html += `<tr>
                <td class="d-none">${registro.cedula}</td>
                <td class="align-middle">${registro.nombre} ${registro.apellido
            }</td>
                <td class="text-center align-middle">${registro.estado === 1
               ? `<span class='badge rounded-pill bg-success' aria-label='Estado WADA del atleta ${registro.nombre} ${registro.apellido} se cumple' role='img' data-tooltip='tooltip' title="Cumple"><i class='fa-solid fa-check'></i></span>`
               : `<span class='badge rounded-pill bg-danger' aria-label='Estado WADA del atleta ${registro.nombre} ${registro.apellido} no se cumple' role='img' data-tooltip='tooltip' title="No Cumple"><i class='fa-solid fa-x'></i></span>`
            }</td>
                <td class="d-none d-xxl-table-cell align-middle">${registro.inscrito
            }</td>
                <td class="d-none d-md-table-cell align-middle">${registro.ultima_actualizacion
            }</td>
                <td class="align-middle">${registro.vencimiento}</td>
                <td class="align-middle">
                ${actualizar === 1
               ? `<button class='btn btn-block btn-warning me-2' aria-label='Modificar WADA del atleta ${registro.nombre} ${registro.apellido}' data-tooltip='tooltip' title='Modificar WADA' data-id=${registro.cedula}><i class='fa-regular fa-pen-to-square'></i></button>`
               : ""
            }  
                ${eliminar === 1
               ? `<button class='btn btn-block btn-danger me-2' aria-label='Eliminar WADA del atleta ${registro.nombre} ${registro.apellido}' data-tooltip='tooltip' title='Eliminar WADA' data-id=${registro.cedula}><i class='fa-regular fa-trash-can'></i></button>`
               : ""
            }  
                </td>
            </tr>`;
      });
      if ($.fn.DataTable.isDataTable("#tablaWada")) {
         $("#tablaWada").DataTable().clear().destroy();
      }
      $("#tablaWada tbody").html(html);
      $("#tablaWada").DataTable({
         autoWidth: false,
         lengthChange: false,
         columnDefs: [
            {
               targets: [6],
               className: "text-nowrap",
               orderable: false,
            },
         ],
         language: {
            lengthMenu: "Mostrar _MENU_ por página",
            zeroRecords: "No se encontraron registros",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            emptyTable: "No hay registros disponibles",
            search: "Buscar:",
            paginate: {
               first: "Primera",
               last: "Última",
               next: "Siguiente",
               previous: "Anterior",
            },
         },
      });
   }
   function actualizarTablaProximosVencer(data) {
      var html = "";
      data.forEach(function (registro) {
         var fechaVencimientoPasada = validarFechaVencidaPasada(registro.vencimiento) ? "class='table-danger'" : "";
         html += `<tr ${fechaVencimientoPasada}>
                  <td class="d-none">${registro.cedula}</td>
                  <td class="align-middle">${registro.nombre} ${registro.apellido
            }</td>
                  <td class="align-middle">${registro.vencimiento}</td>
                  <td class="align-middle">
                  ${actualizar === 1
               ? `<button class='btn btn-block btn-warning me-2' aria-label='Modificar WADA del atleta ${registro.nombre} ${registro.apellido}' data-tooltip='tooltip' title='Modificar WADA'><i class='fa-regular fa-pen-to-square'></i></button>`
               : ""
            }
                  </td>
              </tr>`;
      });
      if ($.fn.DataTable.isDataTable("#tablaProximosVencer")) {
         $("#tablaProximosVencer").DataTable().clear().destroy();
      }
      $("#tablaProximosVencer tbody").html(html);
      $("#tablaProximosVencer").DataTable({
         autoWidth: false,
         lengthChange: false,
         language: {
            lengthMenu: "Mostrar _MENU_ por página",
            zeroRecords: "No se encontraron registros",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "Buscar:",
            emptyTable: "No hay registros disponibles",
            paginate: {
               next: "Siguiente",
               previous: "Anterior",
            },
         },
      });
   }

   function cargarListadoPorVencer() {
      enviaAjax("", "?p=wada&accion=listadoPorVencer", "GET").then((respuesta) => {
         actualizarTablaProximosVencer(respuesta.wadas);
      });
   }

   $("#f1").submit(function (e) {
      e.preventDefault();
      var datos = new FormData(this);
      var accion = datos.get("accion") || "incluir";
      enviaAjax(datos, `?p=wada&accion=${accion}Wada`).then(() => {
         $("#modal").modal("hide");
         muestraMensaje("Exito", "La WADA se registró correctamente", "success");
         cargaListadoWada();
         cargarListadoPorVencer();
      });
   });
   function cargarAtletas() {
      enviaAjax("", "?p=atletas&accion=listadoAtletas", "GET").then((respuesta) => {
         var opciones = "<option value=''>Seleccione un atleta</option>";
         respuesta.atletas.forEach(function (atleta) {
            opciones += `<option value='${atleta.cedula}'>${atleta.cedula} - ${atleta.nombre} ${atleta.apellido}</option>`;
         });
         $("#atleta").html(opciones);
      });
   }
   $("#tablaWada,#tablaProximosVencer").on("click", ".btn-warning", function () {
      const cedula = $(this).data("id");
      enviaAjax("", `?p=wada&accion=obtenerWada&id=${cedula}`, "GET").then((respuesta) => {
         llenarFormularioModificar(respuesta.wada);
      });
   });
   $("#tablaWada").on("click", ".btn-danger", function () {
      Swal.fire({
         title: "¿Estás seguro?",
         text: "No podrás revertir esto",
         icon: "warning",
         showCancelButton: true,
         confirmButtonColor: "#3085d6",
         cancelButtonColor: "#d33",
         confirmButtonText: "Sí, eliminar",
      }).then((result) => {
         if (result.isConfirmed) {
            const cedula = $(this).data("id");
            var datos = new FormData();
            datos.append("cedula", cedula);
            enviaAjax(datos, "?p=wada&accion=eliminarWada").then((respuesta) => {
               cargaListadoWada();
               cargarListadoPorVencer();
               muestraMensaje(
                  "Exito",
                  respuesta.mensaje,
                  "success"
               );
            });
         }
      });
   });

   function llenarFormularioModificar(wada) {
      if (wada?.id_atleta) {
         $("#f1 #atleta").val(wada.id_atleta);
         $("#f1 #inscrito").val(wada.inscrito);
         $("#f1 #ultima_actualizacion").val(wada.ultima_actualizacion);
         $("#f1 #vencimiento").val(wada.vencimiento);
         $("#f1 #status").val(wada.estado);
         $("#f1 #accion").val("modificar");
         $("#modal").modal("show");
      }
   }
   function validarFechaVencidaPasada(fechaVencimiento) {
      const fecha = new Date(fechaVencimiento); // tu fecha
      const hoy = new Date();
      hoy.setHours(0, 0, 0, 0);
      return fecha < hoy;
   }
});
