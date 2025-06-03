import {
   enviaAjax,
   muestraMensaje,
   obtenerNotificaciones,
} from "./comunes.js";
import { initDataTable } from "./datatables.js";
import { crearGrafico } from "./graficos.js";

$(document).ready(() => {
   // Helpers
   const cargarDatosEstadisticos = () => {
      renderGraficoEdades();
      renderGraficoGenero();
      renderGraficoAsistenciasMensuales();
      renderGraficoCumplimientoWADA();
   };
   const actualizarListadoReportes = (reportes) => {
      console.log("Actualizando listado de reportes", reportes);
      if (!reportes.length) {
         $("#listadoReportes").html("<tr><td colspan='4'>No se encontraron reportes.</td></tr>");
         return;
      }

      // Obtener las columnas del primer reporte
      const columnas = Object.keys(reportes[0]);

      // Generar el encabezado de la tabla
      const thead = `
            <tr>
               ${columnas.map(col => `<th class="text-capitalize">${col.replace('_', ' ')}</th>`).join('')}
            </tr>`;

      // Generar las filas
      const filas = reportes.map(reporte => `
         <tr>
            ${columnas.map(col => `<td>${reporte[col]}</td>`).join('')}
         </tr>`).join("");

      const columns = columnas.map(col => ({
         data: col,
         title: col.replace('_', ' '),
         className: 'text-capitalize'
      }));

      // Actualizar la tabla con encabezados y filas
      // $("#tablaReportes").html(thead + '<tbody>' + filas + '</tbody>');
      initDataTable("#tablaReportes", {
         order: [[0, "desc"]],
         destroy: true,
         data: reportes,
         columns: columns,
      }, filas, thead);
      $("#resultadosReporte").show();
   };

   const filtrosMap = {
      atletas: "#filtrosAtletas",
      entrenadores: "#filtrosEntrenadores",
      mensualidades: "#filtrosGenerales",
      wada: "#filtrosGenerales",
      eventos: "#filtrosGenerales",
      asistencias: "#filtrosGenerales",
      reporteIndividualAtleta: "#filtrosIndividualAtleta"
   };


   // Inicialización
   obtenerNotificaciones();
   setInterval(obtenerNotificaciones, 35000);
   cargarDatosEstadisticos();
   cargarListadoGradoInstruccion();

   // Event Listeners
   $("#tipoReporte").change(function () {
      $(".filtros-reporte").hide();
      const filtroSeleccionado = filtrosMap[$(this).val()];
      if (filtroSeleccionado) $(filtroSeleccionado).show();
   });   // Variable para almacenar el último reporte generado
   let ultimoReporteGenerado = null;

   $("#btnGenerarReporte").on("click", function () {
      const tipoReporte = $("#tipoReporte").val();
      const datos = new FormData($("#formReportes")[0]);
      const accion = tipoReporte === 'reporteIndividualAtleta' ? 'obtenerReporteIndividual' : 'obtenerReportes';

      enviaAjax(datos, `?p=reportes&accion=${accion}`).then(respuesta => {
         // Guardar la respuesta completa
         ultimoReporteGenerado = {
            tipoReporte,
            datos: new FormData($("#formReportes")[0]),
            respuesta
         };
         // Actualizar la interfaz
         if (respuesta.reportes) {
            actualizarListadoReportes(respuesta.reportes);
         }
         if (respuesta.estadisticas && ultimoReporteGenerado.tipoReporte != 'reporteIndividualAtleta') {
            actualizaEstadisticas(respuesta.estadisticas);
         }
         if (ultimoReporteGenerado.tipoReporte === 'reporteIndividualAtleta') {
            $("#estadisticasReporte").hide();
         }
      });
   }); $("#btnDescargarPDF").on("click", function () {
      if (!ultimoReporteGenerado) {
         muestraMensaje("Error", "Primero debe generar un reporte", "warning");
         return;
      }
      const jsonData = {
         tipoReporte: ultimoReporteGenerado.tipoReporte,
         datos: {
            ...Object.fromEntries(ultimoReporteGenerado.datos.entries()),
            reportes: ultimoReporteGenerado.respuesta.reportes || []
         },
         estadisticas: ultimoReporteGenerado.respuesta.estadisticas || {}
      };

      // Abre una nueva ventana para el PDF
      const nuevaVentana = window.open('', '_blank');

      // Envia la solicitud fetch para generar el PDF
      fetch('reporte_pdf.php', {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(jsonData)
      })
         .then(response => {
            if (!response.ok) {
               throw new Error('Error al generar el PDF');
            }
            return response.blob();
         })
         .then(blob => {
            // Crear URL del blob y abrirlo en la nueva ventana
            const url = window.URL.createObjectURL(blob);
            nuevaVentana.location.href = url;
         })
         .catch(error => {
            nuevaVentana.close();
            muestraMensaje("Error", "Error al generar el PDF: " + error.message, "error");
         });
   });

   // Funciones de actualización de gráficos
   function actualizaEstadisticas(estadisticas) {
      const VALOR_POR_DEFECTO = "Disponible en reporte";
      const listItems = Object.entries(estadisticas)
         .map(([clave, valor]) => {
            // si es objeto (y no null), uso el valor por defecto
            const displayValue = (valor !== null && typeof valor === "object")
               ? VALOR_POR_DEFECTO
               : valor;

            return `
            <li class="list-group-item d-flex justify-content-between align-items-center text-capitalize">
              ${clave.replace(/_/g, " ")}
              <span class="badge bg-primary rounded-pill">${displayValue}</span>
            </li>`;
         })
         .join("");
      $("#listaEstadisticas").html(listItems);
      $("#estadisticasReporte").show();
   }

   function renderGraficoEdades() {
      enviaAjax("", "?p=reportes&accion=obtenerDatosEstadisticos&tipo=edadAtletas", "GET").then(respuesta => {
         const etiquetas = respuesta.estadisticas?.map(d => d.rango_edad) || [];
         const valores = respuesta.estadisticas?.map(d => d.cantidad) || [];
         const ctx = document.getElementById("edadAtletasChart").getContext("2d");

         crearGrafico({
            tipo: "bar",
            ctx,
            etiquetas,
            valores,
            titulo: "Cantidad de Atletas por Rango de Edad",
            mostrarEjes: true,
            textoSinDatos: "No hay datos de edades disponibles"
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
            tipo: "pie",
            ctx,
            etiquetas,
            valores,
            colores: ["#4caf50", "#f44336", "#ff9800"], // Verde (vigentes), Rojo (vencidas), Naranja (por vencer)
            titulo: `Estado WADA (Total: ${data.total_atletas})`,
            textoSinDatos: "No hay datos WADA disponibles"
         });
      });
   }
   function cargarListadoGradoInstruccion() {
      enviaAjax("", "?p=entrenadores&accion=listadoGradosInstruccion", "GET").then(respuesta => {
         const select = $("#gradoInstruccion");
         select.empty();
         select.append('<option value="" selected>Todos</option>');
         respuesta.grados.forEach(grado => {
            select.append(`<option value="${grado.grado_instruccion}">${grado.grado_instruccion}</option>`);
         });
      }).catch(error => {
         muestraMensaje("Error", "No se pudo cargar el listado de grados de instrucción: " + error.message, "error");
      });
   }
});
