import REGEX from "./regex.js";

export { REGEX };

export function validarKeyPress(e, regex) {
  if (!regex.test(e.key)) {
    e.preventDefault();
  }
}

export function validarKeyUp(regex, input, mensaje, textoError) {
  const isValid = regex.test(input.val());
  input.toggleClass("is-invalid", !isValid).toggleClass("is-valid", isValid);
  mensaje.text(isValid ? "" : textoError);
  return isValid;
}

function modalCarga(cargando) {
  if (cargando) {
    $("#carga").modal("show");
    $("body").addClass("carga");
  } else {
    setTimeout(function () {
      $("#carga").modal("hide");
      $("body").removeClass("carga");
    }, 100);
  }
}

export function modalListener(titulo) {
  var modal = document.getElementById("modal");
  modal.addEventListener("show.bs.modal", function (event) {
    $("#modalTitulo").text(titulo);
  });
  modal.addEventListener("hidden.bs.modal", function (event) {
    $("#modalTitulo").text("");
    $("#accion").val("");
    limpiarForm();
  });
}

function limpiarForm() {
  document.getElementById("f1").reset();
  $("#f1 input").removeClass("is-valid");
  $("#f1 input").removeClass("is-invalid");
  $("#f1 div .invalid-feedback").text("");
}

export function enviaAjax(datos, url) {
  return new Promise((resolve, reject) => {
    $.ajax({
      async: true,
      url: url, // URL pasada como argumento
      type: "POST",
      contentType: false,
      data: datos,
      processData: false,
      cache: false,
      timeout: 10000,
      beforeSend: function () {
        if (url == "?p=notificaciones") {
          return;
        }
        modalCarga(true);
      },
      success: function (respuesta) {
        try {
          const datosParseados = JSON.parse(respuesta);
          if (!datosParseados.ok) {
            muestraMensaje("Error", datosParseados.mensaje, "error");
            return;
          }
          resolve(datosParseados);
        } catch (error) {
          reject("Error al parsear la respuesta JSON");
        }
      },
      error: function (request, status) {
        const errorMsg =
          status === "timeout"
            ? "Servidor ocupado, intente de nuevo"
            : "Error al procesar la solicitud";
        muestraMensaje("Error", errorMsg, "error");
        reject(errorMsg);
      },
      complete: function () {
        if (url == "?p=notificaciones") {
          return;
        }
        modalCarga(false);
      },
    });
  });
}
export function muestraMensaje(titulo, mensaje, icono) {
  Swal.fire({
    title: titulo,
    text: mensaje,
    icon: icono,
    showConfirmButton: false,
    showCancelButton: true,
    cancelButtonText: "Cerrar",
  });
}

