import { enviaAjax, obtenerNotificaciones } from "./comunes.js";
import { crearGrafico } from "./graficos.js";
$(document).ready(function () {
   obtenerNotificaciones();
   cargarUltimasNotificaciones();
   cargarEstadisticasDashboard();
   cargarUltimasAcciones();
   cargarMensualidadesPendientes();
   cargarWadasPendientes();
   setInterval(() => obtenerNotificaciones(), 35000);
   async function cargarUltimasNotificaciones() {
      const respuesta = await enviaAjax("", `?p=notificaciones&accion=obtenerNotificaciones`, "GET");
      renderUltimasNotificaciones(respuesta.notificaciones);
   }
   async function cargarEstadisticasDashboard() {
      const respuesta = await enviaAjax("", `?p=dashboard&accion=obtenerDatosSistema`, "GET");
      renderEstadisticasDashboard(respuesta.estadisticas);
   }
   async function cargarUltimasAcciones() {
      const respuesta = await enviaAjax("", `?p=bitacora&accion=listadoBitacora&draw=0&length=4`, "GET");
      renderUltimasAcciones(respuesta.data);
   }
   async function cargarMensualidadesPendientes() {
      const respuesta = await enviaAjax("", `?p=mensualidad&accion=listadoDeudores`, "GET");
      renderMensualidadesPendientes(respuesta.deudores);
   }
   async function cargarWadasPendientes() {
      const respuesta = await enviaAjax("", `?p=wada&accion=listadoPorVencer`, "GET");
      renderWadasPendientes(respuesta.wadas);
   }
   function renderEstadisticasDashboard(estadisticas) {
      $("#atletasRegistrados").text(estadisticas.total_atletas);
      $("#entrenadoresRegistrados").text(estadisticas.total_entrenadores);
      $("#mensualidadesPendientes").text(estadisticas.total_deudores);
      $("#wadasPendientes").text(estadisticas.total_wadas_pendientes);
   }
   function renderUltimasNotificaciones(notificaciones) {
      let container = notificaciones
         .map(
            (notificacion) => {
               const fechaCorta = new Date(notificacion.fecha_creacion).toLocaleDateString();
               return `<li class="list-group-item d-flex justify-content-between${notificacion.leida ? "" : " list-group-item-warning"}">
               <div>
                  <strong>${notificacion.titulo}</strong>
                  <small class="d-block">${notificacion.mensaje}</small>
               </div>
               <small class="text-muted text-nowrap"
                  title="${notificacion.fecha_creacion}" data-tooltip="tooltip" data-bs-placement="top">${fechaCorta}</small>
            </li>`
            })
         .join("");

      if (container === "") {
         container = `<li class="list-group-item d-flex justify-content-center align-items-center h-100">
                     <span class="h6 text-muted">No hay notificaciones</span>
                  </li>`;
      }
      $("#ultimas_notificaciones").html(container);
   }
   function renderMensualidadesPendientes(deudores) {
      let container = deudores
         .slice(0, 5)
         .map(
            (deudor) => {
               return `<tr>
                        <td>${deudor.cedula}</td>
                        <td>${deudor.nombre} ${deudor.apellido}</td>
                        <td>${deudor.tipo_cobro}</td>
                     </tr>`;
            })
         .join("");
      if (container === "") {
         container = `<tr>
                        <td colspan="3" class="text-center">
                           <span class="h6 text-muted">No hay mensualidades pendientes</span>
                        </td>
                     </tr>`;
      }
      $("#tablaMensualidadesPendientes tbody").html(container);
   }
   function renderWadasPendientes(wadas) {
      const clases = {
         "Por vencer": "text-warning",
         "Vencida": "text-danger"
      }
      let container = wadas
         .slice(0, 5)
         .map(
            (wada) => {
               const diasRestantes = Math.floor((new Date(wada.vencimiento) - new Date()) / (1000 * 60 * 60 * 24));
               const estado = diasRestantes < 30 && diasRestantes >= 0 ? "Por vencer" : "Vencida";
               return `<tr>
                        <td>${wada.nombre} ${wada.apellido}</td>
                        <td><span class="${clases[estado]}" title="${diasRestantes} dias restantes" data-tooltip="tooltip" >${estado}</span></td>
                     </tr>`;
            })
         .join("");
      if (container === "") {
         container = `<tr>
                        <td colspan="3" class="text-center">
                           <span class="h6 text-muted">No hay WADAs pendientes</span>
                        </td>
                     </tr>`;
      }
      $("#tablaWadasPendientes tbody").html(container);
   }
   function renderUltimasAcciones(acciones) {
      const clasesAcciones = {
         "Eliminar": "text-danger",
         "Modificar": "text-warning",
         "Incluir": "text-primary"
      };
      let container = acciones
         .map(
            (accion) => {
               const fechaCorta = new Date(accion.fecha).toLocaleDateString();
               return `<li class="list-group-item d-flex justify-content-between">
               <div class="text-wrap">
                  <strong class="${clasesAcciones[accion.accion]}">${accion.accion}</strong>
                  <small class="d-block"><strong>${accion.nombre_completo}</strong> realizó la acción <strong class="${clasesAcciones[accion.accion]}" title="${accion.registro_modificado}" data-tooltip="tooltip" data-bs-placement="top">${accion.accion}</strong> en el módulo <strong>${accion.modulo}</strong>.</small>
               </div>
               <small class="text-muted text-nowrap"
                  title="${accion.fecha}" data-tooltip="tooltip" data-bs-placement="top">${fechaCorta}</small>
            </li>`
            })
         .join("");

      if (container === "") {
         container = `<li class="list-group-item d-flex justify-content-center align-items-center">
                        <span class="h6 text-muted">No hay actividad reciente</span>
                     </li>`;
      }
      $("#ultimas_acciones").html(container);
   }
   function renderGraficoAsistenciasMensuales() {
      enviaAjax("", "?p=reportes&accion=obtenerProgresoAsistencias", "GET").then(respuesta => {
         const etiquetas = respuesta.asistencias?.map(d => d.mes) || [];
         const valores = respuesta.asistencias?.map(d => d.total_asistencias) || [];
         const ctx = document.getElementById("asistenciasChart").getContext("2d");
         crearGrafico({
            tipo: "line",
            ctx,
            etiquetas,
            valores,
            titulo: "Asistencias Mensuales",
            mostrarEjes: true,
            textoSinDatos: "No hay datos de asistencia disponibles"
         });
      });
   }
   function renderGraficoCumplimientoWADA() {
      enviaAjax("", "?p=reportes&accion=obtenerCumplimientoWADA", "GET").then(respuesta => {
         const data = respuesta.wada;
         const etiquetas = ["Vigentes", "Vencidas", "Por vencer"];
         const valores = [
            parseInt(data.vigentes) || 0,
            parseInt(data.vencidas) || 0,
            parseInt(data.por_vencer) || 0
         ];
         const ctx = document.getElementById("wadaChart").getContext("2d");
         crearGrafico({
            tipo: "bar",
            ctx,
            etiquetas,
            valores,
            colores: ["#4caf50", "#f44336", "#ff9800"], // Verde (vigentes), Rojo (vencidas), Naranja (por vencer)
            titulo: `Estado WADA (Total: ${data.total_atletas})`,
            textoSinDatos: "No hay datos WADA disponibles"
         });
      });
   }

   function renderGraficoGenero() {
      enviaAjax("", "?p=reportes&accion=obtenerDatosEstadisticos&tipo=generoAtletas", "GET").then(respuesta => {
         const datosProcesados = (respuesta.estadisticas || []).reduce((acc, d) => {
            acc.etiquetas.push(d.genero);
            acc.valores.push(d.cantidad);
            acc.colores.push(d.genero.toLowerCase() === 'masculino' ? '#36a2eb' : '#ff6384');
            return acc;
         }, { etiquetas: [], valores: [], colores: [] });

         const ctx = document.getElementById("generoChart").getContext("2d");

         crearGrafico({
            tipo: "pie",
            ctx,
            etiquetas: datosProcesados.etiquetas,
            valores: datosProcesados.valores,
            colores: datosProcesados.colores,
            titulo: "Distribución por Género",
            textoSinDatos: "No hay datos de género disponibles"
         });
      });
   }
   $("#verNotificacionesRecientes").on("click", function (e) {
      const dropdownNoti = bootstrap.Dropdown.getOrCreateInstance(document.getElementById("menuNotificaciones"));
      e.preventDefault();         // evita el salto de ancla
      dropdownNoti.show();      // abre el dropdown
   });
   renderGraficoAsistenciasMensuales();
   renderGraficoCumplimientoWADA();
   renderGraficoGenero();
});
