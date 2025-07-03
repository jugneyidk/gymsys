import {
   validarKeyPress,
   validarKeyUp,
   enviaAjax,
   muestraMensaje,
   REGEX,
   obtenerNotificaciones,
   debounce
} from "./comunes.js";
import { initDataTable } from "./datatables.js";
$(document).ready(function () {
   function cargaListadoRoles() {
      enviaAjax("", "?p=rolespermisos&accion=listadoRoles", "GET").then((respuesta) => {
         actualizarListadoRoles(respuesta.roles);
      });
   }
   obtenerNotificaciones();
   setInterval(() => obtenerNotificaciones(), 35000);
   cargaListadoRoles();

   function validarEnvio(formId) {
      let esValido = true;
      const form = $(formId);
      const validaciones = [
         {
            regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
            id: "nombre_rol",
            errorMsg: "Solo letras y espacios (3-50 caracteres)",
         },
      ];
      validaciones.forEach(({ regex, id, errorMsg }) => {
         esValido &= validarKeyUp(
            regex,
            form.find(`#${id}`),
            form.find(`#s${id}`),
            errorMsg
         );
      });
      return esValido;
   }

   $("#btnCrearRol").on("click", function () {
      $("#modalTitulo").text("Nuevo Rol");
      $("#btnSubmit").text("Registrar Rol");
   });
   $("#btnSubmit").on("click", function (event) {
      event.preventDefault();
      if (!$("#id_rol").val()) {
         if (validarEnvio("#form_incluir")) {
            const datos = new FormData($("#form_incluir")[0]);
            enviaAjax(datos, "?p=rolespermisos&accion=incluirRol").then((respuesta) => {
               muestraMensaje(
                  "Éxito",
                  respuesta.mensaje,
                  "success"
               );
               $("#modal").modal("hide");
               cargaListadoRoles();
            });
         }
      } else if (validarEnvio("#form_incluir")) {
         const datos = new FormData($("#form_incluir")[0]);
         console.log(datos);
         enviaAjax(datos, "?p=rolespermisos&accion=modificarRol").then((respuesta) => {
            muestraMensaje(
               "Éxito",
               respuesta.mensaje,
               "success"
            );
            $("#modal").modal("hide");
            cargaListadoRoles();
         });
      }
   });
   $("#f1").on("submit", function (e) {
      e.preventDefault();
      let regexCedula = /^\d{7,9}$/;
      if (
         !regexCedula.test($("#cedula").val()) ||
         ($("#id_rol_asignar").val() == "" || $("#id_rol_asignar").val() == 0)
      ) {
         muestraMensaje("Error", "Los valores ingresados no son validos", "error");
      } else {
         const datos = new FormData($("#f1")[0]);
         enviaAjax(datos, "?p=rolespermisos&accion=asignarRol").then((respuesta) => {
            muestraMensaje(
               "Éxito",
               respuesta.mensaje,
               "success"
            );
            $("#modalAsignarRol").modal("hide");
            cargaListadoRoles();
         });
      }
   });

   function llenarFormularioModificar(permisos) {
      $("#nombre_rol").val(permisos[0].nombre_rol);
      permisos.forEach((modulo) => {
         let pantalla = modulo.nombre_modulo;
         $(`${"#c" + pantalla}`).prop(
            "checked",
            modulo.crear === 1 ? true : false
         );
         $(`${"#r" + pantalla}`).prop("checked", modulo.leer === 1 ? true : false);
         $(`${"#u" + pantalla}`).prop(
            "checked",
            modulo.actualizar === 1 ? true : false
         );
         $(`${"#d" + pantalla}`).prop(
            "checked",
            modulo.eliminar === 1 ? true : false
         );
      });
   }
   function obtenerRol(idRol) {
      enviaAjax("", `?p=rolespermisos&accion=obtenerRol&id=${idRol}`, "GET").then((respuesta) => {
         llenarFormularioModificar(respuesta.rol);
         $("#modal").modal("show");
         $("#modalTitulo").text("Modificar Rol");
         $("#btnSubmit").text("Modificar Rol");
      });
   }
   function eliminarRol(id_rol) {
      muestraMensaje("¿Estás seguro que deseas eliminar este rol?", "No podrás revertir esto!", "warning", {
         showCancelButton: true,
         confirmButtonColor: "#d33",
         confirmButtonText: "Sí, eliminar!",
      }).then((result) => {
         if (result.isConfirmed) {
            const datos = new FormData();
            const token = document.getElementById('csrf_token_global').value;
            datos.append('_csrf_token', token);
            datos.append("id_rol", id_rol);
            enviaAjax(datos, "?p=rolespermisos&accion=eliminarRol").then((respuesta) => {
               muestraMensaje(
                  "Éxito",
                  respuesta.mensaje,
                  "success"
               );
               $("#modalModificar").modal("hide");
               cargaListadoRoles();
            });
         }
      });
   }
   function actualizarListadoRoles(roles) {
      let listadoRoles = "";
      let selectRoles = "";

      roles.forEach((rol) => {
         listadoRoles += `
                <tr>
                    <td class='align-middle text-capitalize'>${rol.nombre}</td>
                    <td class='align-middle'>
                    ${actualizar === 1
               ? `<button class='btn btn-block btn-warning me-2 w-auto' data-bs-toggle='modal' aria-label='Modificar rol ${rol.nombre}' data-tooltip="tooltip" title="Modificar rol" data-id=${rol.id_rol}><i class='fa-regular fa-pen-to-square'></i></button>`
               : ""
            }
                    ${eliminar === 1
               ? `<button class='btn btn-block btn-danger w-auto' aria-label='Eliminar rol ${rol.nombre}' data-tooltip="tooltip" title="Eliminar rol" data-id=${rol.id_rol}><i class='fa-solid fa-trash-can'></i></button>`
               : ""
            }                        
                    </td>
                </tr>
            `;
         selectRoles += `<option value="${rol.id_rol}" data-hash="${rol.id_rol_hash}">${rol.nombre}</option>`;
      });
      $("#listado").html(listadoRoles);
      $("#id_rol_asignar").html(selectRoles);
      $("#id_rol_asignar").val(0);

      initDataTable("#tablaroles", {
         columnDefs: [{ targets: [1], orderable: false, searchable: false }],
         order: [[0, "asc"]],
      }, listadoRoles);
   }
   const verificarCedulaDebounce = debounce(function () {
      let valor = $("#cedula").val();
      let regex = /^\d{7,9}$/;
      if (regex.test(valor)) {
         consultarUsuario(valor);
      }
   }, 1000); // 1000 ms de espera

   $("input").on("keypress", function (e) {
      const id = $(this).attr("id");
      const regexMap = {
         nombre_rol: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
         cedula: /^\d*$/,
      };
      if (regexMap[id]) {
         validarKeyPress(e, regexMap[id]);
      }
   });
   function consultarUsuario(cedula) {
      $("#cedula").removeClass("is-valid");
      $("#cedula").removeClass("is-invalid");
      $("#spinner-usuario").removeClass("d-none");
      $("#nombreUsuario").addClass("bg-secondary");
      $("#nombreUsuario").removeClass("bg-primary");
      $("#nombreUsuario").text("No seleccionado");
      enviaAjax("", `?p=rolespermisos&accion=obtenerRolUsuario&id=${cedula}`, 'GET', false).then(respuesta => {
         $("#nombreUsuario").text(
            `${respuesta.rol.nombre} ${respuesta.rol.apellido}`
         );
         $("#nombreUsuario").removeClass("bg-secondary");
         $("#nombreUsuario").addClass("bg-primary");
         $("#id_rol_asignar option").each(function () {
            if ($(this).data("hash") == respuesta.rol.id_rol_hash) {
               $("#id_rol_asignar").val($(this).val());
            }
         });
         $("#cedula").removeClass("is-invalid");
         $("#cedula").addClass("is-valid");
      }).catch(() => {
         $("#cedula").addClass("is-invalid");
         $("#nombreUsuario").text("No existe");
         $("#id_rol_asignar").val(0);
      }).finally(() => {
         $("#spinner-usuario").addClass("d-none");

      });
   }
   $("input").on("keyup", function () {
      const id = $(this).attr("id");
      const regexMap = {
         nombre_rol: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}$/,
         cedula: /^\d{7,9}$/,
      };
      if (regexMap[id]) {
         validarKeyUp(
            regexMap[id],
            $(this),
            $(`#s${id}`),
            id == "nombre_rol"
               ? "El nombre del rol debe ser entre 3 y 50 caracteres"
               : "La cedula cédula debe tener al menos 7 números"
         );
      }
   });

   $("#cedula").on("keyup", verificarCedulaDebounce);

   $("#tablaroles").on("click", ".btn-warning", function () {
      const idRol = $(this).data("id");
      $("#id_rol").val(idRol);
      obtenerRol(idRol);
   });

   $("#tablaroles").on("click", ".btn-danger", function () {
      const idRol = $(this).data("id");
      eliminarRol(idRol);
   });

   function limpiarFormulario() {
      const formulario = document.getElementById("form_incluir");
      formulario.reset();
   }
   $("#modal").on("hidden.bs.modal", function () {
      limpiarFormulario();
      $("#nombre_rol").removeClass("is-valid");
   });
});
