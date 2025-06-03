<?php
return [
   'nombres' => [
      'regex' => '/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}$/',
      'mensaje' => 'Solo letras y espacios (3-50 caracteres)',
      'tipo' => 'string'
   ],
   'nombre_representante' => [
      'regex' => '/^([a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}|)$/',
      'mensaje' => 'Solo letras y espacios (3-50 caracteres)',
      'tipo' => 'string'
   ],
   'apellidos' => [
      'regex' => '/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}$/',
      'mensaje' => 'Solo letras y espacios (3-50 caracteres)',
      'tipo' => 'string'
   ],
   'cedula' => [
      'regex' => '/^\d{7,9}$/',
      'mensaje' => 'La cédula debe tener al menos 7 números',
      'tipo' => 'int'
   ],
   'cedula_representante' => [
      'regex' => '/^(\d{7,9}|)$/',
      'mensaje' => 'La cédula debe tener al menos 7 números',
      'tipo' => 'int'
   ],
   'cedula_original' => [
      'regex' => '/^\d{7,9}$/',
      'mensaje' => 'La cédula debe tener al menos 7 números',
      'tipo' => 'int'
   ],
   'telefono' => [
      'regex' => '/^04\d{9}$/',
      'mensaje' => 'El formato del teléfono debe ser 04XXXXXXXXX',
      'tipo' => 'string'
   ],
   'telefono_representante' => [
      'regex' => '/^(04\d{9}|)$/',
      'mensaje' => 'El formato del teléfono debe ser 04XXXXXXXXX',
      'tipo' => 'string'
   ],
   'correo_electronico' => [
      'regex' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
      'mensaje' => 'Correo inválido',
      'tipo' => 'email'
   ],
   'correo' => [
      'regex' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
      'mensaje' => 'Correo inválido',
      'tipo' => 'email'
   ],
   'peso' => [
      'regex' => '/^\d+(\.\d{1,2})?$/',
      'mensaje' => 'Solo números y puntos decimales',
      'tipo' => 'float'
   ],
   'altura' => [
      'regex' => '/^\d+(\.\d{1,2})?$/',
      'mensaje' => 'Solo números y puntos decimales',
      'tipo' => 'float'
   ],
   'password' => [
      'regex' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,15}$/',
      'mensaje' => 'La contraseña debe tener entre 8 y 15 caracteres: 1 mayuscula, 1 minuscula, 1 numero y 1 simbolo ($@$!%*?&)'
   ],
   'old_password' => [
      'regex' => '/^[a-zA-Z0-9@._-]{6,20}$/',
      'mensaje' => 'La contraseña debe tener entre 6 y 20 caracteres'
   ],
   'estado_civil' => [
      'regex' => '/^(Soltero|Casado|Divorciado|Viudo)$/',
      'mensaje' => 'El estado civil no es valido',
      'tipo' => 'string'
   ],
   'lugar_nacimiento' => [
      'regex' => '/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/',
      'mensaje' => 'El lugar de nacimiento no puede estar vacío',
      'tipo' => 'string'
   ],
   'fecha_nacimiento' => [
      'regex' => '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/',
      'mensaje' => 'La fecha de nacimiento no es valida',
      'tipo' => 'string'
   ],
   'genero' => [
      'regex' => '/^(Masculino|Femenino)$/',
      'mensaje' => 'El genero no es valido',
      'tipo' => 'string'
   ],
   'grado_instruccion' => [
      'regex' => '/^[a-zA-Z](?:[a-zA-Z]* ?[a-zA-Z]+){2,49}$/',
      'mensaje' => 'El grado de instruccion no es valido',
      'tipo' => 'string'
   ],
   'nombre_rol' => [
      'regex' => '/^[a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}$/',
      'mensaje' => 'Solo letras y espacios (3-50 caracteres)',
      'tipo' => 'string'
   ],
   'entrenador_asignado' => [
      'regex' => '/^\d{7,9}$/',
      'mensaje' => 'La cédula debe tener al menos 7 números',
      'tipo' => 'int'
   ],
   'parentesco_representante' => [
      'regex' => '/^([a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}|)$/',
      'mensaje' => 'Solo letras y espacios (3-50 caracteres)',
      'tipo' => 'string'
   ],
   'tipo_atleta' => [
      'regex' => '/^[0-9]{1,11}$/',
      'mensaje' => 'El tipo de atleta no es valido',
      'tipo' => 'int'
   ],
   'detalles' => [
      'regex' => '/^[a-zA-ZáéíóúÁÉÍÓÚ\s\d]{0,200}$/',
      'mensaje' => 'Solo letras, números y espacios (200 caracteres maximo)',
      'tipo' => 'string'
   ],
   'bool' => [
      'regex' => '/^[01]$/',
      'mensaje' => 'El valor booleano no es valido',
      'tipo' => 'bool'
   ],
   'nombre_evento' => [
      'regex' => '/^[a-zA-ZáéíóúÁÉÍÓÚ\s\d]{3,100}$/',
      'mensaje' => 'El nombre del evento debe ser letras y/o números (entre 3 y 100 caracteres)',
      'tipo' => 'string'
   ],
   'lugar_competencia' => [
      'regex' => '/^[a-zA-ZáéíóúÁÉÍÓÚ\s\d]{3,100}$/',
      'mensaje' => 'La ubicación debe ser letras y/o números (entre 3 y 100 caracteres)',
      'tipo' => 'string'
   ],
   'nombre_categoria' => [
      'regex' => '/^[a-zA-ZáéíóúÁÉÍÓÚ\s\d]{3,50}$/',
      'mensaje' => 'El nombre de la categoria debe ser letras y/o números (entre 3 y 50 caracteres)',
      'tipo' => 'string'
   ],
   'nombre_sub' => [
      'regex' => '/^[a-zA-ZáéíóúÁÉÍÓÚ\s\d]{3,50}$/',
      'mensaje' => 'El nombre de la sub debe ser letras y/o números (entre 3 y 50 caracteres)',
      'tipo' => 'string'
   ],
   'nombre_tipo' => [
      'regex' => '/^[a-zA-ZáéíóúÁÉÍÓÚ\s\d]{3,50}$/',
      'mensaje' => 'El nombre del tipo de evento debe ser letras y/o números (entre 3 y 50 caracteres)',
      'tipo' => 'string'
   ],
   'medalla' => [
      'regex' => '/^(oro|plata|bronce)$/',
      'mensaje' => 'La medalla ingresada no es válida',
      'tipo' => 'string'
   ],
];
