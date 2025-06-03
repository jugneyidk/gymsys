import {
   enviaAjax,
   muestraMensaje,
   modalListener,
   REGEX,
   obtenerNotificaciones
} from "./comunes.js";
import { initDataTable } from "./datatables.js";

$(document).ready(function () {
   cargaListadoWada();
   cargarListadoPorVencer();
   cargarListadoAtletas();
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
      initDataTable("#tablaWada", {
         lengthChange: false,
         columnDefs: [
            {
               targets: [6],
               className: "text-nowrap",
               orderable: false,
            },
         ],
      }, html);
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
               ? `<button class='btn btn-block btn-warning me-2' aria-label='Modificar WADA del atleta ${registro.nombre} ${registro.apellido}' data-tooltip='tooltip' title='Modificar WADA' data-id=${registro.cedula}><i class='fa-regular fa-pen-to-square'></i></button>`
               : ""
            }
                  </td>
               </tr>`;
      });
      initDataTable("#tablaProximosVencer", {
         lengthChange: false
      }, html);
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
   function cargarListadoAtletas() {
      enviaAjax("", "?p=atletas&accion=listadoAtletas", "GET").then((respuesta) => {
         var opciones = "<option value=''>Seleccione un atleta</option>";
         respuesta.atletas.forEach(function (atleta) {
            opciones += `<option value='${atleta.cedula_encriptado}' data-hash='${atleta.cedula_hash}'>${atleta.cedula} - ${atleta.nombre} ${atleta.apellido}</option>`;
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
      muestraMensaje("¿Estás seguro?", "No podrás revertir esto", "warning", {
         showCancelButton: true,
         confirmButtonColor: "#d33",
         confirmButtonText: "Sí, eliminar"
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
      if (wada?.id_atleta_hash) {
         $("#f1 #atleta option").each(function () {
            if ($(this).data('hash') === wada.id_atleta_hash) {
               $("#f1 #atleta").val($(this).val());
            }
         });
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
