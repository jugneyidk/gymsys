-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-05-2025 a las 04:10:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gymsys`
--
CREATE DATABASE IF NOT EXISTS `gymsys` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gymsys`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id_atleta` varchar(10) NOT NULL,
  `asistio` tinyint(1) NOT NULL,
  `fecha` date NOT NULL,
  `comentario` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `asistencias`
--
DELIMITER $$
CREATE TRIGGER `after_asistencias_delete` AFTER DELETE ON `asistencias` FOR EACH ROW BEGIN
    IF @registro_bitacora IS NULL THEN
        SET @registro_bitacora = 1;

        -- Insertar un solo registro en la bitácora
        INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
        VALUES (
            'Eliminar', 
            'Asistencias', 
            @usuario_actual, 
            OLD.FECHA, 
            CONCAT('Se eliminaron las asistencias del día: ', OLD.fecha)
        );
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_asistencias_insert` AFTER INSERT ON `asistencias` FOR EACH ROW BEGIN
    IF @num_asistencias = 1 THEN
        INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
        VALUES ('Incluir', 'Asistencias', @usuario_actual, NEW.fecha, CONCAT('Se agregó asistencias para la fecha: ', NEW.fecha));
        SET @num_asistencias = NULL;
    ELSE
        SET @num_asistencias = @num_asistencias - 1;  
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_asistencias_update` AFTER UPDATE ON `asistencias` FOR EACH ROW BEGIN
	IF @cambios IS NULL THEN
    	SET @cambios = '';
    END IF;
    IF OLD.asistio != NEW.asistio THEN
    	SET @cambios = CONCAT(@cambios, 'Cambio la asistencia de ', OLD.id_atleta, ': ', IF(OLD.asistio = 0, 'No','Si'), ' -> ', IF(NEW.asistio = 0, 'No','Si'),'; ');
    END IF;
    IF OLD.comentario != NEW.comentario THEN
    	SET @cambios = CONCAT(@cambios, 'Cambio el comentario de ', OLD.id_atleta, ': ', OLD.comentario, ' -> ', NEW.comentario,'; ');
    END IF;
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Modificar', 'Asistencias', @usuario_actual, OLD.fecha, @cambios);
    SET @cambios = NULL;  
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atleta`
--

