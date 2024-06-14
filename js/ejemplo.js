$(document).ready(function () {
   
    function validarCaracteresPermitidos(regex, event) {
        const key = String.fromCharCode(event.which);
        if (!regex.test(key)) {
            event.preventDefault();
        }
    }

    function validarCampo(regex, campo, mensaje) {
        const valor = campo.val();
        const valido = regex.test(valor);
        if (valido) {
            campo.removeClass("is-invalid").addClass("is-valid");
            campo.siblings('.invalid-feedback').text("");
        } else {
            campo.removeClass("is-valid").addClass("is-invalid");
            campo.siblings('.invalid-feedback').text(mensaje);
        }
        return valido;
    }

    function enviaAjax(datos) {
        $.ajax({
            async: true,
            url: '',
            type: "POST",
            contentType: false,
            data: datos,
            processData: false,
            cache: false,
            beforeSend: function () {
             
            },
            timeout: 10000,
            success: function (respuesta) {
                Swal.fire("Éxito", "Operación realizada con éxito", "success");
                $("#f")[0].reset();
            },
            error: function (request, status, err) {
                if (status === "timeout") {
                    Swal.fire("Servidor ocupado", "Intente de nuevo", "error");
                } else {
                    Swal.fire("Error", "Error al procesar la solicitud", "error");
                }
            },
            complete: function () {
                
            },
        });
    }

 
    function validarEnvio() {
        let valid = true;
        return valid;
    }
    $("#f").on("submit", function(e){
        e.preventDefault();
    })
    $("#incluir, #modificar, #eliminar").on("click", function() {
        var action = $(this).attr("id");  
       // (validarEnvio()) {  
            $("#accion").val(action);  
            var datos = new FormData($("#f")[0]);  
            enviaAjax(datos);  
       // }
    });
});
