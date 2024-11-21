import {
  validarKeyPress,
  validarKeyUp,
  enviaAjax,
  muestraMensaje,
  REGEX,
  modalListener,
} from "./comunes.js";
$(document).ready(function () {
  function cargaListadoAtleta() {
    const datos = new FormData();
    datos.append("accion", "listado_atleta");
    enviaAjax(datos, "").then((respuesta) => {
      actualizarListadoAtletas(respuesta.respuesta);
    });
  }
  modalListener("Atleta");
  modal.addEventListener("hidden.bs.modal", function (event) {
    $("#modificar_contraseña_container").addClass("d-none");
    $("#password").prop("disabled", false);
  });
  function cargarEntrenadores() {
    const datos = new FormData();
    datos.append("accion", "obtener_entrenadores");
    enviaAjax(datos, "").then((respuesta) => {
      if (respuesta.ok) {
        const selectEntrenador = $("#entrenador_asignado");
        selectEntrenador.empty(); // Limpiar opciones anteriores
        selectEntrenador.append(
          '<option value="">Seleccione un entrenador</option>'
        );

        respuesta.entrenadores.forEach((entrenador) => {
          selectEntrenador.append(
            `<option value="${entrenador.cedula}">${entrenador.nombre_completo}</option>`
          );
        });
      } else {
        console.error("Error al cargar los entrenadores:", respuesta.mensaje);
      }
    });
  }
  function cargarEntrenadoresParaModificacion(entrenadorAsignado) {
    const datos = new FormData();
    datos.append("accion", "obtener_entrenadores");
    enviaAjax(datos, "").then((respuesta) => {
      if (respuesta.ok) {
        const selectEntrenadorModificar = $("#entrenador_asignado_modificar");
        selectEntrenadorModificar.empty(); // Limpiar opciones anteriores
        selectEntrenadorModificar.append(
          '<option value="">Seleccione un entrenador</option>'
        );

        respuesta.entrenadores.forEach((entrenador) => {
          selectEntrenadorModificar.append(
            `<option value="${entrenador.cedula}" ${
              entrenador.cedula === entrenadorAsignado ? "selected" : ""
            }>${entrenador.nombre_completo}</option>`
          );
        });
      } else {
        console.error("Error al cargar los entrenadores:", respuesta.mensaje);
      }
    });
  }
  function cargarTiposAtleta() {
    const datos = new FormData();
    datos.append("accion", "obtener_tipos_atleta");
    enviaAjax(datos, "").then((respuesta) => {
      if (respuesta.ok) {
        const selectTipoAtleta = $("#tipo_atleta");
        selectTipoAtleta.empty(); // Limpiar opciones anteriores
        selectTipoAtleta.append(
          '<option value="">Seleccione un tipo de atleta</option>'
        );

        respuesta.tipos.forEach((tipo) => {
          selectTipoAtleta.append(
            `<option value="${tipo.id_tipo_atleta}">${tipo.nombre_tipo_atleta}</option>`
          );
        });
      } else {
        console.error(
          "Error al cargar los tipos de atleta:",
          respuesta.mensaje
        );
      }
    });
  }
  function cargarTiposAtletaParaModificacion(tipoAtletaAsignado) {
    const datos = new FormData();
    datos.append("accion", "obtener_tipos_atleta");
    enviaAjax(datos, "").then((respuesta) => {
      if (respuesta.ok) {
        const selectTipoAtletaModificar = $("#tipo_atleta");
        selectTipoAtletaModificar.empty(); // Limpiar opciones anteriores
        selectTipoAtletaModificar.append(
          '<option value="">Seleccione un tipo de atleta</option>'
        );
        respuesta.tipos.forEach((tipo) => {
          selectTipoAtletaModificar.append(
            `<option value="${tipo.id_tipo_atleta}" ${
              tipo.id_tipo_atleta == tipoAtletaAsignado ? "selected" : ""
            }>${tipo.nombre_tipo_atleta}</option>`
          );
        });
      }
    });
  }

  cargarTiposAtleta();
  cargaListadoAtleta();
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

  $("#incluir").on("click", function () {
    if (validarEnvio("#f1")) {
      const datos = new FormData($("#f1")[0]);
      if (datos.get("accion") === "") {
        datos.set("accion", "incluir");
      }
      enviaAjax(datos, "").then((respuesta) => {
        muestraMensaje(
          "Atleta incluido",
          "El atleta se ha incluido satisfactoriamente.",
          "success"
        );
        cargaListadoAtleta();
        limpiarFormulario("#f1");
        $("#modal").modal("hide");
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
                    <td class='align-middle'>${atleta.nombre} ${
        atleta.apellido
      }</td>
                    <td class='align-middle'>
                    ${
                      actualizar === 1
                        ? `<button class='btn btn-block btn-warning me-2 w-auto' data-bs-toggle='modal' aria-label='Modificar atleta ${atleta.nombre} ${atleta.apellido}' data-tooltip="tooltip" data-bs-placement="top" title="Modificar Atleta"><i class='fa-regular fa-pen-to-square'></i></button>`
                        : ""
                    }
                      ${
                        eliminar === 1
                          ? `<button class='btn btn-block btn-danger w-auto' aria-label='Eliminar atleta ${atleta.nombre} ${atleta.apellido}' data-tooltip="tooltip" data-bs-placement="top" title="Eliminar Atleta"><i class='fa-solid fa-trash-can'></i></button>`
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

  function cargarDatosAtleta(cedula) {
    const datos = new FormData();
    datos.append("accion", "obtener_atleta");
    datos.append("cedula", cedula);
    enviaAjax(datos, "").then((respuesta) => {
      if (respuesta.ok) {
        const atleta = respuesta.atleta;
        llenarFormularioModificar(atleta);
        // Llamada para cargar entrenadores y preseleccionar el correspondiente
        cargarEntrenadoresParaModificacion(atleta.entrenador);
        $("#modal").modal("show");
      }
    });
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
    // Lógica para cargar los tipos de atleta y seleccionar el correspondiente
    cargarTiposAtletaParaModificacion(atleta.id_tipo_atleta);

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
    $("#accion").val("modificar");
    cargarDatosAtleta(cedula);
  });

  $("#tablaatleta").on("click", ".btn-danger", function () {
    const cedula = $(this).closest("tr").find("td:first").text();
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
