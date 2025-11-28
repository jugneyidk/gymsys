import {
  validarKeyPress,
  validarKeyUp,
  enviaAjax,
  muestraMensaje,
  REGEX,
  modalListener,
  obtenerNotificaciones,
} from "./comunes.js";
import { initDataTable } from "./datatables.js";

$(document).ready(() => {
  // Helpers
  const cargaListadoAtletas = () =>
    enviaAjax("", "?p=atletas&accion=listadoAtletas", "GET").then(
      ({ atletas }) => actualizarListadoAtletas(atletas)
    );

  const cargaListadoRepresentantes = () =>
    enviaAjax("", "?p=representantes&accion=listadoRepresentantes", "GET").then(
      ({ representantes }) => actualizarListadoRepresentantes(representantes)
    );

  const cargarOpciones = (url, selector, placeholder, key, tpl) =>
    enviaAjax("", url, "GET").then((resp) => {
      const $sel = $(selector).empty().append(placeholder);
      resp[key].forEach((item) => $sel.append(tpl(item)));
    });

  const verificarFecha = ($inp, $msg) => {
    const val = $inp.val(),
      ok = val && new Date(val) <= new Date();
    $inp.toggleClass("is-invalid", !ok).toggleClass("is-valid", ok);
    $msg.text(
      ok
        ? ""
        : val
        ? "La fecha debe ser anterior al día actual"
        : "La fecha de nacimiento es obligatoria"
    );
    return ok;
  };

  const calcularEdad = (fecha) => {
    const hoy = new Date(),
      nac = new Date(fecha);
    let edad = hoy.getFullYear() - nac.getFullYear(),
      m = hoy.getMonth() - nac.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;
    return edad;
  };

  const validarEnvio = (formId) => {
    let esValido = true;
    const $f = $(formId),
      edad = calcularEdad($f.find("#fecha_nacimiento").val());

    $f.find('input[type="text"]:not([name="accion"])').each(function () {
      const name = this.name;
      if (edad >= 18 && name.includes("_representante")) return;
      esValido =
        esValido &&
        validarKeyUp(
          REGEX[name].regex,
          $(this),
          $f.find(`#s${name}`),
          REGEX[name].mensaje
        );
    });

    esValido =
      esValido &&
      verificarFecha(
        $f.find("#fecha_nacimiento"),
        $f.find("#sfecha_nacimiento")
      );

    return esValido;
  };

  const limpiarFormulario = (formId) => {
    $(formId)
      .find("input, select")
      .val("")
      .end()
      .find("input[type=checkbox]")
      .prop("checked", false)
      .end()
      .find("input")
      .removeClass("is-invalid is-valid")
      .end()
      .find("#representantesContainer")
      .addClass("d-none");
  };


  // Inicialización
  modalListener("Atleta");
  obtenerNotificaciones();
  modal.addEventListener("hidden.bs.modal", () => {
    $("#modificar_contraseña_container").addClass("d-none");
    $("#password").prop("disabled", false);
  });
  cargarOpciones(
    "?p=tipoatleta&accion=listadoTipoAtletas",
    "#tipo_atleta",
    '<option value="">Seleccione un tipo de atleta</option>',
    "tipos",
    (t) =>
      `<option value="${t.id_tipo_atleta_encriptado}" data-hash="${t.id_tipo_atleta_hash}">${t.nombre_tipo_atleta}</option>`
  );
  cargarOpciones(
    "?p=entrenadores&accion=listadoEntrenadores",
    "#entrenador_asignado",
    '<option value="">Seleccione un entrenador</option>',
    "entrenadores",
    (e) =>
      `<option value="${e.cedula_encriptado}" data-hash="${e.cedula_hash}">${e.nombre} ${e.apellido}</option>`
  );
  cargaListadoAtletas();
  cargaListadoRepresentantes();

  // Eventos de formulario
  $("#incluir, #btnModificar").click((e) => e.preventDefault());

  $("#incluir").click((e) => {
    e.preventDefault();
    if (!validarEnvio("#f1")) return;
    let accion;
    const datos = new FormData($("#f1")[0]);
    accion = datos.get("accion") || "incluir";
    enviaAjax(datos, `?p=atletas&accion=${accion}Atleta`).then((respuesta) => {
      muestraMensaje("Exito", respuesta.mensaje, "success");
      cargaListadoAtletas();
      limpiarFormulario("#f1");
      $("#modal").modal("hide");
    });
  });

  $("#modificar_contraseña").change(function () {
    const disabled = !this.checked;
    $("#password")
      .prop("disabled", disabled)
      .val("")
      .removeClass("is-valid is-invalid");
    $("#spassword").text("");
  });

  // Listado y acciones de tabla
  const actualizarListadoAtletas = (atletas) => {
    const filas = atletas
      .map(
        (a) => `
      <tr>
         <td class="align-middle">${a.cedula}</td>
         <td class="align-middle">${a.nombre} ${a.apellido}</td>
         <td class="align-middle">
          ${
            window.actualizar === 1
              ? `<button class="btn btn-warning me-2 w-auto" data-id="${a.cedula_encriptado}" data-bs-toggle="modal" title="Modificar Atleta" data-tooltip="tooltip" data-bs-placement="top"><i class="fa-regular fa-pen-to-square"></i></button>`
              : ""
          }
          ${
            window.eliminar === 1
              ? `<button class="btn btn-danger me-2 w-auto" data-id="${a.cedula_encriptado}" title="Eliminar Atleta" data-tooltip="tooltip" data-bs-placement="top"><i class="fa-solid fa-trash-can"></i></button>`
              : ""
          }
          
          <!-- Botón Tarjeta del Atleta -->
          <button class="btn btn-info btn-sm me-2 btn-tarjeta-atleta" 
                  data-id-atleta="${a.cedula_encriptado}" 
                  title="Ver Tarjeta del Atleta" 
                  data-tooltip="tooltip" 
                  data-bs-placement="top">
            <i class="fa-solid fa-id-card"></i> Tarjeta
          </button>
          
          <!-- Dropdown de Evaluaciones -->
          <div class="btn-group">
            <button type="button" 
                    class="btn btn-secondary btn-sm dropdown-toggle" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false"
                    title="Evaluaciones"
                    data-tooltip="tooltip">
              <i class="fa-solid fa-clipboard-list"></i> Evaluaciones
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item btn-abrir-test-postural" 
                   href="#" 
                   data-id-atleta="${a.cedula_encriptado}">
                  <i class="fa-solid fa-user-doctor"></i> Registrar Test Postural
                </a>
              </li>
              <li>
                <a class="dropdown-item btn-abrir-test-fms" 
                   href="#" 
                   data-id-atleta="${a.cedula_encriptado}">
                  <i class="fa-solid fa-dumbbell"></i> Registrar Test FMS
                </a>
              </li>
              <li>
                <a class="dropdown-item btn-abrir-lesion" 
                   href="#" 
                   data-id-atleta="${a.cedula_encriptado}">
                  <i class="fa-solid fa-notes-medical"></i> Registrar Lesión
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item btn-abrir-historial-evaluaciones" 
                   href="#" 
                   data-id-atleta="${a.cedula_encriptado}">
                  <i class="fa-solid fa-clock-rotate-left"></i> Ver Historial
                </a>
              </li>
            </ul>
          </div>
        </td>
      </tr>`
      )
      .join("");
    initDataTable(
      "#tablaatleta",
      {
        columnDefs: [
          {
            targets: [2],
            orderable: false,
            searchable: false,
          },
        ],
      },
      filas
    );
  };
  const actualizarListadoRepresentantes = (representantes) => {
    const filas = representantes
      .map(
        (r) => `
      <tr>
         <td class="align-middle">${r.cedula}</td>
         <td class="align-middle">${r.nombre_completo}</td>
         <td class="align-middle">${r.telefono}</td>
         <td class="align-middle"><span class="w-100 badge rounded-2 text-body${r.atleta_representado ? " bg-success" : " bg-danger"}">${r.atleta_representado ?? "No"}</span></td>
         <td class="align-middle">${r.parentesco}</td>
         <td class="align-middle">
          ${
            window.actualizar === 1
              ? `<button class="btn btn-warning me-2 w-auto" data-id="${r.cedula_encriptado}" data-bs-toggle="modal" title="Modificar Atleta" data-tooltip="tooltip" data-bs-placement="top"><i class="fa-regular fa-pen-to-square"></i></button>`
              : ""
          }
          ${
            window.eliminar === 1
              ? `<button class="btn btn-danger w-auto" data-id="${r.cedula_encriptado}" title="Eliminar Atleta" data-tooltip="tooltip" data-bs-placement="top"><i class="fa-solid fa-trash-can"></i></button>`
              : ""
          }
        </td>
      </tr>`
      )
      .join("");
    initDataTable(
      "#tablaRepresentantes",
      {
        columnDefs: [
          {
            targets: [5],
            orderable: false,
            searchable: false,
          },
        ],
      },
      filas
    );
  };

  $("#tablaatleta")
    .on("click", ".btn-warning", (e) => {
      $("#accion").val("modificar");
      obtenerAtleta($(e.currentTarget).data("id"));
    })
    .on("click", ".btn-danger", (e) => {
      eliminarAtleta($(e.currentTarget).data("id"));
    });

  // Gestión de tipos de atleta
  $("#btnConsultarTipos").click(() => {
    cargarListadoTipos();
    $("#contenedorTablaTipos").show();
  });

  const cargarListadoTipos = () => {
    enviaAjax("", "?p=tipoatleta&accion=listadoTipoAtletas", "GET").then(
      (respuesta) => actualizarTablaTipos(respuesta.tipos)
    );
  };

  const actualizarTablaTipos = (tipos) => {
    const $tbody = $("#tablaTipos tbody").empty();
    tipos.forEach((tipo, i) => {
      $tbody.append(`
        <tr>
          <td>${i + 1}</td>
          <td>${tipo.nombre_tipo_atleta}</td>
          <td>
            <button class="btn btn-danger btn-sm btnEliminarTipo" data-id="${
              tipo.id_tipo_atleta_encriptado
            }">
              Eliminar
            </button>
          </td>
        </tr>
      `);
    });
    if (!tipos.length) {
      $tbody.append("<tr><td colspan='3'>No hay tipos registrados.</td></tr>");
    }
  };

  $(document).on("click", ".btnEliminarTipo", function () {
    const id = $(this).data("id");
    muestraMensaje(
      "¿Estás seguro?",
      "Esta acción eliminará el tipo de atleta seleccionado.",
      "warning",
      {
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "No, cancelar",
      }
    ).then((respuesta) => {
      if (respuesta.isConfirmed) {
        const datos = new FormData();
        datos.append("id_tipo", id);
        enviaAjax(datos, "?p=tipoatleta&accion=eliminarTipoAtleta").then(
          (respuesta) => {
            cargarListadoTipos();
            muestraMensaje("Éxito", respuesta.mensaje, "success");
            cargarOpciones(
              "?p=tipoatleta&accion=listadoTipoAtletas",
              "#tipo_atleta",
              '<option value="">Seleccione un tipo de atleta</option>',
              "tipos",
              (tipos) =>
                `<option value="${tipos.id_tipo_atleta_encriptado}" data-id="${tipos.id_tipo_atleta_hash}">${tipos.nombre_tipo_atleta}</option>`
            );
          }
        );
      }
    });
  });

  // Obtener y editar atleta
  function obtenerAtleta(cedula) {
    enviaAjax("", `?p=atletas&accion=obtenerAtleta&id=${cedula}`, "GET").then(
      (respuesta) => {
        if (respuesta.atleta) {
          llenarFormularioModificar(respuesta.atleta);
          console.log(respuesta.atleta);
          $("#modal").modal("show");
        }
      }
    );
  }

  function llenarFormularioModificar({
    nombre,
    apellido,
    cedula,
    genero,
    fecha_nacimiento,
    lugar_nacimiento,
    peso,
    altura,
    estado_civil,
    telefono,
    correo_electronico,
    entrenador_hash,
    id_tipo_atleta_hash,
    cedula_representante,
    nombre_representante,
    telefono_representante,
    parentesco_representante,
  }) {
    Object.entries({
      nombres: nombre,
      apellidos: apellido,
      cedula,
      genero,
      fecha_nacimiento,
      lugar_nacimiento,
      peso,
      altura,
      estado_civil,
      telefono,
      correo_electronico,
    }).forEach(([id, val]) => {
      $(`#f1 #${id}`).val(val);
    });
    $("#entrenador_asignado option").each(function () {
      if ($(this).data("hash") === entrenador_hash) {
        $("#entrenador_asignado").val($(this).val());
      }
    });
    $("#tipo_atleta option").each(function () {
      if ($(this).data("hash") === id_tipo_atleta_hash) {
        $("#tipo_atleta").val($(this).val());
      }
    });
    $("#modificar_contraseña_container").removeClass("d-none");
    $("#f1 #modificar_contraseña").prop("checked", false);
    $("#f1 #password").prop("disabled", true).val("");
    if (cedula_representante) {
      $("#representantesContainer").removeClass("d-none");
      $("#f1 #cedula_representante").val(cedula_representante);
      $("#f1 #nombre_representante").val(nombre_representante);
      $("#f1 #telefono_representante").val(telefono_representante);
      $("#f1 #parentesco_representante").val(parentesco_representante);
    } else {
      $("#representantesContainer").addClass("d-none");
      $("#f1 #cedula_representante").val("");
      $("#f1 #nombre_representante").val("");
      $("#f1 #telefono_representante").val("");
      $("#f1 #parentesco_representante").val("");
    }
  }
  function eliminarAtleta(cedula) {
    muestraMensaje("¿Estás seguro?", "No podrás revertir esto!", "warning", {
      showCancelButton: true,
      confirmButtonColor: "#d33",
      confirmButtonText: "Sí, eliminar!",
    }).then((res) => {
      if (res.isConfirmed) {
        const datos = new FormData();
        datos.append("cedula", cedula);
        enviaAjax(datos, "?p=atletas&accion=eliminarAtleta").then(
          (respuesta) => {
            muestraMensaje("Eliminado!", respuesta.mensaje, "success");
            cargaListadoAtletas();
          }
        );
      }
    });
  }

  // Validaciones de input
  const reglasKeyPress = {
    keypress_letras: [
      "nombres",
      "apellidos",
      "lugar_nacimiento",
      "nombre_representante",
      "parentesco_representante",
    ],
    keypress_numerico: [
      "cedula",
      "cedula_representante",
      "telefono",
      "telefono_representante",
    ],
    keypress_decimal: ["peso", "altura"],
    keypress_correo: ["correo_electronico"],
    keypress_password: ["password"],
  };

  $("input")
    .on("keypress", function (e) {
      const id = this.id;
      for (let rule in reglasKeyPress) {
        if (reglasKeyPress[rule].includes(id)) {
          validarKeyPress(e, REGEX[rule].regex);
          break;
        }
      }
    })
    .on("keyup", function () {
      const id = this.id,
        $el = $(this);
      validarKeyUp(REGEX[id].regex, $el, $(`#s${id}`), REGEX[id].mensaje);
    });

  // Fecha de nacimiento dinámica
  $("#fecha_nacimiento").change(function () {
    const $f = $(this).closest("form"),
      val = $(this).val();
    verificarFecha($(this), $f.find("#sfecha_nacimiento"));
    const ed = calcularEdad(val);
    $f.find("#edad").val(ed);
    $("#representantesContainer").toggleClass("d-none", ed >= 18);
  });

  // Modal de tipo atleta
  $("#openTipoAtletaModal").click(() => {
    $("#modal").modal("hide");
    $("#modalRegistrarTipoAtleta").modal("show");
  });

  $("#btnRegistrarTipoAtleta").click(() => {
    const nombre = $("#nombre_tipo_atleta").val().trim(),
      cobro = $("#tipo_cobro").val().trim();
    if (!nombre || !cobro) {
      muestraMensaje("Error", "Todos los campos son obligatorios", "error");
      return;
    }
    const datos = new FormData();
    datos.append("nombre_tipo_atleta", nombre);
    datos.append("tipo_cobro", cobro);
    enviaAjax(datos, "?p=tipoatleta&accion=incluirTipoAtleta").then(
      (respuesta) => {
        muestraMensaje("Éxito", respuesta.mensaje, "success");
        cargarOpciones(
          "?p=tipoatleta&accion=listadoTipoAtletas",
          "#tipo_atleta",
          '<option value="">Seleccione un tipo de atleta</option>',
          "tipos",
          (tipos) =>
            `<option value="${tipos.id_tipo_atleta_encriptado}" data-hash="${tipos.id_tipo_atleta_hash}">${tipos.nombre_tipo_atleta}</option>`
        );
        cargarListadoTipos();
        $("#modalRegistrarTipoAtleta").modal("hide");
        $("#modal").modal("show");
        $("#formRegistrarTipoAtleta")[0].reset();
      }
    );
  });

  // ========================================
  // SUBMÓDULO: EVALUACIONES DEL ATLETA
  // ========================================

  // Variables globales para el módulo de evaluaciones
  let idAtletaActual = null;

  // ========================================
  // 1. EVENTOS PARA ABRIR MODALES
  // ========================================

  // Evento: Botón "Tarjeta del Atleta"
  $(document).on("click", ".btn-tarjeta-atleta", function (e) {
    e.preventDefault();
    const idAtleta = $(this).data("id-atleta");
    cargarTarjetaAtleta(idAtleta);
  });

  // Evento: Abrir modal Test Postural
  $(document).on("click", ".btn-abrir-test-postural", function (e) {
    e.preventDefault();
    const idAtleta = $(this).data("id-atleta");
    idAtletaActual = idAtleta;
    limpiarFormulario("#formTestPostural");
    $("#tp_id_atleta").val(idAtleta);
    $("#tp_id_test_postural").val(""); // Vacío = crear nuevo
    $("#modalTestPostural").modal("show");
  });

  // Evento: Abrir modal Test FMS
  $(document).on("click", ".btn-abrir-test-fms", function (e) {
    e.preventDefault();
    const idAtleta = $(this).data("id-atleta");
    idAtletaActual = idAtleta;
    limpiarFormulario("#formTestFms");
    $("#fms_id_atleta").val(idAtleta);
    $("#fms_id_test_fms").val(""); // Vacío = crear nuevo
    $("#fms_puntuacion_total").val(0);
    $("#modalTestFms").modal("show");
  });

  // Evento: Abrir modal Lesión
  $(document).on("click", ".btn-abrir-lesion", function (e) {
    e.preventDefault();
    const idAtleta = $(this).data("id-atleta");
    idAtletaActual = idAtleta;
    limpiarFormulario("#formLesion");
    $("#les_id_atleta").val(idAtleta);
    $("#les_id_lesion").val(""); // Vacío = crear nuevo
    $("#modalLesion").modal("show");
  });

  // Evento: Abrir modal Historial
  $(document).on("click", ".btn-abrir-historial-evaluaciones", function (e) {
    e.preventDefault();
    const idAtleta = $(this).data("id-atleta");
    idAtletaActual = idAtleta;
    $("#hist_tipo").val("");
    $("#tablaHistorialEvaluaciones tbody").html(
      '<tr><td colspan="5" class="text-center">Seleccione un tipo de historial</td></tr>'
    );
    $("#modalHistorialEvaluaciones").modal("show");
  });

  // ========================================
  // 2. CÁLCULO AUTOMÁTICO DE PUNTUACIÓN FMS
  // ========================================

  // Calcular puntuación total cuando cambie cualquier select FMS
  $(document).on("change", ".fms-puntuacion", function () {
    calcularPuntuacionFMS();
  });

  function calcularPuntuacionFMS() {
    let total = 0;
    $(".fms-puntuacion").each(function () {
      total += parseInt($(this).val()) || 0;
    });
    $("#fms_puntuacion_total").val(total);
  }

  // ========================================
  // 3. SUBMIT DE FORMULARIOS (CREATE/UPDATE)
  // ========================================

  // Submit: Test Postural
  $("#formTestPostural").on("submit", function (e) {
    e.preventDefault();
    const datos = new FormData(this);
    const idTest = $("#tp_id_test_postural").val();
    const accion = idTest ? "actualizarTestPostural" : "registrarTestPostural";

    enviaAjax(datos, `?p=evaluacionesatleta&accion=${accion}`).then(
      (respuesta) => {
        muestraMensaje("Éxito", respuesta.mensaje, "success");
        $("#modalTestPostural").modal("hide");
        limpiarFormulario("#formTestPostural");
        // Si estamos en el historial, recargar
        if ($("#modalHistorialEvaluaciones").hasClass("show")) {
          listarHistorialSegunTipo();
        }
      }
    );
  });

  // Submit: Test FMS
  $("#formTestFms").on("submit", function (e) {
    e.preventDefault();
    const datos = new FormData(this);
    const idTest = $("#fms_id_test_fms").val();
    const accion = idTest ? "actualizarTestFms" : "registrarTestFms";

    enviaAjax(datos, `?p=evaluacionesatleta&accion=${accion}`).then(
      (respuesta) => {
        muestraMensaje("Éxito", respuesta.mensaje, "success");
        $("#modalTestFms").modal("hide");
        limpiarFormulario("#formTestFms");
        // Si estamos en el historial, recargar
        if ($("#modalHistorialEvaluaciones").hasClass("show")) {
          listarHistorialSegunTipo();
        }
      }
    );
  });

  // Submit: Lesión
  $("#formLesion").on("submit", function (e) {
    e.preventDefault();
    const datos = new FormData(this);
    const idLesion = $("#les_id_lesion").val();
    const accion = idLesion ? "actualizarLesion" : "registrarLesion";

    enviaAjax(datos, `?p=evaluacionesatleta&accion=${accion}`).then(
      (respuesta) => {
        muestraMensaje("Éxito", respuesta.mensaje, "success");
        $("#modalLesion").modal("hide");
        limpiarFormulario("#formLesion");
        // Si estamos en el historial, recargar
        if ($("#modalHistorialEvaluaciones").hasClass("show")) {
          listarHistorialSegunTipo();
        }
      }
    );
  });

  // ========================================
  // FMS: SELECCIÓN VISUAL Y CÁLCULO TOTAL
  // ========================================

  // Inicializar tooltips de FMS cuando se abre el modal
  $('#modalTestFms').on('shown.bs.modal', function () {
    // Inicializar todos los tooltips dentro del modal
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('#modalTestFms [data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl, {
        html: true,
        sanitize: false
      });
    });
  });

  // Manejar clicks en botones de puntuación FMS
  $('body').on('click', '.fms-score-btn', function() {
    const btn = $(this);
    const container = btn.closest('.fms-score-buttons');
    const targetId = container.data('target');
    const score = btn.data('score');
    
    // Remover clase active de todos los botones del grupo
    container.find('.fms-score-btn').removeClass('active');
    
    // Agregar clase active al botón seleccionado
    btn.addClass('active');
    
    // Actualizar el input hidden con el valor seleccionado
    $('#' + targetId).val(score);
    
    // Calcular y actualizar puntuación total
    calcularPuntuacionTotalFMS();
  });

  // Calcular puntuación total FMS
  function calcularPuntuacionTotalFMS() {
    let total = 0;
    
    // Sumar todas las puntuaciones
    $('#formTestFms input[type="hidden"][name$="_profunda"], #formTestFms input[type="hidden"][name$="_valla"], #formTestFms input[type="hidden"][name$="_linea"], #formTestFms input[type="hidden"][name$="_hombro"], #formTestFms input[type="hidden"][name$="_recta"], #formTestFms input[type="hidden"][name$="_tronco"], #formTestFms input[type="hidden"][name$="_rotacional"]').each(function() {
      const val = parseInt($(this).val()) || 0;
      total += val;
    });
    
    // Actualizar displays
    $('#fms_puntuacion_total').val(total);
    $('#fms_puntuacion_total_display').text(total);
  }

  // ========================================
  // 4. CARGAR TARJETA DEL ATLETA
  // ========================================

  function cargarTarjetaAtleta(idAtleta) {
    enviaAjax(
      "",
      `?p=evaluacionesatleta&accion=obtenerTarjetaAtleta&id_atleta=${idAtleta}`,
      "GET"
    ).then((respuesta) => {
      // Datos básicos del atleta (compactos)
      if (respuesta.atleta) {
        const edad = calcularEdad(respuesta.atleta.fecha_nacimiento);
        $("#tarjeta-datos-basicos").html(`
          <div class="row g-2 small">
            <div class="col-6 col-md-3"><strong>Nombre:</strong><br>${respuesta.atleta.nombre} ${respuesta.atleta.apellido}</div>
            <div class="col-6 col-md-3"><strong>Cédula:</strong><br>${respuesta.atleta.cedula}</div>
            <div class="col-6 col-md-2"><strong>Edad:</strong><br>${edad} años</div>
            <div class="col-6 col-md-2"><strong>Peso:</strong><br>${respuesta.atleta.peso} kg</div>
            <div class="col-6 col-md-2"><strong>Altura:</strong><br>${respuesta.atleta.altura} cm</div>
          </div>
        `);
      } else {
        $("#tarjeta-datos-basicos").html('<p class="text-muted mb-0">No hay datos disponibles</p>');
      }

      // Último Test Postural
      if (respuesta.ultimo_test_postural) {
        const tp = respuesta.ultimo_test_postural;
        // Actualizar header del acordeón con fecha
        $("#headingPostural button").html(`
          <i class="fas fa-spine me-2 text-primary"></i><strong>Última Evaluación Postural</strong>
          <small class="ms-2 text-muted">(${tp.fecha_evaluacion})</small>
        `);
        
        $("#tarjeta-test-postural").html(`
          <div class="row g-2 small">
            <div class="col-6 col-lg-3 d-flex align-items-center">
              <span class="me-2"><strong>Cifosis Dorsal:</strong></span>
              <span class="badge bg-${getBadgeColor(tp.cifosis_dorsal)}">${tp.cifosis_dorsal}</span>
            </div>
            <div class="col-6 col-lg-3 d-flex align-items-center">
              <span class="me-2"><strong>Lordosis Lumbar:</strong></span>
              <span class="badge bg-${getBadgeColor(tp.lordosis_lumbar)}">${tp.lordosis_lumbar}</span>
            </div>
            <div class="col-6 col-lg-3 d-flex align-items-center">
              <span class="me-2"><strong>Escoliosis:</strong></span>
              <span class="badge bg-${getBadgeColor(tp.escoliosis)}">${tp.escoliosis}</span>
            </div>
            <div class="col-6 col-lg-3 d-flex align-items-center">
              <span class="me-2"><strong>Incl. Pelvis:</strong></span>
              <span class="badge bg-${getBadgeColor(tp.inclinacion_pelvis)}">${tp.inclinacion_pelvis}</span>
            </div>
            <div class="col-6 col-lg-3 d-flex align-items-center">
              <span class="me-2"><strong>Valgo Rodilla:</strong></span>
              <span class="badge bg-${getBadgeColor(tp.valgo_rodilla)}">${tp.valgo_rodilla}</span>
            </div>
            <div class="col-6 col-lg-3 d-flex align-items-center">
              <span class="me-2"><strong>Varo Rodilla:</strong></span>
              <span class="badge bg-${getBadgeColor(tp.varo_rodilla)}">${tp.varo_rodilla}</span>
            </div>
            <div class="col-6 col-lg-3 d-flex align-items-center">
              <span class="me-2"><strong>Rot. Hombros:</strong></span>
              <span class="badge bg-${getBadgeColor(tp.rotacion_hombros)}">${tp.rotacion_hombros}</span>
            </div>
            <div class="col-6 col-lg-3 d-flex align-items-center">
              <span class="me-2"><strong>Desn. Escápulas:</strong></span>
              <span class="badge bg-${getBadgeColor(tp.desnivel_escapulas)}">${tp.desnivel_escapulas}</span>
            </div>
          </div>
          ${tp.observaciones ? `<div class="mt-2 small"><strong>Observaciones:</strong> ${tp.observaciones}</div>` : ""}
        `);
      } else {
        $("#headingPostural button").html(`
          <i class="fas fa-spine me-2 text-primary"></i><strong>Última Evaluación Postural</strong>
          <small class="ms-2 text-muted">(Sin registro)</small>
        `);
        $("#tarjeta-test-postural").html('<p class="text-muted mb-0">No hay evaluaciones posturales registradas</p>');
      }

      // Último Test FMS
      if (respuesta.ultimo_test_fms) {
        const fms = respuesta.ultimo_test_fms;
        const nivelRiesgo = fms.puntuacion_total <= 14 ? "Alto" : "Bajo";
        const colorRiesgo = fms.puntuacion_total <= 14 ? "danger" : "success";
        
        // Actualizar header del acordeón con puntuación
        $("#headingFMS button").html(`
          <i class="fas fa-dumbbell me-2 text-success"></i><strong>Último Test FMS</strong>
          <small class="ms-2 text-muted">(${fms.fecha_evaluacion} - <span class="badge bg-${colorRiesgo}">${fms.puntuacion_total}/21</span>)</small>
        `);
        
        $("#tarjeta-test-fms").html(`
          <div class="mb-2">
            <span class="badge bg-${colorRiesgo} fs-6">${fms.puntuacion_total}/21 puntos</span>
            <span class="ms-2">Riesgo de lesión: <strong>${nivelRiesgo}</strong></span>
          </div>
          <div class="row g-2 small">
            <div class="col-6 col-md-4"><strong>Sentadilla Profunda:</strong> <span class="badge bg-secondary">${fms.sentadilla_profunda}/3</span></div>
            <div class="col-6 col-md-4"><strong>Paso de Valla:</strong> <span class="badge bg-secondary">${fms.paso_valla}/3</span></div>
            <div class="col-6 col-md-4"><strong>Estocada en Línea:</strong> <span class="badge bg-secondary">${fms.estocada_en_linea}/3</span></div>
            <div class="col-6 col-md-4"><strong>Movilidad Hombro:</strong> <span class="badge bg-secondary">${fms.movilidad_hombro}/3</span></div>
            <div class="col-6 col-md-4"><strong>Elevación Pierna:</strong> <span class="badge bg-secondary">${fms.elevacion_pierna_recta}/3</span></div>
            <div class="col-6 col-md-4"><strong>Estabilidad Tronco:</strong> <span class="badge bg-secondary">${fms.estabilidad_tronco}/3</span></div>
            <div class="col-12 col-md-4"><strong>Estabilidad Rotacional:</strong> <span class="badge bg-secondary">${fms.estabilidad_rotacional}/3</span></div>
          </div>
          ${fms.observaciones ? `<div class="mt-2 small"><strong>Observaciones:</strong> ${fms.observaciones}</div>` : ""}
        `);
      } else {
        $("#headingFMS button").html(`
          <i class="fas fa-dumbbell me-2 text-success"></i><strong>Último Test FMS</strong>
          <small class="ms-2 text-muted">(Sin registro)</small>
        `);
        $("#tarjeta-test-fms").html('<p class="text-muted mb-0">No hay tests FMS registrados</p>');
      }

      // Lesiones recientes
      if (respuesta.lesiones_recientes && respuesta.lesiones_recientes.length > 0) {
        // Actualizar header con cantidad
        const cantidadActivas = respuesta.lesiones_recientes.filter(l => l.estado_lesion === 'Activa').length;
        $("#headingLesiones button").html(`
          <i class="fas fa-band-aid me-2 text-danger"></i><strong>Lesiones Recientes</strong>
          <small class="ms-2 text-muted">(${cantidadActivas} activa${cantidadActivas !== 1 ? 's' : ''} / ${respuesta.lesiones_recientes.length} total)</small>
        `);
        
        let htmlLesiones = '<div class="list-group list-group-flush">';
        respuesta.lesiones_recientes.forEach((lesion) => {
          const estadoBadge = lesion.estado_lesion === "Activa"
              ? '<span class="badge bg-danger">Activa</span>'
              : '<span class="badge bg-success">Recuperada</span>';
          const gravedadColor = lesion.gravedad === 'severa' ? 'danger' : lesion.gravedad === 'moderada' ? 'warning' : 'info';
          
          htmlLesiones += `
            <div class="list-group-item px-0 py-2">
              <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                  <div><strong>${lesion.zona_afectada}</strong> - ${lesion.tipo_lesion} ${estadoBadge}</div>
                  <small class="text-muted d-block">
                    <i class="far fa-calendar me-1"></i>${lesion.fecha_lesion} | 
                    <span class="badge bg-${gravedadColor} badge-sm">${lesion.gravedad}</span>
                  </small>
                </div>
              </div>
            </div>
          `;
        });
        htmlLesiones += "</div>";
        $("#tarjeta-lesiones-recientes").html(htmlLesiones);
      } else {
        $("#headingLesiones button").html(`
          <i class="fas fa-band-aid me-2 text-danger"></i><strong>Lesiones Recientes</strong>
          <small class="ms-2 text-muted">(Sin registro)</small>
        `);
        $("#tarjeta-lesiones-recientes").html('<p class="text-muted mb-0">No hay lesiones registradas</p>');
      }

      // ============================
      // BLOQUE: IA (Análisis de Riesgo)
      // ============================
      const ia = respuesta.ia || null;

      // Limpieza inicial
      $("#riesgo-score").text("--");
      $("#badge-riesgo")
          .removeClass("bg-success bg-warning bg-danger bg-secondary text-dark")
          .text("Sin análisis disponible");

      $("#desglose-fms").text("--");
      $("#desglose-postural").text("--");
      $("#desglose-lesiones").text("--");
      $("#desglose-asistencia").text("--");

      $("#lista-factores").html(
          `<li class="list-group-item text-muted fst-italic">
              No hay factores de riesgo disponibles.
           </li>`
      );
      $("#lista-recomendaciones").html(
          `<li class="list-group-item text-muted fst-italic">
              No hay recomendaciones disponibles.
           </li>`
      );

      // Si el backend envió IA, procesarla
      if (ia) {
          // Score y nivel
          if (typeof ia.riesgo_score !== "undefined") {
              $("#riesgo-score").text(ia.riesgo_score);
          }

          if (ia.riesgo_nivel) {
              let clase = "bg-secondary";
              let texto = "Riesgo desconocido";

              if (ia.riesgo_nivel === "bajo") {
                  clase = "bg-success";
                  texto = "Riesgo BAJO";
              } else if (ia.riesgo_nivel === "medio") {
                  clase = "bg-warning text-dark";
                  texto = "Riesgo MEDIO";
              } else if (ia.riesgo_nivel === "alto") {
                  clase = "bg-danger";
                  texto = "Riesgo ALTO";
              }

              $("#badge-riesgo")
                  .removeClass("bg-success bg-warning bg-danger bg-secondary text-dark")
                  .addClass(clase)
                  .text(texto);
          }

          // Desglose
          const desglose = ia.desglose || {};
          if (typeof desglose.fms !== "undefined") {
              $("#desglose-fms").text(desglose.fms);
          }
          if (typeof desglose.postural !== "undefined") {
              $("#desglose-postural").text(desglose.postural);
          }
          if (typeof desglose.lesiones !== "undefined") {
              $("#desglose-lesiones").text(desglose.lesiones);
          }
          if (typeof desglose.asistencia !== "undefined") {
              $("#desglose-asistencia").text(desglose.asistencia);
          }

          // Factores clave
          if (Array.isArray(ia.factores_clave) && ia.factores_clave.length > 0) {
              const factoresHTML = ia.factores_clave
                  .map(
                      (f) =>
                          `<li class="list-group-item">
                              <i class="bi bi-exclamation-circle text-warning me-1"></i>
                              ${f}
                           </li>`
                  )
                  .join("");
              $("#lista-factores").html(factoresHTML);
          }

          // Recomendaciones
          if (Array.isArray(ia.recomendaciones) && ia.recomendaciones.length > 0) {
              const recsHTML = ia.recomendaciones
                  .map(
                      (r) =>
                          `<li class="list-group-item">
                              <i class="bi bi-lightbulb text-primary me-1"></i>
                              ${r}
                           </li>`
                  )
                  .join("");
              $("#lista-recomendaciones").html(recsHTML);
          }
      }

      // ============================
      // Resumen de riesgo simple (Legacy, para compatibilidad)
      // ============================
      const resumen = respuesta.resumen_lesiones || {
        total_lesiones: 0,
        lesiones_activas: 0,
      };
      
      // Usar datos de IA si están disponibles, sino fallback a cálculo antiguo
      let nivelRiesgo = ia && ia.riesgo_nivel ? ia.riesgo_nivel.charAt(0).toUpperCase() + ia.riesgo_nivel.slice(1) : "Bajo";
      let colorRiesgo = ia && ia.riesgo_nivel ? (ia.riesgo_nivel === "alto" ? "danger" : ia.riesgo_nivel === "medio" ? "warning" : "success") : "success";
      
      $("#tarjeta-resumen-riesgo").html(`
        <div class="alert alert-${colorRiesgo} mb-2 py-2">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <strong class="fs-5"><i class="fas fa-exclamation-triangle me-2"></i>RIESGO: ${nivelRiesgo.toUpperCase()}</strong>
              <div class="small mt-1">
                ${ia ? '<em>Análisis IA disponible en la sección "Análisis de Riesgo (IA)" ▼</em>' : 'No hay análisis de riesgo disponible'}
              </div>
            </div>
            <div class="text-end small">
              <div><strong>${resumen.lesiones_activas}</strong> activa${resumen.lesiones_activas !== 1 ? 's' : ''}</div>
              <div class="text-muted">${resumen.total_lesiones} total${resumen.total_lesiones !== 1 ? 'es' : ''}</div>
            </div>
          </div>
        </div>
      `);

      // Mostrar modal
      $("#modalTarjetaAtleta").modal("show");
    });
  }

  // Helper: Color de badge según gravedad
  function getBadgeColor(nivel) {
    switch (nivel) {
      case "ninguna":
        return "success";
      case "leve":
        return "info";
      case "moderada":
        return "warning";
      case "severa":
        return "danger";
      default:
        return "secondary";
    }
  }

  // ========================================
  // 5. HISTORIAL DE EVALUACIONES
  // ========================================

  // Evento: Cambio de tipo de historial
  $("#hist_tipo").on("change", function () {
    listarHistorialSegunTipo();
  });

  function listarHistorialSegunTipo() {
    const tipo = $("#hist_tipo").val();
    if (!tipo) {
      $("#tablaHistorialEvaluaciones tbody").html(
        '<tr><td colspan="5" class="text-center">Seleccione un tipo de historial</td></tr>'
      );
      return;
    }

    switch (tipo) {
      case "postural":
        listarTestsPosturales(idAtletaActual);
        break;
      case "fms":
        listarTestsFms(idAtletaActual);
        break;
      case "lesiones":
        listarLesiones(idAtletaActual);
        break;
    }
  }

  // Función helper: Verificar si han pasado 24 horas
  function hanPasado24Horas(fechaCreacion) {
    const fecha = new Date(fechaCreacion);
    const ahora = new Date();
    const diferencia = ahora - fecha;
    return diferencia > 86400000; // 86400000 ms = 24 horas
  }

  // Listar Tests Posturales
  function listarTestsPosturales(idAtleta) {
    enviaAjax(
      "",
      `?p=evaluacionesatleta&accion=listarTestsPosturalesPorAtleta&id_atleta=${idAtleta}`,
      "GET"
    ).then((respuesta) => {
      const tbody = $("#tablaHistorialEvaluaciones tbody");
      tbody.empty();

      if (
        respuesta.tests_posturales &&
        respuesta.tests_posturales.length > 0
      ) {
        respuesta.tests_posturales.forEach((test) => {
          // Contar problemas
          let problemas = 0;
          ["cifosis_dorsal","lordosis_lumbar","escoliosis","inclinacion_pelvis",
           "valgo_rodilla","varo_rodilla","rotacion_hombros","desnivel_escapulas"
          ].forEach(campo => {
            if (test[campo] === "moderada" || test[campo] === "severa") {
              problemas++;
            }
          });

          const estado =
            problemas === 0
              ? '<span class="badge bg-success">Normal</span>'
              : problemas <= 2
              ? '<span class="badge bg-warning">Leve</span>'
              : '<span class="badge bg-danger">Atención</span>';

          // Verificar si han pasado 24 horas y si el usuario no es admin
          const bloqueado = !esAdministrador && hanPasado24Horas(test.fecha_creacion);
          const disabledAttr = bloqueado ? 'disabled' : '';
          const titleBloqueo = bloqueado ? 'title="Solo administradores pueden editar/eliminar después de 24 horas"' : '';

          tbody.append(`
            <tr>
              <td>${test.fecha_evaluacion}</td>
              <td>Test Postural</td>
              <td>${problemas} problema(s) detectado(s)</td>
              <td>${estado}</td>
              <td>
                <button class="btn btn-sm btn-info btn-ver-test-postural" 
                        data-id="${test.id_test_postural}"
                        data-hash="${test.id_test_postural_hash}"
                        title="Ver detalles">
                  <i class="fa-solid fa-eye"></i>
                </button>
                <button class="btn btn-sm btn-warning btn-editar-test-postural" 
                        data-id="${test.id_test_postural}"
                        data-hash="${test.id_test_postural_hash}"
                        ${disabledAttr}
                        ${titleBloqueo}>
                  <i class="fa-solid fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-eliminar-test-postural" 
                        data-id="${test.id_test_postural}"
                        data-hash="${test.id_test_postural_hash}"
                        ${disabledAttr}
                        ${titleBloqueo}>
                  <i class="fa-solid fa-trash"></i>
                </button>
              </td>
            </tr>
          `);
        });
      } else {
        tbody.html(
          '<tr><td colspan="5" class="text-center">No hay tests posturales registrados</td></tr>'
        );
      }
    });
  }

  // Listar Tests FMS
  function listarTestsFms(idAtleta) {
    enviaAjax(
      "",
      `?p=evaluacionesatleta&accion=listarTestsFmsPorAtleta&id_atleta=${idAtleta}`,
      "GET"
    ).then((respuesta) => {
      const tbody = $("#tablaHistorialEvaluaciones tbody");
      tbody.empty();

      if (respuesta.tests_fms && respuesta.tests_fms.length > 0) {
        respuesta.tests_fms.forEach((test) => {
          const nivelRiesgo = test.puntuacion_total <= 14 ? "Alto" : "Bajo";
          const colorRiesgo =
            test.puntuacion_total <= 14 ? "danger" : "success";

          // Verificar si han pasado 24 horas y si el usuario no es admin
          const bloqueado = !esAdministrador && hanPasado24Horas(test.fecha_creacion);
          const disabledAttr = bloqueado ? 'disabled' : '';
          const titleBloqueo = bloqueado ? 'title="Solo administradores pueden editar/eliminar después de 24 horas"' : '';

          tbody.append(`
            <tr>
              <td>${test.fecha_evaluacion}</td>
              <td>Test FMS</td>
              <td>Puntuación: ${test.puntuacion_total}/21</td>
              <td><span class="badge bg-${colorRiesgo}">Riesgo ${nivelRiesgo}</span></td>
              <td>
                <button class="btn btn-sm btn-info btn-ver-test-fms" 
                        data-id="${test.id_test_fms}"
                        data-hash="${test.id_test_fms_hash}"
                        title="Ver detalles">
                  <i class="fa-solid fa-eye"></i>
                </button>
                <button class="btn btn-sm btn-warning btn-editar-test-fms" 
                        data-id="${test.id_test_fms}"
                        data-hash="${test.id_test_fms_hash}"
                        ${disabledAttr}
                        ${titleBloqueo}>
                  <i class="fa-solid fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-eliminar-test-fms" 
                        data-id="${test.id_test_fms}"
                        data-hash="${test.id_test_fms_hash}"
                        ${disabledAttr}
                        ${titleBloqueo}>
                  <i class="fa-solid fa-trash"></i>
                </button>
              </td>
            </tr>
          `);
        });
      } else {
        tbody.html(
          '<tr><td colspan="5" class="text-center">No hay tests FMS registrados</td></tr>'
        );
      }
    });
  }

  // Listar Lesiones
  function listarLesiones(idAtleta) {
    enviaAjax(
      "",
      `?p=evaluacionesatleta&accion=listarLesionesPorAtleta&id_atleta=${idAtleta}`,
      "GET"
    ).then((respuesta) => {
      const tbody = $("#tablaHistorialEvaluaciones tbody");
      tbody.empty();

      if (respuesta.lesiones && respuesta.lesiones.length > 0) {
        respuesta.lesiones.forEach((lesion) => {
          const estadoBadge =
            lesion.estado_lesion === "Activa"
              ? '<span class="badge bg-danger">Activa</span>'
              : '<span class="badge bg-success">Recuperada</span>';

          tbody.append(`
            <tr>
              <td>${lesion.fecha_lesion}</td>
              <td>Lesión ${lesion.tipo_lesion}</td>
              <td>${lesion.zona_afectada} - Gravedad: ${lesion.gravedad}</td>
              <td>${estadoBadge}</td>
              <td>
                <button class="btn btn-sm btn-warning btn-editar-lesion" 
                        data-id="${lesion.id_lesion}"
                        data-hash="${lesion.id_lesion_hash}">
                  <i class="fa-solid fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-eliminar-lesion" 
                        data-id="${lesion.id_lesion}"
                        data-hash="${lesion.id_lesion_hash}">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </td>
            </tr>
          `);
        });
      } else {
        tbody.html(
          '<tr><td colspan="5" class="text-center">No hay lesiones registradas</td></tr>'
        );
      }
    });
  }

  // ========================================
  // 6. EDITAR REGISTROS DESDE HISTORIAL
  // ========================================

  // Editar Test Postural
  $(document).on("click", ".btn-editar-test-postural", function () {
    const idTest = $(this).data("id");
    // Obtener datos del test (deberías implementar un endpoint para esto)
    enviaAjax(
      "",
      `?p=evaluacionesatleta&accion=obtenerTestPostural&id=${idTest}`,
      "GET"
    ).then((respuesta) => {
      if (!respuesta.exito) {
        mostrarNotificacion(respuesta.mensaje || "Error al obtener el test", "error");
        return;
      }
      const test = respuesta.datos;
      $("#tp_id_test_postural").val(test.id_test_postural);
      $("#tp_id_atleta").val(test.id_atleta);
      $("#tp_fecha_evaluacion").val(test.fecha_evaluacion);
      $("#tp_cifosis_dorsal").val(test.cifosis_dorsal);
      $("#tp_lordosis_lumbar").val(test.lordosis_lumbar);
      $("#tp_escoliosis").val(test.escoliosis);
      $("#tp_inclinacion_pelvis").val(test.inclinacion_pelvis);
      $("#tp_valgo_rodilla").val(test.valgo_rodilla);
      $("#tp_varo_rodilla").val(test.varo_rodilla);
      $("#tp_rotacion_hombros").val(test.rotacion_hombros);
      $("#tp_desnivel_escapulas").val(test.desnivel_escapulas);
      $("#tp_observaciones").val(test.observaciones);

      $("#modalHistorialEvaluaciones").modal("hide");
      $("#modalTestPostural").modal("show");
    });
  });

  // Editar Test FMS
  $(document).on("click", ".btn-editar-test-fms", function () {
    const idTest = $(this).data("id");
    enviaAjax(
      "",
      `?p=evaluacionesatleta&accion=obtenerTestFms&id=${idTest}`,
      "GET"
    ).then((respuesta) => {
      if (!respuesta.exito) {
        mostrarNotificacion(respuesta.mensaje || "Error al obtener el test", "error");
        return;
      }
      const test = respuesta.datos;
      $("#fms_id_test_fms").val(test.id_test_fms);
      $("#fms_id_atleta").val(test.id_atleta);
      $("#fms_fecha_evaluacion").val(test.fecha_evaluacion);
      $("#fms_sentadilla_profunda").val(test.sentadilla_profunda);
      $("#fms_paso_valla").val(test.paso_valla);
      $("#fms_estocada_en_linea").val(test.estocada_en_linea);
      $("#fms_movilidad_hombro").val(test.movilidad_hombro);
      $("#fms_elevacion_pierna_recta").val(test.elevacion_pierna_recta);
      $("#fms_estabilidad_tronco").val(test.estabilidad_tronco);
      $("#fms_estabilidad_rotacional").val(test.estabilidad_rotacional);
      $("#fms_puntuacion_total").val(test.puntuacion_total);
      $("#fms_observaciones").val(test.observaciones);

      $("#modalHistorialEvaluaciones").modal("hide");
      $("#modalTestFms").modal("show");
    });
  });

  // ========================================
  // VER DETALLES DE EVALUACIONES
  // ========================================

  // Ver Test Postural
  $(document).on("click", ".btn-ver-test-postural", function () {
    const idTest = $(this).data("id");
    enviaAjax(
      "",
      `?p=evaluacionesatleta&accion=obtenerTestPostural&id=${idTest}`,
      "GET"
    ).then((respuesta) => {
      if (!respuesta.exito) {
        mostrarNotificacion(respuesta.mensaje || "Error al obtener el test", "error");
        return;
      }
      const test = respuesta.datos;
      // Fecha y estado general
      $("#ver_post_fecha_evaluacion").text(test.fecha_evaluacion);
      
      // Calcular problemas
      let problemas = 0;
      ["cifosis_dorsal","lordosis_lumbar","escoliosis","inclinacion_pelvis",
       "valgo_rodilla","varo_rodilla","rotacion_hombros","desnivel_escapulas"
      ].forEach(campo => {
        if (test[campo] === "moderada" || test[campo] === "severa") {
          problemas++;
        }
      });

      const estadoTexto = problemas === 0 ? "Normal" : problemas <= 2 ? "Leve" : "Requiere Atención";
      const estadoBadge = problemas === 0 ? "success" : problemas <= 2 ? "warning" : "danger";
      $("#ver_post_estado_general").html(`<span class="badge bg-${estadoBadge}">${estadoTexto}</span>`);

      // Función helper para mostrar resultado
      function mostrarResultado(campo, valor) {
        const clase = valor === "ninguna" || valor === "normal" ? "normal" : 
                     valor === "leve" ? "leve" : 
                     valor === "moderada" ? "moderada" : "severa";
        const texto = valor === "ninguna" ? "Normal" : 
                     valor === "normal" ? "Normal" :
                     valor.charAt(0).toUpperCase() + valor.slice(1);
        $(`#ver_post_${campo}`).html(`<span class="badge-resultado ${clase}">${texto}</span>`);
      }

      // Llenar datos
      mostrarResultado("cifosis", test.cifosis_dorsal);
      mostrarResultado("lordosis", test.lordosis_lumbar);
      mostrarResultado("escoliosis", test.escoliosis);
      mostrarResultado("pelvis", test.inclinacion_pelvis);
      mostrarResultado("valgo", test.valgo_rodilla);
      mostrarResultado("varo", test.varo_rodilla);
      mostrarResultado("hombros", test.rotacion_hombros);
      mostrarResultado("escapulas", test.desnivel_escapulas);

      // Observaciones
      if (test.observaciones && test.observaciones.trim() !== "") {
        $("#ver_post_observaciones").text(test.observaciones);
        $("#ver_post_observaciones_container").show();
        $("#ver_post_sin_observaciones").hide();
      } else {
        $("#ver_post_observaciones_container").hide();
        $("#ver_post_sin_observaciones").show();
      }

      $("#modalVerTestPostural").modal("show");
    });
  });

  // Ver Test FMS
  $(document).on("click", ".btn-ver-test-fms", function () {
    const idTest = $(this).data("id");
    enviaAjax(
      "",
      `?p=evaluacionesatleta&accion=obtenerTestFms&id=${idTest}`,
      "GET"
    ).then((respuesta) => {
      if (!respuesta.exito) {
        mostrarNotificacion(respuesta.mensaje || "Error al obtener el test", "error");
        return;
      }
      const test = respuesta.datos;
      // Fecha
      $("#ver_fms_fecha_evaluacion").text(test.fecha_evaluacion);
      
      // Puntuación total
      $("#ver_fms_puntuacion_total").text(test.puntuacion_total);
      
      // Nivel de riesgo
      const nivelRiesgo = test.puntuacion_total <= 14 ? "Alto" : test.puntuacion_total <= 17 ? "Medio" : "Bajo";
      const colorRiesgo = test.puntuacion_total <= 14 ? "danger" : test.puntuacion_total <= 17 ? "warning" : "success";
      $("#ver_fms_nivel_riesgo").html(`<span class="badge bg-${colorRiesgo}">Riesgo ${nivelRiesgo}</span>`);
      $("#ver_fms_card_total").removeClass("bg-success bg-warning bg-danger").addClass(`bg-${colorRiesgo} bg-opacity-10`);

      // Función helper para mostrar score
      function mostrarScore(campo, valor) {
        const textos = {
          0: "Dolor",
          1: "No puede",
          2: "Compensación",
          3: "Correcto"
        };
        $(`#ver_fms_${campo}`).html(`<span class="badge-fms-score score-${valor}">${valor}</span> <small class="text-muted ms-2">${textos[valor]}</small>`);
      }

      // Llenar scores
      mostrarScore("sentadilla", test.sentadilla_profunda);
      mostrarScore("paso_valla", test.paso_valla);
      mostrarScore("estocada", test.estocada_en_linea);
      mostrarScore("movilidad_hombro", test.movilidad_hombro);
      mostrarScore("elevacion_pierna", test.elevacion_pierna_recta);
      mostrarScore("estabilidad_tronco", test.estabilidad_tronco);
      mostrarScore("estabilidad_rotacional", test.estabilidad_rotacional);

      // Observaciones
      if (test.observaciones && test.observaciones.trim() !== "") {
        $("#ver_fms_observaciones").text(test.observaciones);
        $("#ver_fms_observaciones_container").show();
        $("#ver_fms_sin_observaciones").hide();
      } else {
        $("#ver_fms_observaciones_container").hide();
        $("#ver_fms_sin_observaciones").show();
      }

      $("#modalVerTestFms").modal("show");
    });
  });

  // Editar Lesión
  $(document).on("click", ".btn-editar-lesion", function () {
    const idLesion = $(this).data("id");
    enviaAjax(
      "",
      `?p=evaluacionesatleta&accion=obtenerLesion&id=${idLesion}`,
      "GET"
    ).then((respuesta) => {
      if (!respuesta.exito) {
        mostrarNotificacion(respuesta.mensaje || "Error al obtener la lesión", "error");
        return;
      }
      const lesion = respuesta.datos;
      $("#les_id_lesion").val(lesion.id_lesion);
      $("#les_id_atleta").val(lesion.id_atleta);
      $("#les_fecha_lesion").val(lesion.fecha_lesion);
      $("#les_tipo_lesion").val(lesion.tipo_lesion);
      $("#les_zona_afectada").val(lesion.zona_afectada);
      $("#les_gravedad").val(lesion.gravedad);
      $("#les_mecanismo_lesion").val(lesion.mecanismo_lesion);
      $("#les_tiempo_estimado_recuperacion").val(
        lesion.tiempo_estimado_recuperacion
      );
      $("#les_fecha_recuperacion").val(lesion.fecha_recuperacion);
      $("#les_tratamiento_realizado").val(lesion.tratamiento_realizado);
      $("#les_observaciones").val(lesion.observaciones);

      $("#modalHistorialEvaluaciones").modal("hide");
      $("#modalLesion").modal("show");
    });
  });

  // ========================================
  // 7. ELIMINAR REGISTROS
  // ========================================

  // Eliminar Test Postural
  $(document).on("click", ".btn-eliminar-test-postural", function () {
    const idTest = $(this).data("id");
    muestraMensaje(
      "¿Estás seguro?",
      "Esta acción eliminará el test postural seleccionado",
      "warning",
      {
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }
    ).then((res) => {
      if (res.isConfirmed) {
        const datos = new FormData();
        datos.append("id_test_postural", idTest);
        enviaAjax(datos, "?p=evaluacionesatleta&accion=eliminarTestPostural").then(
          (respuesta) => {
            muestraMensaje("Eliminado", respuesta.mensaje, "success");
            listarHistorialSegunTipo();
          }
        );
      }
    });
  });

  // Eliminar Test FMS
  $(document).on("click", ".btn-eliminar-test-fms", function () {
    const idTest = $(this).data("id");
    muestraMensaje(
      "¿Estás seguro?",
      "Esta acción eliminará el test FMS seleccionado",
      "warning",
      {
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }
    ).then((res) => {
      if (res.isConfirmed) {
        const datos = new FormData();
        datos.append("id_test_fms", idTest);
        enviaAjax(datos, "?p=evaluacionesatleta&accion=eliminarTestFms").then(
          (respuesta) => {
            muestraMensaje("Eliminado", respuesta.mensaje, "success");
            listarHistorialSegunTipo();
          }
        );
      }
    });
  });

  // Eliminar Lesión
  $(document).on("click", ".btn-eliminar-lesion", function () {
    const idLesion = $(this).data("id");
    muestraMensaje(
      "¿Estás seguro?",
      "Esta acción eliminará el registro de la lesión",
      "warning",
      {
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }
    ).then((res) => {
      if (res.isConfirmed) {
        const datos = new FormData();
        datos.append("id_lesion", idLesion);
        enviaAjax(datos, "?p=evaluacionesatleta&accion=eliminarLesion").then(
          (respuesta) => {
            muestraMensaje("Eliminado", respuesta.mensaje, "success");
            listarHistorialSegunTipo();
          }
        );
      }
    });
  });

  // ========================================
  // CÁLCULO AUTOMÁTICO DE PUNTUACIÓN FMS
  // ========================================
  
  // Calcular puntuación total del FMS automáticamente
  $(document).on('change', '.fms-puntuacion', function() {
    let total = 0;
    $('.fms-puntuacion').each(function() {
      const valor = parseInt($(this).val()) || 0;
      total += valor;
    });
    $('#fms_puntuacion_total').val(total);
  });

  // ========================================
  // FIN SUBMÓDULO EVALUACIONES DEL ATLETA
  // ========================================
});
