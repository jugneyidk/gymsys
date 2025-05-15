import { enviaAjax, obtenerNotificaciones } from "./comunes.js";
$(document).ready(function () {
   obtenerNotificaciones();
   cargarUltimasNotificaciones();
   cargarEstadisticasDashboard();
   cargarUltimasAcciones();
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
   // Realizamos la solicitud AJAX al cargar la página
   $.ajax({
      url: "", // Coloca la URL correcta de tu controlador
      method: "POST",
      data: { accion: "estadisticas" },
      success: function (response) {
         // Parseamos los datos recibidos desde el servidor
         var data = JSON.parse(response);

         // Actualizamos el gráfico de medallas en competencias
         var ctx = document.getElementById("myChart").getContext("2d");
         var myChart = new Chart(ctx, {
            type: "line",
            data: {
               labels: data.labels_medallas, // etiquetas dinámicas
               datasets: [
                  {
                     label: "# medallas en competencias",
                     data: data.medallas_por_mes, // datos dinámicos
                     backgroundColor: "rgba(255, 99, 132, 0.2)",
                     borderColor: "rgba(255, 99, 132, 1)",
                     borderWidth: 1,
                  },
               ],
            },
            options: {
               scales: {
                  y: {
                     beginAtZero: true,
                  },
               },
            },
         });



         function renderGraficoGenero() {
            $.ajax({
               url: "",
               method: "POST",
               data: { accion: "obtenerDatosEstadisticos", tipo: "generoAtletas" },
               success: function (respuesta) {
                  if (respuesta.ok) {
                     const etiquetas = respuesta.data.map(d => d.genero);
                     const valores = respuesta.data.map(d => d.cantidad);

                     const ctx = document.getElementById("generoChart").getContext("2d");
                     new Chart(ctx, {
                        type: "pie",
                        data: {
                           labels: etiquetas,
                           datasets: [{
                              data: valores,
                              backgroundColor: ["#ff6384", "#36a2eb", "#cc65fe"],
                              hoverOffset: 4
                           }]
                        },
                        options: {
                           responsive: true,
                           plugins: {
                              legend: { position: "bottom" },
                              tooltip: { enabled: true }
                           }
                        }
                     });
                  } else {
                     console.error("Error:", respuesta.mensaje);
                  }
               },
               error: function () {
                  console.error("No se pudo obtener los datos.");
               }
            });
         }

         // Actualizamos el gráfico de Medallas por mes
         var ctx2 = document.getElementById("myChart1").getContext("2d");
         var myChart1 = new Chart(ctx2, {
            type: "bar",
            data: {
               labels: data.labels_medallas, // etiquetas dinámicas
               datasets: [
                  {
                     label: "Medallas por mes",
                     data: data.medallas_por_mes, // datos dinámicos
                     backgroundColor: [
                        "rgba(75, 192, 192, 0.2)",
                        "rgba(54, 162, 235, 0.2)",
                        "rgba(255, 206, 86, 0.2)",
                        "rgba(153, 102, 255, 0.2)",
                     ],
                     borderColor: [
                        "rgba(75, 192, 192, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(255, 206, 86, 1)",
                        "rgba(153, 102, 255, 1)",
                     ],
                     borderWidth: 1,
                  },
               ],
            },
            options: {
               scales: {
                  y: {
                     beginAtZero: true,
                  },
               },
            },
         });

         // Actualizamos el gráfico de progreso semanal
         var ctxProgress = document
            .getElementById("progressChart")
            .getContext("2d");
         var progressChart = new Chart(ctxProgress, {
            type: "line",
            data: {
               labels: data.labels_progreso, // etiquetas dinámicas
               datasets: [
                  {
                     label: "Progreso de levantamientos (kg)",
                     data: data.progreso_semanal, // datos dinámicos
                     fill: false,
                     borderColor: "rgba(54, 162, 235, 1)",
                     tension: 0.1,
                  },
               ],
            },
            options: {
               scales: {
                  y: {
                     beginAtZero: true,
                     title: {
                        display: true,
                        text: "Kilogramos",
                     },
                  },
                  x: {
                     title: {
                        display: true,
                        text: "Semanas",
                     },
                  },
               },
            },
         });
      },
      error: function (xhr, status, error) {
         console.error("Error en la solicitud AJAX:", error);
      },
   });
   function renderGraficoAsistenciasMensuales() {
      $.ajax({
         url: "",
         method: "POST",
         data: { accion: "obtenerProgresoAsistencias" },
         success: function (respuesta) {
            if (respuesta.ok) {
               const etiquetas = respuesta.data.map(d => d.mes);
               const valores = respuesta.data.map(d => d.total_asistencias);

               const ctx = document.getElementById("asistenciasChart").getContext("2d");
               new Chart(ctx, {
                  type: "line",
                  data: {
                     labels: etiquetas,
                     datasets: [{
                        label: "Asistencias Mensuales",
                        data: valores,
                        borderColor: "#42a5f5",
                        backgroundColor: "rgba(66, 165, 245, 0.5)",
                        fill: true
                     }]
                  },
                  options: {
                     responsive: true,
                     scales: {
                        y: {
                           beginAtZero: true
                        }
                     }
                  }
               });
            } else {
               console.error("Error:", respuesta.mensaje);
            }
         },
         error: function () {
            console.error("No se pudo obtener los datos.");
         }
      });
   }
   function renderGraficoCumplimientoWADA() {
      $.ajax({
         url: "",
         method: "POST",
         data: { accion: "obtenerCumplimientoWADA" },
         success: function (respuesta) {
            if (respuesta.ok) {
               const etiquetas = respuesta.data.map(d => d.cumplimiento);
               const valores = respuesta.data.map(d => d.cantidad);

               const ctx = document.getElementById("wadaChart").getContext("2d");
               new Chart(ctx, {
                  type: "pie",
                  data: {
                     labels: etiquetas,
                     datasets: [{
                        data: valores,
                        backgroundColor: ["#4caf50", "#f44336"]
                     }]
                  },
                  options: {
                     responsive: true,
                     plugins: {
                        legend: { position: "bottom" },
                        tooltip: { enabled: true }
                     }
                  }
               });
            } else {
               console.error("Error:", respuesta.mensaje);
            }
         },
         error: function () {
            console.error("No se pudo obtener los datos.");
         }
      });
   }
   function renderGraficoEdades() {
      $.ajax({
         url: "",
         method: "POST",
         data: { accion: "obtenerDatosEstadisticos", tipo: "edadAtletas" },
         success: function (respuesta) {
            if (respuesta.ok) {
               const etiquetas = respuesta.data.map(d => d.rango_edad);
               const valores = respuesta.data.map(d => d.cantidad);

               const ctx = document.getElementById("edadAtletasChart").getContext("2d");
               new Chart(ctx, {
                  type: "bar",
                  data: {
                     labels: etiquetas,
                     datasets: [{
                        label: "Cantidad de Atletas",
                        data: valores,
                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1
                     }]
                  },
                  options: {
                     responsive: true,
                     scales: { y: { beginAtZero: true } }
                  }
               });
            } else {
               console.error("Error:", respuesta.mensaje);
            }
         },
         error: function () {
            console.error("No se pudo obtener los datos.");
         }
      });
   }
   function renderGraficoGenero() {
      $.ajax({
         url: "",
         method: "POST",
         data: { accion: "obtenerDatosEstadisticos", tipo: "generoAtletas" },
         success: function (respuesta) {
            if (respuesta.ok) {
               const etiquetas = respuesta.data.map(d => d.genero);
               const valores = respuesta.data.map(d => d.cantidad);

               const ctx = document.getElementById("generoChart").getContext("2d");
               new Chart(ctx, {
                  type: "pie",
                  data: {
                     labels: etiquetas,
                     datasets: [{
                        data: valores,
                        backgroundColor: ["#ff6384", "#36a2eb", "#cc65fe"],
                        hoverOffset: 4
                     }]
                  },
                  options: {
                     responsive: true,
                     plugins: {
                        legend: { position: "bottom" },
                        tooltip: { enabled: true }
                     }
                  }
               });
            } else {
               console.error("Error:", respuesta.mensaje);
            }
         },
         error: function () {
            console.error("No se pudo obtener los datos.");
         }
      });
   }
   renderGraficoAsistenciasMensuales();
   renderGraficoEdades();
   renderGraficoGenero();
});
