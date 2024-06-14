$(document).ready(function () {
   

    function validarKeyPress(e, er) {
        var key = e.key;
        if (!er.test(key)) {
            e.preventDefault();
        }
    }

    function validarKeyUp(er, input, mensaje, textoError) {
        if (er.test(input.val())) {
            input.removeClass('is-invalid').addClass('is-valid');
            mensaje.text('');
            return true;
        } else {
            input.removeClass('is-valid').addClass('is-invalid');
            mensaje.text(textoError);
            return false;
        }
    }

    $("#f").on("submit", function(e){
        e.preventDefault();
    });
    $("#incluir, #modificar, #eliminar").on("click", function() {
        var action = $(this).attr("id");  
       // (validarEnvio()) {  
            $("#accion").val(action);  
            var datos = new FormData($("#f")[0]);  
            enviaAjax(datos);  
       // }
    });

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
});