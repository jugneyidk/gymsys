import { enviaAjax, muestraMensaje, modalListener, REGEX, obtenerNotificaciones } from "./comunes.js";
$(document).ready(function () {
  carga_listado_wada();
  cargaProximosVencer();
  obtenerNotificaciones(idUsuario);
  setInterval(() => obtenerNotificaciones(idUsuario), 35000);
  modalListener("WADA");

  function carga_listado_wada() {
    var datos = new FormData();
    datos.append("accion", "listado_wada");
    enviaAjax(datos, "").then((respuesta) => {
      console.log(respuesta);
      actualizarTablaWada(respuesta.respuesta);
    });
  }

  function actualizarTablaWada(data) {
    var html = "";
    data.forEach(function (registro) {
      html += `<tr>
                <td class="d-none">${registro.cedula}</td>
                <td class="align-middle">${registro.nombre} ${
        registro.apellido
      }</td>
                <td class="text-center align-middle">${
                  registro.estado === 1
                    ? `<span class='badge rounded-pill bg-success' aria-label='Estado WADA del atleta ${registro.nombre} ${registro.apellido} se cumple' role='img' data-tooltip='tooltip' title="Cumple"><i class='fa-solid fa-check'></i></span>`
                    : `<span class='badge rounded-pill bg-danger' aria-label='Estado WADA del atleta ${registro.nombre} ${registro.apellido} no se cumple' role='img' data-tooltip='tooltip' title="No Cumple"><i class='fa-solid fa-x'></i></span>`
                }</td>
                <td class="d-none d-lg-table-cell align-middle">${
                  registro.inscrito
                }</td>
                <td class="d-none d-md-table-cell align-middle">${
                  registro.ultima_actualizacion
                }</td>
                <td class="align-middle">${registro.vencimiento}</td>
                <td class="align-middle">
                ${
                  actualizar === 1
                    ? `<button class='btn btn-block btn-warning me-2' data-bs-toggle='modal' data-bs-target='#modal' aria-label='Modificar WADA del atleta ${registro.nombre} ${registro.apellido}' data-tooltip='tooltip' title='Modificar WADA'><i class='fa-regular fa-pen-to-square'></i></button>`
                    : ""
                }  
                ${
                  eliminar === 1
                    ? `<button class='btn btn-block btn-danger me-2' aria-label='Eliminar WADA del atleta ${registro.nombre} ${registro.apellido}' data-tooltip='tooltip' title='Eliminar WADA'><i class='fa-regular fa-trash-can'></i></button>`
                    : ""
                }  
                </td>
            </tr>`;
    });
    if ($.fn.DataTable.isDataTable("#tablaWada")) {
      $("#tablaWada").DataTable().clear().destroy();
    }
    $("#tablaWada tbody").html(html);
    $("#tablaWada").DataTable({
      autoWidth: false,
      columnDefs: [
        {
          targets: [6],
          className: "text-nowrap",
          orderable: false,
        },
      ],
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
  }
  function actualizarTablaProximosVencer(data) {
    var html = "";
    console.log(data);
    data.forEach(function (registro) {
      html += `<tr>
                  <td class="d-none">${registro.cedula}</td>
                  <td class="align-middle">${registro.nombre} ${
        registro.apellido
      }</td>
                  <td class="align-middle">${registro.vencimiento}</td>
                  <td class="align-middle">
                  ${
                    actualizar === 1
                      ? `<button class='btn btn-block btn-warning me-2' data-bs-toggle='modal' data-bs-target='#modal' aria-label='Modificar WADA del atleta ${registro.nombre} ${registro.apellido}' data-tooltip='tooltip' title='Modificar WADA'><i class='fa-regular fa-pen-to-square'></i></button>`
                      : ""
                  }
                  </td>
              </tr>`;
    });
    if ($.fn.DataTable.isDataTable("#tablaProximosVencer")) {
      $("#tablaProximosVencer").DataTable().clear().destroy();
    }
    $("#tablaProximosVencer tbody").html(html);
    $("#tablaProximosVencer").DataTable({
      autoWidth: false,
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

  function cargaProximosVencer() {
    var datos = new FormData();
    datos.append("accion", "obtener_proximos_vencer");
    enviaAjax(datos, "").then((respuesta) => {
      actualizarTablaProximosVencer(respuesta.respuesta);
    });
  }

  $("#f1").submit(function (e) {
    e.preventDefault();
    var datos = new FormData(this);
    if (datos.get("accion") === "") {
      datos.set("accion", "incluir");
    }
    enviaAjax(datos, "").then(() => {
      muestraMensaje("Exito", "La WADA se registró correctamente", "success");
      carga_listado_wada();
      cargaProximosVencer();
      $("#modal").modal("hide");
    });
  });

  window.editarWada = function (cedula) {
    var datos = new FormData();
    datos.append("accion", "obtener_wada");
    datos.append("atleta", cedula);
    enviaAjax(datos, "").then((respuesta) => {
      var wada = respuesta.wada;
      $("#accion").val(wada.cedula);
      $("#atleta").val(wada.cedula);
      $("#status").val(wada.estado);
      $("#inscrito").val(wada.inscrito);
      $("#ultima_actualizacion").val(wada.ultima_actualizacion);
      $("#vencimiento").val(wada.vencimiento);
      $("#modal").modal("show");
    });
  };

  function carga_atletas() {
    var datos = new FormData();
    datos.append("accion", "listado_atletas");
    enviaAjax(datos, "").then((respuesta) => {
      var opciones = "<option value=''>Seleccione un atleta</option>";
      respuesta.respuesta.forEach(function (atleta) {
        opciones += `<option value='${atleta.cedula}'>${atleta.cedula} - ${atleta.nombre} ${atleta.apellido}</option>`;
      });
      $("#atleta").html(opciones);
    });
  }

  $("#tablaWada,#tablaProximosVencer").on("click", ".btn-warning", function () {
    const cedula = $(this).closest("tr").find("td:first").text();
    console.log(cedula);
    var datos = new FormData();
    datos.append("accion", "obtener_wada");
    datos.append("cedula", cedula);
    enviaAjax(datos, "").then((respuesta) => {
      llenarFormularioModificar(respuesta.wada);
    });
  });
  $("#tablaWada").on("click", ".btn-danger", function () {
    Swal.fire({
      title: "¿Estás seguro?",
      text: "No podrás revertir esto",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, eliminar",
    }).then((result) => {
      if (result.isConfirmed) {
        const cedula = $(this).closest("tr").find("td:first").text();
        console.log(cedula);
        var datos = new FormData();
        datos.append("accion", "eliminar");
        datos.append("cedula", cedula);
        enviaAjax(datos, "").then((respuesta) => {
          carga_listado_wada();
          cargaProximosVencer();
          muestraMensaje(
            "Exito",
            "La WADA se eliminó correctamente",
            "success"
          );
        });
      }
    });
  });

  function llenarFormularioModificar(atleta) {
    $("#f1 #atleta").val(atleta.id_atleta);
    $("#f1 #inscrito").val(atleta.inscrito);
    $("#f1 #ultima_actualizacion").val(atleta.ultima_actualizacion);
    $("#f1 #vencimiento").val(atleta.vencimiento);
    $("#f1 #status").val(atleta.estado);
    $("#f1 #accion").val("modificar");
  }
  carga_atletas();
});
