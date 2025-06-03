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
      if (validarEnvio()) {
         let datos = new FormData(this);
         enviaAjax(datos, "?p=recovery&accion=generarRecuperacion").then((respuesta) => {
            muestraMensaje("Éxito", respuesta.mensaje, "success");
         });
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