export function obtenerNotificaciones(idUsuario) {
  var datos = new FormData();
  datos.append("id_usuario", idUsuario);
  enviaAjax(datos, "?p=notificaciones").then((respuesta) => {
    if (respuesta.notificaciones.length < 1) {
      var contenido = `
        <li class="list-group-item list-group-item-secondary">
            <div class="text-nowrap p-2">
                <div class="w-100 text-center">
                    <span class="mb-1 h6">No hay notificaciones</span>
                </div>
            </div>
        </li>
        `;
      $("#contenedor-notificaciones").html(contenido);
      $("#contador-notificaciones").removeClass("d-inline-block");
      $("#contador-notificaciones").addClass("d-none");
      return;
    }
    var contenido = ``;
    var leidas = true;
    var dominio = window.location.hostname + window.location.pathname;
    respuesta.notificaciones.forEach((notificacion) => {
      contenido += `
        <li class="list-group-item${
          !notificacion.leida ? " list-group-item-warning" : ""
        } list-group-item-action p-3" data-link="http://${dominio}?p=${
        notificacion.objetivo
      }" role="button" data-id="${notificacion.id}"> 
          <div class="d-flex w-100 justify-content-between">
              <span class="mb-1 h6 text-nowrap">${notificacion.titulo}</span>
              <small class="text-muted ms-3 text-nowrap">${calcularTiempoNotificacion(
                notificacion.fecha_creacion
              )}</small>
          </div>
          <small class="my-1">${notificacion.mensaje}</small>
        </li>
        `;
      leidas &= notificacion.leida;
    });
    if (!leidas) {
      $("#contador-notificaciones").removeClass("d-none");
    } else {
      $("#contador-notificaciones").addClass("d-inline-block");
    }
    $("#contenedor-notificaciones").html(contenido);
  });
  $("body").on(
    "click",
    "#contenedor-notificaciones li, #modalListaNotificaciones li",
    function () {
      var id = $(this).data("id");
      const datos = new FormData();
      datos.append("accion", "marcar_leida");
      datos.append("id_notificacion", id);
      datos.append("id_usuario", idUsuario);
      enviaAjax(datos, "?p=notificaciones");
      window.location.href = $(this).data("link");
    }
  );
  $("body").on("click", "#marcar-todo-leido", function () {
    const datos = new FormData();
    datos.append("accion", "marcar_todo_leido");
    datos.append("id_usuario", idUsuario);
    enviaAjax(datos, "?p=notificaciones").then(() => {
      obtenerNotificaciones(idUsuario);
      $("#contador-notificaciones").removeClass("d-inline-block");
      $("#contador-notificaciones").addClass("d-none");
    });
  });
  $("body").on("click", "#ver-todas-notificaciones", function () {
    const datos = new FormData();
    datos.append("accion", "ver_todas_notificaciones");
    datos.append("id_usuario", idUsuario);
    datos.append("pagina", pagina);
    enviaAjax(datos, "?p=notificaciones").then((respuesta) => {
      if (respuesta.notificaciones.length < 1) {
        var contenido = `
          <li class="list-group-item list-group-item-secondary">
              <div class="text-nowrap p-2">
                  <div class="w-100 text-center">
                      <span class="mb-1 h6">No hay notificaciones</span>
                  </div>
              </div>
          </li>
          `;
        $("#modalListaNotificaciones").html(contenido);
        return;
      }
      var contenido = ``;
      var dominio = window.location.hostname + window.location.pathname;
      respuesta.notificaciones.forEach((notificacion) => {
        contenido += `
          <li class="list-group-item${
            !notificacion.leida ? " list-group-item-warning" : ""
          } list-group-item-action p-3" data-link="http://${dominio}?p=${
          notificacion.objetivo
        }" role="button" data-id="${notificacion.id}"> 
            <div class="d-flex w-100 justify-content-between">
                <span class="mb-1 h6 text-nowrap">${notificacion.titulo}</span>
                <small class="text-muted ms-3 text-nowrap">${calcularTiempoNotificacion(
                  notificacion.fecha_creacion
                )}</small>
            </div>
            <small class="my-1">${notificacion.mensaje}</small>
          </li>
          `;
      });
      if (respuesta.ver_mas) {
        $("#ver-mas-notificaciones").removeClass("d-none");
        $("#ver-mas-notificaciones").addClass("d-inline-block");
      } else {
        $("#ver-mas-notificaciones").removeClass("d-inline-block");
        $("#ver-mas-notificaciones").addClass("d-none");
      }
      $("#modalListaNotificaciones").html(contenido);
    });
  });
  $("body").on("click", "#ver-mas-notificaciones", function () {
    pagina++;
    const datos = new FormData();
    datos.append("accion", "ver_todas_notificaciones");
    datos.append("id_usuario", idUsuario);
    datos.append("pagina", pagina);
    $("#ver-mas-notificaciones").addClass("disabled");
    enviaAjax(datos, "?p=notificaciones").then((respuesta) => {
      if (respuesta.notificaciones.length < 1) {
        var contenido = `
          <li class="list-group-item list-group-item-secondary">
              <div class="text-nowrap p-2">
                  <div class="w-100 text-center">
                      <span class="mb-1 h6">No hay notificaciones</span>
                  </div>
              </div>
          </li>
          `;
        $("#modalListaNotificaciones").html(contenido);
        $("#ver-mas-notificaciones").remove("disabled");
        return;
      }
      var contenido = $("#modalListaNotificaciones").html();
      var dominio = window.location.hostname + window.location.pathname;
      respuesta.notificaciones.forEach((notificacion) => {
        $("#modalListaNotificaciones").append(`
          <li class="list-group-item${
            !notificacion.leida ? " list-group-item-warning" : ""
          } list-group-item-action p-3" data-link="http://${dominio}?p=${
          notificacion.objetivo
        }" role="button" data-id="${notificacion.id}"> 
            <div class="d-flex w-100 justify-content-between">
                <span class="mb-1 h6 text-nowrap">${notificacion.titulo}</span>
                <small class="text-muted ms-3 text-nowrap">${calcularTiempoNotificacion(
                  notificacion.fecha_creacion
                )}</small>
            </div>
            <small class="my-1">${notificacion.mensaje}</small>
          </li>`);
      });
      if (respuesta.ver_mas) {
        $("#ver-mas-notificaciones").removeClass("d-none");
        $("#ver-mas-notificaciones").addClass("d-inline-block");
      } else {
        $("#ver-mas-notificaciones").removeClass("d-inline-block");
        $("#ver-mas-notificaciones").addClass("d-none");
      }
    });
  });
  $("#modalVerNotificaciones").on("hidden.bs.modal", function (event) {
    pagina = 1;
    $("#ver-mas-notificaciones").removeClass("disabled");
  });
}

function calcularTiempoNotificacion(fecha_creacion) {
  const ahora = new Date();
  const fecha = new Date(fecha_creacion);
  const diferencia = Math.floor((ahora - fecha) / 1000); // Diferencia en segundos
  if (diferencia < 60) {
    // Menos de 1 minuto
    return `Hace ${diferencia} segundos`;
  } else if (diferencia < 3600) {
    // Menos de 1 hora
    const minutos = Math.floor(diferencia / 60);
    return `Hace ${minutos} minuto${minutos > 1 ? "s" : ""}`;
  } else if (diferencia < 86400) {
    // Menos de 1 dia
    const horas = Math.floor(diferencia / 3600);
    return `Hace ${horas} hora${horas > 1 ? "s" : ""}`;
  } else if (diferencia < 7 * 86400) {
    // Menos de 1 semana
    const dias = Math.floor(diferencia / 86400);
    return `Hace ${dias} dia${dias > 1 ? "s" : ""}`;
  } else {
    // Fecha completa para mas de una semana
    return fecha.toLocaleDateString();
  }
}
