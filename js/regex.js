const REGEX = {
  nombres: {
    regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}$/,
    mensaje: "Solo letras y espacios (3-50 caracteres)",
  },
  apellidos: {
    regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{3,50}$/,
    mensaje: "Solo letras y espacios (3-50 caracteres)",
  },
  cedula: {
    regex: /^\d{7,9}$/,
    mensaje: "La cédula debe tener al menos 7 números",
  },
  telefono: {
    regex: /^04\d{9}$/,
    mensaje: "El formato del teléfono debe ser 04XXXXXXXXX",
  },
  correo_electronico: {
    regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    mensaje: "Correo inválido",
  },
  peso: {
    regex: /^\d+(\.\d{1,2})?$/,
    mensaje: "Solo números y puntos decimales",
  },
  altura: {
    regex: /^\d+(\.\d{1,2})?$/,
    mensaje: "Solo números y puntos decimales",
  },
  password: {
    regex:
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,15}$/,
    mensaje:
      "La contraseña debe tener entre 8 y 15 caracteres: 1 mayuscula, 1 minuscula, 1 numero y 1 simbolo ($@$!%*?&)",
  },
  estado_civil: {
    regex: /^(Soltero|Casado|Divorciado|Viudo)$/,
    mensaje: "El estado civil no es valido",
  },
  lugar_nacimiento: {
    regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
    mensaje: "El lugar de nacimiento no puede estar vacío",
  },
  fecha_nacimiento: {
    regex: /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/,
    mensaje: "La fecha de nacimiento no es valida",
  },
  genero: {
    regex: /^(Masculino|Femenino)$/,
    mensaje: "El genero no es valido",
  },
  grado_instruccion: {
    regex: /^[a-zA-Z](?:[a-zA-Z]* ?[a-zA-Z]+){2,49}$/,
    mensaje: "El grado de instruccion no es valido",
  },
  keypress_letras: {
    regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]*$/,
    mensaje: "",
  },
  keypress_alfanumerico: {
    regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s\d]*$/,
    mensaje: "",
  },
  keypress_numerico: {
    regex: /^\d*$/,
    mensaje: "",
  },
  keypress_decimal: {
    regex: /^[0-9.]$/,
    mensaje: "",
  },
  keypress_correo: {
    regex: /^[a-zA-Z0-9._%+-@]$/,
    mensaje: "",
  },
  keypress_password: {
    regex: /^[A-Za-z\d$@$!%*?&]$/,
    mensaje: "",
  },
  nombre_representante: {
    regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,100}$/,
    mensaje: "Nombre del representante es obligatorio (1-100 caracteres)",
  },
  cedula_representante: {
    regex: /^\d{7,9}$/,
    mensaje: "La cédula del representante debe tener 7-9 números",
  },
  telefono_representante: {
    regex: /^04\d{9}$/,
    mensaje: "El teléfono del representante debe ser 04XXXXXXXXX",
  },
  parentesco_representante: {
    regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,50}$/,
    mensaje: "El parentesco debe ser de 1-50 caracteres",
  },
  detalles: {
    regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s\d]{0,200}$/,
    mensaje: "Solo letras, números y espacios (200 caracteres maximo)",
  },
};
export default REGEX;
