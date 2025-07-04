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
              ? `<button class="btn btn-danger w-auto" data-id="${a.cedula_encriptado}" title="Eliminar Atleta" data-tooltip="tooltip" data-bs-placement="top"><i class="fa-solid fa-trash-can"></i></button>`
              : ""
          }
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
});
