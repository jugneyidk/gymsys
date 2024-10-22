-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-10-2024 a las 06:51:59
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
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id_atleta`, `asistio`, `fecha`, `comentario`) VALUES
('9252463', 1, '2024-07-20', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atleta`
--

CREATE TABLE `atleta` (
  `cedula` varchar(10) NOT NULL,
  `entrenador` varchar(10) NOT NULL,
  `tipo_atleta` int(11) NOT NULL,
  `peso` decimal(6,2) NOT NULL,
  `altura` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `atleta`
--

INSERT INTO `atleta` (`cedula`, `entrenador`, `tipo_atleta`, `peso`, `altura`) VALUES
('23124144', '22222222', 1, 75.00, 24.00),
('24244444', '22222222', 1, 75.00, 24.00),
('2594894', '22222222', 1, 22.00, 1.00),
('3376883', '22222222', 1, 92.00, 60.00),
('42342344', '22222222', 1, 75.00, 24.00),
('6645684', '22222222', 0, 21.00, 61.00),
('66456842', '22222222', 0, 21.00, 61.00),
('664568422', '22222222', 0, 21.00, 61.00),
('6759472', '22222222', 0, 8.00, 70.00),
('6828158', '22222222', 1, 75.00, 24.00),
('68281580', '22222222', 1, 75.00, 24.00),
('68281581', '22222222', 1, 75.00, 24.00),
('682815811', '22222222', 1, 75.00, 24.00),
('68281582', '22222222', 1, 75.00, 24.00),
('9252463', '22222222', 0, 73.00, 100.00),
('99389012', '22222222', 0, 12.00, 223.00);

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
    INSERT INTO bitacora (id_usuario, accion, modulo, usuario_modificado)
    VALUES (@usuario_actual, 'Elminar', 'Atletas', OLD.cedula);
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
  `usuario_modificado` varchar(10) DEFAULT NULL,
  `detalles` varchar(500) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id_accion`, `id_usuario`, `accion`, `modulo`, `usuario_modificado`, `detalles`, `fecha`) VALUES
(3, '22222222', 'elimino', '', '28609560', NULL, '2024-07-20 04:00:00'),
(16, '22222222', 'Agregó', '', '42342344', NULL, '2024-07-20 04:00:00'),
(17, '22222222', 'Agregó', '', '3376883', NULL, '2024-07-20 04:00:00'),
(18, '22222222', 'Agregó un atleta', '', '6759472', NULL, '2024-07-20 04:00:00'),
(19, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(20, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(21, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(22, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(23, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(24, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(25, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(26, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(27, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(28, '22222222', 'Modificó atleta', '', NULL, NULL, '2024-08-18 04:00:00'),
(29, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(30, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(31, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(33, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-08-18 04:00:00'),
(35, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-10-11 04:00:00'),
(36, '22222222', 'Entró al modulo \'Atletas\'', '', NULL, NULL, '2024-10-11 04:00:00'),
(37, '22222222', 'Elminó al atleta', '', NULL, NULL, '2024-10-12 04:00:00'),
(38, '22222222', 'Elminó al atleta', '', '682815813', NULL, '2024-10-17 04:00:00'),
(39, '22222222', 'Incluir', 'Atletas', '26773645', 'Se agregó el Atleta: 26773645 - Junsey magra', '2024-10-17 04:00:00'),
(40, '22222222', 'Incluir', 'Atletas', '99389012', 'Se agregó el Atleta: 99389012 - pepito fulanito', '2024-10-18 04:00:00'),
(41, '22222222', 'Modificar', 'Atletas', '26773645', 'Nombre cambiado de \"Junsey\" a \"Jusdney\"; Apellido cambiado de \"magra\" a \"Maclovin\"; Estado civil cambiado de \"Soltero\" a \"Casado\"; Tipo de atleta cambiado de \"0\" a \"1\"; Peso cambiado de \"44.00\" a \"43.00\"; ', '2024-10-18 04:00:00'),
(42, '22222222', 'Modificar', 'Atletas', '26773645', 'Correo electrónico cambiado de \"jusney2331@gmail.com\" a \"jusney12331@gmail.com\"; Altura cambiado de \"200.00\" a \"222.00\"; ', '2024-10-18 04:00:00'),
(43, '22222222', 'Elminar', 'Atletas', '26773645', NULL, '2024-10-18 04:00:00'),
(44, '22222222', 'Modificar', 'Atletas', '682815811', 'Telefono cambiado de \"04436386697\" a \"04436386698\"; ', '2024-10-18 04:00:00'),
(45, '22222222', 'Modificar', 'Atletas', '682815811', 'Fecha de nacimiento cambiada de \"1996-03-04\" a \"1996-03-20\"; ', '2024-10-19 01:37:15'),
(46, '22222222', 'Incluir', 'Entrenador', '4412968', 'Se agregó el Atleta: 4412968 - Lorem nisi nobis id Ratione cupiditate e', '2024-10-20 23:44:42'),
(47, '22222222', 'Modificar', 'Entrenador', '22222222', 'Nombre cambiado de \"jugneys\" a \"Jugney\"; Apellido cambiado de \"dfdfdf\" a \"Vargas\"; ', '2024-10-21 00:09:05'),
(48, '22222222', 'Modificar', 'Entrenador', '22222222', 'Genero cambiado de \"Masculino\" a \"Femenino\"; Telefono cambiado de \"04245681343\" a \"04245681341\"; Grado de instrucción cambiado de \"dsffdfsdf\" a \"cacaca\"; ', '2024-10-21 00:09:47'),
(49, '22222222', 'Eliminar', 'Entrenadores', '4412968', 'Se eliminó el Entrenador con ID: 4412968', '2024-10-21 00:18:23'),
(50, '22222222', 'Incluir', 'Entrenadores', '2517624', 'Se agregó el Entrenador: 2517624 - Facilis officia quo  Illum sit nostrud d', '2024-10-21 03:59:27'),
(51, '22222222', 'Eliminar', 'Entrenadores', '2517624', 'Se eliminó el Entrenador con ID: 2517624', '2024-10-21 03:59:31'),
(52, '22222222', 'Incluir', 'Entrenadores', '4822255', 'Se agregó el Entrenador: 4822255 - Occaecat corrupti v Sed excepturi dolore', '2024-10-21 04:00:49'),
(53, '22222222', 'Eliminar', 'Entrenadores', '4822255', 'Se eliminó el Entrenador con ID: 4822255', '2024-10-21 04:00:52'),
(54, '22222222', 'Incluir', 'Entrenadores', '1797963', 'Se agregó el Entrenador: 1797963 - Doloribus pariatur  Dolores perspiciatis', '2024-10-21 04:04:12'),
(55, '22222222', 'Eliminar', 'Entrenadores', '1797963', NULL, '2024-10-21 04:04:14'),
(56, '22222222', 'Incluir', 'Entrenadores', '8131964', 'Se agregó el Entrenador: 8131964 - Et ipsum enim enim  Cupidatat occaecat e', '2024-10-21 04:05:53'),
(57, '22222222', 'Eliminar', 'Entrenadores', '8131964', NULL, '2024-10-21 04:05:56');

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
  `fecha_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
    INSERT INTO bitacora (accion, modulo, id_usuario, usuario_modificado)
    VALUES ('Eliminar', 'Entrenadores', @usuario_actual, OLD.cedula);
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
  `cobro` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensualidades`
--

INSERT INTO `mensualidades` (`id_mensualidad`, `id_atleta`, `fecha`, `tipo`, `monto`, `cobro`) VALUES
(3, '9252463', '2024-07-26', 0, 23.00, 0);

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
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_rol` int(50) NOT NULL,
  `modulo` int(50) NOT NULL,
  `crear` tinyint(4) NOT NULL,
  `leer` tinyint(4) NOT NULL,
  `actualizar` tinyint(4) NOT NULL,
  `eliminar` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_rol`, `modulo`, `crear`, `leer`, `actualizar`, `eliminar`) VALUES
(1, 1, 1, 1, 1, 0),
(1, 2, 1, 1, 0, 0),
(30, 1, 1, 1, 1, 1),
(30, 2, 1, 1, 1, 1),
(30, 3, 1, 1, 1, 1),
(30, 4, 0, 0, 0, 0),
(30, 5, 0, 1, 0, 0),
(30, 6, 1, 1, 0, 0),
(30, 7, 1, 1, 1, 1),
(30, 8, 0, 1, 0, 0),
(30, 9, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `cedula` varchar(10) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL
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
  `medalla_arranque` varchar(255) NOT NULL,
  `medalla_envion` varchar(255) NOT NULL,
  `medalla_total` int(5) NOT NULL,
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
(0, 'atleta'),
(1, 'entrenador'),
(30, 'rol prueba');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_competencia`
--

CREATE TABLE `tipo_competencia` (
  `id_tipo_competencia` int(5) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('22222222', 'Jugney', 'Vargas', 'Femenino', '2002-07-15', 'sdfdsfdfds', 'Soltero', '04245681341', 'dsfdsfd@gmail.com'),
('23124144', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('24244444', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('2517624', 'Facilis officia quo ', 'Illum sit nostrud d', 'Masculino', '1987-09-15', 'Nisi officiis explic', 'Casado', '04744313188', 'webyrajic@mailinator.com'),
('2594894', 'Repudiandae harum do', 'Voluptatem et labori', 'Masculino', '1992-09-17', 'Molestiae officia ad', 'Divorciado', '04844940895', 'sikylydig@mailinator.com'),
('28609560', 'jugney', 'vargas', 'Masculino', '2002-07-15', 'dsdj', 'Soltero', '04245681343', 'KJSHJSHKJH@GMAIL.COM'),
('3376883', 'Id voluptas rerum c', 'Velit in blanditiis ', 'Masculino', '2004-12-27', 'Maiores fugiat aut ', 'Casado', '04534055751', 'zazehoz@mailinator.com'),
('42342344', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('4412968', 'Lorem nisi nobis id', 'Ratione cupiditate e', 'Masculino', '2010-06-07', 'Accusamus do sed nat', 'Casado', '04193003130', 'tewuvijo@mailinator.com'),
('6645684', 'Est pariatur Nihil ', 'Non et non molestias', 'Femenino', '2003-01-13', 'Ex qui architecto to', 'Viudo', '04823255865', 'nudob@mailinator.com'),
('66456842', 'Est pariatur Nihil ', 'Non et non molestias', 'Femenino', '2003-01-13', 'Ex qui architecto to', 'Viudo', '04823255865', 'nudob@mailinator.com'),
('664568422', 'Est pariatur Nihil ', 'Non et non molestias', 'Femenino', '2003-01-13', 'Ex qui architecto to', 'Viudo', '04823255865', 'nudob@mailinator.com'),
('6759472', 'Minima adipisci anim', 'Non aliquam voluptat', 'Masculino', '1995-11-03', 'Sed deserunt quis as', 'Casado', '04418277535', 'pise@mailinator.com'),
('6828158', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('68281580', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('68281581', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('682815811', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-20', 'Ut quaerat eveniet ', 'Viudo', '04436386698', 'zuda@mailinator.com'),
('68281582', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('9252463', 'Reprehenderit fuga ', 'Sit impedit vero in', 'Masculino', '1989-03-13', 'Et accusantium maior', 'Viudo', '04559403067', 'lodujobyqa@mailinator.com'),
('99389012', 'pepito', 'fulanito', 'Masculino', '2000-02-08', 'maracay', 'Soltero', '04555555555', 'dieodollo12@ooglo.com');

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
('22222222', 30, '$2y$10$syf4uVv4j1iML9whitgx2.ylZwVlWhUHrA7zFhvMyP0qqpzD6yNWO', '0'),
('23124144', 0, '$2y$10$.U6xdgWQPqo4HJbgPmi.u.j.6XZzLH46HiGXdB5lIS19BATJIKaCa', '0'),
('24244444', 0, '$2y$10$AlnDESIrQ20GjFP2bL5G5eXc.FAXQsbITN.Z1VSxpOf2EtaIaU6Oq', '0'),
('2517624', 1, '$2y$10$FgILLleAxEHPjdia6vXFx.7EZ96T7utWeeCeAmgypvd0E4lx9Dww6', '0'),
('2594894', 0, '$2y$10$XWoALDkOSs/n2fT30oBlluAS9RPUNKozcntpT/Tk9b4zJvR.mYZCO', '0'),
('3376883', 0, '$2y$10$ltWDsUEwgZ94BjvHY7tHMO7oJM6bEzPqoyzeqhQDExl6.tfRR5MeG', '0'),
('42342344', 0, '$2y$10$oPNkW491S4A4p7dKf2ngSePU2L4oBz/iezYPFKXxNagC6hduogbZ.', '0'),
('4412968', 1, '$2y$10$de8lZBRdxcBCEjk4vpINsOJrSQxrzKTA8Owtz9gHhR/1Ma.4mxxwC', '0'),
('6645684', 0, '$2y$10$84Huw1bt0oXtZ8mnIeNtPu9D0Qt0zhYLMkocsA413vMWX5YxP2tii', '0'),
('66456842', 0, '$2y$10$wdnIzo5Js4PI8TInBAhL.ORc1siZNVKbbaNg9ir99GZ3fuR24cTou', '0'),
('664568422', 0, '$2y$10$P6vxsbx8q8ITDfhHqu9VaOU310ZTDjdPoBFIn5AKZtMiMIMB91adS', '0'),
('6759472', 0, '$2y$10$OulaL.OOEq7fwxJmig51rugXt4UHPPywK/R5oMclKs7jvytCSYrUu', '0'),
('6828158', 0, '$2y$10$md4PwPLFMXm6RQf8gine2OLunXu4Y/l75cAik4GrKNk.FDAMqSm.G', '0'),
('68281580', 0, '$2y$10$hvCMlvd9BEFiyXbdghtz.eikWnVVyv.0XRGYLPIX/dlwhOAd6oia6', '0'),
('68281581', 0, '$2y$10$QxgU9C2kdQnmURdS.YhOkORUbk33RyNPXBS6MCjfgOCBpF1fY6yY2', '0'),
('682815811', 0, '$2y$10$JQ4ytSeERzWg5630.qYZRuDLmRBM219/ABuAAAoJve52TsELtR1tW', '0'),
('68281582', 0, '$2y$10$IegZMzWD3iDEV7Zxu5Z8k.rX.pg1ib8jPSd2k57kz4QXzar8XiuWO', '0'),
('9252463', 0, '$2y$10$HKTnPY5Ndj4ljvylWoszouAsfl8RyRll5pSpZOUmQI7Wb9i.9SibO', '0'),
('99389012', 0, '$2y$10$BRdgyLi2EP6cQlyfq4eeXugzCaY0QucArbuz2b0MVjSS9dMSOG0yW', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wada`
--

CREATE TABLE `wada` (
  `id_atleta` varchar(10) NOT NULL,
  `inscrito` date NOT NULL,
  `vencimiento` date NOT NULL,
  `ultima_actualizacion` date NOT NULL,
  `estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `wada`
--

INSERT INTO `wada` (`id_atleta`, `inscrito`, `vencimiento`, `ultima_actualizacion`, `estado`) VALUES
('68281580', '2024-10-23', '2024-11-28', '2024-11-08', 1),
('9252463', '2024-07-20', '2024-08-10', '2024-07-20', 1);

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
  ADD KEY `id_atleta` (`id_atleta`);

--
-- Indices de la tabla `atleta`
--
ALTER TABLE `atleta`
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `entrenador` (`entrenador`);

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
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `modulo` (`modulo`);

--
-- Indices de la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD UNIQUE KEY `id_atleta` (`id_atleta`);

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
-- Indices de la tabla `tipo_competencia`
--
ALTER TABLE `tipo_competencia`
  ADD PRIMARY KEY (`id_tipo_competencia`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cedula`);

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
  MODIFY `id_accion` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `competencia`
--
ALTER TABLE `competencia`
  MODIFY `id_competencia` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id_marca` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensualidades`
--
ALTER TABLE `mensualidades`
  MODIFY `id_mensualidad` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `resultado_competencia`
--
ALTER TABLE `resultado_competencia`
  MODIFY `id_competencia` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `subs`
--
ALTER TABLE `subs`
  MODIFY `id_sub` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_competencia`
--
ALTER TABLE `tipo_competencia`
  MODIFY `id_tipo_competencia` int(5) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `atleta_ibfk_2` FOREIGN KEY (`entrenador`) REFERENCES `entrenador` (`cedula`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `entrenador_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`) ON UPDATE CASCADE;

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
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`modulo`) REFERENCES `modulos` (`id_modulo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD CONSTRAINT `representantes_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `wada`
--
ALTER TABLE `wada`
  ADD CONSTRAINT `wada_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
