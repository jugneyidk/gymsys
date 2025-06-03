import {
   enviaAjax,
   validarKeyPress,
   validarKeyUp,
   REGEX,
   muestraMensaje,
} from "./comunes.js";
$(document).ready(function () {
   verificarToken();
   function verificarToken() {
      const url = new URL(window.location.href);
      const token = url.searchParams.get('token');
      const datos = new FormData();
      datos.append("token", token);
      enviaAjax(datos, `?p=restablecer&accion=verificarToken`).then((respuesta) => {
         $("#token").val(token);
      }).catch(error => {
         (function () {
            const observer = new MutationObserver((mutations, obs) => {
               for (const m of mutations) {
                  for (const nodo of m.removedNodes) {
                     // Cuando un nodo swal2-container es eliminado, se cerró el modal
                     if (nodo.nodeType === 1 && nodo.classList.contains('swal2-container')) {
                        const url = new URL(window.location.href);
                        url.searchParams.set('p', 'login');
                        url.searchParams.delete('token')
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

      })
   }
   $("input").on("keyup", function (e) {
      var id = $(this).attr("id");
      switch (id) {
         case "nueva_password":
         case "confirmar_password":
            validarKeyUp(
               REGEX.password.regex,
               $(this),
               $(`#s${id}`),
               REGEX.password.mensaje
            );
            break;
      }
   });
   function validarEnvio() {
      var esValido = true;
      esValido &= validarKeyUp(
         REGEX.password.regex,
         $("#nueva_password"),
         $("#snueva_password"),
         REGEX.password.mensaje
      );
      esValido &= validarKeyUp(
         REGEX.password.regex,
         $("#confirmar_password"),
         $("#sconfirmar_password"),
         REGEX.password.mensaje
      );
      if (esValido && ($("#nueva_password").val() !== $("#confirmar_password").val())) {
         $("#sconfirmar_password").text("Las contraseñas no coinciden").show();
         $("#confirmar_password").addClass("is-invalid");
         $("#confirmar_password").removeClass("is-valid");
         esValido = false;
      }
      return esValido;
   }
   $("#reset-form").on("submit", function (e) {
      e.preventDefault();
      if (validarEnvio()) {
         let datos = new FormData(this);
         enviaAjax(datos, "?p=restablecer&accion=restablecerPassword").then((respuesta) => {
            muestraMensaje("Éxito", respuesta.mensaje, "success").then(() => {
               location.href = "?p=login";
            });
         })
      }
   });
});
