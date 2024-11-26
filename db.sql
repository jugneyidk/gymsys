-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-11-2024 a las 02:26:34
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
        INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
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
        INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
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
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
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
('26846371', '22222222', 4, 58.00, 178.00, '2122554');

--
-- Disparadores `atleta`
--
DELIMITER $$
CREATE TRIGGER `after_atleta_create` AFTER INSERT ON `atleta` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Incluir', 'Atletas', @usuario_actual, NEW.cedula, CONCAT('Se agregó el Atleta: ', NEW.cedula, ' - ', @nombre_usuario, ' ', @apellido_usuario));
    SET @nombre_usuario = NULL;
    SET @apellido_usuario = NULL;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_atleta_delete` AFTER DELETE ON `atleta` FOR EACH ROW BEGIN
    INSERT INTO bitacora (id_usuario, accion, modulo, usuario_modificado,detalles)
    VALUES (@usuario_actual, 'Elminar', 'Atletas', OLD.cedula,CONCAT("Se eliminó el atleta: ",OLD.cedula));
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
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Modificar', 'Atletas', @usuario_actual, OLD.cedula, @cambios);
    SET @cambios = NULL;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_accion` int(50) NOT NULL,
  `id_usuario` varchar(10) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `usuario_modificado` varchar(20) DEFAULT NULL,
  `detalles` varchar(2000) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id_accion`, `id_usuario`, `accion`, `modulo`, `usuario_modificado`, `detalles`, `fecha`) VALUES
(761, '22222222', 'Modificar', 'Atletas', '26846371', '', '2024-11-25 13:54:44'),
(762, '22222222', 'Modificar', 'Atletas', '26846371', 'Nombre cambiado de \"Diego\" a \"Dieguito\"; ', '2024-11-25 13:54:51'),
(763, '22222222', 'Modificar', 'Roles', 'Atleta', ' Modulo atletas - crear: No -> Si;  Modulo eventos - leer: Si -> No; ', '2024-11-25 16:00:19');

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
(9, '81M', 73.00, 81.99);

--
-- Disparadores `categorias`
--
DELIMITER $$
CREATE TRIGGER `after_categorias_delete` AFTER DELETE ON `categorias` FOR EACH ROW BEGIN
        INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
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
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
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
  `tipo_competicion` int(5) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` int(5) NOT NULL,
  `subs` int(5) NOT NULL,
  `lugar_competencia` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `competencia`
--