CREATE TABLE `atleta` (
  `cedula` varchar(10) NOT NULL,
  `entrenador` varchar(10) NOT NULL,
  `tipo_atleta` int(11) NOT NULL,
  `peso` decimal(6,2) NOT NULL,
  `altura` decimal(6,2) NOT NULL,
  `representante` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `atleta`
--

INSERT INTO `atleta` (`cedula`, `entrenador`, `tipo_atleta`, `peso`, `altura`, `representante`) VALUES
('29831802', '7376581', 3, 66.00, 164.00, NULL);

--
-- Disparadores `atleta`
--
DELIMITER $$
CREATE TRIGGER `after_atleta_create` AFTER INSERT ON `atleta` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Atletas', @usuario_actual, NEW.cedula, CONCAT('Se agregó el Atleta: ', NEW.cedula, ' - ', @nombre_usuario, ' ', @apellido_usuario));
    SET @nombre_usuario = NULL;
    SET @apellido_usuario = NULL;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_atleta_delete` AFTER DELETE ON `atleta` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (id_usuario, accion, modulo, registro_modificado, detalles)
    VALUES (@usuario_actual, 'Eliminar', 'Atletas', OLD.cedula,CONCAT("Se eliminó el atleta: ",OLD.cedula));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_atleta_update` AFTER UPDATE ON `atleta` FOR EACH ROW BEGIN
    IF OLD.entrenador != NEW.entrenador THEN
        SET @cambios = CONCAT(@cambios, 'Entrenador cambiado de "', OLD.entrenador, '" a "', NEW.entrenador, '"; ');
    END IF;
    IF OLD.tipo_atleta != NEW.tipo_atleta THEN
        SET @cambios = CONCAT(@cambios, 'Tipo de atleta cambiado de "', OLD.tipo_atleta, '" a "', NEW.tipo_atleta, '"; ');
    END IF;
    IF OLD.peso != NEW.peso THEN
        SET @cambios = CONCAT(@cambios, 'Peso cambiado de "', OLD.peso, '" a "', NEW.peso, '"; ');
    END IF;
    IF OLD.altura != NEW.altura THEN
        SET @cambios = CONCAT(@cambios, 'Altura cambiado de "', OLD.altura, '" a "', NEW.altura, '"; ');
    END IF;
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Modificar', 'Atletas', @usuario_actual, OLD.cedula, @cambios);
    SET @cambios = NULL;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(5) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `peso_minimo` decimal(10,2) NOT NULL,
  `peso_maximo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `peso_minimo`, `peso_maximo`) VALUES
(6, '61M', 61.00, 66.99),
(7, '67M', 67.00, 72.99),
(10, '73M', 73.00, 76.99),
(11, '77M', 77.00, 80.99),
(12, '81M', 81.00, 88.99);

--
-- Disparadores `categorias`
--
DELIMITER $$
CREATE TRIGGER `after_categorias_create` AFTER INSERT ON `categorias` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Categorías', @usuario_actual, NEW.nombre, CONCAT('Se agregó la categoría: ', NEW.nombre));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_categorias_delete` AFTER DELETE ON `categorias` FOR EACH ROW BEGIN
        INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
        VALUES (
            'Eliminar', 
            'Categorias', 
            @usuario_actual, 
            OLD.nombre, 
            CONCAT('Se eliminó la categoría: ', OLD.nombre)
        );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_categorias_update` AFTER UPDATE ON `categorias` FOR EACH ROW BEGIN
	IF @cambios IS NULL THEN
    	SET @cambios = '';
    END IF;
    IF OLD.nombre != NEW.nombre THEN
    	SET @cambios = CONCAT(@cambios, 'Cambio el nombre de ', OLD.nombre, ' a ', NEW.nombre, '; ');
    END IF;
    IF OLD.peso_minimo != NEW.peso_minimo THEN
    	SET @cambios = CONCAT(@cambios, 'Cambio el peso mínimo de ', OLD.peso_minimo, ' a ', NEW.peso_minimo, '; ');
    END IF;
    IF OLD.peso_maximo != NEW.peso_maximo THEN
    	SET @cambios = CONCAT(@cambios, 'Cambio el peso máximo de ', OLD.peso_maximo, ' a ', NEW.peso_maximo, '; ');
    END IF;
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Modificar', 'Categorias', @usuario_actual, OLD.nombre, @cambios);
    SET @cambios = NULL;  
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competencia`
--

CREATE TABLE `competencia` (
  `id_competencia` int(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `lugar_competencia` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `categoria` int(5) NOT NULL,
  `subs` int(5) NOT NULL,
  `tipo_competicion` int(5) NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `competencia`
--
DELIMITER $$
CREATE TRIGGER `after_competencia_create` AFTER INSERT ON `competencia` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Eventos', @usuario_actual, NEW.nombre, CONCAT('Se agregó el Evento: ', NEW.nombre, " - ", NEW.lugar_competencia));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_competencia_delete` AFTER DELETE ON `competencia` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Eliminar', 'Eventos', @usuario_actual, OLD.nombre, CONCAT('Se eliminó el Evento: ', OLD.nombre));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_competencia_update` AFTER UPDATE ON `competencia` FOR EACH ROW BEGIN
	SET @cambios = '';
    IF OLD.nombre != NEW.nombre THEN
        SET @cambios = CONCAT(@cambios, 'Nombre cambiado de "', OLD.nombre, '" a "', NEW.nombre, '"; ');
    END IF;
    IF OLD.lugar_competencia != NEW.lugar_competencia THEN
        SET @cambios = CONCAT(@cambios, 'Ubicación cambiado de "', OLD.lugar_competencia, '" a "', NEW.lugar_competencia, '"; ');
    END IF;
    IF OLD.fecha_inicio != NEW.fecha_inicio THEN
        SET @cambios = CONCAT(@cambios, 'Fecha de apertura cambiada de "', OLD.fecha_inicio, '" a "', NEW.fecha_inicio, '"; ');
    END IF;
    IF OLD.fecha_fin != NEW.fecha_fin THEN
        SET @cambios = CONCAT(@cambios, 'Fecha de clausura cambiada de "', OLD.fecha_fin, '" a "', NEW.fecha_fin, '"; ');
    END IF;
    IF OLD.categoria != NEW.categoria THEN
        SET @cambios = CONCAT(@cambios, 'Categoria cambiada de "', (SELECT nombre from categorias WHERE id_categoria = OLD.categoria), '" a "', (SELECT nombre from categorias WHERE id_categoria = NEW.categoria), '"; ');
    END IF;
    IF OLD.subs != NEW.subs THEN
        SET @cambios = CONCAT(@cambios, 'Subs cambiada de "', (SELECT nombre from subs WHERE id_sub = OLD.subs), '" a "', (SELECT nombre from subs WHERE id_sub = NEW.subs), '"; ');
    END IF;
    IF OLD.tipo_competicion != NEW.tipo_competicion THEN
        SET @cambios = CONCAT(@cambios, 'Tipo de competición cambiada de "', (SELECT nombre from tipo_competencia WHERE id_tipo_competencia = OLD.tipo_competicion), '" a "', (SELECT nombre from tipo_competencia WHERE id_tipo_competencia = NEW.tipo_competicion), '"; ');
    END IF;
    IF OLD.estado != NEW.estado THEN
        SET @cambios = CONCAT(@cambios, 'Estado de cambiado de "', OLD.estado, '" a "', NEW.estado, '"; ');
    END IF;
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Modificar', 'Eventos', @usuario_actual, OLD.nombre, @cambios);
    SET @cambios = NULL;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenador`
--

CREATE TABLE `entrenador` (
  `cedula` varchar(10) NOT NULL,
  `grado_instruccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrenador`
--

INSERT INTO `entrenador` (`cedula`, `grado_instruccion`) VALUES
('28609560', 'TSU'),
('7376581', 'Magister');

--
-- Disparadores `entrenador`
--
DELIMITER $$
CREATE TRIGGER `after_entrenador_create` AFTER INSERT ON `entrenador` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Entrenadores', @usuario_actual, NEW.cedula, CONCAT('Se agregó el Entrenador: ', NEW.cedula, ' - ', @nombre_usuario, ' ', @apellido_usuario));
    SET @nombre_usuario = NULL;
    SET @apellido_usuario = NULL;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_entrenador_delete` AFTER DELETE ON `entrenador` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Eliminar', 'Entrenadores', @usuario_actual, OLD.cedula,CONCAT("Se eliminó el entrenador: ", OLD.cedula));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_entrenador_update` AFTER UPDATE ON `entrenador` FOR EACH ROW BEGIN
    IF OLD.grado_instruccion != NEW.grado_instruccion THEN
        SET @cambios = CONCAT(@cambios, 'Grado de instrucción cambiado de "', OLD.grado_instruccion, '" a "', NEW.grado_instruccion, '"; ');
    END IF;
    
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Modificar', 'Entrenadores', @usuario_actual, OLD.cedula, @cambios);
    SET @cambios = NULL;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `estadisticas_dashboard`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `estadisticas_dashboard` (
`total_atletas` bigint(21)
,`total_entrenadores` bigint(21)
,`total_deudores` bigint(21)
,`total_wadas_pendientes` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_asistencias`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_asistencias` (
`id_atleta` varchar(10)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`asistio` tinyint(1)
,`comentario` varchar(200)
,`fecha` date
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_atletas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_atletas` (
`cedula` varchar(10)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`tipo_atleta` int(11)
,`tipo_cobro` float
,`entrenador` varchar(10)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_deudores`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_deudores` (
`cedula` varchar(10)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`nombre_tipo_atleta` varchar(25)
,`tipo_cobro` float
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_entrenadores`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_entrenadores` (
`cedula` varchar(10)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`telefono` varchar(15)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_eventos_activos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_eventos_activos` (
`id_competencia` int(50)
,`nombre` varchar(100)
,`categoria` int(5)
,`subs` int(5)
,`tipo_competicion` int(5)
,`lugar_competencia` varchar(100)
,`fecha_inicio` date
,`fecha_fin` date
,`participantes` bigint(21)
,`cupos_disponibles` bigint(22)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_eventos_anteriores`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_eventos_anteriores` (
`id_competencia` int(50)
,`nombre` varchar(100)
,`lugar_competencia` varchar(100)
,`fecha_inicio` date
,`fecha_fin` date
,`categoria` int(5)
,`subs` int(5)
,`tipo_competicion` int(5)
,`estado` enum('activo','inactivo')
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_mensualidades`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_mensualidades` (
`id_mensualidad` int(50)
,`cedula` varchar(10)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`monto` decimal(20,2)
,`fecha` date
,`detalles` varchar(200)
,`nombre_tipo_atleta` varchar(25)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_wada`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_wada` (
`cedula` varchar(10)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`estado` tinyint(1)
,`inscrito` date
,`vencimiento` date
,`ultima_actualizacion` date
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_wada_por_vencer`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_wada_por_vencer` (
`cedula` varchar(10)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`vencimiento` date
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensualidades`
--

CREATE TABLE `mensualidades` (
  `id_mensualidad` int(50) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `monto` decimal(20,2) NOT NULL,
  `detalles` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `mensualidades`
--
DELIMITER $$
CREATE TRIGGER `after_mensualidades_create` AFTER INSERT ON `mensualidades` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Mensualidad', @usuario_actual, NEW.id_atleta, CONCAT('Se agregó la mensualidad del Atleta con la cedula: ', NEW.id_atleta, ' para la fecha ', NEW.fecha));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_mensualidades_delete` AFTER DELETE ON `mensualidades` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Eliminar', 'Mensualidad', @usuario_actual, OLD.id_atleta, CONCAT('Se eliminó la mensualidad del atleta con la cedula: ', OLD.id_atleta, ' para la fecha ', OLD.fecha, ' y monto: ', OLD.monto));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `cedula` varchar(10) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `parentesco` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultado_competencia`
--

CREATE TABLE `resultado_competencia` (
  `id_competencia` int(10) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `arranque` varchar(255) NOT NULL,
  `envion` varchar(255) NOT NULL,
  `medalla_arranque` enum('oro','plata','bronce','ninguna') DEFAULT NULL,
  `medalla_envion` enum('oro','plata','bronce','ninguna') DEFAULT NULL,
  `medalla_total` enum('oro','plata','bronce','ninguna') DEFAULT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `resultado_competencia`
--
DELIMITER $$
CREATE TRIGGER `after_resultado_competencia_create` AFTER INSERT ON `resultado_competencia` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Inscripción a Evento', @usuario_actual, NEW.id_atleta, CONCAT('Se agregó el Atleta con ID: ', NEW.id_atleta, ' a la competencia: ', (SELECT nombre FROM competencia WHERE id_competencia = NEW.id_competencia) ));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_resultado_competencia_update` AFTER UPDATE ON `resultado_competencia` FOR EACH ROW BEGIN
	SET @cambios = '';
    IF OLD.arranque != NEW.arranque THEN
        SET @cambios = CONCAT(@cambios, 'Arranque cambiado de "', OLD.arranque, '" a "', NEW.arranque, '"; ');
    END IF;
    IF OLD.envion != NEW.envion THEN
        SET @cambios = CONCAT(@cambios, 'Envion cambiado de "', OLD.envion, '" a "', NEW.envion, '"; ');
    END IF;
    IF OLD.medalla_arranque != NEW.medalla_arranque THEN
        SET @cambios = CONCAT(@cambios, 'Medalla de arranque cambiado de "', OLD.medalla_arranque, '" a "', NEW.medalla_arranque, '"; ');
    END IF;
    IF OLD.medalla_envion != NEW.medalla_envion THEN
        SET @cambios = CONCAT(@cambios, 'Medalla de envion cambiado de "', OLD.medalla_envion, '" a "', NEW.medalla_envion, '"; ');
    END IF;
    IF OLD.medalla_total != NEW.medalla_total THEN
        SET @cambios = CONCAT(@cambios, 'Medalla del total cambiado de "', OLD.medalla_total, '" a "', NEW.medalla_total, '"; ');
    END IF;
    IF OLD.total != NEW.total THEN
        SET @cambios = CONCAT(@cambios, 'Total cambiado de "', OLD.total, '" a "', NEW.total, '"; ');
    END IF;
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Modificar', 'Resultado de evento', @usuario_actual, OLD.id_atleta, @cambios);
    SET @cambios = NULL;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subs`
--

CREATE TABLE `subs` (
  `id_sub` int(5) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `edad_minima` int(3) NOT NULL,
  `edad_maxima` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subs`
--

INSERT INTO `subs` (`id_sub`, `nombre`, `edad_minima`, `edad_maxima`) VALUES
(6, 'U13', 11, 13),
(7, 'U15', 13, 15),
(9, 'U20', 15, 20),
(10, 'U23', 17, 23);

--
-- Disparadores `subs`
--
DELIMITER $$
CREATE TRIGGER `after_subs_create` AFTER INSERT ON `subs` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Subs', @usuario_actual, NEW.nombre, CONCAT('Se agregó la sub: ', NEW.nombre));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_subs_delete` AFTER DELETE ON `subs` FOR EACH ROW BEGIN
        INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
        VALUES (
            'Eliminar', 
            'Subs', 
            @usuario_actual, 
            OLD.nombre, 
            CONCAT('Se eliminó la sub: ', OLD.nombre)
        );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_subs_update` AFTER UPDATE ON `subs` FOR EACH ROW BEGIN
	IF @cambios IS NULL THEN
    	SET @cambios = '';
    END IF;
    IF OLD.nombre != NEW.nombre THEN
    	SET @cambios = CONCAT(@cambios, 'Cambio el nombre de ', OLD.nombre, ' a ', NEW.nombre, '; ');
    END IF;
    IF OLD.edad_minima != NEW.edad_minima THEN
    	SET @cambios = CONCAT(@cambios, 'Cambio la edad mínima de ', OLD.edad_minima, ' a ', NEW.edad_minima, '; ');
    END IF;
    IF OLD.edad_maxima != NEW.edad_maxima THEN
    	SET @cambios = CONCAT(@cambios, 'Cambio la edad máxima de ', OLD.edad_maxima, ' a ', NEW.edad_maxima, '; ');
    END IF;
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Modificar', 'Subs', @usuario_actual, OLD.nombre, @cambios);
    SET @cambios = NULL;  
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_atleta`
--

CREATE TABLE `tipo_atleta` (
  `id_tipo_atleta` int(11) NOT NULL,
  `nombre_tipo_atleta` varchar(25) NOT NULL,
  `tipo_cobro` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_atleta`
--

INSERT INTO `tipo_atleta` (`id_tipo_atleta`, `nombre_tipo_atleta`, `tipo_cobro`) VALUES
(3, 'Convenio', 5),
(4, 'Atleta UPTAEB', 2);

--
-- Disparadores `tipo_atleta`
--
DELIMITER $$
CREATE TRIGGER `after_tipo_atleta_create` AFTER INSERT ON `tipo_atleta` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Tipo de Atleta', @usuario_actual, NEW.nombre_tipo_atleta, CONCAT('Se agregó el Tipo de Atleta: ', NEW.nombre_tipo_atleta));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_tipo_atleta_delete` AFTER DELETE ON `tipo_atleta` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Eliminar', 'Tipo de Atleta', @usuario_actual, OLD.nombre_tipo_atleta, CONCAT('Se eliminó el Tipo de Atleta con: ', OLD.nombre_tipo_atleta));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_competencia`
--

CREATE TABLE `tipo_competencia` (
  `id_tipo_competencia` int(5) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_competencia`
--

INSERT INTO `tipo_competencia` (`id_tipo_competencia`, `nombre`) VALUES
(9, 'Junior'),
(11, 'Senior'),
(15, 'Panamericano'),
(16, 'Internacional'),
(17, 'Nacional'),
(18, 'Municipal');

--
-- Disparadores `tipo_competencia`
--
DELIMITER $$
CREATE TRIGGER `after_tipo_competencia_create` AFTER INSERT ON `tipo_competencia` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Tipo de Competencia', @usuario_actual, NEW.nombre, CONCAT('Se agregó el tipo de competencia: ', NEW.nombre));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_tipo_competencia_delete` AFTER DELETE ON `tipo_competencia` FOR EACH ROW BEGIN
        INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
        VALUES (
            'Eliminar', 
            'Tipo de Competencia', 
            @usuario_actual, 
            OLD.nombre, 
            CONCAT('Se eliminó el tipo de competencia: ', OLD.nombre)
        );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_tipo_competencia_update` AFTER UPDATE ON `tipo_competencia` FOR EACH ROW BEGIN
	IF @cambios IS NULL THEN
    	SET @cambios = '';
    END IF;
    IF OLD.nombre != NEW.nombre THEN
    	SET @cambios = CONCAT(@cambios, 'Cambio el nombre de ', OLD.nombre, ' a ', NEW.nombre, '; ');
    END IF;
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Modificar', 'Tipo de Competencia', @usuario_actual, OLD.nombre, @cambios);
    SET @cambios = NULL;  
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wada`
--

CREATE TABLE `wada` (
  `id_atleta` varchar(10) NOT NULL,
  `inscrito` date NOT NULL,
  `vencimiento` date NOT NULL,
  `ultima_actualizacion` date NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `wada`
--
DELIMITER $$
CREATE TRIGGER `after_wada_create` AFTER INSERT ON `wada` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'WADA', @usuario_actual, NEW.id_atleta, CONCAT('Se agregó la WADA para el Atleta con ID: ', NEW.id_atleta));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_wada_delete` AFTER DELETE ON `wada` FOR EACH ROW BEGIN
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Eliminar', 'WADA', @usuario_actual, OLD.id_atleta, CONCAT('Se eliminó la WADA del Atleta con ID: ', OLD.id_atleta));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_wada_update` AFTER UPDATE ON `wada` FOR EACH ROW BEGIN
	SET @cambios = '';
    IF OLD.inscrito != NEW.inscrito THEN
        SET @cambios = CONCAT(@cambios, 'Fecha de inscripción cambiada de "', OLD.inscrito, '" a "', NEW.inscrito, '"; ');
    END IF;
    IF OLD.ultima_actualizacion != NEW.ultima_actualizacion THEN
        SET @cambios = CONCAT(@cambios, 'Fecha de última actualización cambiada de "', OLD.ultima_actualizacion, '" a "', NEW.ultima_actualizacion, '"; ');
    END IF;
    IF OLD.vencimiento != NEW.vencimiento THEN
        SET @cambios = CONCAT(@cambios, 'Fecha de vencimiento cambiada de "', OLD.vencimiento, '" a "', NEW.vencimiento, '"; ');
    END IF;
    IF OLD.estado != NEW.estado THEN
        SET @cambios = CONCAT(@cambios, 'Estado cambiado de "', IF(OLD.estado = 0, 'No cumple', 'Si cumple'), '" a "',IF(NEW.estado = 0, 'No cumple', 'Si cumple'), '"; ');
    END IF;
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Modificar', 'WADA', @usuario_actual, OLD.id_atleta, @cambios);
    SET @cambios = NULL;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura para la vista `estadisticas_dashboard`
--
DROP TABLE IF EXISTS `estadisticas_dashboard`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `estadisticas_dashboard`  AS SELECT (select count(0) from `atleta`) AS `total_atletas`, (select count(0) from `entrenador`) AS `total_entrenadores`, (select count(0) from `lista_deudores`) AS `total_deudores`, (select count(0) from ((`atleta` `a` join `gymsys_secure`.`usuarios` `u` on(`a`.`cedula` = `u`.`cedula`)) join `wada` `w` on(`w`.`id_atleta` = `u`.`cedula`)) where `w`.`vencimiento` <> 0 and `w`.`vencimiento` <= curdate() + interval 30 day order by `w`.`vencimiento` desc) AS `total_wadas_pendientes` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `lista_asistencias`
--
DROP TABLE IF EXISTS `lista_asistencias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_asistencias`  AS SELECT `a`.`id_atleta` AS `id_atleta`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `a`.`asistio` AS `asistio`, `a`.`comentario` AS `comentario`, `a`.`fecha` AS `fecha` FROM (`asistencias` `a` join `gymsys_secure`.`usuarios` `u` on(`a`.`id_atleta` = `u`.`cedula`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `lista_atletas`
--
DROP TABLE IF EXISTS `lista_atletas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_atletas`  AS SELECT `u`.`cedula` AS `cedula`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `a`.`tipo_atleta` AS `tipo_atleta`, `ta`.`tipo_cobro` AS `tipo_cobro`, `a`.`entrenador` AS `entrenador` FROM ((`atleta` `a` join `gymsys_secure`.`usuarios` `u` on(`a`.`cedula` = `u`.`cedula`)) join `tipo_atleta` `ta` on(`a`.`tipo_atleta` = `ta`.`id_tipo_atleta`)) ORDER BY `u`.`cedula` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `lista_deudores`
--
DROP TABLE IF EXISTS `lista_deudores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_deudores`  AS SELECT `u`.`cedula` AS `cedula`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `t`.`nombre_tipo_atleta` AS `nombre_tipo_atleta`, `t`.`tipo_cobro` AS `tipo_cobro` FROM (((`atleta` `a` join `gymsys_secure`.`usuarios` `u` on(`a`.`cedula` = `u`.`cedula`)) join `tipo_atleta` `t` on(`a`.`tipo_atleta` = `t`.`id_tipo_atleta`)) left join `mensualidades` `m` on(`a`.`cedula` = `m`.`id_atleta` and `m`.`fecha` >= date_format(current_timestamp(),'%Y-%m-01') and `m`.`fecha` <= last_day(current_timestamp()))) WHERE `m`.`id_atleta` is null GROUP BY `u`.`cedula` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `lista_entrenadores`
--
DROP TABLE IF EXISTS `lista_entrenadores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_entrenadores`  AS SELECT `u`.`cedula` AS `cedula`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `u`.`telefono` AS `telefono` FROM (`entrenador` `e` join `gymsys_secure`.`usuarios` `u` on(`e`.`cedula` = `u`.`cedula`)) ORDER BY `u`.`cedula` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `lista_eventos_activos`
--
DROP TABLE IF EXISTS `lista_eventos_activos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_eventos_activos`  AS SELECT `c`.`id_competencia` AS `id_competencia`, `c`.`nombre` AS `nombre`, `c`.`categoria` AS `categoria`, `c`.`subs` AS `subs`, `c`.`tipo_competicion` AS `tipo_competicion`, `c`.`lugar_competencia` AS `lugar_competencia`, `c`.`fecha_inicio` AS `fecha_inicio`, `c`.`fecha_fin` AS `fecha_fin`, (select count(0) from `resultado_competencia` `rc` where `rc`.`id_competencia` = `c`.`id_competencia`) AS `participantes`, 10 - (select count(0) from `resultado_competencia` `rc` where `rc`.`id_competencia` = `c`.`id_competencia`) AS `cupos_disponibles` FROM `competencia` AS `c` WHERE `c`.`estado` = 'activo' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `lista_eventos_anteriores`
--
DROP TABLE IF EXISTS `lista_eventos_anteriores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_eventos_anteriores`  AS SELECT `competencia`.`id_competencia` AS `id_competencia`, `competencia`.`nombre` AS `nombre`, `competencia`.`lugar_competencia` AS `lugar_competencia`, `competencia`.`fecha_inicio` AS `fecha_inicio`, `competencia`.`fecha_fin` AS `fecha_fin`, `competencia`.`categoria` AS `categoria`, `competencia`.`subs` AS `subs`, `competencia`.`tipo_competicion` AS `tipo_competicion`, `competencia`.`estado` AS `estado` FROM `competencia` WHERE `competencia`.`fecha_fin` < curdate() OR `competencia`.`estado` = 'inactivo' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `lista_mensualidades`
--
DROP TABLE IF EXISTS `lista_mensualidades`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_mensualidades`  AS SELECT `m`.`id_mensualidad` AS `id_mensualidad`, `u`.`cedula` AS `cedula`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `m`.`monto` AS `monto`, `m`.`fecha` AS `fecha`, `m`.`detalles` AS `detalles`, `t`.`nombre_tipo_atleta` AS `nombre_tipo_atleta` FROM (((`mensualidades` `m` join `atleta` `a` on(`m`.`id_atleta` = `a`.`cedula`)) join `gymsys_secure`.`usuarios` `u` on(`a`.`cedula` = `u`.`cedula`)) join `tipo_atleta` `t` on(`a`.`tipo_atleta` = `t`.`id_tipo_atleta`)) ORDER BY `m`.`fecha` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `lista_wada`
--
DROP TABLE IF EXISTS `lista_wada`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_wada`  AS SELECT `u`.`cedula` AS `cedula`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `w`.`estado` AS `estado`, `w`.`inscrito` AS `inscrito`, `w`.`vencimiento` AS `vencimiento`, `w`.`ultima_actualizacion` AS `ultima_actualizacion` FROM ((`atleta` `a` join `gymsys_secure`.`usuarios` `u` on(`a`.`cedula` = `u`.`cedula`)) join `wada` `w` on(`w`.`id_atleta` = `u`.`cedula`)) ORDER BY `u`.`cedula` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `lista_wada_por_vencer`
--
DROP TABLE IF EXISTS `lista_wada_por_vencer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_wada_por_vencer`  AS SELECT `u`.`cedula` AS `cedula`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `w`.`vencimiento` AS `vencimiento` FROM ((`atleta` `a` join `gymsys_secure`.`usuarios` `u` on(`a`.`cedula` = `u`.`cedula`)) join `wada` `w` on(`w`.`id_atleta` = `u`.`cedula`)) WHERE `w`.`vencimiento` <> 0 AND `w`.`vencimiento` <= curdate() + interval 30 day ORDER BY `w`.`vencimiento` DESC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id_atleta`,`fecha`);

--
-- Indices de la tabla `atleta`
--
ALTER TABLE `atleta`
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `entrenador` (`entrenador`),
  ADD KEY `tipo_atleta` (`tipo_atleta`),
  ADD KEY `representante` (`representante`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `competencia`
--
ALTER TABLE `competencia`
  ADD PRIMARY KEY (`id_competencia`),
  ADD KEY `categoria` (`categoria`),
  ADD KEY `subs` (`subs`),
  ADD KEY `tipo_competicion` (`tipo_competicion`);

--
-- Indices de la tabla `entrenador`
--
ALTER TABLE `entrenador`
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `mensualidades`
--
ALTER TABLE `mensualidades`
  ADD PRIMARY KEY (`id_mensualidad`),
  ADD KEY `id_atleta` (`id_atleta`);

--
-- Indices de la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `resultado_competencia`
--
ALTER TABLE `resultado_competencia`
  ADD PRIMARY KEY (`id_competencia`),
  ADD KEY `id_atleta` (`id_atleta`);

--
-- Indices de la tabla `subs`
--
ALTER TABLE `subs`
  ADD PRIMARY KEY (`id_sub`);

--
-- Indices de la tabla `tipo_atleta`
--
ALTER TABLE `tipo_atleta`
  ADD PRIMARY KEY (`id_tipo_atleta`);

--
-- Indices de la tabla `tipo_competencia`
--
ALTER TABLE `tipo_competencia`
  ADD PRIMARY KEY (`id_tipo_competencia`);

--
-- Indices de la tabla `wada`
--
ALTER TABLE `wada`
  ADD UNIQUE KEY `id_atleta` (`id_atleta`) USING BTREE,
  ADD KEY `vencimiento` (`vencimiento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `competencia`
--
ALTER TABLE `competencia`
  MODIFY `id_competencia` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `mensualidades`
--
ALTER TABLE `mensualidades`
  MODIFY `id_mensualidad` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `resultado_competencia`
--
ALTER TABLE `resultado_competencia`
  MODIFY `id_competencia` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `subs`
--
ALTER TABLE `subs`
  MODIFY `id_sub` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `tipo_atleta`
--
ALTER TABLE `tipo_atleta`
  MODIFY `id_tipo_atleta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `tipo_competencia`
--
ALTER TABLE `tipo_competencia`
  MODIFY `id_tipo_competencia` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `atleta`
--
ALTER TABLE `atleta`
  ADD CONSTRAINT `atleta_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `gymsys_secure`.`usuarios` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `atleta_ibfk_2` FOREIGN KEY (`entrenador`) REFERENCES `entrenador` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `atleta_ibfk_3` FOREIGN KEY (`tipo_atleta`) REFERENCES `tipo_atleta` (`id_tipo_atleta`) ON UPDATE CASCADE,
  ADD CONSTRAINT `atleta_ibfk_4` FOREIGN KEY (`representante`) REFERENCES `representantes` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `competencia`
--
ALTER TABLE `competencia`
  ADD CONSTRAINT `competencia_ibfk_1` FOREIGN KEY (`tipo_competicion`) REFERENCES `tipo_competencia` (`id_tipo_competencia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `competencia_ibfk_2` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`id_categoria`) ON UPDATE CASCADE,
  ADD CONSTRAINT `competencia_ibfk_3` FOREIGN KEY (`subs`) REFERENCES `subs` (`id_sub`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `entrenador`
--
ALTER TABLE `entrenador`
  ADD CONSTRAINT `entrenador_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `gymsys_secure`.`usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensualidades`
--
ALTER TABLE `mensualidades`
  ADD CONSTRAINT `mensualidades_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `resultado_competencia`
--
ALTER TABLE `resultado_competencia`
  ADD CONSTRAINT `resultado_competencia_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `resultado_competencia_ibfk_2` FOREIGN KEY (`id_competencia`) REFERENCES `competencia` (`id_competencia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `wada`
--
ALTER TABLE `wada`
  ADD CONSTRAINT `wada_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
