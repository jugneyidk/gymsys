$(document).ready(function () {
    $("#recovery-form").on("submit", function (e) {
        e.preventDefault();

        let email = $("#email").val();
        if (validateEmail(email)) {
            let datos = new FormData(this);
            datos.append("accion", "recuperar");
            enviaAjax(datos, "controlador_recuperacion.php").then((respuesta) => {
                if (respuesta.ok) {
                    Swal.fire("Éxito", respuesta.mensaje, "success");
                } else {
                    Swal.fire("Error", respuesta.mensaje, "error");
                }
            });
        } else {
            $("#semail").text("Por favor, introduce un correo válido").show();
        }
    });

    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});