INSERT INTO `competencia` (`id_competencia`, `tipo_competicion`, `nombre`, `categoria`, `subs`, `lugar_competencia`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(11, 9, 'Campeonato Nacional', 6, 6, 'Ciudad Deportiva Lara', '2024-12-01', '2024-12-05', 'activo'),
(12, 11, 'Panamericano 2024', 6, 6, 'Caracas', '2024-11-25', '2024-11-29', 'activo');

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
('22222222', 'cacaca');

--
-- Disparadores `entrenador`
--
DELIMITER $$
CREATE TRIGGER `after_entrenador_create` AFTER INSERT ON `entrenador` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Incluir', 'Entrenadores', @usuario_actual, NEW.cedula, CONCAT('Se agregó el Entrenador: ', NEW.cedula, ' - ', @nombre_usuario, ' ', @apellido_usuario));
    SET @nombre_usuario = NULL;
    SET @apellido_usuario = NULL;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_entrenador_delete` AFTER DELETE ON `entrenador` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado,detalles)
    VALUES ('Eliminar', 'Entrenadores', @usuario_actual, OLD.cedula,CONCAT("Se eliminó el entrenador: ", OLD.cedula));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_entrenador_update` AFTER UPDATE ON `entrenador` FOR EACH ROW BEGIN
    IF OLD.grado_instruccion != NEW.grado_instruccion THEN
        SET @cambios = CONCAT(@cambios, 'Grado de instrucción cambiado de "', OLD.grado_instruccion, '" a "', NEW.grado_instruccion, '"; ');
    END IF;
    
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Modificar', 'Entrenadores', @usuario_actual, OLD.cedula, @cambios);
    SET @cambios = NULL;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_atletas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_atletas` (
`cedula` varchar(10)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`genero` varchar(30)
,`fecha_nacimiento` date
,`lugar_nacimiento` varchar(50)
,`estado_civil` varchar(50)
,`telefono` varchar(15)
,`correo_electronico` varchar(50)
,`tipo_atleta` int(11)
,`peso` decimal(6,2)
,`altura` decimal(6,2)
,`entrenador` varchar(10)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id_marca` int(10) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `arranque` decimal(10,2) NOT NULL,
  `envion` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensualidades`
--

CREATE TABLE `mensualidades` (
  `id_mensualidad` int(50) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `tipo` int(2) NOT NULL,
  `monto` decimal(20,2) NOT NULL,
  `cobro` tinyint(1) NOT NULL,
  `detalles` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `mensualidades`
--
DELIMITER $$
CREATE TRIGGER `after_mensualidad_create` AFTER INSERT ON `mensualidades` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Incluir', 'Mensualidad', @usuario_actual, NEW.id_atleta, CONCAT('Se agregó la mensualidad del Atleta con la cedula: ', NEW.id_atleta, ' para la fecha ', NEW.fecha));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulo` int(50) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulo`, `nombre`) VALUES
(1, 'entrenadores'),
(2, 'atletas'),
(3, 'rolespermisos'),
(4, 'asistencias'),
(5, 'eventos'),
(6, 'mensualidad'),
(7, 'wada'),
(8, 'reportes'),
(9, 'bitacora');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `id_usuario` varchar(10) NOT NULL,
  `titulo` text NOT NULL,
  `mensaje` text DEFAULT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `objetivo` varchar(20) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_rol` int(50) NOT NULL,
  `modulo` int(50) NOT NULL,
  `crear` tinyint(1) NOT NULL DEFAULT 0,
  `leer` tinyint(1) NOT NULL DEFAULT 0,
  `actualizar` tinyint(1) NOT NULL DEFAULT 0,
  `eliminar` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_rol`, `modulo`, `crear`, `leer`, `actualizar`, `eliminar`) VALUES
(0, 1, 0, 0, 0, 0),
(0, 2, 1, 1, 0, 0),
(0, 3, 0, 0, 0, 0),
(0, 4, 0, 1, 0, 0),
(0, 5, 0, 0, 0, 0),
(0, 6, 0, 0, 0, 0),
(0, 7, 0, 0, 0, 0),
(0, 8, 0, 0, 0, 0),
(0, 9, 0, 0, 0, 0),
(1, 1, 1, 1, 1, 0),
(1, 2, 1, 1, 1, 1),
(1, 3, 0, 1, 0, 0),
(1, 4, 0, 0, 0, 0),
(1, 5, 0, 0, 0, 0),
(1, 6, 0, 0, 0, 0),
(1, 7, 0, 0, 0, 0),
(1, 8, 0, 0, 0, 0),
(1, 9, 0, 0, 0, 0),
(2, 1, 1, 1, 1, 1),
(2, 2, 1, 1, 1, 1),
(2, 3, 1, 1, 1, 1),
(2, 4, 1, 1, 1, 1),
(2, 5, 1, 1, 1, 1),
(2, 6, 1, 1, 1, 1),
(2, 7, 1, 1, 1, 1),
(2, 8, 1, 1, 0, 0),
(2, 9, 0, 1, 0, 0);

--
-- Disparadores `permisos`
--
DELIMITER $$
CREATE TRIGGER `after_permiso_update` AFTER UPDATE ON `permisos` FOR EACH ROW BEGIN
 IF @cambios IS NULL THEN
        SET @cambios = '';
    END IF;
    IF @filas_afectadas IS NULL THEN
        SET @filas_afectadas = 0;
    END IF;
    IF @total_modulos IS NULL THEN
        SET @total_modulos = (SELECT COUNT(*) FROM modulos);
    END IF;
	IF OLD.crear != NEW.crear THEN
    	SET @cambios = CONCAT(@cambios, ' Modulo ', (SELECT nombre FROM modulos WHERE id_modulo = OLD.modulo), ' - crear: ', IF(OLD.crear = 0, 'No', 'Si'), ' -> ', 			IF(NEW.crear = 0, 'No', 'Si'), '; ');
    END IF;
    IF OLD.leer != NEW.leer THEN
    	SET @cambios = CONCAT(@cambios, ' Modulo ', (SELECT nombre FROM modulos WHERE id_modulo = OLD.modulo), ' - leer: ', IF(OLD.leer = 0, 'No', 'Si'), ' -> ', IF(NEW.leer = 0, 'No', 'Si'), '; ');
    END IF;
    IF OLD.actualizar != NEW.actualizar THEN
    	SET @cambios = CONCAT(@cambios, ' Modulo ', (SELECT nombre FROM modulos WHERE id_modulo = OLD.modulo), ' - actualizar: ', IF(OLD.actualizar = 0, 'No', 'Si'), ' -> ', 			IF(NEW.actualizar = 0, 'No', 'Si'), '; ');
    END IF;
    IF OLD.eliminar != NEW.eliminar THEN
    	SET @cambios = CONCAT(@cambios, ' Modulo ', (SELECT nombre FROM modulos WHERE id_modulo = OLD.modulo), ' - eliminar: ', IF(OLD.eliminar = 0, 'No', 'Si'), ' -> ', 			IF(NEW.eliminar = 0, 'No', 'Si'), '; ');
    END IF;
    SET @filas_afectadas = @filas_afectadas + 1; 
    IF @filas_afectadas = @total_modulos THEN
        IF @cambios != '' THEN
            INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
            VALUES ('Modificar', 'Roles', @usuario_actual, @nombre_rol, @cambios);
            SET @cambios = NULL;
        	SET @nombre_rol = NULL;
            SET @filas_afectadas = NULL;
            SET @total_modulos = NULL;
        END IF;        
    END IF;
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
-- Estructura de tabla para la tabla `reset`
--

CREATE TABLE `reset` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expira` datetime NOT NULL
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(50) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(0, 'Atleta'),
(1, 'entrenador'),
(2, 'Superusuario');

--
-- Disparadores `roles`
--
DELIMITER $$
CREATE TRIGGER `after_rol_create` AFTER INSERT ON `roles` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Incluir', 'Roles', @usuario_actual, NEW.nombre, CONCAT('Se agregó el Rol: ', NEW.nombre));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_rol_delete` AFTER DELETE ON `roles` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Eliminar', 'Roles', @usuario_actual, OLD.nombre, CONCAT('Se eliminó el Rol: ', OLD.nombre));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_rol_update` AFTER UPDATE ON `roles` FOR EACH ROW BEGIN
	IF @cambios IS NULL THEN
        SET @cambios = '';
    END IF;
    SET @nombre_rol = OLD.nombre;
    IF OLD.nombre != NEW.nombre THEN
        SET @cambios = CONCAT(@cambios, 'Nombre de rol cambiado de "', OLD.nombre, '" a "', NEW.nombre, '"; ');
    END IF;
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
(9, 'U20', 15, 20);

--
-- Disparadores `subs`
--
DELIMITER $$
CREATE TRIGGER `after_subs_delete` AFTER DELETE ON `subs` FOR EACH ROW BEGIN
        INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
        VALUES (
            'Eliminar', 
            'Subs', 
            @usuario_actual, 
            OLD.nombre, 
            CONCAT('Se eliminó el sub: ', OLD.nombre)
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
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
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
(4, 'Atleta UPTAEB', 2),
(8, 'Obrero', 0);

--
-- Disparadores `tipo_atleta`
--
DELIMITER $$
CREATE TRIGGER `after_tipo_atleta_create` AFTER INSERT ON `tipo_atleta` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Incluir', 'Tipo de Atleta', @usuario_actual, NEW.nombre_tipo_atleta, CONCAT('Se agregó el Tipo de Atleta: ', NEW.nombre_tipo_atleta));
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
(12, 'Sub13'),
(14, 'Sub23');

--
-- Disparadores `tipo_competencia`
--
DELIMITER $$
CREATE TRIGGER `after_tipo_competencia_delete` AFTER DELETE ON `tipo_competencia` FOR EACH ROW BEGIN
        INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
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
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Modificar', 'Tipo de Competencia', @usuario_actual, OLD.nombre, @cambios);
    SET @cambios = NULL;  
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `cedula` varchar(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `genero` varchar(30) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `lugar_nacimiento` varchar(50) NOT NULL,
  `estado_civil` varchar(50) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo_electronico` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`cedula`, `nombre`, `apellido`, `genero`, `fecha_nacimiento`, `lugar_nacimiento`, `estado_civil`, `telefono`, `correo_electronico`) VALUES
