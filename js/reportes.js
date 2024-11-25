import { obtenerNotificaciones } from "./comunes.js";
$(document).ready(function () {
  obtenerNotificaciones(idUsuario);
  setInterval(() => obtenerNotificaciones(idUsuario), 35000);
  $(
    "#filtrosAtletas, #filtrosEntrenadores, #filtrosMensualidades, #filtrosWada, #filtrosEventos, #filtrosAsistencias"
  ).hide();

  $("#tipoReporte").change(function () {
    $(
      "#filtrosAtletas, #filtrosEntrenadores, #filtrosMensualidades, #filtrosWada, #filtrosEventos, #filtrosAsistencias"
    ).hide();
    switch ($(this).val()) {
      case "atletas":
        $("#filtrosAtletas").show();
        break;
      case "entrenadores":
        $("#filtrosEntrenadores").show();
        break;
      case "mensualidades":
        $("#filtrosMensualidades").show();
        break;
      case "wada":
        $("#filtrosWada").show();
        break;
      case "eventos":
        $("#filtrosEventos").show();
        break;
      case "asistencias":
        $("#filtrosAsistencias").show();
        break;
    }
  });

  $("#btnGenerarReporte").on("click", function () {
    var datos = new FormData($("#formReportes")[0]);
    datos.append("accion", "obtener_reportes");
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
          var lee;
          if (typeof respuesta === "string") {
            lee = JSON.parse(respuesta);
          } else {
            lee = respuesta;
          }

          if (lee.ok && Array.isArray(lee.reportes)) {
            var listado_reportes = "";
            lee.reportes.forEach((reporte) => {
              listado_reportes += "<tr>";
              listado_reportes += "<td>" + reporte.id + "</td>";
              listado_reportes += "<td>" + reporte.nombre + "</td>";
              listado_reportes += "<td>" + reporte.detalles + "</td>";
              listado_reportes += "<td>" + reporte.fecha + "</td>";
              listado_reportes += "</tr>";
            });

            if ($.fn.DataTable.isDataTable("#tablaReportes")) {
              $("#tablaReportes").DataTable().clear().destroy();
            }

            $("#listadoReportes").html(listado_reportes);
           
              
        
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
                  previous: "Anterior",
                },
              },
              autoWidth: true,
              order: [[0, "desc"]],
              dom: '<"top"f>rt<"bottom"lp><"clear">',
            });
          } else {
            Swal.fire(
              "Error",
              "No se encontraron reportes o la estructura de la respuesta es incorrecta.",
              "error"
            );
          }
        } catch (error) {
          console.error("Error al procesar la respuesta JSON: ", error);
          Swal.fire(
            "Error",
            "Algo salió mal al procesar la respuesta del servidor",
            "error"
          );
        }
      },
      error: function (request, status, err) {
        Swal.fire("Error", "Error al procesar la solicitud", "error");
      },
    });
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
        } else {
          console.error("Error:", respuesta.mensaje);
        }
      },
      error: function () {
        console.error("No se pudo obtener los datos.");
      }
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
        } else {
          console.error("Error:", respuesta.mensaje);
        }
      },
      error: function () {
        console.error("No se pudo obtener los datos.");
      }
    });
  }





  $("#btnDescargarPDF").on("click", function () {
    var tipoReporte = $("#tipoReporte").val();
    var edadMin = $("#edadMin").val();
    var edadMax = $("#edadMax").val();
    var genero = $("#genero").val();
    var tipoAtleta = $("#tipoAtleta").val();
    var pesoMin = $("#pesoMin").val();
    var pesoMax = $("#pesoMax").val();
    var edadMinEntrenador = $("#edadMinEntrenador").val();
    var edadMaxEntrenador = $("#edadMaxEntrenador").val();
    var gradoInstruccion = $("#gradoInstruccion").val();
    var fechaInicioEventos = $("#fechaInicioEventos").val();
    var fechaFinEventos = $("#fechaFinEventos").val();
    var fechaInicioMensualidades = $("#fechaInicioMensualidades").val();
    var fechaFinMensualidades = $("#fechaFinMensualidades").val();

    if (!tipoReporte) {
      Swal.fire("Error", "Debe seleccionar un tipo de reporte", "error");
      return;
    }

    var form = $("<form>", {
      action: "reportes_pdf.php",
      method: "POST",
      target: "_blank",
    });

    form.append(
      $("<input>", {
        type: "hidden",
        name: "tipoReporte",
        value: tipoReporte,
      })
    );

    if (edadMin) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "edadMin",
          value: edadMin,
        })
      );
    }

    if (edadMax) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "edadMax",
          value: edadMax,
        })
      );
    }

    if (genero) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "genero",
          value: genero,
        })
      );
    }

    if (tipoAtleta) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "tipoAtleta",
          value: tipoAtleta,
        })
      );
    }

    if (pesoMin) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "pesoMin",
          value: pesoMin,
        })
      );
    }

    if (pesoMax) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "pesoMax",
          value: pesoMax,
        })
      );
    }

    if (edadMinEntrenador) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "edadMinEntrenador",
          value: edadMinEntrenador,
        })
      );
    }

    if (edadMaxEntrenador) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "edadMaxEntrenador",
          value: edadMaxEntrenador,
        })
      );
    }

    if (gradoInstruccion) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "gradoInstruccion",
          value: gradoInstruccion,
        })
      );
    }

    if (fechaInicioEventos) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "fechaInicioEventos",
          value: fechaInicioEventos,
        })
      );
    }

    if (fechaFinEventos) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "fechaFinEventos",
          value: fechaFinEventos,
        })
      );
    }

    if (fechaInicioMensualidades) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "fechaInicioMensualidades",
          value: fechaInicioMensualidades,
        })
      );
    }

    if (fechaFinMensualidades) {
      form.append(
        $("<input>", {
          type: "hidden",
          name: "fechaFinMensualidades",
          value: fechaFinMensualidades,
        })
      );
    }

    $("body").append(form);
    form.submit();
    form.remove();
  });
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
        } else {
          console.error("Error:", respuesta.mensaje);
        }
      },
      error: function () {
        console.error("No se pudo obtener los datos.");
      }
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
        } else {
          console.error("Error:", respuesta.mensaje);
        }
      },
      error: function () {
        console.error("No se pudo obtener los datos.");
      }
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
        } else {
          console.error("Error:", respuesta.mensaje);
        }
      },
      error: function () {
        console.error("No se pudo obtener los datos.");
      }
    });
  }


  renderGraficoCumplimientoWADA();
  mostrarVencimientosWADA();
  renderGraficoAsistenciasMensuales();
  renderGraficoEdades();
  renderGraficoGenero();
});
