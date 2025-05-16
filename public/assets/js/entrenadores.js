import {
   validarKeyPress,
   validarKeyUp,
   enviaAjax,
   muestraMensaje,
   REGEX,
   modalListener,
   obtenerNotificaciones
} from "./comunes.js";
$(document).ready(function () {
   function obtenerListadoEntrenadores() {
      enviaAjax("", "?p=entrenadores&accion=listadoEntrenadores", "GET").then((respuesta) => {
         actualizarListadoEntrenadores(respuesta.entrenadores);
      });
   }
   obtenerNotificaciones(idUsuario);
   setInterval(() => obtenerNotificaciones(idUsuario), 35000);
   obtenerListadoEntrenadores();

   modalListener("Entrenador");
   modal.addEventListener("hidden.bs.modal", function (event) {
      $("#modificar_contraseña_container").addClass("d-none");
      $("#password").prop("disabled", false);
   });

   function verificarFecha(fechaInput, mensaje) {
      var fecha = fechaInput.val();
      if (!fecha) {
         fechaInput.removeClass("is-valid").addClass("is-invalid");
         mensaje.text("La fecha de nacimiento es obligatoria");
         return false;
      }
      var hoy = new Date();
      var fechaNac = new Date(fecha);
      if (fechaNac > hoy) {
         fechaInput.removeClass("is-valid").addClass("is-invalid");
         mensaje.text("La fecha debe ser anterior al día actual");
         return false;
      } else {
         fechaInput.removeClass("is-invalid").addClass("is-valid");
         mensaje.text("");
         return true;
      }
   }

   function calcularEdad(fechaNacimiento) {
      var hoy = new Date();
      var fechaNac = new Date(fechaNacimiento);
      var edad = hoy.getFullYear() - fechaNac.getFullYear();
      var mes = hoy.getMonth() - fechaNac.getMonth();
      if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
         edad--;
      }
      return edad;
   }

   function validarEnvio(formId) {
      var esValido = true;
      var form = $(formId);
      esValido &= validarKeyUp(
         REGEX.nombres.regex,
         form.find("#nombres"),
         form.find("#snombres"),
         "Solo letras y espacios (1-50 caracteres)"
      );
      esValido &= validarKeyUp(
         REGEX.apellidos.regex,
         form.find("#apellidos"),
         form.find("#sapellidos"),
         "Solo letras y espacios (1-50 caracteres)"
      );
      esValido &= validarKeyUp(
         REGEX.cedula.regex,
         form.find("#cedula"),
         form.find("#scedula"),
         "La cédula debe tener al menos 7 números"
      );
      esValido &= validarKeyUp(
         REGEX.telefono.regex,
         form.find("#telefono"),
         form.find("#stelefono"),
         "El formato del teléfono debe ser 04XXXXXXXXX"
      );
      esValido &= validarKeyUp(
         REGEX.correo_electronico.regex,
         form.find("#correo_electronico"),
         form.find("#scorreo_electronico"),
         "Correo inválido"
      );
      esValido &= verificarFecha(
         form.find("#fecha_nacimiento"),
         form.find("#sfecha_nacimiento")
      );
      esValido &= validarKeyUp(
         REGEX.lugar_nacimiento.regex,
         form.find("#lugar_nacimiento"),
         form.find("#slugarnacimiento"),
         "El lugar de nacimiento no puede estar vacío"
      );
      esValido &= validarKeyUp(
         REGEX.grado_instruccion.regex,
         form.find("#grado_instruccion"),
         form.find("#sgrado_instruccion"),
         "El grado de instrucción no puede estar vacío"
      );
      if (formId === "#f2" && $("#modificar_contraseña").is(":checked")) {
         esValido &= validarKeyUp(
            REGEX.password.regex,
            form.find("#password_modificar"),
            form.find("#spassword_modificar"),
            "La contraseña debe tener entre 6 y 20 caracteres"
         );
      }
      return esValido;
   }
   $("#f1").on("submit", function (e) {
      e.preventDefault();
      if (validarEnvio("#f1")) {
         var datos = new FormData($(this)[0]);
         var accion = datos.get("accion") || "incluir";
         enviaAjax(datos, `?p=entrenadores&accion=${accion}Entrenador`).then((respuesta) => {
            muestraMensaje("Exito", respuesta.mensaje, "success");
            obtenerListadoEntrenadores();
            $("#modal").modal("hide");
         });
      }
   });
   function actualizarListadoEntrenadores(entrenadores) {
      var listado_entrenador = "";
      if ($.fn.DataTable.isDataTable("#tablaentrenador")) {
         $("#tablaentrenador").DataTable().destroy();
      }
      entrenadores.forEach((entrenador) => {
         listado_entrenador +=
            "<tr><td class='align-middle'>" + entrenador.cedula + "</td>";
         listado_entrenador +=
            "<td class='align-middle'>" +
            entrenador.nombre +
            " " +
            entrenador.apellido +
            "</td>";
         listado_entrenador +=
            "<td class='align-middle d-none d-md-table-cell'>" +
            entrenador.telefono +
            "</td>";
         listado_entrenador += `<td>${actualizar === 1
            ? `<button class='btn btn-block btn-warning me-2 w-auto' data-bs-toggle='modal' aria-label='Modificar entrenador ${entrenador.nombre} ${entrenador.apellido}' data-tooltip="tooltip" data-bs-placement="top" title="Modificar Entrenador" data-id=${entrenador.cedula}><i class='fa-regular fa-pen-to-square'></i></button>`
            : ""
            }${eliminar === 1
               ? `<button class='btn btn-block btn-danger w-auto' aria-label='Eliminar entrenador ${entrenador.nombre} ${entrenador.apellido}' data-tooltip="tooltip" data-bs-placement="top" title="Eliminar Entrenador" data-id=${entrenador.cedula}><i class='fa-solid fa-trash-can'></i></button>`
               : ""
            } </td>`;
         listado_entrenador += "</tr>";
      });
      $("#listado").html(listado_entrenador);
      $("#tablaentrenador").DataTable({
         columnDefs: [{ targets: [3], orderable: false, searchable: false }],
         language: {
            lengthMenu: "Mostrar _MENU_ por página",
            zeroRecords: "No se encontraron entrenadores",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay entrenadores disponibles",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "Buscar:",
            emptyTable: "No hay registros disponibles",
            paginate: {
               next: "Siguiente",
               previous: "Anterior",
            },
         },
         autoWidth: false,
         order: [[0, "desc"]],
      });
   }
   function llenarFormularioModificar(entrenador) {
      $("#f1 #nombres").val(entrenador.nombre);
      $("#f1 #apellidos").val(entrenador.apellido);
      $("#f1 #cedula").val(entrenador.cedula);
      $("#f1 #genero").val(entrenador.genero);
      $("#f1 #fecha_nacimiento").val(entrenador.fecha_nacimiento);
      $("#f1 #lugar_nacimiento").val(entrenador.lugar_nacimiento);
      $("#f1 #estado_civil").val(entrenador.estado_civil);
      $("#f1 #telefono").val(entrenador.telefono);
      $("#f1 #correo_electronico").val(entrenador.correo_electronico);
      $("#f1 #grado_instruccion").val(entrenador.grado_instruccion);
      $("#f1 #password").val("");
      $("#f1 #password").prop("disabled", true);
      $("#f1 #accion").val("modificar");
      $("#f1 #cedula_original").val(entrenador.cedula);
      $("#modificar_contraseña").prop("checked", false);
      $("#modificar_contraseña_container").removeClass("d-none");
   }
   function cargarDatosEntrenador(cedula) {
      enviaAjax("", `?p=entrenadores&accion=obtenerEntrenador&id=${cedula}`, "GET").then((respuesta) => {
         llenarFormularioModificar(respuesta.entrenador);
         $("#modal").modal("show");
      });
   }

   function eliminarEntrenador(cedula) {
      Swal.fire({
         title: "¿Estás seguro?",
         text: "No podrás revertir esto!",
         icon: "warning",
         showCancelButton: true,
         confirmButtonColor: "#3085d6",
         cancelButtonColor: "#d33",
         confirmButtonText: "Sí, eliminar!",
         cancelButtonText: "Cancelar",
      }).then((result) => {
         if (result.isConfirmed) {
            var datos = new FormData();
            datos.append("cedula", cedula);
            enviaAjax(datos, "?p=entrenadores&accion=eliminarEntrenador").then((respuesta) => {
               muestraMensaje(
                  "Éxito",
                  "El entrenador fue eliminado exitosamente.",
                  "success"
               );
               obtenerListadoEntrenadores();
            });
         }
      });
   }

   $("#modificar_contraseña").on("change", function () {
      if ($(this).is(":checked")) {
         $("#password").prop("disabled", false);
      } else {
         $("#password").prop("disabled", true).val("");
         $("#password").removeClass("is-valid is-invalid");
         $("#spassword").text("");
      }
   });

   $("input").on("keypress", function (e) {
      var id = $(this).attr("id");
      switch (id) {
         case "nombres":
         case "apellidos":
         case "lugar_nacimiento":
         case "grado_instruccion":
         case "nombres":
         case "apellidos":
         case "lugar_nacimiento":
         case "grado_instruccion":
            validarKeyPress(e, REGEX.keypress_letras.regex);
            break;
         case "cedula":
         case "telefono":
            validarKeyPress(e, REGEX.keypress_numerico.regex);
            break;
         case "correo_electronico":
            validarKeyPress(e, REGEX.keypress_correo.regex);
            break;
         case "password":
            validarKeyPress(e, REGEX.keypress_password.regex);
            break;
      }
   });

   $("input").on("keyup", function () {
      var id = $(this).attr("id");
      validarKeyUp(REGEX[id].regex, $(this), $("#s" + id), REGEX[id].mensaje);
   });

   $("#fecha_nacimiento, #fecha_nacimiento_modificar").on("change", function () {
      var form = $(this).closest("form");
      verificarFecha($(this), form.find("#sfecha_nacimiento"));
      var edad = calcularEdad($(this).val());
      form.find("#edad").val(edad);
      if (edad < 18) {
         form.find("#representanteInfo").show();
      } else {
         form.find("#representanteInfo").hide();
      }
   });

   $("#tablaentrenador").on("click", ".btn-warning", function () {
      var cedula = $(this).data("id");
      cargarDatosEntrenador(cedula);
   });

   $("#tablaentrenador").on("click", ".btn-danger", function () {
      var cedula = $(this).data("id");
      eliminarEntrenador(cedula);
   });
});
