import { obtenerNotificaciones } from "./comunes.js";
$(document).ready(function () {
  obtenerNotificaciones(idUsuario);
  setInterval(() => obtenerNotificaciones(idUsuario), 35000);
  const filtrosMap = {
    atletas: "#filtrosAtletas",
    entrenadores: "#filtrosEntrenadores",
    mensualidades: "#filtrosGenerales",
    wada: "#filtrosGenerales",
    eventos: "#filtrosGenerales",
    asistencias: "#filtrosGenerales",
    reporteIndividualAtleta: "#filtrosIndividualAtleta"
};

$("#tipoReporte").change(function () {
    $(".filtros-reporte").hide();
    const filtroSeleccionado = filtrosMap[$(this).val()];
    if (filtroSeleccionado) $(filtroSeleccionado).show();
});
$("#btnGenerarReporte").on("click", function () {
  const tipoReporte = $("#tipoReporte").val();
  const datos = new FormData($("#formReportes")[0]);

  if (tipoReporte === 'reporteIndividualAtleta') {
      datos.append("accion", "obtener_reporte_individual");
  } else {
      datos.append("accion", "obtener_reportes");
  }

  for (let [key, value] of datos.entries()) {
      console.log(key, value);
  }

  enviaAjax(datos);
});



function enviaAjax(datos) {
  $.ajax({
      async: true,
      url: "",
      type: "POST",
      contentType: false,
      data: datos,
      processData: false,
      cache: false,
      success: function (respuesta) {
        try {
            const lee = typeof respuesta === "string" ? JSON.parse(respuesta) : respuesta;
    
            if (lee.ok) {
                if (Array.isArray(lee.reportes) && lee.reportes.length > 0) {
                    $("#listadoReportes").html(
                        lee.reportes
                            .map(reporte => `
                                <tr>
                                    <td>${reporte.id}</td>
                                    <td>${reporte.nombre}</td>
                                    <td>${reporte.detalles}</td>
                                    <td>${reporte.fecha}</td>
                                </tr>
                            `)
                            .join("")
                    );
                } else {
                    $("#listadoReportes").html("<tr><td colspan='4'>No se encontraron reportes.</td></tr>");
                }
    
                if (lee.resultados) {
                    actualizaEstadisticas(lee.resultados);
                }
            } else {
                Swal.fire("Error", lee.mensaje || "Error en la respuesta del servidor.", "error");
            }
        } catch (error) {
            console.error("Error al procesar la respuesta JSON: ", error);
            Swal.fire("Error", "Error procesando respuesta del servidor.", "error");
        }
    }
    
  });
}

function mostrarTablaReportes(reportes) {
  if ($.fn.DataTable.isDataTable("#tablaReportes")) {
      $("#tablaReportes").DataTable().clear().destroy();
  }

  $("#listadoReportes").html(
      reportes.map(reporte => `
          <tr>
              <td>${reporte.id}</td>
              <td>${reporte.nombre}</td>
              <td>${reporte.detalles}</td>
              <td>${reporte.fecha}</td>
          </tr>
      `).join("")
  );

  $("#tablaReportes").DataTable({
      language: {
          lengthMenu: "Mostrar _MENU_ por página",
          zeroRecords: "No se encontraron reportes",
          info: "Mostrando página _PAGE_ de _PAGES_",
          infoEmpty: "No hay reportes disponibles",
          infoFiltered: "(filtrado de _MAX_ registros totales)",
          search: "Buscar:",
          paginate: {
              first: "Primera",
              last: "Última",
              next: "Siguiente",
              previous: "Anterior"
          }
      },
      autoWidth: true,
      order: [[0, "desc"]],
      dom: '<"top"f>rt<"bottom"lp><"clear">'
  });
}

function mostrarResultadosIndividuales(resultados) {
  // Mostrar datos del reporte individual en el formato que desees.
  console.log("Resultados individuales:", resultados);
}




  function actualizaEstadisticas(estadisticas) {
    $("#listaEstadisticas").html(
      Object.entries(estadisticas)
        .map(([clave, valor]) => `<li>${clave.replace("_", " ")}: ${valor}</li>`)
        .join("")
    );
  }

  function renderGraficoEdades() {
    $.ajax({
      url: "",
      method: "POST",
      data: { accion: "obtenerDatosEstadisticos", tipo: "edadAtletas" },
      success: function (respuesta) {
        if (respuesta.ok) {
          const etiquetas = respuesta.data.map(d => d.rango_edad);
          const valores = respuesta.data.map(d => d.cantidad);
          const ctx = document.getElementById("edadAtletasChart").getContext("2d");
          new Chart(ctx, {
            type: "bar",
            data: {
              labels: etiquetas,
              datasets: [{
                label: "Cantidad de Atletas",
                data: valores,
                backgroundColor: "rgba(75, 192, 192, 0.2)",
                borderColor: "rgba(75, 192, 192, 1)",
                borderWidth: 1
              }]
            },
            options: {
              responsive: true,
              scales: { y: { beginAtZero: true } }
            }
          });
        }
      },
      error: function () {}
    });
  }

  function renderGraficoGenero() {
    $.ajax({
      url: "",
      method: "POST",
      data: { accion: "obtenerDatosEstadisticos", tipo: "generoAtletas" },
      success: function (respuesta) {
        if (respuesta.ok) {
          const etiquetas = respuesta.data.map(d => d.genero);
          const valores = respuesta.data.map(d => d.cantidad);
          const ctx = document.getElementById("generoChart").getContext("2d");
          new Chart(ctx, {
            type: "pie",
            data: {
              labels: etiquetas,
              datasets: [{
                data: valores,
                backgroundColor: ["#ff6384", "#36a2eb", "#cc65fe"],
                hoverOffset: 4
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: { position: "bottom" },
                tooltip: { enabled: true }
              }
            }
          });
        }
      },
      error: function () {}
    });
  }

  function renderGraficoAsistenciasMensuales() {
    $.ajax({
      url: "",
      method: "POST",
      data: { accion: "obtenerProgresoAsistencias" },
      success: function (respuesta) {
        if (respuesta.ok) {
          const etiquetas = respuesta.data.map(d => d.mes);
          const valores = respuesta.data.map(d => d.total_asistencias);
          const ctx = document.getElementById("asistenciasChart").getContext("2d");
          new Chart(ctx, {
            type: "line",
            data: {
              labels: etiquetas,
              datasets: [{
                label: "Asistencias Mensuales",
                data: valores,
                borderColor: "#42a5f5",
                backgroundColor: "rgba(66, 165, 245, 0.5)",
                fill: true
              }]
            },
            options: {
              responsive: true,
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
        }
      },
      error: function () {}
    });
  }

  function renderGraficoCumplimientoWADA() {
    $.ajax({
      url: "",
      method: "POST",
      data: { accion: "obtenerCumplimientoWADA" },
      success: function (respuesta) {
        if (respuesta.ok) {
          const etiquetas = respuesta.data.map(d => d.cumplimiento);
          const valores = respuesta.data.map(d => d.cantidad);
          const ctx = document.getElementById("wadaChart").getContext("2d");
          new Chart(ctx, {
            type: "pie",
            data: {
              labels: etiquetas,
              datasets: [{
                data: valores,
                backgroundColor: ["#4caf50", "#f44336"]
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: { position: "bottom" },
                tooltip: { enabled: true }
              }
            }
          });
        }
      },
      error: function () {}
    });
  }

  function mostrarVencimientosWADA() {
    $.ajax({
      url: "",
      method: "POST",
      data: { accion: "obtenerVencimientosWADA" },
      success: function (respuesta) {
        if (respuesta.ok) {
          let contenido = "";
          respuesta.data.forEach(atleta => {
            contenido += `<tr>
              <td>${atleta.id_atleta}</td>
              <td>${atleta.fecha_vencimiento}</td>
            </tr>`;
          });
          $("#tablaVencimientos tbody").html(contenido);
        }
      },
      error: function () {}
    });
  }
  $("#btnDescargarPDF").on("click", function () {
    const form = $("<form>", {
      action: "reportes_pdf.php",
      method: "POST",
      target: "_blank"
    });
  
    const formData = $("#formReportes").serializeArray();
    formData.forEach(item => {
      form.append($("<input>", {
        type: "hidden",
        name: item.name,
        value: item.value
      }));
    });
  
    $("body").append(form);
    form.submit();
    form.remove();
  });
    
  
  renderGraficoCumplimientoWADA();
  mostrarVencimientosWADA();
  renderGraficoAsistenciasMensuales();
  renderGraficoEdades();
  renderGraficoGenero();
});
