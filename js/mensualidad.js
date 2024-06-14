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

    function verificarCampoVacio(input, mensaje, textoError) {
        if (input.val().trim() === '') {
            input.removeClass('is-valid').addClass('is-invalid');
            mensaje.text(textoError);
            return false;
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
            mensaje.text('');
            return true;
        }
    }

    function validarEnvio() {
        var esValido = true;
        esValido &= verificarCampoVacio($('#id_atleta'), $('#sid_atleta'), 'El campo atleta es obligatorio');
        esValido &= verificarCampoVacio($('#tipo_mensualidad'), $('#stipo_mensualidad'), 'El tipo de mensualidad es obligatorio');
        esValido &= verificarCampoVacio($('#cobro'), $('#scobro'), 'El cobro es obligatorio');
        esValido &= verificarCampoVacio($('#pago'), $('#spago'), 'El pago es obligatorio');
        esValido &= verificarCampoVacio($('#fecha'), $('#sfecha'), 'La fecha es obligatoria');
        return esValido;
    }
    $("f").on("submit", function(e){
        e.preventDefault();
    })

    $("#incluir, #modificar, #eliminar").on("click", function () {
    var action = $(this).attr("id");
    if (validarEnvio()) {
      $("#accion").val(action);
      var datos = new FormData($("#f")[0]);
      enviaAjax(datos);
    }
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
            beforeSend: function () {},
            timeout: 10000,
            success: function(respuesta){
                try{
                    var lee = JSON.parse(respuesta);
                    if (lee.ok) {
                        Swal.fire("Éxito", "Operación realizada con éxito", "success");
                        $("#f")[0].reset();
                        $("input, select").removeClass('is-valid').removeClass('is-invalid'); // reset validation classes
                    } else {
                        Swal.fire("Error", lee.mensaje, "error");
                    }
                } catch {
                    Swal.fire("Error", "Algo salió mal", "error");
                }
            },
            error: function (request, status, err) {
                if (status === "timeout") {
                    Swal.fire("Servidor ocupado", "Intente de nuevo", "error");
                } else {
                    Swal.fire("Error", "Error al procesar la solicitud", "error");
                }
            },
            complete: function () {}
        });
    }

    $('input, select').on('keypress', function(e) {
        var id = $(this).attr('id');
        switch(id) {
            case 'id_atleta':
                validarKeyPress(e, /^[0-9]*$/);
                break;
            case 'cobro':
            case 'pago':
                validarKeyPress(e, /^[0-9]*$/);
                break;
            case 'tipo_mensualidad':
                // No hace falta validación de keypress para el select
                break;
        }
    });

    $('input, select').on('keyup change', function() {
        var id = $(this).attr('id');
        switch(id) {
            case 'id_atleta':
                verificarCampoVacio($(this), $('#sid_atleta'), 'El campo atleta es obligatorio');
                break;
            case 'tipo_mensualidad':
                verificarCampoVacio($(this), $('#stipo_mensualidad'), 'El tipo de mensualidad es obligatorio');
                break;
            case 'cobro':
                verificarCampoVacio($(this), $('#scobro'), 'El cobro es obligatorio');
                break;
            case 'pago':
                verificarCampoVacio($(this), $('#spago'), 'El pago es obligatorio');
                break;
            case 'fecha':
                verificarCampoVacio($(this), $('#sfecha'), 'La fecha es obligatoria');
                break;
        }
    });
});
