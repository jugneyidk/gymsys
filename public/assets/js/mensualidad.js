import {
   muestraMensaje,
   enviaAjax,
   validarKeyUp,
   REGEX,
   obtenerNotificaciones,
} from "./comunes.js";
$(document).ready(function () {
   cargaListadoDeudores();
   cargaListadoMensualidades();
   cargaAtletas();
   obtenerNotificaciones();
   setInterval(() => obtenerNotificaciones(), 35000);
   function cargaListadoDeudores() {
      enviaAjax("", "?p=mensualidad&accion=listadoDeudores", "GET").then((respuesta) => {
         var html = "";
         respuesta.deudores.forEach(function (deudor) {
            html += `<tr>
              <td class="align-middle">${deudor.nombre} ${deudor.apellido}</td>
              <td class="align-middle">${deudor.cedula}</td>
              <td class="align-middle">${deudor.nombre_tipo_atleta}</td>
              ${$("#columnaAccion").length > 0
                  ? `<td class="align-middle">
                <button class="btn btn-primary btn-seleccionar" data-cedula="${deudor.cedula}" data-nombre="${deudor.nombre} ${deudor.apellido}" data-tipo="${deudor.tipo_cobro}" data-tooltip="tooltip" title="Seleccionar atleta" aria-label="Seleccionar atleta ${deudor.nombre} ${deudor.apellido}"><i class="fa-solid fa-up-right-from-square"></i></button>
              </td>`
                  : ""
               }
            </tr>`;
         });
         if ($.fn.DataTable.isDataTable("#tablaDeudores")) {
            $("#tablaDeudores").DataTable().destroy();
         }
         $("#listadoDeudores").html(html);
         inicializarDataTable("#tablaDeudores", 5);
      });
   }
   $("#listadoPagosRegistrados").on("click", ".btn-danger", function () {
      const id = $(this).data("id");
      eliminarMensualidad(id);
   });
   function eliminarMensualidad(id) {
      Swal.fire({
         title: "¿Estás seguro?",
         text: "No podrás revertir esto!",
         icon: "warning",
         showCancelButton: true,
         confirmButtonColor: "#3085d6",
         cancelButtonColor: "#d33",
         confirmButtonText: "Sí, eliminar!",
         cancelButtonText: "Cancelar",
         scrollbarPadding: false
      }).then((result) => {
         if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("id", id);
            enviaAjax(datos, "?p=mensualidad&accion=eliminarMensualidad").then((respuesta) => {
               muestraMensaje(
                  "Eliminado!",
                  respuesta.mensaje,
                  "success"
               );
               cargaListadoMensualidades();
               cargaListadoDeudores();
            });
         }
      });
   }

   function cargaListadoMensualidades() {
      enviaAjax("", "?p=mensualidad&accion=listadoMensualidades", 'GET').then((respuesta) => {
         var html = "";
         respuesta.mensualidades.forEach(function (mensualidad) {
            html += `
          <tr>
            <td class="align-middle">${mensualidad.nombre} ${mensualidad.apellido
               }</td>
            <td class="align-middle">${mensualidad.cedula}</td>
            <td class="align-middle">${mensualidad.nombre_tipo_atleta}</td>
            <td class="align-middle">${mensualidad.monto}</td>
            <td class="align-middle">${mensualidad.fecha}</td>
            <td class="align-middle">${mensualidad.detalles
                  ? mensualidad.detalles
                  : `<span class="badge bg-secondary">Sin detalles</span>`
               }</td>
            ${actualizar || modificar
                  ? `<td class="align-middle">
                  <button class="btn btn-danger" 
                          data-id="${mensualidad.id_mensualidad}" data-tooltip="tooltip" data-bs-placement="top" title="Eliminar mensualidad" aria-label='Eliminar mensualidad de ${mensualidad.nombre} ${mensualidad.apellido} de la fecha ${mensualidad.fecha}'>
                      <i class='fa-solid fa-trash-can'></i>
                  </button>
                </td>`
                  : null
               }
          </tr>`;
         });

         if ($.fn.DataTable.isDataTable("#tablaPagosRegistrados")) {
            $("#tablaPagosRegistrados").DataTable().destroy();
         }
         $("#listadoPagosRegistrados").html(html);
         inicializarDataTable("#tablaPagosRegistrados");
      });
   }

   function cargaAtletas() {
      enviaAjax("", "?p=atletas&accion=listadoAtletas", "GET").then((respuesta) => {
         var html = "<option>Seleccione un atleta</option>";
         respuesta.atletas.forEach(function (atleta) {
            // Validación para excluir atletas con tipo_atleta igual a 0 o null
            if (atleta?.tipo_atleta) {
               html += `<option value="${atleta.cedula}" data-tipo="${atleta.tipo_cobro}">${atleta.nombre} ${atleta.apellido} - ${atleta.cedula}</option>`;
            }
         });
         $("#atleta").html(html);
      });
   }
   function inicializarDataTable(selector, pageLength = 10) {
      $(selector).DataTable({
         lengthChange: false,
         pageLength: pageLength,
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
   $("#tablaDeudores").on("click", ".btn-seleccionar", function () {
      var cedula = $(this).data("cedula");
      var tipo = $(this).data("tipo");
      $("#atleta").val(cedula);
      $("#monto").val(tipo);
   });

   $("#registrarPago").on("click", function (e) {
      e.preventDefault();
      if (validarEnvio()) {
         Swal.fire({
            title: "¿Estás seguro que deseas registrar esta mensualidad?",
            text: "No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Registrar",
            cancelButtonText: "Cancelar",
            scrollbarPadding: false
         }).then((result) => {
            if (result.isConfirmed) {
               var datos = new FormData($("#formPago")[0]);
               enviaAjax(datos, "?p=mensualidad&accion=incluirMensualidad").then((respuesta) => {
                  muestraMensaje("Éxito", respuesta.mensaje, "success");
                  cargaListadoDeudores();
                  cargaListadoMensualidades();
                  limpiarFormulario("#formPago");
               });
            }
         });
      }
   });
   function limpiarFormulario(formId) {
      $(formId)
         .find("input[type=text], input[type=number], input[type=date], select")
         .val("");
      $(formId).find("input, select").removeClass("is-invalid is-valid");
   }
   function validarEnvio() {
      var esValido = true;
      const form = $("#formPago");
      const fecha = $("#fecha").val();
      esValido &= validarKeyUp(
         REGEX.cedula.regex,
         form.find(`#atleta`),
         form.find(`#satleta`),
         "Debe elegir un atleta"
      );
      esValido &= validarKeyUp(
         REGEX.monto.regex,
         form.find(`#monto`),
         form.find(`#smonto`),
         REGEX.monto.mensaje
      );
      esValido &= validarKeyUp(
         REGEX.detalles.regex,
         form.find(`#detalles`),
         form.find(`#sdetalles`),
         REGEX.detalles.mensaje
      );
      var fechaValida = validarFecha(fecha);
      if (!fechaValida) {
         $("#fecha").addClass("is-invalid");
         $("#sfecha").text("La fecha no es valida");
         return false;
      }
      return esValido;
   }
   $("#fecha").change(function (e) {
      let fechaValida = validarFecha($(this).val());
      if (!fechaValida) {
         $("#fecha").addClass("is-invalid");
         $("#fecha").removeClass("is-valid");
         $("#sfecha").text("La fecha no es valida");
         return false;
      }
      $("#fecha").removeClass("is-invalid");
      $("#fecha").addClass("is-valid");
      return true;
   });
   function validarFecha(fecha) {
      const date = new Date(fecha);
      return !isNaN(date.getTime());
   }
});
