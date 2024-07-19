$(document).ready(function () {
  $("#login").on("submit", function (e) {
    e.preventDefault();
  });
  $("#submit").on("click", function () {
    var datos = new FormData($("#login")[0]);
    if (validarEnvio()) {
      datos.append("accion", "login");
      enviaAjax(datos);
    }
  });
  $("#id_usuario").on("keyup", function () {
    validarKeyUp(
      /^\d{7,9}$/,
      $(this),
      $("#susuario"),
      "La cedula no es valida ej.(12345678)"
    );
  });

  $("#password").on("keypress", function (e) {
    validarKeyPress(e, /^[a-zA-Z0-9@._-]$/);
  });

  $("#password").on("keyup", function () {
    validarKeyUp(
      /^[a-zA-Z0-9@._-]{6,20}$/,
      $(this),
      $("#spassword"),
      "Debe ingresar entre 6 y 20 caracteres"
    );
  });
});

function validarEnvio() {
  var esValido = true;
  esValido &= validarKeyUp(
    /^\d{7,9}$/,
    $("#id_usuario"),
    $("#susuario"),
    "La cedula no es valida ej.(12345678)"
  );
  esValido &= validarKeyUp(
    /^[a-zA-Z0-9@._-]{6,20}$/,
    $("#password"),
    $("#spassword"),
    "Debe ingresar entre 6 y 20 caracteres"
  );
  return esValido;
}

function validarKeyPress(e, er) {
  var key = e.key;
  if (!er.test(key)) {
    e.preventDefault();
  }
}

function validarKeyUp(er, input, mensaje, textoError) {
  if (er.test(input.val())) {
    input.removeClass("is-invalid").addClass("is-valid");
    mensaje.text("");
    return true;
  } else {
    input.removeClass("is-valid").addClass("is-invalid");
    mensaje.text(textoError);
    return false;
  }
}

function muestraMensaje(titulo, mensaje, icono) {
  Swal.fire({
    title: titulo,
    text: mensaje,
    icon: icono,
    showConfirmButton: false,
    showCancelButton: true,
    cancelButtonText: "Cerrar",
  });
}
function enviaAjax(datos) {
  $.ajax({
    async: true,
    url: "",
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    beforeSend: function () {},
    timeout: 10000,
    success: function (respuesta) {
      try {
        var lee = JSON.parse(respuesta);
        if (lee.resultado) {
          location = ".";
        } else if (!lee.resultado) {
          muestraMensaje(lee.mensaje, "", "error");
        } else if (lee.resultado == "error") {
          muestraMensaje(lee.mensaje, "", "error");
        }
      } catch (e) {
        alert("Error en JSON " + e.name);
        muestraMensaje(lee.mensaje, "", "success");
        console.error(respuesta);
      }
    },
    error: function (request, status, err) {
      // if (status == "timeout") {
      //   muestraMensaje("Servidor ocupado", "Intente de nuevo", "error");
      // } else {
      //   muestraMensaje("Error", request + status + err, "error");
      // }
    },
    complete: function () {},
  });
}
