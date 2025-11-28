import {
   validarKeyPress,
   validarKeyUp,
   enviaAjax,
   muestraMensaje,
   obtenerNotificaciones,
   REGEX,
   validarFecha,
} from "./comunes.js";
import { initDataTable } from "./datatables.js";
$(document).ready(function () {
   function cargarListadoAtletas() {
      enviaAjax("", "?p=atletas&accion=listadoAtletas", "GET").then((respuesta) => {
         actualizarListadoAtletas(respuesta.atletas);
         if (!$("fechaAsistencia").val()) {
            $("#fechaAsistencia").val(new Date().toISOString().split("T")[0]);
            obtenerAsistencias($("#fechaAsistencia").val());
         }
      });
   }
   obtenerNotificaciones();
   function enviarAsistencias() {
      var fecha = $("#fechaAsistencia").val() ?? null;
      if (!fecha) {
         muestraMensaje("Error", "Debe seleccionar una fecha", "error");
         return;
      }
      var asistencias = [];
      var tabla = $("#tablaAsistencias").DataTable();
      var datos = tabla.rows().data({ search: "applied" }).toArray();
      let errorValidacion = null;
      
      for (let index = 0; index < datos.length; index++) {
         const dato = datos[index];
         const fila = tabla.row(index).node();
         const id = $(fila).find('select.estado-asistencia').data("id");
         const estadoAsistencia = $(fila).find('select.estado-asistencia').val();
         const horaEntrada = $(fila).find('input.hora-entrada').val();
         const horaSalida = $(fila).find('input.hora-salida').val();
         const rpe = $(fila).find('input.rpe-slider').val();
         const tipoSesion = $(fila).find('select.tipo-sesion').val();
         const observaciones = $(fila).find('input.observaciones').val();
         
         if (estadoAsistencia === 'presente' && !horaEntrada) {
            errorValidacion = `El atleta con cédula ${dato[0]} está marcado como PRESENTE pero no tiene hora de entrada`;
            break;
         }
         
         const rpeValue = parseInt(rpe);
         const rpeFinal = (rpeValue > 0 && rpeValue <= 10) ? rpeValue : null;
         
         asistencias.push({
            id_atleta: id,
            estado_asistencia: estadoAsistencia,
            hora_entrada: horaEntrada || null,
            hora_salida: horaSalida || null,
            rpe: rpeFinal,
            tipo_sesion: tipoSesion || 'entrenamiento',
            observaciones: observaciones || null
         });
      }
      
      if (errorValidacion) {
         muestraMensaje("Error de validación", errorValidacion, "error");
         return false;
      }
      var datos = new FormData();
      datos.append("fecha", fecha);
      datos.append("asistencias", JSON.stringify(asistencias));
      enviaAjax(datos, "?p=asistencias&accion=guardarAsistencias").then((respuesta) => {
         muestraMensaje(
            "Éxito",
            respuesta.mensaje,
            "success"
         );
      });
   }
   function actualizarListadoAtletas(atletas) {
      var listado = "";
      atletas.forEach(function (atleta) {
         listado += `<tr>
                           <td class="align-middle">${atleta.cedula}</td>
                           <td class="align-middle">${atleta.nombre}</td>
                           <td class="d-none d-md-table-cell align-middle">${atleta.apellido}</td>
                           <td class="align-middle">
                              <select class="form-select form-select-sm estado-asistencia" data-id="${atleta.cedula_encriptado}" aria-label='Estado de asistencia'>
                                 <option value="ausente" selected>Ausente</option>
                                 <option value="presente">Presente</option>
                                 <option value="justificado">Justificado</option>
                              </select>
                           </td>
                           <td class="align-middle">
                              <input type="time" class="form-control form-control-sm hora-entrada" aria-label='Hora entrada'/>
                           </td>
                           <td class="align-middle">
                              <input type="time" class="form-control form-control-sm hora-salida" aria-label='Hora salida'/>
                           </td>
                           <td class="align-middle">
                              <div class="rpe-container">
                                 <input type="range" class="form-range rpe-slider" min="0" max="10" value="0" aria-label='RPE'/>
                                 <div class="rpe-display">
                                    <span class="rpe-value">-</span>
                                    <small class="rpe-label text-muted">Sin dato</small>
                                 </div>
                              </div>
                           </td>
                           <td class="align-middle">
                              <select class="form-select form-select-sm tipo-sesion" aria-label='Tipo de sesión'>
                                 <option value="entrenamiento" selected>Entrenamiento</option>
                                 <option value="competencia">Competencia</option>
                                 <option value="evaluacion">Evaluación</option>
                                 <option value="otro">Otro</option>
                              </select>
                           </td>
                           <td class="align-middle">
                              <input type="text" class="form-control form-control-sm observaciones" placeholder="Observaciones" aria-label='Observaciones'/>
                           </td>
                     </tr>`;
      });
      $("#listadoAsistencias").html(listado);
      initDataTable("#tablaAsistencias", {}, listado);
   }
   function obtenerAsistencias(fecha) {
      if (!fecha) {
         muestraMensaje("Error", "Debe introducir una fecha válida", "error");
         reiniciarFormulario();
         return false;
      }
      enviaAjax("", `?p=asistencias&accion=obtenerAsistencias&fecha=${fecha}`, "GET").then((respuesta) => {
         actualizarListadoAsistencias(respuesta.asistencias, fecha);
         reiniciarFormulario();
      });
   }

   function actualizarListadoAsistencias(asistencias, fecha) {
      var fechaSeleccionada = new Date(fecha);
      fechaSeleccionada.setHours(0, 0, 0, 0);
      var fechaActual = new Date();
      fechaActual.setHours(0, 0, 0, 0);
      var unDia = 24 * 60 * 60 * 1000; // Un día en milisegundos
      var deshabilitar = fechaActual - fechaSeleccionada > unDia;
      var tabla = $("#tablaAsistencias").DataTable();
      var datos = tabla.rows().data({ search: "applied" }).toArray();
      datos.forEach((dato, index) => {
         // Encontrar la fila correspondiente en la tabla
         const fila = tabla.row(index).node();
         const id = dato[0]; // ID del atleta (cédula)
         
         var asistencia = asistencias.find(function (asistencia) {
            return asistencia.id_atleta == id;
         });
         
         if (asistencia) {
            // Cargar datos existentes
            $(fila).find('select.estado-asistencia').val(asistencia.estado_asistencia || 'ausente');
            $(fila).find('input.hora-entrada').val(asistencia.hora_entrada || '');
            $(fila).find('input.hora-salida').val(asistencia.hora_salida || '');
            const rpeValue = asistencia.rpe || 0;
            $(fila).find('input.rpe-slider').val(rpeValue);
            actualizarRPEDisplay($(fila).find('input.rpe-slider'));
            $(fila).find('select.tipo-sesion').val(asistencia.tipo_sesion || 'entrenamiento');
            $(fila).find('input.observaciones').val(asistencia.observaciones || '');
         } else {
            // Valores por defecto si no hay asistencia registrada
            $(fila).find('select.estado-asistencia').val('ausente');
            $(fila).find('input.hora-entrada').val('');
            $(fila).find('input.hora-salida').val('');
            $(fila).find('input.rpe-slider').val(0);
            actualizarRPEDisplay($(fila).find('input.rpe-slider'));
            $(fila).find('select.tipo-sesion').val('entrenamiento');
            $(fila).find('input.observaciones').val('');
         }
         
         // Deshabilitar edición si la fecha es anterior a ayer
         if (deshabilitar) {
            $(fila).find('select, input').prop("disabled", true);
         } else {
            $(fila).find('select, input').prop("disabled", false);
         }
      });
   }
   $("#btnGuardarAsistencias").on("click", function () {
      enviarAsistencias();
   });
   $("#btnEliminarAsistencias").on("click", function () {
      let fecha = $("#fechaAsistencia").val();
      if (!validarFecha(fecha)) {
         muestraMensaje("Error", "La fecha no es válida", "error");
         return false;
      }
      var fechaSeleccionada = new Date(fecha);
      fechaSeleccionada.setHours(0, 0, 0, 0);
      var fechaActual = new Date();
      fechaActual.setHours(0, 0, 0, 0);
      if (fechaActual - fechaSeleccionada > 0) {
         muestraMensaje("Error", "No se puede eliminar asistencias de fechas anteriores a hoy", "error");
         return false;
      }
      muestraMensaje("¿Estás seguro?", "No podrás revertir esto!", "warning", {
         showCancelButton: true,
         confirmButtonColor: "#d33",
         confirmButtonText: "Sí, eliminar!",
      }).then((result) => {
         if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("fecha", fecha);
            enviaAjax(datos, "?p=asistencias&accion=eliminarAsistencias").then((respuesta) => {
               muestraMensaje(
                  "Eliminado!",
                  respuesta.mensaje,
                  "success"
               );
               obtenerAsistencias(fecha);
            });
         }
      });
   });

   $("body").on("keypress", "input.observaciones", function (e) {
      validarKeyPress(e, REGEX.keypress_alfanumerico.regex);
   });

   $("body").on("keyup", "input.observaciones", function () {
      validarKeyUp(
         REGEX.detalles.regex,
         $(this),
         $(this).siblings("span"),
         REGEX.detalles.mensaje
      );
   });

   $("#fechaAsistencia").on("change", function () {
      var fecha = $(this).val();
      obtenerAsistencias(fecha);
   });

   // Función para actualizar la visualización del RPE
   function actualizarRPEDisplay(slider) {
      const valor = parseInt(slider.val());
      const container = slider.closest('.rpe-container');
      const display = container.find('.rpe-value');
      const label = container.find('.rpe-label');
      
      const labels = [
         { text: 'Sin dato', class: 'text-muted' },
         { text: 'Muy suave', class: 'text-success' },
         { text: 'Suave', class: 'text-success' },
         { text: 'Moderado', class: 'text-info' },
         { text: 'Algo duro', class: 'text-info' },
         { text: 'Duro', class: 'text-warning' },
         { text: 'Duro+', class: 'text-warning' },
         { text: 'Muy duro', class: 'text-orange' },
         { text: 'Muy duro+', class: 'text-orange' },
         { text: 'Extremo', class: 'text-danger' },
         { text: 'Máximo', class: 'text-danger' }
      ];
      
      if (valor === 0) {
         display.text('-');
         label.text(labels[0].text).attr('class', 'rpe-label ' + labels[0].class);
      } else {
         display.text(valor);
         label.text(labels[valor].text).attr('class', 'rpe-label ' + labels[valor].class);
      }
   }
   
   // Listener para actualizar RPE al mover el slider
   $('body').on('input', 'input.rpe-slider', function() {
      actualizarRPEDisplay($(this));
   });
   
   // Inicializar tooltips de Bootstrap
   var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
   var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
   });
   
   cargarListadoAtletas();

   function reiniciarFormulario() {
      var tabla = $("#tablaAsistencias").DataTable();
      var datos = tabla.rows().data({ search: "applied" }).toArray();
      datos.forEach((dato, index) => {
         const fila = tabla.row(index).node();
         // Limpiar clases de validación de todos los inputs
         $(fila).find('input, select').removeClass("is-valid is-invalid");
      });
   }
});
