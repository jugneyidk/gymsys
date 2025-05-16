const REGEX_EVENTOS = {
    nombre_evento: {
      regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{3,100}$/,
      mensaje: "El nombre del evento debe tener entre 3 y 100 caracteres, solo letras y espacios.",
    },
    ubicacion: {
      regex: /^[a-zA-ZáéíóúÁÉÍÓÚ\s\d,.#-]{3,150}$/,
      mensaje: "La ubicación debe tener entre 3 y 150 caracteres, y puede incluir números y caracteres especiales como ,.#-",
    },
    fecha: {
      regex: /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/,
      mensaje: "La fecha debe estar en formato YYYY-MM-DD.",
    },
    categoria: {
      regex: /^(?!Seleccione una$).+/,
      mensaje: "Debe seleccionar una categoría válida.",
    },
    subs: {
      regex: /^(?!Seleccione una$).+/,
      mensaje: "Debe seleccionar un sub válido.",
    },
    tipo_competencia: {
      regex: /^(?!Seleccione una$).+/,
      mensaje: "Debe seleccionar un tipo de competencia válido.",
    },
    peso_minimo: {
      regex: /^\d{1,3}(\.\d{1,2})?$/,
      mensaje: "El peso mínimo debe ser un número válido con hasta dos decimales.",
    },
    peso_maximo: {
      regex: /^\d{1,3}(\.\d{1,2})?$/,
      mensaje: "El peso máximo debe ser un número válido con hasta dos decimales.",
    },
    edad_minima: {
      regex: /^\d{1,2}$/,
      mensaje: "La edad mínima debe ser un número entero válido.",
    },
    edad_maxima: {
      regex: /^\d{1,2}$/,
      mensaje: "La edad máxima debe ser un número entero válido.",
    },
    arranque: {
      regex: /^\d{1,3}$/,
      mensaje: "El arranque debe ser un número entre 1 y 3 dígitos.",
    },
    envion: {
      regex: /^\d{1,3}$/,
      mensaje: "El envión debe ser un número entre 1 y 3 dígitos.",
    },
    total: {
      regex: /^\d{1,4}$/,
      mensaje: "El total debe ser un número entre 1 y 4 dígitos.",
    },
    medalla: {
      regex: /^(oro|plata|bronce|ninguna)$/i,
      mensaje: "Debe seleccionar una opción válida para la medalla.",
    },
    estado: {
      regex: /^(activo|inactivo)$/i,
      mensaje: "El estado debe ser 'activo' o 'inactivo'.",
    },
  };
  
  export default REGEX_EVENTOS;
  