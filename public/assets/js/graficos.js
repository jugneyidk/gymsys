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
   // Si no hay datos, mostrar mensaje por defecto
   if (!valores.length) {
      valores = [1];
      etiquetas = [textoSinDatos];
      colores = ['#e0e0e0'];
   }

   const configBase = {
      type: tipo,
      data: {
         labels: etiquetas,
         datasets: [{
            data: valores,
            backgroundColor: colores.length ? colores : 'rgba(75, 192, 192, 0.2)',
            borderColor: tipo === 'line' ? '#42a5f5' : undefined,
            borderWidth: tipo === 'bar' ? 1 : undefined,
            fill: tipo === 'line',
            label: titulo
         }]
      },
      options: {
         responsive: true,
         plugins: {
            legend: {
               display: tipo === 'pie',
               position: "bottom"
            },
            tooltip: {
               enabled: true,
               callbacks: {
                  label: function (context) {
                     if (!valores.length || valores[0] === 1 && etiquetas[0] === textoSinDatos) {
                        return textoSinDatos;
                     }
                     return context.raw;
                  }
               }
            }
         },
         scales: mostrarEjes ? {
            y: { beginAtZero: true }
         } : undefined
      }
   };

   return new Chart(ctx, configBase);
};
