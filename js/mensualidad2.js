import { muestraMensaje, enviaAjax } from "./comunes.js";

$(document).ready(function () {
  cargaListadoDeudores();
  cargaListadoMensualidades();
  cargaAtletas();

  function cargaListadoDeudores() {
    var datos = new FormData();
    datos.append("accion", "listado_deudores");
    enviaAjax(datos, "").then((respuesta) => {
      var html = "";
      respuesta.respuesta.forEach(function (deudor) {
        // Validación para excluir deudores sin nombre_tipo_atleta
        if (deudor.nombre_tipo_atleta) {
          html += `
            <tr>
              <td>${deudor.nombre} ${deudor.apellido}</td>
              <td>${deudor.cedula}</td>
              <td>${deudor.nombre_tipo_atleta}</td>
              <td>
                <button class="btn btn-primary btn-seleccionar" data-cedula="${deudor.cedula}" data-nombre="${deudor.nombre} ${deudor.apellido}" data-tipo="${deudor.tipo_cobro}">Seleccionar</button>
              </td>
            </tr>`;
        }
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
            <td>${mensualidad.nombre_tipo_atleta}</td>
            <td>${mensualidad.monto}</td>
            <td>${mensualidad.fecha}</td>
            <td>${mensualidad.detalles}</td>
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
      var html = '<option>Seleccione un atleta</option>';
      respuesta.respuesta.forEach(function (atleta) {
        // Validación para excluir atletas sin nombre_tipo_atleta
        if (atleta.nombre_tipo_atleta) {
          html += `<option value="${atleta.cedula}" data-tipo="${atleta.tipo_cobro}">${atleta.nombre} ${atleta.apellido} - ${atleta.nombre_tipo_atleta}</option>`;
        }
      });
      $("#atleta").html(html);
    });
  }

  function inicializarDataTable(selector, pageLength = 10) {
    setTimeout(function () {
      $(selector).DataTable({
        pageLength: pageLength,
        destroy: true, // Asegúrate de destruir instancias anteriores correctamente
        language: {
          lengthMenu: "Mostrar _MENU_ por página",
          zeroRecords: "No se encontraron registros",
          info: "Mostrando página _PAGE_ de _PAGES_",
          infoEmpty: "No hay registros disponibles",
          infoFiltered: "(filtrado de _MAX_ registros totales)",
          search: "Buscar:",
          paginate: {
            first: "Primera",
            last: "Última",
            next: "Siguiente",
            previous: "Anterior",
          },
        },
      });
    }, 100); // Pequeño retraso para asegurarse de que el DOM esté actualizado
  }

  $("#tablaDeudores").on("click", ".btn-seleccionar", function () {
    var cedula = $(this).data("cedula");
    var nombre = $(this).data("nombre");
    var tipo = $(this).data("tipo");
    $("#atleta")
      .html(`<option value="${cedula}">${nombre}</option>`) // Cambia el select por el atleta seleccionado
      .val(cedula);
    $("#monto").val(tipo); // Usar el valor del campo tipo_cobro para el monto
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

  function validarEnvio() {
    var esValido = true;
    esValido &= verificarCampoVacio($("#atleta"), $("#satleta"), "El atleta es obligatorio");
    esValido &= verificarCampoVacio($("#monto"), $("#smonto"), "El monto es obligatorio");
    esValido &= verificarCampoVacio($("#fecha"), $("#sfecha"), "La fecha es obligatoria");
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