('22222222', 'Jugney', 'Vargas', 'Femenino', '2002-07-15', 'sdfdsfdfds', 'Casado', '04245681341', 'soykuuhaku@gmail.com'),
('26846371', 'Dieguito', 'Salazar', 'Masculino', '2000-05-16', 'Nostrum et eos dese', 'Soltero', '04664073259', 'ryvin@mailinator.com');

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `after_usuario_create` AFTER INSERT ON `usuarios` FOR EACH ROW BEGIN
    SET @nombre_usuario = NEW.nombre;
    SET @apellido_usuario = NEW.apellido;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_usuario_update` AFTER UPDATE ON `usuarios` FOR EACH ROW BEGIN
    SET @cambios = '';
    IF OLD.cedula != NEW.cedula THEN
        SET @cambios = CONCAT(@cambios, 'Cedula cambiada de "', OLD.cedula, '" a "', NEW.cedula, '"; ');
    END IF;
    IF OLD.nombre != NEW.nombre THEN
        SET @cambios = CONCAT(@cambios, 'Nombre cambiado de "', OLD.nombre, '" a "', NEW.nombre, '"; ');
    END IF;
    IF OLD.apellido != NEW.apellido THEN
        SET @cambios = CONCAT(@cambios, 'Apellido cambiado de "', OLD.apellido, '" a "', NEW.apellido, '"; ');
    END IF;
    IF OLD.genero != NEW.genero THEN
        SET @cambios = CONCAT(@cambios, 'Genero cambiado de "', OLD.genero, '" a "', NEW.genero, '"; ');
    END IF;
    IF OLD.fecha_nacimiento != NEW.fecha_nacimiento THEN
        SET @cambios = CONCAT(@cambios, 'Fecha de nacimiento cambiada de "', OLD.fecha_nacimiento, '" a "', NEW.fecha_nacimiento, '"; ');
    END IF;
    IF OLD.lugar_nacimiento != NEW.lugar_nacimiento THEN
        SET @cambios = CONCAT(@cambios, 'Lugar de nacimiento cambiado de "', OLD.lugar_nacimiento, '" a "', NEW.lugar_nacimiento, '"; ');
    END IF;
    IF OLD.estado_civil != NEW.estado_civil THEN
        SET @cambios = CONCAT(@cambios, 'Estado civil cambiado de "', OLD.estado_civil, '" a "', NEW.estado_civil, '"; ');
    END IF;
    IF OLD.telefono != NEW.telefono THEN
        SET @cambios = CONCAT(@cambios, 'Telefono cambiado de "', OLD.telefono, '" a "', NEW.telefono, '"; ');
    END IF;
    IF OLD.correo_electronico != NEW.correo_electronico THEN
        SET @cambios = CONCAT(@cambios, 'Correo electrónico cambiado de "', OLD.correo_electronico, '" a "', NEW.correo_electronico, '"; ');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE `usuarios_roles` (
  `id_usuario` varchar(10) NOT NULL,
  `id_rol` int(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`id_usuario`, `id_rol`, `password`, `token`) VALUES
('22222222', 2, '$2y$10$hsNGdgLpo4hJzbboYhNt9.HEmkJHGJ81RpX6r..Cd05ya0wNCUlJa', '0'),
('26846371', 0, '$2y$10$8SfB9eQknUxTiiEzY2Wb9urV3eLk1omrN647oUgawaG9mlPlTzMbu', '0');

--
-- Disparadores `usuarios_roles`
--
DELIMITER $$
CREATE TRIGGER `after_usuarios_roles_update` AFTER UPDATE ON `usuarios_roles` FOR EACH ROW BEGIN
    DECLARE cambios VARCHAR(255) DEFAULT ""; 
    IF OLD.id_rol != NEW.id_rol THEN
    SET
        cambios = CONCAT(
            cambios,
            'Rol del usuario cambiado de "',
            (SELECT nombre FROM roles WHERE id_rol = OLD.id_rol),
            '" a "',
            (SELECT nombre FROM roles WHERE id_rol = NEW.id_rol),
            '"; '
        ) ;
END IF ; IF OLD.password != NEW.password THEN
SET
    cambios = CONCAT(
        cambios,
        'Contraseña del usuario fue modificada.; '
    ) ;
END IF ; IF cambios != "" THEN
INSERT INTO bitacora(
    accion,
    modulo,
    id_usuario,
    usuario_modificado,
    detalles
)
VALUES(
    'Modificar',
    'Roles',
    @usuario_actual,
    OLD.id_usuario,
    cambios
) ;
END IF ;
SET
    cambios = NULL ;
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
-- Volcado de datos para la tabla `wada`
--

INSERT INTO `wada` (`id_atleta`, `inscrito`, `vencimiento`, `ultima_actualizacion`, `estado`) VALUES
('26846371', '2024-11-25', '2024-12-10', '2024-11-25', 1);

--
-- Disparadores `wada`
--
DELIMITER $$
CREATE TRIGGER `after_wada_create` AFTER INSERT ON `wada` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Incluir', 'WADA', @usuario_actual, NEW.id_atleta, CONCAT('Se agregó la WADA para el Atleta con ID: ', NEW.id_atleta));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_wada_delete` AFTER DELETE ON `wada` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
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
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado, detalles)
    VALUES ('Modificar', 'WADA', @usuario_actual, OLD.id_atleta, @cambios);
    SET @cambios = NULL;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura para la vista `lista_atletas`
