/**
 * Crea un gráfico utilizando Chart.js con configuración predeterminada y manejo de casos sin datos
 * @param {Object} config Configuración del gráfico
 * @param {string} config.tipo - Tipo de gráfico ('pie', 'bar', 'line')
 * @param {CanvasRenderingContext2D} config.ctx - Contexto del canvas donde se dibujará el gráfico
 * @param {string[]} config.etiquetas - Array de etiquetas para el eje X o sectores
 * @param {number[]} config.valores - Array de valores numéricos
 * @param {string[]} config.colores - Array de colores en formato hexadecimal
 * @param {string} config.titulo - Título del conjunto de datos
 * @param {boolean} config.mostrarEjes - Si se deben mostrar los ejes (true para gráficos de barras y líneas)
 * @param {string} config.textoSinDatos - Texto a mostrar cuando no hay datos
 * @returns {Chart} Instancia del gráfico creado
 */
export const crearGrafico = ({
   tipo,
   ctx,
   etiquetas = [],
   valores = [],
   colores = [],
   titulo = '',
   mostrarEjes = false,
   textoSinDatos = 'No hay datos disponibles'
}) => {
   const paletaProducto = ['#1d6ff2', '#38bdf8', '#18b981', '#f59e0b', '#e5484d', '#7c3aed'];
   const colorTexto = '#5f6f83';
   const colorLinea = 'rgba(216, 226, 239, 0.9)';
   const sinDatos = !valores.length;

   // Si no hay datos, mostrar mensaje por defecto
   if (sinDatos) {
      valores = [1];
      etiquetas = [textoSinDatos];
      colores = ['#d8e2ef'];
   }

   const configBase = {
      type: tipo,
      data: {
         labels: etiquetas,
         datasets: [{
            data: valores,
            backgroundColor: colores.length ? colores : paletaProducto,
            borderColor: tipo === 'line' ? '#1d6ff2' : '#ffffff',
            borderWidth: tipo === 'bar' ? 0 : 2,
            fill: tipo === 'line',
            tension: tipo === 'line' ? 0.38 : undefined,
            pointRadius: tipo === 'line' ? 4 : undefined,
            pointHoverRadius: tipo === 'line' ? 6 : undefined,
            pointBackgroundColor: tipo === 'line' ? '#ffffff' : undefined,
            pointBorderColor: tipo === 'line' ? '#1d6ff2' : undefined,
            label: titulo
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         plugins: {
            legend: {
               display: tipo === 'pie',
               position: "bottom",
               labels: {
                  boxWidth: 12,
                  boxHeight: 12,
                  color: colorTexto,
                  font: {
                     family: 'Inter, Segoe UI, Arial, sans-serif',
                     weight: 700
                  },
                  padding: 18,
                  usePointStyle: true
               }
            },
            tooltip: {
               enabled: true,
               backgroundColor: '#071827',
               borderColor: 'rgba(255, 255, 255, 0.12)',
               borderWidth: 1,
               cornerRadius: 12,
               padding: 12,
               titleFont: { weight: 800 },
               bodyFont: { weight: 700 },
               callbacks: {
                  label: function (context) {
                     if (sinDatos || valores[0] === 1 && etiquetas[0] === textoSinDatos) {
                        return textoSinDatos;
                     }
                     return context.raw;
                  }
               }
            }
         },
         scales: mostrarEjes ? {
            x: {
               grid: { display: false },
               ticks: { color: colorTexto, font: { weight: 700 } }
            },
            y: {
               beginAtZero: true,
               grid: { color: colorLinea },
               ticks: { color: colorTexto, precision: 0 }
            }
         } : undefined
      }
   };

   return new Chart(ctx, configBase);
};
