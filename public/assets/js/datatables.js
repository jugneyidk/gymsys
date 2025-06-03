/**
 * Función común para inicializar DataTables con configuraciones personalizadas
 * @param {string} selector - Selector del elemento tabla (ej: '#miTabla')
 * @param {Object} options  - Opciones adicionales para personalizar la configuración
 * @param {string|Array} html   - Si se pasa string, se interpreta como HTML para <tbody>.
 *                               Si es un array vacío, la tabla se inicializa sin filas.
 * @param {string|null} thead   - Si se proporciona, reemplaza el contenido de <thead>.
 * @returns {Object}           - Instancia de DataTable
 */
export function initDataTable(selector, options = {}, html = null, thead = null) {
   if ($.fn.DataTable.isDataTable(selector)) {
      $(selector).DataTable().destroy();
   }
   if (Array.isArray(html)) {
      if (html.length === 0) {
         html = '';
      }
   }

   if (typeof html === 'string') {
      $(`${selector} tbody`).html(html);
   }

   if (typeof thead === 'string') {
      $(`${selector} thead`).html(thead);
   }

   const defaultConfig = {
      autoWidth: false,
      lengthChange: true,
      pageLength: 10,
      language: {
         lengthMenu: "Mostrar _MENU_ por página",
         zeroRecords: "No se encontraron registros",
         info: "Mostrando página _PAGE_ de _PAGES_",
         infoEmpty: "No hay registros disponibles",
         infoFiltered: "(filtrado de _MAX_ registros totales)",
         search: "Buscar:",
         emptyTable: "No hay registros disponibles",
         loadingRecords: "Cargando...",
         paginate: {
            first: "«",
            last: "»",
            next: "Siguiente",
            previous: "Anterior"
         }
      },
      order: [[0, "desc"]], // Orden por defecto de la primera columna (desc)
   };
   const finalConfig = {
      ...defaultConfig,
      ...options
   };
   return $(selector).DataTable(finalConfig);
}