--
DROP TABLE IF EXISTS `lista_atletas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_atletas`  AS SELECT `u`.`cedula` AS `cedula`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `u`.`genero` AS `genero`, `u`.`fecha_nacimiento` AS `fecha_nacimiento`, `u`.`lugar_nacimiento` AS `lugar_nacimiento`, `u`.`estado_civil` AS `estado_civil`, `u`.`telefono` AS `telefono`, `u`.`correo_electronico` AS `correo_electronico`, `a`.`tipo_atleta` AS `tipo_atleta`, `a`.`peso` AS `peso`, `a`.`altura` AS `altura`, `a`.`entrenador` AS `entrenador` FROM (`atleta` `a` join `usuarios` `u` on(`a`.`cedula` = `u`.`cedula`)) ORDER BY `u`.`cedula` DESC ;

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
  ADD KEY `tipo_atleta` (`tipo_atleta`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id_accion`),
  ADD KEY `id_usuario` (`id_usuario`);

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
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id_marca`),
  ADD KEY `id_atleta` (`id_atleta`);

--
-- Indices de la tabla `mensualidades`
--
ALTER TABLE `mensualidades`
  ADD PRIMARY KEY (`id_mensualidad`),
  ADD KEY `id_atleta` (`id_atleta`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulo`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_rol`,`modulo`),
  ADD KEY `permisos_ibfk_2` (`modulo`);

