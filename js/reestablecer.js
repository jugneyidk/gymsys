import {
    enviaAjax,
    validarKeyPress,
    validarKeyUp,
    REGEX,
  } from "./comunes.js";
$(document).ready(function () {
    $("#reset-form").on("submit", function (e) {
        e.preventDefault();
        let nuevaContraseña = $("#nueva_contraseña").val();
        let confirmarContraseña = $("#confirmar_contraseña").val();
        let token = $("#token").val();

        if (nuevaContraseña === confirmarContraseña) {
            let datos = new FormData(this);
            datos.append("accion", "restablecer");
            enviaAjax(datos, "").then((respuesta) => {
                console.log("Respuesta del servidor:", respuesta);
                if (respuesta.ok) {
                    Swal.fire("Éxito", respuesta.mensaje, "success").then(() => {
                        location.href = "?p=login";
                    });
                } else {
                    Swal.fire("Error", respuesta.mensaje, "error");
                }
            })
        } else {
            $("#sconfirmar_contraseña").text("Las contraseñas no coinciden").show();
        }
    });
});
