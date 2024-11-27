import {
  enviaAjax,
  validarKeyPress,
  validarKeyUp,
  REGEX,
  muestraMensaje,
} from "./comunes.js";
$(document).ready(function () {
  $("#recovery-form").on("submit", function (e) {
    e.preventDefault();
    let email = $("#email").val();
    let cedula = $("#cedula").val();

    if (validarEnvio()) {
      let datos = new FormData(this);
      datos.append("accion", "recuperar");
      enviaAjax(datos, "").then((respuesta) => {
        muestraMensaje("Éxito", respuesta.mensaje, "success");
      });
    }
  });

  $("#restablecer-form").on("submit", function (e) {
    e.preventDefault();
    let nuevaContraseña = $("#nueva_contraseña").val();
    let confirmarContraseña = $("#confirmar_contraseña").val();
    let token = $("#token").val();

    if (nuevaContraseña === confirmarContraseña) {
      let datos = new FormData(this);
      datos.append("accion", "restablecer");
      enviaAjax(datos, "").then((respuesta) => {
        if (respuesta.ok) {
          Swal.fire("Éxito", respuesta.mensaje, "success").then(() => {
            location.href = "?p=login";
          });
        } else {
          Swal.fire("Error", respuesta.mensaje, "error");
        }
      });
    } else {
      $("#sconfirmar_contraseña").text("Las contraseñas no coinciden").show();
    }
  });
  $("input").on("keypress", function (e) {
    var id = $(this).attr("id");
    switch (id) {
      case "cedula":
        validarKeyPress(e, REGEX.keypress_numerico.regex);
        break;
      case "email":
        validarKeyPress(e, REGEX.keypress_correo.regex);
        break;
    }
  });

  $("input").on("keyup", function (e) {
    var id = $(this).attr("id");
    switch (id) {
      case "cedula":
        validarKeyUp(
          REGEX.cedula.regex,
          $(this),
          $("#scedula"),
          REGEX.cedula.mensaje
        );
        break;
      case "email":
        validarKeyUp(
            REGEX.correo_electronico.regex,
            $(this),
            $("#semail"),
            REGEX.correo_electronico.mensaje
          );
        break;
    }
  });
  function validarEnvio() {
    var esValido = true;
    esValido &= validarKeyUp(
      REGEX.correo_electronico.regex,
      $("#email"),
      $("#semail"),
      REGEX.correo_electronico.mensaje
    );
    esValido &= validarKeyUp(
      REGEX.cedula.regex,
      $("#cedula"),
      $("#scedula"),
      REGEX.cedula.mensaje
    );
    return esValido;
  }
});
