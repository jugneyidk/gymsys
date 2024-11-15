import { muestraMensaje, enviaAjax } from "./comunes.js";
$(document).ready(function () {
  // Cargar listado de deudores y atletas sin registro
  cargaListadoDeudores();
  cargaListadoMensualidades();
  cargaAtletas();

  function cargaListadoDeudores() {
    var datos = new FormData();
    datos.append("accion", "listado_deudores");
    enviaAjax(datos, "").then((respuesta) => {
      var html = "";
      respuesta.respuesta.forEach(function (deudor) {
        html += `
                    <tr>
                        <td>${deudor.nombre} ${deudor.apellido}</td>
                        <td>${deudor.cedula}</td>
                        <td>${deudor.tipo_atleta}</td>
                        <td>
                            <button class="btn btn-primary btn-seleccionar" data-cedula="${deudor.cedula}" data-nombre="${deudor.nombre} ${deudor.apellido}" data-tipo="${deudor.tipo_atleta}">Seleccionar</button>
                        </td>
                    </tr>`;
      });
      if ($.fn.DataTable.isDataTable("#tablaDeudores")) {
        $("#tablaDeudores").DataTable().destroy();
      }
      $("#listadoDeudores").html(html);
      inicializarDataTable("#tablaDeudores", 5);
    });
  }

  function cargaListadoMensualidades() {
    var datos = new FormData();
    datos.append("accion", "listado_mensualidades");
    enviaAjax(datos, "").then((respuesta) => {
      var html = "";
      respuesta.respuesta.forEach(function (mensualidad) {
        html += `
                    <tr>
                        <td>${mensualidad.nombre} ${mensualidad.apellido}</td>
                        <td>${mensualidad.cedula}</td>
                        <td>${mensualidad.tipo}</td>
                        <td>${mensualidad.monto}</td>
                        <td>${mensualidad.fecha}</td>
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
    var datos = new FormData();
    datos.append("accion", "listado_atletas");
    enviaAjax(datos, "").then((respuesta) => {
      var html = "<option>Seleccione un atleta</option>";
      respuesta.respuesta.forEach(function (atleta) {
        html += `<option value="${atleta.cedula}" data-tipo="${atleta.tipo_atleta}">${atleta.nombre} ${atleta.apellido}</option>`;
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
        paginate: {
          next: "Siguiente",
          previous: "Anterior",
        },
      },
    });
  }

  $("#tablaDeudores").on("click", ".btn-seleccionar", function () {
    var cedula = $(this).data("cedula");
    var nombre = $(this).data("nombre");
    var tipo = $(this).data("tipo");
    $("#atleta")
      .html(`<option value="${cedula}">${nombre}</option>`)
      .val(cedula);
    var monto = calcularMonto(tipo);
    $("#monto").val(monto);
  });

  $("#registrarPago").on("click", function () {
    if (validarEnvio()) {
      var datos = new FormData($("#formPago")[0]);
      datos.append("accion", "incluir");
      enviaAjax(datos, "").then(() => {
        muestraMensaje("Éxito", "Pago registrado con éxito", "success");
        cargaListadoDeudores();
        cargaListadoMensualidades();
        limpiarFormulario("#formPago");
      });
    }
  });

  function limpiarFormulario(formId) {
    $(formId)
      .find("input[type=text], input[type=number], input[type=date], select")
      .val("");
    $(formId).find("input").removeClass("is-invalid is-valid");
  }

  function calcularMonto(tipo) {
    switch (tipo) {
      case 1: // Obreros
        return 0;
      case 2: // Externos
        return 10;
      case 3: // Universidad no Halterofilia
        return 5;
      case 4: // Universidad Obreros
        return 0;
      default:
        return 0;
    }
  }

  function validarEnvio() {
    var esValido = true;
    esValido &= verificarCampoVacio(
      $("#atleta"),
      $("#satleta"),
      "El atleta es obligatorio"
    );
    esValido &= verificarCampoVacio(
      $("#monto"),
      $("#smonto"),
      "El monto es obligatorio"
    );
    esValido &= verificarCampoVacio(
      $("#fecha"),
      $("#sfecha"),
      "La fecha es obligatoria"
    );
    return esValido;
  }

  function verificarCampoVacio(input, mensaje, textoError) {
    if (input.val().trim() === "") {
      input.removeClass("is-valid").addClass("is-invalid");
      mensaje.text(textoError);
      return false;
    } else {
      input.removeClass("is-invalid").addClass("is-valid");
      mensaje.text("");
      return true;
    }
  }
});
