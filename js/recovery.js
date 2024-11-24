import {
    enviaAjax,
    validarKeyPress,
    validarKeyUp,
    REGEX,
  } from "./comunes.js";
$(document).ready(function () {
    $("#recovery-form").on("submit", function (e) {
        e.preventDefault();
        let email = $("#email").val();
        let cedula = $("#cedula").val();

        if (validateEmail(email) && validateCedula(cedula)) {
            let datos = new FormData(this);
            datos.append("accion", "recuperar");
            enviaAjax(datos, "").then((respuesta) => {
                if (respuesta.ok) {
                    Swal.fire("Éxito", respuesta.mensaje, "success");
                } else {
                    Swal.fire("Error", respuesta.mensaje, "error");
                }
            });
        } else {
            if (!validateEmail(email)) {
                $("#semail").text("Por favor, introduce un correo válido").show();
            }
            if (!validateCedula(cedula)) {
                $("#scedula").text("Por favor, introduce una cédula válida").show();
            }
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

    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    function validateCedula(cedula) {
        return cedula.length > 0;
    }
});
