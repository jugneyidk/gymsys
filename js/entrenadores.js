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
        esValido &= validarKeyUp(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, $('#correo'), $('#scorreo'), 'Correo inválido');
        esValido &= validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/, $('#lugar_nacimiento'), $('#slugarnacimiento'), 'El lugar de nacimiento no puede estar vacío');
        esValido &= validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, $('#grado_instruccion'), $('#sgrado_instruccion'), 'El grado de instrucción no puede estar vacío');
        esValido &= verificarFecha();

        return esValido;
    }

    $('input').on('keypress', function(e) {
        var id = $(this).attr('id');
        switch(id) {
            case 'nombres':
            case 'apellidos':
            case 'lugar_nacimiento':
            case 'grado_instruccion':
                validarKeyPress(e, /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/);
                break;
            case 'cedula':
            case 'edad':
            case 'telefono':
                validarKeyPress(e, /^\d*$/);
                break;
            case 'correo':
                validarKeyPress(e, /^[a-zA-Z0-9@._-]*$/);
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
            case 'correo':
                validarKeyUp(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, $(this), $('#scorreo'), 'Correo inválido');
                break;
            case 'lugar_nacimiento':
                validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/, $(this), $('#slugarnacimiento'), 'El lugar de nacimiento no puede estar vacío');
                break;
            case 'grado_instruccion':
                validarKeyUp(/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/, $(this), $('#sgrado_instruccion'), 'El grado de instrucción no puede estar vacío');
                break;
        }
    });

    $('#fecha_nacimiento').on('change', function() {
        verificarFecha();
        var edad = calcularEdad($(this).val());
        $('#edad').val(edad);
    });

    $('form').on('submit', function(e) {
        e.preventDefault();
        if (validarEnvio()) {
            $.ajax({
                url: 'submit_entrenador.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert('Entrenador registrado exitosamente.');
                    $('form').trigger('reset');
                    $('.is-valid').removeClass('is-valid');
                    $('.is-invalid').removeClass('is-invalid');
                },
                error: function() {
                    alert('Error al registrar el entrenador.');
                }
            });
        } else {
            alert('Por favor, corrija los errores en el formulario.');
        }
    });
});
