import {
    validarKeyPress,
    validarKeyUp,
    enviaAjax,
    muestraMensaje,
  } from "./comunes.js";
  
  $(document).ready(function () {
    function cargarOpcionesSelect(urlAccion, selector, placeholder, asignado = null) {
      const datos = new FormData();
      datos.append("accion", urlAccion);
      enviaAjax(datos, "").then((respuesta) => {
        if (respuesta.ok) {
          const selectElement = $(selector);
          selectElement.empty();
          selectElement.append(`<option value="">${placeholder}</option>`);
          respuesta[`${urlAccion.replace("obtener_", "")}`].forEach((opcion) => {
            selectElement.append(
              `<option value="${opcion.id}" ${opcion.id === asignado ? "selected" : ""}>${opcion.nombre}</option>`
            );
          });
        } else {
          console.error(`Error al cargar ${urlAccion}:`, respuesta.mensaje);
        }
      });
    }
  
    function cargarEntrenadores() {
      cargarOpcionesSelect("obtener_entrenadores", "#entrenador_asignado", "Seleccione un entrenador");
    }
  
    function cargarEntrenadoresParaModificacion(entrenadorAsignado) {
      cargarOpcionesSelect("obtener_entrenadores", "#entrenador_asignado_modificar", "Seleccione un entrenador", entrenadorAsignado);
    }
  
    function cargarTiposAtleta() {
      cargarOpcionesSelect("obtener_tipos_atleta", "#tipo_atleta", "Seleccione un tipo de atleta");
    }
  
    function cargarTiposAtletaParaModificacion(tipoAtletaAsignado) {
      cargarOpcionesSelect("obtener_tipos_atleta", "#tipo_atleta_modificar", "Seleccione un tipo de atleta", tipoAtletaAsignado);
    }
  
    cargarTiposAtleta();
    cargarEntrenadores();
    cargaListadoAtleta();
  
    function cargaListadoAtleta() {
      const datos = new FormData();
      datos.append("accion", "listado_atleta");
      enviaAjax(datos, "").then((respuesta) => {
        actualizarListadoAtletas(respuesta.respuesta);
      });
    }
  
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
      const sufijo = formId === "#f2" ? "_modificar" : "";
  
      const validaciones = [
        { regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, id: "nombres", errorMsg: "Solo letras y espacios (1-50 caracteres)" },
        { regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, id: "apellidos", errorMsg: "Solo letras y espacios (1-50 caracteres)" },
        { regex: /^\d{7,9}$/, id: "cedula", errorMsg: "La cédula debe tener al menos 7 números" },
        { regex: /^04\d{9}$/, id: "telefono", errorMsg: "El formato del teléfono debe ser 04XXXXXXXXX" },
        { regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, id: "correo", errorMsg: "Correo inválido" },
        { regex: /^\d+(\.\d{1,2})?$/, id: "peso", errorMsg: "Solo números y puntos decimales" },
        { regex: /^\d+(\.\d{1,2})?$/, id: "altura", errorMsg: "Solo números y puntos decimales" },
        { regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/, id: "lugar_nacimiento", errorMsg: "El lugar de nacimiento no puede estar vacío" }
      ];
  
      validaciones.forEach(({ regex, id, errorMsg }) => {
        esValido &= validarKeyUp(regex, form.find(`#${id}${sufijo}`), form.find(`#s${id}${sufijo}`), errorMsg);
      });
  
      const fechaNacimiento = form.find(`#fecha_nacimiento${sufijo}`).val();
      const edad = calcularEdad(fechaNacimiento);
      if (edad < 18) {
        const validacionesRepresentante = [
          { regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/, id: "nombre_representante", errorMsg: "Nombre del representante es obligatorio (1-100 caracteres)" },
          { regex: /^\d{7,9}$/, id: "cedula_representante", errorMsg: "La cédula del representante debe tener 7-9 números" },
          { regex: /^04\d{9}$/, id: "telefono_representante", errorMsg: "El teléfono del representante debe ser 04XXXXXXXXX" },
          { regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, id: "parentesco_representante", errorMsg: "El parentesco debe ser de 1-50 caracteres" }
        ];
  
        validacionesRepresentante.forEach(({ regex, id, errorMsg }) => {
          esValido &= validarKeyUp(regex, form.find(`#${id}`), form.find(`#s${id}`), errorMsg);
        });
      }
  
      esValido &= verificarFecha(form.find(`#fecha_nacimiento${sufijo}`), form.find(`#sfecha_nacimiento${sufijo}`));
  
      return esValido;
    }
  
    $("#btnIncluir, #btnModificar").on("click", function (event) {
      event.preventDefault();
    });
  
    function limpiarFormulario(formId) {
      $(formId).find("input[type=text], input[type=email], input[type=tel], input[type=number], input[type=password], input[type=date], select").val('');
      $(formId).find("input[type=checkbox]").prop("checked", false);
      $(formId).find("input").removeClass("is-invalid is-valid");
      $(formId).find(".representante").hide();
    }
  
    $("#btnIncluir").on("click", function () {
      if (validarEnvio("#f1")) {
        const datos = new FormData($("#f1")[0]);
        enviaAjax(datos, "").then((respuesta) => {
          muestraMensaje(
            "Atleta incluido",
            "El atleta se ha incluido satisfactoriamente.",
            "success"
          );
          cargaListadoAtleta();
          limpiarFormulario("#f1");
          $("#modalInscripcion").modal("hide");
        });
      }
    });
  
    $("#btnModificar").on("click", function () {
      if (validarEnvio("#f2")) {
        const datos = new FormData($("#f2")[0]);
        enviaAjax(datos, "").then((respuesta) => {
          muestraMensaje(
            "Atleta modificado",
            "El atleta se ha modificado satisfactoriamente.",
            "success"
          );
          cargaListadoAtleta();
          $("#modalModificar").modal("hide");
        });
      }
    });
  
    $("#modificar_contraseña").on("change", function () {
      $("#password_modificar").prop("disabled", !$(this).is(":checked")).val("");
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
                      <td class='align-middle'>${atleta.nombre} ${atleta.apellido}</td>
                      <td class='align-middle'>
                      ${actualizar === 1
            ? "<button class='btn btn-block btn-warning me-2' data-bs-toggle='modal'><i class='fa-regular fa-pen-to-square'></i></button>"
            : ""
          }
                        ${eliminar === 1
            ? "<button class='btn btn-block btn-danger'><i class='fa-solid fa-trash-can'></i></button>"
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
            first: "Primera",
            last: "Última",
            next: "Siguiente",
            previous: "Anterior",
          },
        },
        autoWidth: true,
        order: [[0, "desc"]],
        dom: '<"top"f>rt<"bottom"lp><"clear">',
      });
    }
  
    function cargarDatosAtleta(cedula) {
      const datos = new FormData();
      datos.append("accion", "obtener_atleta");
      datos.append("cedula", cedula);
      enviaAjax(datos, "").then((respuesta) => {
        if (respuesta.ok) {
          const atleta = respuesta.atleta;
          llenarFormularioModificar(atleta);
          cargarEntrenadoresParaModificacion(atleta.entrenador);
          $("#modalModificar").modal("show");
        } else {
          console.error("Error al obtener los datos del atleta:", respuesta.mensaje);
        }
      });
    }
  
    function mostrarCamposRepresentante() {
      const fechaNacimiento = $("#fecha_nacimiento").val();
      if (fechaNacimiento) {
        const edad = calcularEdad(fechaNacimiento);
        if (edad < 18) {
          $(".representante").show();
        } else {
          $(".representante").hide();
        }
      }
    }
  
    $("#fecha_nacimiento").on("change", function () {
      mostrarCamposRepresentante();
    });
  
    function llenarFormularioModificar(atleta) {
      $("#f2 #nombres_modificar").val(atleta.nombre);
      $("#f2 #apellidos_modificar").val(atleta.apellido);
      $("#f2 #cedula_modificar").val(atleta.cedula);
      $("#f2 #genero_modificar").val(atleta.genero);
      $("#f2 #fecha_nacimiento_modificar").val(atleta.fecha_nacimiento);
      $("#f2 #lugar_nacimiento_modificar").val(atleta.lugar_nacimiento);
      $("#f2 #peso_modificar").val(atleta.peso);
      $("#f2 #altura_modificar").val(atleta.altura);
      $("#f2 #estado_civil_modificar").val(atleta.estado_civil);
      $("#f2 #telefono_modificar").val(atleta.telefono);
      $("#f2 #correo_modificar").val(atleta.correo_electronico);
      $("#f2 #entrenador_asignado_modificar").val(atleta.entrenador);
      cargarTiposAtletaParaModificacion(atleta.id_tipo_atleta);
      $("#f2 #modificar_contraseña").prop("checked", false);
      $("#f2 #password_modificar").prop("disabled", true).val("");
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
          datos.append("accion", "eliminar");
          datos.append("cedula", cedula);
          enviaAjax(datos, "").then((respuesta) => {
            muestraMensaje(
              "Eliminado!",
              "El atleta ha sido eliminado.",
              "success"
            );
            cargaListadoAtleta();
          });
        }
      });
    }
  
    $("#tablaatleta").on("click", ".btn-warning", function () {
      const cedula = $(this).closest("tr").find("td:first").text();
      cargarDatosAtleta(cedula);
    });
  
    $("#tablaatleta").on("click", ".btn-danger", function () {
      const cedula = $(this).closest("tr").find("td:first").text();
      eliminarAtleta(cedula);
    });
  
    $("input").on("keypress", function (e) {
      const id = $(this).attr("id");
      const regexMap = {
        nombres: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
        apellidos: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
        lugar_nacimiento: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
        nombres_modificar: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
        apellidos_modificar: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
        lugar_nacimiento_modificar: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
        cedula: /^\d*$/,
        entrenador_asignado: /^\d*$/,
        edad: /^\d*$/,
        telefono: /^\d*$/,
        telefono_representante: /^\d*$/,
        cedula_modificar: /^\d*$/,
        entrenador_asignado_modificar: /^\d*$/,
        edad_modificar: /^\d*$/,
        telefono_modificar: /^\d*$/,
        telefono_representante_modificar: /^\d*$/,
        correo: /^[a-zA-Z0-9@._-]*$/,
        correo_modificar: /^[a-zA-Z0-9@._-]*$/,
        peso: /^\d*\.?\d*$/,
        altura: /^\d*\.?\d*$/,
        peso_modificar: /^\d*\.?\d*$/,
        altura_modificar: /^\d*\.?\d*$/,
        password: /^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]*$/,
        password_modificar: /^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]*$/,
        nombre_representante: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
        parentesco_representante: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
      };
  
      const fechaNacimiento = $("#fecha_nacimiento").val();
      const edad = calcularEdad(fechaNacimiento);
  
      if (edad >= 18 && ["nombre_representante", "cedula_representante", "telefono_representante", "parentesco_representante"].includes(id)) {
        return;
      }
  
      if (regexMap[id]) {
        validarKeyPress(e, regexMap[id]);
      }
    });
  
    $("input").on("keyup", function () {
      const id = $(this).attr("id");
      const formId = $(this).closest("form").attr("id");
      const sufijo = formId === "f2" ? "_modificar" : "";
      const regexMap = {
        nombres: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
        apellidos: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
        cedula: /^\d{7,9}$/,
        telefono: /^04\d{9}$/,
        telefono_representante: /^04\d{9}$/,
        correo: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
        peso: /^\d+(\.\d{1,2})?$/,
        altura: /^\d+(\.\d{1,2})?$/,
        lugar_nacimiento: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
        password: /^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{6,}$/,
        nombres_modificar: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
        apellidos_modificar: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
        cedula_modificar: /^\d{7,9}$/,
        telefono_modificar: /^04\d{9}$/,
        telefono_representante_modificar: /^04\d{9}$/,
        correo_modificar: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
        peso_modificar: /^\d+(\.\d{1,2})?$/,
        altura_modificar: /^\d+(\.\d{1,2})?$/,
        lugar_nacimiento_modificar: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
        password_modificar: /^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{6,}$/,
        nombre_representante: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
        parentesco_representante: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/
    };

    const fechaNacimiento = $("#fecha_nacimiento").val();
    const edad = calcularEdad(fechaNacimiento);

    if (edad >= 18 && ["nombre_representante", "cedula_representante", "telefono_representante", "parentesco_representante"].includes(id)) {
      return;
    }

    if (regexMap[id.replace(sufijo, "")]) {
      validarKeyUp(
        regexMap[id.replace(sufijo, "")],
        $(this),
        $(`#s${id}`),
        $(`#${id.replace(sufijo, "")}_error`).text()
      );
    }
  });

  $("#fecha_nacimiento, #fecha_nacimiento_modificar").on("change", function () {
    const form = $(this).closest("form");
    const sufijo = form.attr("id") === "f2" ? "_modificar" : "";
    verificarFecha($(this), form.find(`#sfecha_nacimiento${sufijo}`));
    const edad = calcularEdad($(this).val());
    form.find(`#edad${sufijo}`).val(edad);
    form.find(`#representanteInfo${sufijo}`).toggle(edad < 18);
  });

  $("#openTipoAtletaModal").on("click", function () {
    $("#modalInscripcion").modal("hide");
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
        $("#modalInscripcion").modal("show");
        $("#formRegistrarTipoAtleta")[0].reset();
      } else {
        alert("Error al registrar el tipo de atleta: " + respuesta.mensaje);
      }
    });
  });
});
    