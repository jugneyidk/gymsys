$(document).ready(function() {
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

    function verificarFecha() {
        var fecha = $('#fecha_nacimiento').val();
        if (!fecha) {
            $('#fecha_nacimiento').removeClass('is-valid').addClass('is-invalid');
            $('#sfecha_nacimiento').text('La fecha de nacimiento es obligatoria');
            return false;
        }
        var hoy = new Date();
        var fechaNac = new Date(fecha);
        if (fechaNac > hoy) {
            $('#fecha_nacimiento').removeClass('is-valid').addClass('is-invalid');
            $('#sfecha_nacimiento').text('La fecha debe ser anterior al día actual');
            return false;
        } else {
            $('#fecha_nacimiento').removeClass('is-invalid').addClass('is-valid');
            $('#sfecha_nacimiento').text('');
            return true;
        }
    }

    function calcularEdad(fechaNacimiento) {
        var hoy = new Date();
        var fechaNac = new Date(fechaNacimiento);
        var edad = hoy.getFullYear() - fechaNac.getFullYear();
        var mes = hoy.getMonth() - fechaNac.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
            edad--;
        }
        return edad;
    }

    function validarEnvio() {
        var esValido = true;

        esValido &= validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, $('#nombres'), $('#snombres'), 'Solo letras y espacios (1-50 caracteres)');
        esValido &= validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, $('#apellidos'), $('#sapellidos'), 'Solo letras y espacios (1-50 caracteres)');
        esValido &= validarKeyUp(/^\d{8,}$/, $('#cedula'), $('#scedula'), 'La cédula debe tener al menos 8 números');
        esValido &= validarKeyUp(/^04\d{9}$/, $('#telefono'), $('#stelefono'), 'El formato del teléfono debe ser 04XXXXXXXXX');
        esValido &= validarKeyUp(/^04\d{9}$/, $('#telefono_representante'), $('#stelefono_representante'), 'El formato del teléfono debe ser 04XXXXXXXXX');
        esValido &= validarKeyUp(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, $('#correo'), $('#scorreo'), 'Correo inválido');
        esValido &= validarKeyUp(/^\d+(\.\d{1,2})?$/, $('#peso'), $('#speso'), 'Solo números y puntos decimales');
        esValido &= validarKeyUp(/^\d+(\.\d{1,2})?$/, $('#altura'), $('#saltura'), 'Solo números y puntos decimales');
        esValido &= validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, $('#entrenador_asignado'), $('#sentrenador_asignado'), 'Solo letras y espacios (1-50 caracteres)');
        esValido &= verificarFecha();
        esValido &= validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/, $('#lugar_nacimiento'), $('#slugarnacimiento'), 'El lugar de nacimiento no puede estar vacío');

        return esValido;
    }

    $('input').on('keypress', function(e) {
        var id = $(this).attr('id');
        switch(id) {
            case 'nombres':
            case 'apellidos':
            case 'entrenador_asignado':
            case 'lugar_nacimiento':
                validarKeyPress(e, /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/);
                break;
            case 'cedula':
            case 'edad':
            case 'telefono':
            case 'telefono_representante':
                validarKeyPress(e, /^\d*$/);
                break;
            case 'correo':
                validarKeyPress(e, /^[a-zA-Z0-9@._-]*$/);
                break;
            case 'peso':
            case 'altura':
                validarKeyPress(e, /^\d*\.?\d*$/);
                break;
        }
    });

    $('input').on('keyup', function() {
        var id = $(this).attr('id');
        switch(id) {
            case 'nombres':
                validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, $(this), $('#snombres'), 'Solo letras y espacios (1-50 caracteres)');
                break;
            case 'apellidos':
                validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, $(this), $('#sapellidos'), 'Solo letras y espacios (1-50 caracteres)');
                break;
            case 'cedula':
                validarKeyUp(/^\d{8,}$/, $(this), $('#scedula'), 'La cédula debe tener al menos 8 números');
                break;
            case 'telefono':
                validarKeyUp(/^04\d{9}$/, $(this), $('#stelefono'), 'El formato del teléfono debe ser 04XXXXXXXXX');
                break;
            case 'telefono_representante':
                validarKeyUp(/^04\d{9}$/, $(this), $('#stelefono_representante'), 'El formato del teléfono debe ser 04XXXXXXXXX');
                break;
            case 'correo':
                validarKeyUp(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, $(this), $('#scorreo'), 'Correo inválido');
                break;
            case 'peso':
                validarKeyUp(/^\d+(\.\d{1,2})?$/, $(this), $('#speso'), 'Solo números y puntos decimales');
                break;
            case 'altura':
                validarKeyUp(/^\d+(\.\d{1,2})?$/, $(this), $('#saltura'), 'Solo números y puntos decimales');
                break;
            case 'entrenador_asignado':
                validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, $(this), $('#sentrenador_asignado'), 'Solo letras y espacios (1-50 caracteres)');
                break;
            case 'lugar_nacimiento':
                validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/, $(this), $('#slugarnacimiento'), 'El lugar de nacimiento no puede estar vacío');
                break;
        }
    });

    $('#fecha_nacimiento').on('change', function() {
        verificarFecha();
        var edad = calcularEdad($(this).val());
        $('#edad').val(edad);
        if (edad < 18) {
            $('#representanteInfo').show();
        } else {
            $('#representanteInfo').hide();
        }
    });

    $('form').on('submit', function(e) {
        e.preventDefault();
        if (validarEnvio()) {
            $.ajax({
                url: 'submit_atleta.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert('Atleta registrado exitosamente.');
                    $('form').trigger('reset');
                    $('.is-valid').removeClass('is-valid');
                    $('.is-invalid').removeClass('is-invalid');
                },
                error: function() {
                    alert('Error al registrar el atleta.');
                }
            });
        } else {
            alert('Por favor, corrija los errores en el formulario.');
        }
    });
});
