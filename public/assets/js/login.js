import { enviaAjax, validarKeyPress, validarKeyUp, REGEX, generarClaveAES, encriptarFormularioAES, encriptarClaveAESRSA } from "./comunes.js";

$(document).ready(function () {
   localStorage.removeItem("access_token");
   $("#login").on("submit", function (e) {
      e.preventDefault();
   });
   $("#submit").on("click", async function () {
      if (!window.JSEncrypt) {
         alert('No se ha cargado JSEncrypt.');
         return;
      }
      if (validarEnvio()) {
         try {
            const form = document.getElementById('login');
            const { claveAES, iv } = await generarClaveAES();
            const encryptedData = await encriptarFormularioAES(form, claveAES, iv);
            const encryptedKey = await encriptarClaveAESRSA(claveAES);
            // Prepara datos para enviar
            const datos = new FormData();
            datos.append('encryptedData', encryptedData);
            datos.append('encryptedKey', encryptedKey);
            // Enviar por AJAX
            enviaAjax(datos, "?p=login&accion=authUsuario").then((respuesta) => {
               if (respuesta.auth === true) {
                  localStorage.setItem("access_token", respuesta.accessToken);
                  location = ".";
               }
            });
         } catch (err) {
            alert('Error en el cifrado: ' + err.message);
         }
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
