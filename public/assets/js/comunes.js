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

export function modalCarga(cargando) {
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

export function limpiarForm() {
  var forms = document.getElementsByTagName("form");
  Array.from(forms).forEach(function (form) {
    form.reset();
  });
  $("form input").removeClass("is-valid");
  $("form input").removeClass("is-invalid");
  $("form div .invalid-feedback").text("");
}

// Promesa de refresco de token
let refreshTokenPromise = null;

// Variables para WebSocket
let websocket = null;
const WEBSOCKET_URL = "ws://localhost:8080"; // URL del servidor WebSocket
const RECONNECT_INTERVAL = 5000; // Intentar reconectar cada 5 segundos
const PING_INTERVAL = 30000; // Enviar ping cada 30 segundos
let pingIntervalId = null;
let reconnectIntervalId = null;

// Canal de comunicación entre pestañas para sincronizar notificaciones
const broadcastChannel = new BroadcastChannel("notificaciones_gymsys");

// Iniciar la conexión WebSocket si hay un token JWT presente
document.addEventListener("DOMContentLoaded", () => {
  if (obtenerTokenJWT()) {
    iniciarConexionWebSocket();
  }
});

/**
 * Inicia la conexión WebSocket y maneja los eventos.
 */
export function iniciarConexionWebSocket() {
  if (
    websocket &&
    (websocket.readyState === WebSocket.OPEN ||
      websocket.readyState === WebSocket.CONNECTING)
  ) {
    console.log("WebSocket ya está conectado o conectándose.");
    return;
  }

  websocket = new WebSocket(WEBSOCKET_URL);
  console.log(websocket);
  websocket.onopen = () => {
    console.log("Conexión WebSocket establecida.");
    // Autenticar al usuario al abrir la conexión
    const token = obtenerTokenJWT();
    if (token) {
      websocket.send(JSON.stringify({ action: "authenticate", token: token }));
      // Solicitar notificaciones una vez que la conexión está abierta y autenticada
      obtenerNotificaciones();
      // Iniciar pings para mantener la conexión viva
      if (pingIntervalId) clearInterval(pingIntervalId);
      pingIntervalId = setInterval(() => {
        if (websocket.readyState === WebSocket.OPEN) {
          websocket.send(JSON.stringify({ action: "ping" }));
        }
      }, PING_INTERVAL);
    } else {
      console.warn("No se encontró token JWT para autenticación WebSocket.");
    }
    // Limpiar el intervalo de reconexión si la conexión se establece
    if (reconnectIntervalId) {
      clearInterval(reconnectIntervalId);
      reconnectIntervalId = null;
    }
  };

  websocket.onmessage = (event) => {
    const data = JSON.parse(event.data);
    console.log("Mensaje recibido del WebSocket:", data);

    if (data.type === "new_notification" && data.data && Array.isArray(data.data.notificaciones)) {
      // Recibimos una nueva notificación por websocket
      let nuevasNotificaciones = data.data.notificaciones;
      // Obtener las notificaciones actuales en la UI (copia)
      let notificacionesDOM = [];
      $("#contenedor-notificaciones li[data-id]").each(function () {
        const id = $(this).data("id");
        const titulo = $(this).find(".h6").text();
        const mensaje = $(this).find(".my-1").text();
        const leida = !$(this).hasClass("list-group-item-warning");
        const objetivo = $(this).attr("data-link")?.split("?p=")[1] || "";
        const fechaDivSmall = $(this).find("div > small.text-muted").attr("title");
        const fechaSmallAttr = $(this).find("small.text-muted[title]").attr("title");
        const fecha_creacion = fechaSmallAttr || fechaDivSmall || "";
        notificacionesDOM.push({ id, titulo, mensaje, leida, objetivo, fecha_creacion });
      });
      // Filtrar notificaciones que NO estén ya presentes
      const nuevasFiltradas = nuevasNotificaciones.filter(notificacion =>
        !notificacionesDOM.some(n => Number(n.id) === Number(notificacion.id))
      );
      if (nuevasFiltradas.length > 0) {
        // Tomamos la primera (asumimos solo una por mensaje)
        const nueva = nuevasFiltradas[0];
        // Unimos la nueva con las anteriores (máximo 4)
        const nuevasLista = [nueva, ...notificacionesDOM].slice(0, 4);
        // Actualiza la UI usando el renderizador centralizado
        actualizarInterfazNotificaciones({ notificaciones: nuevasLista });
        // Sincronizar con otras pestañas (solo los datos, no el HTML)
        broadcastChannel.postMessage({
          type: "update_notifications",
          data: { notificaciones: nuevasLista },
        });
      }
    } else if (
      data.type === "notifications" ||
      data.type === "broadcast_notification"
    ) {
      // Actualizar las notificaciones en la UI
      actualizarInterfazNotificaciones(data.data);
      // Notificar a otras pestañas para que también actualicen
      broadcastChannel.postMessage({
        type: "update_notifications",
        data: data.data,
      });
    } else if (data.error) {
      console.error("Error del servidor WebSocket:", data.error);
      if (data.error === "Authentication failed") {
        muestraMensaje(
          "Error de autenticación",
          "Su sesión ha expirado o es inválida. Por favor, inicie sesión nuevamente.",
          "error"
        ).then(() => {
          window.location.href = "?p=login";
        });
      }
    }
  };

  websocket.onclose = (event) => {
    console.log("Conexión WebSocket cerrada:", event.code, event.reason);
    if (pingIntervalId) clearInterval(pingIntervalId);
    pingIntervalId = null;
    // Intentar reconectar si no fue un cierre intencional
    if (!event.wasClean && !reconnectIntervalId) {
      reconnectWebSocket();
    }
  };

  websocket.onerror = (error) => {
    console.error("Error WebSocket:", error);
    if (pingIntervalId) clearInterval(pingIntervalId);
    pingIntervalId = null;
    if (!reconnectIntervalId) {
      reconnectWebSocket();
    }
  };
}

/**
 * Intenta reconectar el WebSocket después de un intervalo.
 */
function reconnectWebSocket() {
  if (reconnectIntervalId) return; // Ya hay un intento de reconexión en curso

  console.log(
    `Intentando reconectar WebSocket en ${
      RECONNECT_INTERVAL / 1000
    } segundos...`
  );
  reconnectIntervalId = setInterval(() => {
    if (websocket.readyState === WebSocket.CLOSED) {
      iniciarConexionWebSocket();
    } else if (websocket.readyState === WebSocket.OPEN) {
      clearInterval(reconnectIntervalId);
      reconnectIntervalId = null;
    }
  }, RECONNECT_INTERVAL);
}

/**
 * Escucha mensajes del BroadcastChannel para sincronizar notificaciones entre pestañas.
 */
broadcastChannel.onmessage = (event) => {
  if (event.data && event.data.type === "update_notifications") {
    console.log("Sincronizando notificaciones desde otra pestaña.");
    actualizarInterfazNotificaciones(event.data.data);
  }
};

/**
 * Actualiza la interfaz de usuario con las notificaciones recibidas.
 * @param {object} respuesta - Objeto de respuesta con las notificaciones.
 */
function actualizarInterfazNotificaciones(respuesta) {
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
              <small class="text-muted ms-3 text-nowrap" title="${notificacion.fecha_creacion}">${calcularTiempoNotificacion(
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
    $("#icono-notificaciones").addClass("fa-shake");
  } else {
    $("#icono-notificaciones").removeClass("fa-shake");
    $("#contador-notificaciones").addClass("d-inline-block");
  }
  $("#contenedor-notificaciones").html(contenido);
}

export function enviaAjax(
  datos,
  url,
  type = "POST",
  mostrarCargaMensaje = true,
  esReintento = false
) {
  return new Promise((resolve, reject) => {
    $.ajax({
      async: true,
      url: url, // URL pasada como argumento
      type: type,
      contentType: false,
      data: datos,
      processData: false,
      cache: false,
      timeout: 10000,
      headers: {
        "X-Client-Type": "web",
        Authorization: obtenerTokenJWT(),
      },
      beforeSend: function () {
        if (mostrarCargaMensaje) {
          modalCarga(true);
        }
      },
      success: function (respuesta) {
        resolve(respuesta.data);
      },
      error: function (request, status) {
        if (request.status === 401 && !esReintento) {
          if (!refreshTokenPromise) {
            refreshTokenPromise = refreshAccessToken()
              .then(() => {
                refreshTokenPromise = null;
              })
              .catch((err) => {
                refreshTokenPromise = null;
                return Promise.reject(err);
              });
          }
          refreshTokenPromise
            .then(() => {
              return enviaAjax(datos, url, type, mostrarCargaMensaje, true);
            })
            .then(resolve)
            .catch((err) => {
              reject(err);
            });
        } else {
          const errorResponse = request.responseJSON?.data?.error ?? null;
          const errorMsg = errorResponse
            ? errorResponse
            : status === "timeout"
            ? "Servidor ocupado, intente de nuevo"
            : "Error al procesar la solicitud";

          if (mostrarCargaMensaje) muestraMensaje("Error", errorMsg, "error");
          reject(errorMsg);
        }
      },
      complete: function () {
        if (mostrarCargaMensaje) {
          modalCarga(false);
        }
      },
    });
  });
}

// Función de refresco de access token
function refreshAccessToken() {
  return $.ajax({
    url: "?p=authrefresh&accion=refreshtoken",
    method: "POST",
  })
    .then(function (response) {
      localStorage.setItem("access_token", response.data.accessToken);
      return Promise.resolve();
    })
    .catch(function () {
      muestraMensaje(
        "Sesión expirada",
        "Por favor inicia sesión nuevamente.",
        "error"
      );
      (function () {
        const observer = new MutationObserver((mutations, obs) => {
          for (const m of mutations) {
            for (const nodo of m.removedNodes) {
              // Cuando un nodo swal2-container es eliminado, se cerró el modal
              if (
                nodo.nodeType === 1 &&
                nodo.classList.contains("swal2-container")
              ) {
                const url = new URL(window.location.href);
                url.searchParams.set("p", "login");
                window.location.href = url.toString();
                obs.disconnect();
                return;
              }
            }
          }
        });
        // Observar hijos directos de body (inserciones/remociones)
        observer.observe(document.body, { childList: true });
      })();
    });
}
export function muestraMensaje(titulo, texto, tipo, opciones = {}) {
  const configDefault = {
    title: titulo,
    text: texto,
    icon: tipo,
    confirmButtonText: opciones.confirmButtonText || "Aceptar",
    confirmButtonColor: opciones.confirmButtonColor || "#198754",
    cancelButtonText: opciones.cancelButtonText || "Cancelar",
    scrollbarPadding: false,
    theme: localStorage.getItem("theme") || "light",
  };

  // Si se proporcionan opciones adicionales, las mezclamos con la configuración por defecto
  const config = { ...configDefault, ...opciones };
  return Swal.fire(config);
}

/**
 * Solicita y actualiza las notificaciones.
 * Si hay una conexión WebSocket abierta, la usa para solicitar las notificaciones.
 * De lo contrario, usa AJAX como fallback.
 */
export function obtenerNotificaciones() {
  if (websocket && websocket.readyState === WebSocket.OPEN) {
    // Si el WebSocket está abierto, solicita las notificaciones a través de él
    websocket.send(JSON.stringify({ action: "fetch_notifications" }));
  } else {
    // Fallback a AJAX si el WebSocket no está disponible o no está abierto
    console.warn(
      "WebSocket no está abierto. Usando AJAX para obtener notificaciones."
    );
    // setTimeout(() => {
    //   enviaAjax(
    //     "",
    //     "?p=notificaciones&accion=obtenerNotificaciones",
    //     "GET",
    //     false
    //   ).then((respuesta) => {
    //     actualizarInterfazNotificaciones(respuesta);
    //   });
    // }, 2000);
  }

  // Eventos de click para notificaciones
  $("body")
    .off("click", "#contenedor-notificaciones li, #modalListaNotificaciones li")
    .on(
      "click",
      "#contenedor-notificaciones li, #modalListaNotificaciones li",
      function () {
        var idNotificacion = $(this).data("id");
        enviaAjax(
          "",
          `?p=notificaciones&accion=marcarLeida&id=${idNotificacion}`,
          "GET"
        );
        window.location.href = $(this).data("link");
      }
    );
  $("body")
    .off("click", "#marcar-todo-leido")
    .on("click", "#marcar-todo-leido", function () {
      enviaAjax("", "?p=notificaciones&accion=marcarTodoLeido").then(() => {
        obtenerNotificaciones(); // Vuelve a obtener las notificaciones después de marcar como leídas
        $("#contador-notificaciones").removeClass("d-inline-block");
        $("#contador-notificaciones").addClass("d-none");
        $("#icono-notificaciones").removeClass("fa-shake");
      });
    });
  $("body")
    .off("click", "#ver-todas-notificaciones")
    .on("click", "#ver-todas-notificaciones", function () {
      enviaAjax(
        "",
        `?p=notificaciones&accion=verTodas&pagina=${pagina}`,
        "GET"
      ).then((respuesta) => {
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
                  <span class="mb-1 h6 text-nowrap">${
                    notificacion.titulo
                  }</span>
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
  $("body")
    .off("click", "#ver-mas-notificaciones")
    .on("click", "#ver-mas-notificaciones", function () {
      pagina++;
      $("#ver-mas-notificaciones").addClass("disabled");
      enviaAjax(
        "",
        `?p=notificaciones&accion=verTodas&pagina=${pagina}`,
        "GET"
      ).then((respuesta) => {
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
                  <span class="mb-1 h6 text-nowrap">${
                    notificacion.titulo
                  }</span>
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

export function validarFecha(fecha) {
  fecha = fecha.trim();
  const regex = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/;
  if (!regex.test(fecha)) {
    return false;
  }
  const [year, month, day] = fecha.split("-").map(Number);
  // Verificar si es una fecha válida del calendario
  const date = new Date(year, month - 1, day);
  return (
    date.getFullYear() === year &&
    date.getMonth() === month - 1 &&
    date.getDate() === day
  );
}

// Codificar base64
export function encodeBase64(str) {
  const encoder = new TextEncoder();
  const bytes = encoder.encode(str);
  const base64 = btoa(String.fromCharCode(...bytes));
  return base64;
}

// Decodificar base64
export function decodeBase64(base64) {
  const binary = atob(base64);
  const bytes = Uint8Array.from(binary, (char) => char.charCodeAt(0));
  const decoder = new TextDecoder();
  return decoder.decode(bytes);
}

export function debounce(func, delay) {
  let timer;
  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => {
      func.apply(this, args);
    }, delay);
  };
}
export function obtenerTokenJWT() {
  const token = localStorage.getItem("access_token");
  return token ? `Bearer ${token}` : "";
}

export function handleTheme() {
  const theme = localStorage.getItem("theme") || "light";
  const newTheme = theme === "dark" ? "light" : "dark";

  // Actualizar el ícono
  const icon = document.querySelector("#botonTema i");
  if (icon) {
    icon.className =
      newTheme === "dark" ? "fa-solid fa-sun" : "fa-solid fa-moon";
    icon.setAttribute(
      "title",
      newTheme === "dark" ? "Cambiar a modo claro" : "Cambiar a modo oscuro"
    );
  }

  // Actualizar el tema
  localStorage.setItem("theme", newTheme);
  document.documentElement.setAttribute("data-bs-theme", newTheme);
}

(function () {
  const theme = localStorage.getItem("theme") || "light";
  document.documentElement.setAttribute("data-bs-theme", theme);

  // Inicializar el ícono del tema
  const icon = document.querySelector("#botonTema i");
  if (icon) {
    icon.className = theme === "dark" ? "fa-solid fa-sun" : "fa-solid fa-moon";
    icon.setAttribute(
      "title",
      theme === "dark" ? "Cambiar a modo claro" : "Cambiar a modo oscuro"
    );
  }
})();
