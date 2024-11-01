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

function modalCarga(parametro) {
  if (parametro) {
    $("#carga").modal("show");
    $("body").addClass("carga");
  } else {
    setTimeout(function () {
      $("#carga").modal("hide");
      $("body").removeClass("carga");
    }, 100);
  }
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