--
-- Indices de la tabla `reset`
--
ALTER TABLE `reset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cedula` (`cedula`),
  ADD KEY `email` (`email`);

--
-- Indices de la tabla `resultado_competencia`
--
ALTER TABLE `resultado_competencia`
  ADD PRIMARY KEY (`id_competencia`),
  ADD KEY `id_atleta` (`id_atleta`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

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
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cedula`),
  ADD UNIQUE KEY `correo_unico` (`correo_electronico`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `wada`
--
ALTER TABLE `wada`
  ADD UNIQUE KEY `id_atleta` (`id_atleta`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_accion` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=764;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `competencia`
--
ALTER TABLE `competencia`
  MODIFY `id_competencia` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id_marca` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensualidades`
--
ALTER TABLE `mensualidades`
  MODIFY `id_mensualidad` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `reset`
--
ALTER TABLE `reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `resultado_competencia`
--
ALTER TABLE `resultado_competencia`
  MODIFY `id_competencia` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `subs`
--
ALTER TABLE `subs`
  MODIFY `id_sub` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tipo_atleta`
--
ALTER TABLE `tipo_atleta`
  MODIFY `id_tipo_atleta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tipo_competencia`
--
ALTER TABLE `tipo_competencia`
  MODIFY `id_tipo_competencia` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  ADD CONSTRAINT `atleta_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `atleta_ibfk_2` FOREIGN KEY (`entrenador`) REFERENCES `entrenador` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `atleta_ibfk_3` FOREIGN KEY (`tipo_atleta`) REFERENCES `tipo_atleta` (`id_tipo_atleta`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`cedula`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `entrenador_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD CONSTRAINT `marcas_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensualidades`
--
ALTER TABLE `mensualidades`
  ADD CONSTRAINT `mensualidades_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`modulo`) REFERENCES `modulos` (`id_modulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reset`
--
ALTER TABLE `reset`
  ADD CONSTRAINT `reset_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reset_ibfk_2` FOREIGN KEY (`email`) REFERENCES `usuarios` (`correo_electronico`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `resultado_competencia`
--
ALTER TABLE `resultado_competencia`
  ADD CONSTRAINT `resultado_competencia_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `resultado_competencia_ibfk_2` FOREIGN KEY (`id_competencia`) REFERENCES `competencia` (`id_competencia`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `wada`
--
ALTER TABLE `wada`
  ADD CONSTRAINT `wada_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
