import {
  validarKeyPress,
  validarKeyUp,
  enviaAjax,
  muestraMensaje,
  obtenerNotificaciones,
  REGEX,
  validarFecha,
} from "./comunes.js";
$(document).ready(function () {
  function cargarListadoAtletas() {
    var datos = new FormData();
    datos.append("accion", "obtener_atletas");
    enviaAjax(datos, "").then((respuesta) => {
      actualizarListadoAtletas(respuesta.atletas);
      if (!$("fechaAsistencia").val()) {
        $("#fechaAsistencia").val(new Date().toISOString().split("T")[0]);
        obtenerAsistencias($("#fechaAsistencia").val());
      }
    });
  }
  obtenerNotificaciones(idUsuario);
  setInterval(() => obtenerNotificaciones(idUsuario), 35000);
  function enviarAsistencias() {
    var fecha = $("#fechaAsistencia").val();
    if (!fecha) {
      muestraMensaje("Error", "Debe seleccionar una fecha", "error");
      return;
    }
    var esValido = true;
    var asistencias = [];
    var tabla = $("#tablaAsistencias").DataTable();
    var datos = tabla.rows().data({ search: "applied" }).toArray();
    datos.forEach((dato, index) => {
      // Encontrar la fila correspondiente en la tabla
      const fila = tabla.row(index).node(); // Obtener el nodo de la fila
      const id = dato[0];
      const checkbox = $(fila).find('input[type="checkbox"]').is(":checked")
        ? 1
        : 0; // Obtener la asistencia
      const input = $(fila).find("input.comentario"); // Encontrar el input en la fila
      const valorInput = input.val();
      esValido &= validarKeyUp(
        REGEX.detalles.regex,
        $(input),
        $(this).find("span"),
        REGEX.detalles.mensaje
      );
      if (!esValido) {
        asistencias = [];
        return false;
      }
      asistencias.push({
        id_atleta: id,
        asistio: checkbox,
        comentario: valorInput,
      });
    });
    if (!esValido) {
      muestraMensaje(
        "Error",
        "Los detalles no cumplen el requisito de solo letras y números hasta 200 caracteres",
        "error"
      );
      return false;
    }
    var datos = new FormData();
    datos.append("accion", "guardar_asistencias");
    datos.append("fecha", fecha);
    datos.append("asistencias", JSON.stringify(asistencias));
    enviaAjax(datos, "").then((respuesta) => {
      muestraMensaje(
        "Éxito",
        "Asistencias guardadas correctamente.",
        "success"
      );
    });
  }
  function actualizarListadoAtletas(atletas) {
    var listado = "";
    atletas.forEach(function (atleta) {
      listado += `
                        <tr>
                            <td class="align-middle">${atleta.cedula}</td>
                            <td class="align-middle">${atleta.nombre}</td>
                            <td class="d-none d-md-table-cell align-middle">${atleta.apellido}</td>
                            <td class="align-middle form-switch"><input type="checkbox" class="form-check-input ms-0" data-id="${atleta.cedula}" aria-label='Asistio el atleta ${atleta.cedula}'/></td>
                            <td class="align-middle"><input type="text" class="form-control comentario" data-id="${atleta.nombre}" aria-label='Comentario de asistencia del atleta ${atleta.nombre}'/>
                            </td>
                        </tr>
                    `;
    });
    $("#listadoAsistencias").html(listado);
    $("#tablaAsistencias").DataTable({
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
      autoWidth: false,
      order: [[0, "desc"]],
      dom: '<"top"f>rt<"bottom"lp><"clear">',
    });
  }
  function obtenerAsistencias(fecha) {
    var datos = new FormData();
    datos.append("accion", "obtener_asistencias");
    datos.append("fecha", fecha);
    enviaAjax(datos, "").then((respuesta) => {
      actualizarListadoAsistencias(respuesta.asistencias, fecha);
      reiniciarFormulario();
    });
  }

  function actualizarListadoAsistencias(asistencias, fecha) {
    var fechaSeleccionada = new Date(fecha);
    fechaSeleccionada.setHours(0, 0, 0, 0);
    var fechaActual = new Date();
    fechaActual.setHours(0, 0, 0, 0);
    var unDia = 24 * 60 * 60 * 1000; // Un día en milisegundos
    var deshabilitar = fechaActual - fechaSeleccionada > unDia;
    var tabla = $("#tablaAsistencias").DataTable();
    var datos = tabla.rows().data({ search: "applied" }).toArray();
    datos.forEach((dato, index) => {
      // Encontrar la fila correspondiente en la tabla
      const fila = tabla.row(index).node(); // Obtener el nodo de la fila
      const id = dato[0]; // ID del atleta en la fila
      const input = $(fila).find('input[type="text"]');
      const checkbox = $(fila).find('input[type="checkbox"]');
      var asistencia = asistencias.find(function (asistencia) {
        return asistencia.id_atleta == id;
      });
      if (asistencia) {
        $(fila)
          .find('input[type="checkbox"]')
          .prop("checked", asistencia.asistio == 1);
        $(fila).find('input[type="text"]').val(asistencia.comentario);
      } else {
        $(fila).find('input[type="checkbox"]').prop("checked", false);
        $(fila).find('input[type="text"]').val("");
      }
      if (deshabilitar) {
        $(input).prop("disabled", true);
        $(checkbox).prop("disabled", true);
      } else {
        $(input).prop("disabled", false);
        $(checkbox).prop("disabled", false);
      }
    });
  }

  $("#btnGuardarAsistencias").on("click", function () {
    enviarAsistencias();
  });
  $("#btnEliminarAsistencias").on("click", function () {
    let fecha = $("#fechaAsistencia").val();
    if (!validarFecha(fecha)) {
      muestraMensaje("Error", "La fecha no es válida", "error");
      return false;
    }
    Swal.fire({
      title: "¿Estás seguro?",
      text: "No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, eliminar!",
      cancelButtonText: "Cancelar",
      scrollbarPadding: false,
    }).then((result) => {
      if (result.isConfirmed) {
        const datos = new FormData();
        datos.append("accion", "eliminar_asistencias");
        datos.append("fecha", fecha);
        enviaAjax(datos, "").then(() => {
          muestraMensaje(
            "Eliminado!",
            "Las asistencias fueron eliminadas",
            "success"
          );
          obtenerAsistencias(fecha);
        });
      }
    });
  });

  $("body").on("keypress", "input.comentario", function (e) {
    validarKeyPress(e, REGEX.keypress_alfanumerico.regex);
  });

  $("body").on("keyup", "input.comentario", function () {
    validarKeyUp(
      REGEX.detalles.regex,
      $(this),
      $(this).siblings("span"),
      REGEX.detalles.mensaje
    );
  });

  $("#fechaAsistencia").on("change", function () {
    var fecha = $(this).val();
    obtenerAsistencias(fecha);
  });

  cargarListadoAtletas();

  function reiniciarFormulario() {
    var tabla = $("#tablaAsistencias").DataTable();
    var datos = tabla.rows().data({ search: "applied" }).toArray();
    datos.forEach((dato, index) => {
      // Encontrar la fila correspondiente en la tabla
      const fila = tabla.row(index).node(); // Obtener el nodo de la fila
      const input = $(fila).find('input[type="text"]');
      $(input).removeClass("is-valid");
      $(input).removeClass("is-invalid");
    });
  }
});
