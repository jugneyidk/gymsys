import {
   validarKeyPress,
   validarKeyUp,
   enviaAjax,
   muestraMensaje,
   REGEX,
   modalListener,
   obtenerNotificaciones,
} from "./comunes.js";
$(document).ready(function () {
   function cargaListadoAtletas() {
      enviaAjax("", "?p=atletas&accion=listadoAtletas", "GET").then((respuesta) => {
         actualizarListadoAtletas(respuesta.atletas);
      });
   }
   modalListener("Atleta");
   obtenerNotificaciones();
   setInterval(() => obtenerNotificaciones(), 35000);
   modal.addEventListener("hidden.bs.modal", function (event) {
      $("#modificar_contraseña_container").addClass("d-none");
      $("#password").prop("disabled", false);
   });
   function cargarEntrenadores() {
      enviaAjax("", "?p=entrenadores&accion=listadoEntrenadores", "GET").then((respuesta) => {
         const selectEntrenador = $("#entrenador_asignado");
         selectEntrenador.empty();
         selectEntrenador.append(
            '<option value="">Seleccione un entrenador</option>'
         );
         respuesta.entrenadores.forEach((entrenador) => {
            selectEntrenador.append(
               `<option value="${entrenador.cedula}">${entrenador.nombre} ${entrenador.apellido}</option>`
            );
         });
      });
   }
   function cargarTiposAtleta() {
      const datos = new FormData();
      datos.append("accion", "obtener_tipos_atleta");
      enviaAjax(datos, "").then((respuesta) => {
         const selectTipoAtleta = $("#tipo_atleta");
         selectTipoAtleta.empty();
         selectTipoAtleta.append(
            '<option value="">Seleccione un tipo de atleta</option>'
         );

         respuesta.tipos.forEach((tipo) => {
            selectTipoAtleta.append(
               `<option value="${tipo.id_tipo_atleta}">${tipo.nombre_tipo_atleta}</option>`
            );
         });
      });
   }
   // function cargarTiposAtletaParaModificacion(tipoAtletaAsignado) {
   //   const datos = new FormData();
   //   datos.append("accion", "obtener_tipos_atleta");
   //   enviaAjax(datos, "").then((respuesta) => {
   //     const selectTipoAtletaModificar = $("#tipo_atleta");
   //     selectTipoAtletaModificar.empty(); // Limpiar opciones anteriores
   //     selectTipoAtletaModificar.append(
   //       '<option value="">Seleccione un tipo de atleta</option>'
   //     );
   //     respuesta.tipos.forEach((tipo) => {
   //       selectTipoAtletaModificar.append(
   //         `<option value="${tipo.id_tipo_atleta}" ${
   //           tipo.id_tipo_atleta == tipoAtletaAsignado ? "selected" : ""
   //         }>${tipo.nombre_tipo_atleta}</option>`
   //       );
   //     });
   //   });
   // }

   // cargarTiposAtleta();
   cargaListadoAtletas();
   cargarEntrenadores();
   function verificarFecha(fechaInput, mensaje) {
      const fecha = fechaInput.val();
      const hoy = new Date();
      const fechaNac = new Date(fecha);
      const isValid = fecha && fechaNac <= hoy;
      fechaInput
         .toggleClass("is-invalid", !isValid)
         .toggleClass("is-valid", isValid);
      mensaje.text(
         isValid
            ? ""
            : fecha
               ? "La fecha debe ser anterior al día actual"
               : "La fecha de nacimiento es obligatoria"
      );
      return isValid;
   }

   function calcularEdad(fechaNacimiento) {
      const hoy = new Date();
      const fechaNac = new Date(fechaNacimiento);
      let edad = hoy.getFullYear() - fechaNac.getFullYear();
      const mes = hoy.getMonth() - fechaNac.getMonth();

      if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
         edad--;
      }
      return edad;
   }

   function validarEnvio(formId) {
      let esValido = true;
      const form = $(formId);
      const fechaNacimiento = form.find(`#fecha_nacimiento`).val();
      const edad = calcularEdad(fechaNacimiento);
      // Validación de campos comunes
      form.find('input[type="text"]:not([name="accion"])').each(function () {
         const nombreInput = $(this).attr("name");
         if (edad >= 18 && nombreInput.includes("_representante")) {
            return;
         }
         esValido &= validarKeyUp(
            REGEX[nombreInput].regex,
            form.find(`#${nombreInput}`),
            form.find(`#s${nombreInput}`),
            REGEX[nombreInput].mensaje
         );
         console.log(`${nombreInput} ${esValido}`);
      });
      esValido &= verificarFecha(
         form.find(`#fecha_nacimiento`),
         form.find(`#sfecha_nacimiento`)
      );
      return esValido;
   }

   $("#incluir, #btnModificar").on("click", function (event) {
      event.preventDefault();
   });

   function limpiarFormulario(formId) {
      $(formId)
         .find(
            "input[type=text], input[type=email], input[type=tel], input[type=number], input[type=password], input[type=date], select"
         )
         .val("");
      $(formId).find("input[type=checkbox]").prop("checked", false);
      $(formId).find("input").removeClass("is-invalid is-valid");
      $(formId).find("#representantesContainer").hide();
   }

   $("#incluir").on("click", function (event) {
      event.preventDefault();

      if (validarEnvio("#f1")) {
         const datos = new FormData($("#f1")[0]);
         if (!datos.get("accion")) {
            datos.set("accion", "incluir");
         }
         enviaAjax(datos, "").then((respuesta) => {
            console.log("Respuesta del servidor:", respuesta);

            if (
               !respuesta.ok &&
               respuesta.mensaje &&
               respuesta.mensaje.toLowerCase().includes("¿desea asignarlo?")
            ) {
               Swal.fire({
                  title: "¿Asignar representante existente?",
                  text: respuesta.mensaje,
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Sí, asignar",
                  cancelButtonText: "Cancelar"
               }).then((result) => {
                  if (result.isConfirmed) {
                     const datosNuevo = new FormData();
                     for (let [key, value] of datos.entries()) {
                        datosNuevo.append(key, value);
                     }

                     datosNuevo.set("asignar_representante_existente", "true");

                     enviaAjax(datosNuevo, "").then((respuesta2) => {
                        if (respuesta2.ok) {
                           Swal.fire({
                              title: "Atleta registrado",
                              text: "El atleta ha sido registrado y se asignó el representante.",
                              icon: "success",
                              confirmButtonText: "Cerrar"
                           });
                           cargaListadoAtletas();
                           limpiarFormulario("#f1");
                           $("#modal").modal("hide");
                        } else {
                           Swal.fire({
                              title: "Error",
                              text: respuesta2.mensaje,
                              icon: "error",
                              confirmButtonText: "Cerrar"
                           });
                        }
                     });
                  } else {
                     Swal.fire({
                        title: "Cancelado",
                        text: "No se asignó el representante existente.",
                        icon: "info",
                        confirmButtonText: "Cerrar"
                     });
                  }
               });
            } else if (respuesta.ok) {
               Swal.fire({
                  title: "Atleta incluido",
                  text: "El atleta se ha registrado correctamente.",
                  icon: "success",
                  confirmButtonText: "Cerrar"
               });
               cargaListadoAtletas();
               limpiarFormulario("#f1");
               $("#modal").modal("hide");
            } else {
               Swal.fire({
                  title: "Error",
                  text: "Ocurrió un error al procesar la solicitud.",
                  icon: "error",
                  confirmButtonText: "Cerrar"
               });
            }
         });
      }
   });


   $("#modificar_contraseña").on("change", function () {
      if ($(this).is(":checked")) {
         $("#password").prop("disabled", false);
      } else {
         $("#password").prop("disabled", true).val("");
         $("#password").removeClass("is-valid is-invalid");
         $("#spassword").text("");
      }
   });

   function actualizarListadoAtletas(atletas) {
      let listadoAtleta = "";
      if ($.fn.DataTable.isDataTable("#tablaatleta")) {
         $("#tablaatleta").DataTable().destroy();
      }
      atletas.forEach((atleta) => {
         listadoAtleta += `
                <tr>
                    <td class='align-middle'>${atleta.cedula}</td>
                    <td class='align-middle'>${atleta.nombre} ${atleta.apellido
            }</td>
                    <td class='align-middle'>
                    ${window.actualizar === 1
               ? `<button class='btn btn-block btn-warning me-2 w-auto' data-bs-toggle='modal' aria-label='Modificar atleta ${atleta.nombre} ${atleta.apellido}' data-tooltip="tooltip" data-bs-placement="top" title="Modificar Atleta" data-id='${atleta.cedula}'><i class='fa-regular fa-pen-to-square'></i></button>`
               : ""
            }
                      ${window.eliminar === 1
               ? `<button class='btn btn-block btn-danger w-auto' aria-label='Eliminar atleta ${atleta.nombre} ${atleta.apellido}' data-tooltip="tooltip" data-bs-placement="top" title="Eliminar Atleta" data-id='${atleta.cedula}'><i class='fa-solid fa-trash-can'></i></button>`
               : ""
            }      
                    </td>
                </tr>
            `;
      });

      $("#listado").html(listadoAtleta);
      $("#tablaatleta").DataTable({
         columnDefs: [{ targets: [2], orderable: false, searchable: false }],
         language: {
            lengthMenu: "Mostrar _MENU_ por página",
            zeroRecords: "No se encontraron atletas",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay atletas disponibles",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "Buscar:",
            paginate: {
               next: "Siguiente",
               previous: "Anterior",
            },
         },
         autoWidth: true,
         order: [[0, "desc"]],
         dom: '<"top"f>rt<"bottom"lp><"clear">',
      });
   }
   $("#btnConsultarTipos").on("click", function () {
      cargarListadoTipos2();
      $("#contenedorTablaTipos").show();
   });

   function cargarListadoTipos2() {
      const datos = new FormData();
      datos.append("accion", "obtener_tipos_atleta");
      enviaAjax(datos, "").then((respuesta) => {
         actualizarTablaTipos(respuesta.tipos);
      });
   }

   function obtenerAtleta(cedula) {
      enviaAjax("", `?p=atletas&accion=obtenerAtleta&id=${cedula}`, 'GET').then((respuesta) => {
         if (respuesta.atleta) {
            const atleta = respuesta.atleta;
            llenarFormularioModificar(atleta);
            $("#modal").modal("show");
         }
      });
   }
   function actualizarTablaTipos(tipos) {
      const tbody = $("#tablaTipos tbody");
      tbody.empty();
      console.log(tipos);
      tipos.forEach((tipo, index) => {
         tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${tipo.nombre_tipo_atleta}</td>
                    <td>
                        <button class="btn btn-danger btn-sm btnEliminarTipo" 
                                data-id="${tipo.id_tipo_atleta}">
                            Eliminar
                        </button>
                    </td>
                </tr>
            `);
      });

      if (tipos.length === 0) {
         tbody.append("<tr><td colspan='3'>No hay tipos registrados.</td></tr>");
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

   function mostrarCamposRepresentante() {
      var fechaNacimiento = $("#fecha_nacimiento").val();
      if (fechaNacimiento) {
         var edad = calcularEdad(fechaNacimiento);
         if (edad < 18) {
            $("#representantesContainer").removeClass("d-none");
         } else {
            $("#representantesContainer").addClass("d-none");
         }
      }
   }

   $("#fecha_nacimiento").on("change", function () {
      mostrarCamposRepresentante();
   });
   function llenarFormularioModificar(atleta) {
      console.log(atleta);
      $("#f1 #nombres").val(atleta.nombre);
      $("#f1 #apellidos").val(atleta.apellido);
      $("#f1 #cedula").val(atleta.cedula);
      $("#f1 #genero").val(atleta.genero);
      $("#f1 #fecha_nacimiento").val(atleta.fecha_nacimiento);
      $("#f1 #lugar_nacimiento").val(atleta.lugar_nacimiento);
      $("#f1 #peso").val(atleta.peso);
      $("#f1 #altura").val(atleta.altura);
      $("#f1 #estado_civil").val(atleta.estado_civil);
      $("#f1 #telefono").val(atleta.telefono);
      $("#f1 #correo_electronico").val(atleta.correo_electronico);
      $("#f1 #entrenador_asignado").val(atleta.entrenador);
      $("#modificar_contraseña_container").removeClass("d-none");
      $("#f1 #entrenador_asignado").val(atleta.entrenador);
      $("#f1 #tipo_atleta").val(atleta.id_tipo_atleta);

      // Resetea y deshabilita el campo de contraseña
      $("#f1 #modificar_contraseña").prop("checked", false);
      $("#f1 #password").prop("disabled", true).val("");
   }

   function eliminarAtleta(cedula) {
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
            const datos = new FormData();
            datos.append("cedula", cedula);
            enviaAjax(datos, "?p=atletas&accion=eliminarAtleta").then((respuesta) => {
               muestraMensaje(
                  "Eliminado!",
                  respuesta.mensaje,
                  "success"
               );
               cargaListadoAtletas();
            });
         }
      });
   }
   $(document).on("click", ".btnEliminarTipo", function () {
      const id_tipo = $(this).data("id");
      Swal.fire({
         title: "¿Estás seguro?",
         text: "Esta acción eliminará el tipo de atleta seleccionado.",
         icon: "warning",
         showCancelButton: true,
         confirmButtonText: "Sí, eliminar",
         cancelButtonText: "No, cancelar",
      }).then((result) => {
         if (result.isConfirmed) {
            const datos = new FormData();
            datos.append("accion", "eliminar_tipo_atleta");
            datos.append("id_tipo", id_tipo);
            enviaAjax(datos, "").then(() => {
               muestraMensaje(
                  "Éxito",
                  "Tipo de atleta eliminado con éxito",
                  "success"
               );
               cargarListadoTipos2();
            });
         }
      });
   });

   $("#tablaatleta").on("click", ".btn-warning", function () {
      const cedula = $(this).data('id');
      $("#accion").val("modificar");
      obtenerAtleta(cedula);
   });

   $("#tablaatleta").on("click", ".btn-danger", function () {
      const cedula = $(this).data('id');
      eliminarAtleta(cedula);
   });

   $("input").on("keypress", function (e) {
      var id = $(this).attr("id");
      switch (id) {
         case "nombres":
         case "apellidos":
         case "lugar_nacimiento":
         case "nombres":
         case "apellidos":
         case "lugar_nacimiento":
         case "nombre_representante":
         case "parentesco_representante":
            validarKeyPress(e, REGEX.keypress_letras.regex);
            break;
         case "cedula":
         case "cedula_representante":
         case "telefono":
         case "telefono_representante":
            validarKeyPress(e, REGEX.keypress_numerico.regex);
            break;
         case "peso":
         case "altura":
            validarKeyPress(e, REGEX.keypress_decimal.regex);
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

   $("#fecha_nacimiento").on("change", function () {
      const form = $(this).closest("form");
      verificarFecha($(this), form.find(`#sfecha_nacimiento`));
      const edad = calcularEdad($(this).val());
      form.find(`#edad`).val(edad);
      form.find(`#representantesContainer`).toggle(edad < 18);
   });

   $("#openTipoAtletaModal").on("click", function () {
      $("#modal").modal("hide");
      $("#modalRegistrarTipoAtleta").modal("show");
   });

   $("#btnRegistrarTipoAtleta").on("click", function () {
      const nombreTipo = $("#nombre_tipo_atleta").val().trim();
      const tipoCobro = $("#tipo_cobro").val().trim();
      if (!nombreTipo || !tipoCobro) {
         alert("Por favor, complete todos los campos.");
         return;
      }
      const datos = new FormData();
      datos.append("accion", "registrar_tipo_atleta");
      datos.append("nombre_tipo_atleta", nombreTipo);
      datos.append("tipo_cobro", tipoCobro);
      enviaAjax(datos, "").then((respuesta) => {
         if (respuesta.ok) {
            cargarTiposAtleta();
            $("#modalRegistrarTipoAtleta").modal("hide");
            $("#modal").modal("show");
            $("#formRegistrarTipoAtleta")[0].reset();
         } else {
            alert("Error al registrar el tipo de atleta: " + respuesta.mensaje);
         }
      });
   });
});
