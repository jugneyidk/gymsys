import { enviaAjax, validarKeyPress, validarKeyUp, REGEX } from "./comunes.js";
$(document).ready(function () {
  $("#login").on("submit", function (e) {
    e.preventDefault();
  });
  $("#submit").on("click", function () {
    var datos = new FormData($("#login")[0]);
    if (validarEnvio()) {
      enviaAjax(datos, "?p=login&accion=authUsuario").then((respuesta) => {
        if (respuesta.auth === true) {
          location = ".";
          console.log(respuesta);
        }
      });
    }
  });
  $("#id_usuario").on("keyup", function () {
    validarKeyUp(
      REGEX.cedula.regex,
      $(this),
      $("#susuario"),
      REGEX.cedula.mensaje
    );
  });

  $("#password").on("keypress", function (e) {
    validarKeyPress(e, REGEX.keypress_password.regex);
  });

  $("#password").on("keyup", function () {
    validarKeyUp(
      REGEX.password.regex,
      $(this),
      $("#spassword"),
      REGEX.password.mensaje
    );
  });
  function validarEnvio() {
    var esValido = true;
    esValido &= validarKeyUp(
      REGEX.cedula.regex,
      $("#id_usuario"),
      $("#susuario"),
      REGEX.cedula.mensaje
    );
    esValido &= validarKeyUp(
      REGEX.password.regex,
      $("#password"),
      $("#spassword"),
      REGEX.password.mensaje
    );
    return esValido;
  }
});
