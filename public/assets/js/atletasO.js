import {
   validarKeyPress,
   validarKeyUp,
   enviaAjax,
   muestraMensaje,
   REGEX,
   modalListener,
   obtenerNotificaciones,
} from "./comunes.js";

//INTENTO DE OPTIMIZACION DE ESTA MONDA
$(document).ready(function () {
   // Cachewe
   const $modal = $("#modal");
   const $f1 = $("#f1");
   const $tablaAtleta = $("#tablaatleta");
   const $fechaNacimiento = $("#fecha_nacimiento");
   const $modificarContraseña = $("#modificar_contraseña");
   const $password = $("#password");
   const $representantesContainer = $("#representantesContainer");

   // Configuración 
   const configSelect = {
      entrenadores: {
         accion: "listadoEntrenadores",
         selector: "#entrenador_asignado",
         template: (item) => `
          <option value="${item.cedula}">
            ${item.nombre} ${item.apellido}
          </option>`
      },
      tipoAtleta: {
         accion: "listadoTipoAtletas",
         selector: "#tipo_atleta",
         template: (item) => `
          <option value="${item.id_tipo_atleta}">
            ${item.nombre_tipo_atleta}
          </option>`
      }
   };

   // Funciones reutilizables
   const cargarDatosSelect = (tipo, selectedValue = null, selectorModificar = null) => {
      console.log('targetSelector:', selectorModificar || configSelect[tipo].selector);
      const { accion, selector, template } = configSelect[tipo];
      const targetSelector = selectorModificar || selector;
      enviaAjax("", `?p=${tipo.toLowerCase()}&accion=${accion}`, "GET").then((respuesta) => {
         const $select = $(targetSelector).empty().append('<option value="">Seleccione una opción</option>');
         const tipoElegido = tipo === 'entrenadores' ? 'entrenadores' : 'tipos';
         respuesta[tipoElegido].forEach(item => {
            $select.append(template(item));
         });
      });
   };

   const crearDataTable = (selector, columnsConfig) => {
      if ($.fn.DataTable.isDataTable(selector)) {
         $(selector).DataTable().destroy();
      }

      return $(selector).DataTable({
         columnDefs: columnsConfig,
         language: {
            lengthMenu: "Mostrar _MENU_ por página",
            zeroRecords: "No se encontraron registros",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "Buscar:",
            paginate: { next: "Siguiente", previous: "Anterior" }
         },
         autoWidth: true,
         order: [[0, "desc"]],
         dom: '<"top"f>rt<"bottom"lp><"clear">'
      });
   };

   const validarCampo = (campo, regexConfig) => {
      const $campo = $(`#${campo}`);
      const $mensaje = $(`#s${campo}`);
      return validarKeyUp(regexConfig.regex, $campo, $mensaje, regexConfig.mensaje);
   };

   const manejarExito = (accion) => {
      const mensajes = {
         incluir: { titulo: "Atleta incluido", texto: "registrado correctamente" },
         modificar: { titulo: "Atleta actualizado", texto: "actualizado correctamente" },
         eliminar: { titulo: "Atleta eliminado", texto: "eliminado correctamente" }
      };

      muestraMensaje(mensajes[accion].titulo, `El atleta se ha ${mensajes[accion].texto}.`, "success");
      return true;
   };

   // F principales
   const cargaListadoAtleta = () => {
      enviaAjax("", "?p=atletas&accion=listadoAtletas", "GET").then((respuesta) => actualizarListadoAtletas(respuesta.atletas));
   };

   const actualizarListadoAtletas = (atletas) => {
      const contenido = atletas.map(atleta => `
      <tr>
          <td class='align-middle'>${atleta.cedula}</td>
          <td class='align-middle'>${atleta.nombre} ${atleta.apellido}</td>
          <td class='align-middle'>
            ${actualizar === 1 ? `
              <button class='btn btn-warning me-2' 
                data-accion="modificar" 
                data-cedula="${atleta.cedula}"
                title="Modificar Atleta">
                <i class='fa-regular fa-pen-to-square'></i>
              </button>` : ''}
            ${eliminar === 1 ? `
              <button class='btn btn-danger' 
                data-accion="eliminar"
                data-cedula="${atleta.cedula}"
                title="Eliminar Atleta">
                <i class='fa-solid fa-trash-can'></i>
              </button>` : ''}
          </td>
        </tr>
      `).join('');
      $("#listado").html(contenido);
      crearDataTable("#tablaatleta", [{ targets: [2], orderable: false, searchable: false }]);
   };

   const validarFechaNacimiento = () => {
      const fecha = $fechaNacimiento.val();
      const hoy = new Date();
      const fechaNac = new Date(fecha);
      const isValid = fecha && fechaNac <= hoy;

      $fechaNacimiento
         .toggleClass("is-invalid", !isValid)
         .toggleClass("is-valid", isValid);

      $("#sfecha_nacimiento").text(
         isValid ? "" : fecha ? "La fecha debe ser anterior al día actual" : "Fecha obligatoria"
      );
      return isValid;
   };

   const calcularEdad = (fechaNacimiento) => {
      const hoy = new Date();
      const fechaNac = new Date(fechaNacimiento);
      let edad = hoy.getFullYear() - fechaNac.getFullYear();
      const mes = hoy.getMonth() - fechaNac.getMonth();
      return (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) ? edad - 1 : edad;
   };

   const validarFormulario = (formId) => {
      const $form = $(formId);
      const fechaNac = $form.find("#fecha_nacimiento").val();
      const edad = calcularEdad(fechaNac);

      let valido = validarFechaNacimiento();

      $form.find('input[type="text"]:not([name="accion"])').each(function () {
         const nombreInput = $(this).attr("name");
         if (edad >= 18 && nombreInput.includes("_representante")) return;
         valido &= validarCampo(nombreInput, REGEX[nombreInput]);
      });

      return valido;
   };

   const manejarFormulario = (accion) => {
      if (!validarFormulario("#f1")) return;

      const datos = new FormData($f1[0]);
      datos.set("accion", accion);

      enviaAjax(datos, "").then(respuesta => {
         if (respuesta.mensaje?.toLowerCase().includes("¿desea asignarlo?")) {
            Swal.fire({
               title: "¿Asignar representante existente?",
               text: respuesta.mensaje,
               icon: "warning",
               showCancelButton: true,
               confirmButtonText: "Sí, asignar",
               cancelButtonText: "Cancelar"
            }).then((result) => {
               if (result.isConfirmed) {
                  datos.append("asignar_representante_existente", "true");
                  enviaAjax(datos, "").then(procesarRespuestaPostConfirmacion(accion));
               }
            });
         } else {
            procesarRespuestaPostConfirmacion(accion)(respuesta);
         }
      });
   };

   const procesarRespuestaPostConfirmacion = (accion) => (respuesta) => {
      if (respuesta.ok) {
         manejarExito(accion);
         recargarInterfaz();
      } else {
         muestraMensaje("Error", respuesta.mensaje || "Error en la operación", "error");
      }
   };

   const recargarInterfaz = () => {
      cargaListadoAtleta();
      limpiarFormulario("#f1");
      $modal.modal("hide");
   };

   const limpiarFormulario = (formId) => {
      $(formId).trigger("reset");
      $(formId).find("input, select")
         .removeClass("is-invalid is-valid")
         .prop("disabled", false);
      $("#representantesContainer").addClass("d-none");
   };

   const cargarDatosAtleta = (cedula) => {
      enviaAjax("", `?p=atletas&accion=obtenerAtleta&id=${cedula}`, "GET").then((respuesta) => {
         Object.entries(respuesta.atleta).forEach(([key, value]) => {
            $(`#f1 #${key} `).val(value).trigger("change");
         });
         $("#entrenador_asignado").val(respuesta.atleta.entrenador);
         $("#tipo_atleta").val(respuesta.atleta.id_tipo_atleta);
         $("#modificar_contraseña_container").removeClass("d-none");
      });
   };

   const manejarEventos = () => {
      $(document)
         .on("click", "[data-accion]", function () {
            const accion = $(this).data("accion");
            const cedula = $(this).data("cedula");

            if (accion === "modificar") {
               $("#accion").val("modificar");
               cargarDatosAtleta(cedula);
               $modal.modal("show");
            } else if (accion === "eliminar") {
               eliminarAtleta(cedula);
            }
         })
         .on("input", "[data-regex]", function () {
            const $input = $(this);
            const regexKey = $input.data("regex");
            validarCampo(this.id, REGEX[regexKey]);
         })
         .on("keypress", "[data-keypress]", function (e) {
            const tipo = $(this).data("keypress");
            validarKeyPress(e, REGEX[`keypress_${tipo} `].regex);
         });

      $fechaNacimiento.on("change", function () {
         const edad = calcularEdad(this.value);
         $("#edad").val(edad);
         $representantesContainer.toggle(edad < 18);
      });

      $("#incluir, #btnModificar").click(e => {
         e.preventDefault();
         manejarFormulario(e.currentTarget.id === "incluir" ? "incluir" : "modificar");
      });
   };
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
   // Inicialización
   const inicializar = () => {
      modalListener("Atleta");
      obtenerNotificaciones();
      setInterval(() => obtenerNotificaciones(), 35000);
      cargarDatosSelect("entrenadores");
      cargarDatosSelect("tipoAtleta");
      cargaListadoAtleta();
      manejarEventos();
   };

   inicializar();
});
