-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2024 a las 06:23:30
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
('23124144', 0, '2024-10-27', ''),
('23124144', 0, '2024-10-28', ''),
('24244444', 0, '2024-10-27', ''),
('24244444', 0, '2024-10-28', ''),
('42342344', 0, '2024-10-27', ''),
('42342344', 0, '2024-10-28', ''),
('66456842', 0, '2024-10-27', ''),
('66456842', 0, '2024-10-28', ''),
('664568422', 0, '2024-10-27', ''),
('664568422', 0, '2024-10-28', 'lerololelole'),
('68281580', 0, '2024-10-27', ''),
('68281580', 0, '2024-10-28', ''),
('68281581', 0, '2024-10-27', ''),
('68281581', 1, '2024-10-28', ''),
('682815811', 1, '2024-10-27', ''),
('682815811', 0, '2024-10-28', ''),
('68281582', 1, '2024-10-27', ''),
('68281582', 1, '2024-10-28', ''),
('99389012', 0, '2024-10-27', ''),
('99389012', 0, '2024-10-28', 'jejeje');

--
-- Disparadores `asistencias`
--
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
('1328547', '22222222', 0, 2.00, 96.00, NULL),
('23124144', '22222222', 1, 75.00, 24.00, NULL),
('24244444', '22222222', 1, 75.00, 24.00, NULL),
('2594894', '22222222', 1, 22.00, 1.00, NULL),
('3376883', '22222222', 1, 92.00, 60.00, NULL),
('42342344', '22222222', 1, 75.00, 24.00, NULL),
('66456842', '22222222', 0, 21.00, 61.00, NULL),
('664568422', '22222222', 0, 21.00, 61.00, NULL),
('6759472', '22222222', 0, 8.00, 70.00, NULL),
('6828158', '22222222', 1, 75.00, 24.00, NULL),
('68281580', '22222222', 1, 75.00, 24.00, NULL),
('68281581', '22222222', 1, 75.00, 24.00, NULL),
('682815811', '22222222', 1, 75.00, 24.00, NULL),
('68281582', '22222222', 1, 75.00, 24.00, NULL),
('9252463', '22222222', 0, 73.00, 100.00, NULL),
('99389012', '22222222', 0, 12.00, 223.00, NULL);

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
  `usuario_modificado` varchar(10) DEFAULT NULL,
  `detalles` varchar(2000) DEFAULT NULL,
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
(57, '22222222', 'Eliminar', 'Entrenadores', '8131964', NULL, '2024-10-21 04:05:56'),
(58, '22222222', 'Incluir', 'Rol', 'washuwa', 'Se agregó el Rol: washuwa', '2024-10-25 20:42:37'),
(59, '22222222', 'Eliminar', 'Roles', 'washuwa', 'Se eliminó el Rol: washuwa', '2024-10-25 20:45:24'),
(60, '22222222', 'Modificar', 'Roles', 'rol prueba', '', '2024-10-25 21:27:56'),
(61, '22222222', 'Modificar', 'Roles', NULL, NULL, '2024-10-25 21:27:56'),
(62, '22222222', 'Modificar', 'Roles', NULL, NULL, '2024-10-25 21:27:56'),
(63, '22222222', 'Modificar', 'Roles', NULL, NULL, '2024-10-25 21:27:56'),
(64, '22222222', 'Modificar', 'Roles', NULL, NULL, '2024-10-25 21:27:56'),
(65, '22222222', 'Modificar', 'Roles', NULL, NULL, '2024-10-25 21:27:56'),
(66, '22222222', 'Modificar', 'Roles', NULL, NULL, '2024-10-25 21:27:56'),
(69, '22222222', 'Modificar', 'Roles', NULL, ' Modulo rolespermisos - crear: Si cambió a No; ', '2024-10-25 21:37:09'),
(70, '22222222', 'Modificar', 'Roles', NULL, ' Modulo asistencias - crear: No cambió a Si; ', '2024-10-25 21:37:09'),
(71, '22222222', 'Modificar', 'Roles', NULL, ' Modulo eventos - crear: No cambió a Si; ', '2024-10-25 21:37:09'),
(72, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo rolespermisos - crear: No -> Si;  Modulo asistencias - crear: Si -> No;  Modulo eventos - crear: Si -> No;  Modulo mensualidad - crear: Si -> No; ', '2024-10-25 21:40:07'),
(73, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo entrenadores - crear: Si -> No;  Modulo atletas - crear: Si -> No;  Modulo rolespermisos - crear: Si -> No;  Modulo asistencias - crear: No -> Si;  Modulo eventos - crear: No -> Si; ', '2024-10-25 21:40:41'),
(74, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo entrenadores - crear: No -> Si;  Modulo atletas - crear: No -> Si;  Modulo rolespermisos - crear: No -> Si;  Modulo asistencias - leer: No -> Si;  Modulo asistencias - actualizar: No -> Si;  Modulo asistencias - eliminar: No -> Si;  Modulo eventos - actualizar: No -> Si;  Modulo eventos - eliminar: No -> Si;  Modulo mensualidad - crear: No -> Si;  Modulo mensualidad - actualizar: No -> Si;  Modulo mensualidad - eliminar: No -> Si; ', '2024-10-25 21:44:07'),
(75, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo wada - crear: Si -> No;  Modulo reportes - crear: Si -> No; ', '2024-10-25 21:46:06'),
(76, '22222222', 'Modificar', 'Roles', NULL, ' Modulo bitacora - leer: No -> Si; ', '2024-10-25 21:47:33'),
(77, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo mensualidad - crear: Si -> No; ', '2024-10-25 21:50:29'),
(78, '22222222', 'Modificar', 'Roles', NULL, ' Modulo atletas - crear: Si -> No; ', '2024-10-25 21:53:24'),
(79, '22222222', 'Modificar', 'Roles', NULL, ' Modulo rolespermisos - crear: Si -> No; ', '2024-10-25 21:53:24'),
(80, '22222222', 'Modificar', 'Roles', NULL, ' Modulo asistencias - crear: Si -> No; ', '2024-10-25 21:53:24'),
(81, '22222222', 'Modificar', 'Roles', NULL, ' Modulo eventos - crear: Si -> No;  Modulo eventos - leer: Si -> No; ', '2024-10-25 21:53:24'),
(82, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo eventos - crear: No -> Si;  Modulo eventos - leer: No -> Si; ', '2024-10-25 22:22:40'),
(83, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo atletas - crear: No -> Si;  Modulo rolespermisos - crear: No -> Si;  Modulo asistencias - crear: No -> Si; ', '2024-10-25 22:24:45'),
(84, '22222222', 'Modificar', 'Roles', NULL, ' Modulo mensualidad - crear: No -> Si; ', '2024-10-25 22:24:45'),
(85, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo mensualidad - crear: Si -> No;  Modulo mensualidad - leer: Si -> No;  Modulo wada - crear: Si -> No;  Modulo wada - leer: Si -> No;  Modulo reportes - crear: Si -> No;  Modulo reportes - leer: Si -> No;  Modulo bitacora - crear: Si -> No;  Modulo bitacora - leer: Si -> No; ', '2024-10-25 22:39:57'),
(86, '22222222', 'Modificar', 'Roles', 'rol prueba', 'Nombre de rol cambiado de \"rol prueba\" a \"rol pruebas\";  Modulo mensualidad - crear: No -> Si;  Modulo mensualidad - leer: No -> Si;  Modulo wada - crear: No -> Si;  Modulo wada - leer: No -> Si;  Modulo reportes - crear: No -> Si;  Modulo reportes - leer: No -> Si;  Modulo bitacora - crear: No -> Si;  Modulo bitacora - leer: No -> Si; ', '2024-10-25 22:40:36'),
(87, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo entrenadores - crear: Si -> No;  Modulo entrenadores - leer: Si -> No;  Modulo atletas - crear: Si -> No;  Modulo atletas - leer: Si -> No;  Modulo asistencias - crear: Si -> No;  Modulo asistencias - leer: Si -> No;  Modulo eventos - crear: Si -> No;  Modulo eventos - leer: Si -> No;  Modulo mensualidad - crear: Si -> No;  Modulo mensualidad - leer: Si -> No;  Modulo wada - crear: Si -> No;  Modulo wada - leer: Si -> No;  Modulo reportes - crear: Si -> No;  Modulo reportes - leer: Si ->', '2024-10-25 22:41:13'),
(88, '22222222', 'Modificar', 'Roles', 'rol prueba', 'Nombre de rol cambiado de \"rol pruebas\" a \"rol prueba\";  Modulo entrenadores - crear: No -> Si;  Modulo entrenadores - leer: No -> Si;  Modulo entrenadores - eliminar: Si -> No;  Modulo atletas - crear: No -> Si;  Modulo atletas - leer: No -> Si;  Modulo atletas - eliminar: Si -> No;  Modulo rolespermisos - eliminar: Si -> No;  Modulo asistencias - crear: No -> Si;  Modulo asistencias - leer: No -> Si;  Modulo asistencias - eliminar: Si -> No;  Modulo eventos - crear: No -> Si;  Modulo eventos - leer: No -> Si;  Modulo eventos - eliminar: Si -> No;  Modulo mensualidad - crear: No -> Si;  Modulo mensualidad - leer: No -> Si;  Modulo mensualidad - eliminar: Si -> No;  Modulo wada - crear: No -> Si;  Modulo wada - leer: No -> Si;  Modulo wada - eliminar: Si -> No;  Modulo reportes - crear: No -> Si;  Modulo reportes - leer: No -> Si;  Modulo reportes - eliminar: Si -> No;  Modulo bitacora - crear: No -> Si;  Modulo bitacora - leer: No -> Si;  Modulo bitacora - eliminar: Si -> No; ', '2024-10-25 22:43:43'),
(89, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo entrenadores - eliminar: No -> Si;  Modulo atletas - eliminar: No -> Si;  Modulo rolespermisos - eliminar: No -> Si;  Modulo asistencias - eliminar: No -> Si;  Modulo eventos - eliminar: No -> Si;  Modulo mensualidad - eliminar: No -> Si;  Modulo wada - eliminar: No -> Si;  Modulo reportes - eliminar: No -> Si;  Modulo bitacora - eliminar: No -> Si; ', '2024-10-25 22:49:33'),
(90, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo mensualidad - eliminar: Si -> No;  Modulo wada - actualizar: Si -> No;  Modulo reportes - leer: Si -> No;  Modulo bitacora - crear: Si -> No; ', '2024-10-27 15:40:03'),
(91, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo mensualidad - eliminar: No -> Si;  Modulo wada - actualizar: No -> Si;  Modulo reportes - leer: No -> Si;  Modulo bitacora - crear: No -> Si; ', '2024-10-27 15:40:40'),
(92, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo eventos - crear: Si -> No;  Modulo eventos - leer: Si -> No;  Modulo eventos - actualizar: Si -> No;  Modulo eventos - eliminar: Si -> No; ', '2024-10-27 19:38:34'),
(93, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo eventos - crear: No -> Si;  Modulo eventos - leer: No -> Si;  Modulo eventos - actualizar: No -> Si;  Modulo eventos - eliminar: No -> Si; ', '2024-10-27 19:39:13'),
(94, '22222222', 'Incluir', 'Asistencias', '2024-10-27', 'Se agregó asistencias para la fecha: 2024-10-27', '2024-10-27 23:30:27'),
(97, '22222222', 'Incluir', 'Asistencias', '2024-10-28', 'Se agregó asistencias para la fecha: 2024-10-28', '2024-10-28 03:11:57'),
(98, '22222222', 'Modificar', 'Asistencias', '2024-10-28', 'Cambio la asistencia de 664568422: Si -> No; ', '2024-10-28 03:43:08'),
(99, '22222222', 'Modificar', 'Asistencias', '2024-10-28', 'Cambio la asistencia de 99389012: No -> Si; ', '2024-10-28 03:43:08'),
(100, '22222222', 'Modificar', 'Asistencias', '2024-10-28', 'Cambio la asistencia de 664568422: No -> Si; ', '2024-10-28 03:49:19'),
(101, '22222222', 'Modificar', 'Asistencias', '2024-10-28', 'Cambio la asistencia de 664568422: Si -> No; Cambio el comentario de 664568422:  -> lerololelole; ', '2024-10-28 03:53:05'),
(102, '22222222', 'Modificar', 'Asistencias', '2024-10-28', 'Cambio la asistencia de 99389012: Si -> No; Cambio el comentario de 99389012:  -> jejeje; ', '2024-10-28 03:53:05'),
(103, '22222222', 'Incluir', 'Atletas', '5464325', 'Se agregó el Atleta: 5464325 - Libero harum dolorem Facilis ullamco proi', '2024-11-01 02:59:14'),
(104, '22222222', 'Elminar', 'Atletas', '5464325', NULL, '2024-11-01 02:59:30'),
(105, '22222222', 'Incluir', 'Atletas', '7971287', 'Se agregó el Atleta: 7971287 - Et libero omnis cons Nesciunt perferendi', '2024-11-01 03:01:21'),
(106, '22222222', 'Elminar', 'Atletas', '7971287', NULL, '2024-11-01 03:01:49'),
(107, '22222222', 'Incluir', 'Atletas', '1328547', 'Se agregó el Atleta: 1328547 - Pariatur Cumque est Do eaque duis vel la', '2024-11-01 03:04:35'),
(108, '22222222', 'Modificar', 'Atletas', '1328547', 'Telefono cambiado de \"04414890128\" a \"04414890127\"; ', '2024-11-01 03:04:47'),
(109, '22222222', 'Elminar', 'Atletas', '6645684', NULL, '2024-11-01 03:05:39'),
(110, '22222222', 'Modificar', 'Atletas', '682815811', 'Telefono cambiado de \"04436386698\" a \"04436386697\"; ', '2024-11-01 03:05:45'),
(111, '22222222', 'Incluir', 'Entrenadores', '5752655', 'Se agregó el Entrenador: 5752655 - Quam voluptatem Rep Ullam magnam dolorum', '2024-11-01 03:44:13'),
(112, '22222222', 'Eliminar', 'Entrenadores', '5752655', NULL, '2024-11-01 03:44:51'),
(113, '22222222', 'Incluir', 'Entrenadores', '8182499', 'Se agregó el Entrenador: 8182499 - Vitae amet blanditi Vitae ut pariatur B', '2024-11-01 03:44:56'),
(114, '22222222', 'Eliminar', 'Entrenadores', '8182499', NULL, '2024-11-01 03:45:05'),
(115, '22222222', 'Incluir', 'Entrenadores', '2314415', 'Se agregó el Entrenador: 2314415 - Velit sint fugiat  Et facere eu eos ex', '2024-11-01 03:47:37'),
(116, '22222222', 'Modificar', 'Entrenadores', '2314415', 'Telefono cambiado de \"04617221960\" a \"04617221961\"; ', '2024-11-01 03:47:45'),
(117, '22222222', 'Eliminar', 'Entrenadores', '2314415', 'Se eliminó el entrenador: 2314415', '2024-11-01 03:54:43'),
(118, '22222222', 'Incluir', 'Roles', 'cacapitola', 'Se agregó el Rol: cacapitola', '2024-11-01 06:13:44'),
(119, '22222222', 'Incluir', 'Entrenadores', '5826525', 'Se agregó el Entrenador: 5826525 - Ullamco nobis veniam Neque vero occaecat ', '2024-11-04 06:00:38'),
(120, '22222222', 'Eliminar', 'Entrenadores', '5826525', 'Se eliminó el entrenador: 5826525', '2024-11-04 06:30:55'),
(121, '22222222', 'Incluir', 'Entrenadores', '1572979', 'Se agregó el Entrenador: 1572979 - Est quis sed volupt Autem nemo magni ape', '2024-11-04 07:22:09'),
(122, '22222222', 'Incluir', 'Entrenadores', '2871843', 'Se agregó el Entrenador: 2871843 - Dolore deserunt moll Esse tenetur invento', '2024-11-04 07:28:23'),
(123, '22222222', 'Eliminar', 'Entrenadores', '2871843', 'Se eliminó el entrenador: 2871843', '2024-11-04 07:33:55'),
(124, '22222222', 'Eliminar', 'Entrenadores', '1572979', 'Se eliminó el entrenador: 1572979', '2024-11-04 07:33:58'),
(125, '22222222', 'Incluir', 'Entrenadores', '8566948', 'Se agregó el Entrenador: 8566948 - Exercitation ipsam r Laboris ut pariatur', '2024-11-04 07:35:02'),
(126, '22222222', 'Incluir', 'Entrenadores', '7752234', 'Se agregó el Entrenador: 7752234 - Exercitation ipsam r Laboris ut pariatur', '2024-11-04 20:30:57'),
(127, '22222222', 'Eliminar', 'Entrenadores', '7752234', 'Se eliminó el entrenador: 7752234', '2024-11-04 21:28:36'),
(128, '22222222', 'Incluir', 'Entrenadores', '222222222', 'Se agregó el Entrenador: 222222222 - Exercitation ipsam Laboris ut pariatur', '2024-11-04 22:22:06'),
(129, '22222222', 'Eliminar', 'Entrenadores', '222222222', 'Se eliminó el entrenador: 222222222', '2024-11-04 22:22:22'),
(130, '22222222', 'Incluir', 'Entrenadores', '2222223', 'Se agregó el Entrenador: 2222223 - Ut quis qui veritati Ex quam sed alias ea', '2024-11-04 22:27:23'),
(131, '22222222', 'Eliminar', 'Entrenadores', '2222223', 'Se eliminó el entrenador: 2222223', '2024-11-04 22:33:44'),
(132, '22222222', 'Incluir', 'Entrenadores', '9968472', 'Se agregó el Entrenador: 9968472 - Ipsa non inventore  Veniam esse irure ', '2024-11-04 22:34:46'),
(133, '22222222', 'Eliminar', 'Entrenadores', '8566948', 'Se eliminó el entrenador: 8566948', '2024-11-04 22:34:56'),
(134, '22222222', 'Incluir', 'Entrenadores', '7712442', 'Se agregó el Entrenador: 7712442 - Laudantium deserunt Sit ducimus ab qui', '2024-11-04 22:42:39'),
(135, '22222222', 'Eliminar', 'Entrenadores', '7712442', 'Se eliminó el entrenador: 7712442', '2024-11-04 22:44:31'),
(136, '22222222', 'Incluir', 'Entrenadores', '6252848', 'Se agregó el Entrenador: 6252848 - Aut sunt maxime face Eveniet illum sed ', '2024-11-04 22:48:36'),
(137, '22222222', 'Eliminar', 'Entrenadores', '6252848', 'Se eliminó el entrenador: 6252848', '2024-11-04 22:49:55'),
(138, '22222222', 'Eliminar', 'Entrenadores', '9968472', 'Se eliminó el entrenador: 9968472', '2024-11-04 22:50:02'),
(139, '22222222', 'Incluir', 'Entrenadores', '8578689', 'Se agregó el Entrenador: 8578689 - Ipsa non modi eum p Beatae perferendis v', '2024-11-04 23:17:43'),
(140, '22222222', 'Incluir', 'Entrenadores', '222222223', 'Se agregó el Entrenador: 222222223 - Exercitation ipsam Laboris ut pariatur', '2024-11-04 23:20:56'),
(141, '22222222', 'Modificar', 'Entrenadores', '8578689', '', '2024-11-05 00:35:20'),
(142, '22222222', 'Modificar', 'Entrenadores', '8578689', '', '2024-11-05 00:36:41'),
(143, '22222222', 'Modificar', 'Entrenadores', '8578689', '', '2024-11-05 00:37:43'),
(144, '22222222', 'Modificar', 'Entrenadores', '8578689', 'Estado civil cambiado de \"Casado\" a \"Soltero\"; ', '2024-11-05 00:38:14'),
(145, '22222222', 'Modificar', 'Entrenadores', '8578689', '', '2024-11-05 00:38:29'),
(146, '22222222', 'Modificar', 'Entrenadores', '8578689', '', '2024-11-05 00:40:13'),
(147, '22222222', 'Modificar', 'Entrenadores', '8578689', '', '2024-11-05 00:44:31'),
(148, '22222222', 'Modificar', 'Entrenadores', '222222223', 'Telefono cambiado de \"04244034516\" a \"04244034515\"; ', '2024-11-05 01:49:14'),
(149, '22222222', 'Modificar', 'Entrenadores', '222222223', '', '2024-11-05 02:57:59'),
(150, '22222222', 'Incluir', 'Entrenadores', '2313657', 'Se agregó el Entrenador: 2313657 - Est in minim cupidit Aut dolore velit qui', '2024-11-05 03:06:38'),
(151, '22222222', 'Incluir', 'Entrenadores', '8676719', 'Se agregó el Entrenador: 8676719 - Minim dolores molest Veniam eos explicab', '2024-11-05 03:29:40'),
(152, '22222222', 'Modificar', 'Entrenadores', '222222223', '', '2024-11-07 02:36:39'),
(153, '22222222', 'Modificar', 'Entrenadores', '22222222', 'Estado civil cambiado de \"Soltero\" a \"Casado\"; ', '2024-11-07 02:38:20'),
(154, '22222222', 'Modificar', 'Entrenadores', '222222223', '', '2024-11-08 02:19:40'),
(155, '22222222', 'Incluir', 'Entrenadores', '4765884', 'Se agregó el Entrenador: 4765884 - Aut dolores nihil vo Voluptate expedita e', '2024-11-08 02:22:59'),
(156, '22222222', 'Eliminar', 'Entrenadores', '222222223', 'Se eliminó el entrenador: 222222223', '2024-11-08 02:36:42'),
(157, '22222222', 'Incluir', 'Roles', 'cacala', 'Se agregó el Rol: cacala', '2024-11-08 02:47:05'),
(158, '22222222', 'Modificar', 'Roles', 'cacala', ' Modulo bitacora - leer: Si -> No; ', '2024-11-08 02:47:08'),
(159, '22222222', 'Modificar', 'Roles', 'cacala', ' Modulo mensualidad - actualizar: No -> Si;  Modulo mensualidad - eliminar: No -> Si;  Modulo wada - eliminar: No -> Si;  Modulo reportes - eliminar: No -> Si; ', '2024-11-08 02:47:14'),
(160, '22222222', 'Incluir', 'Roles', 'misiwasa', 'Se agregó el Rol: misiwasa', '2024-11-08 03:05:16'),
(161, '22222222', 'Modificar', 'Roles', 'misiwasa', 'Nombre de rol cambiado de \"misiwasa\" a \"misiwasa2\"; ', '2024-11-08 03:10:55'),
(162, '22222222', 'Modificar', 'Roles', 'misiwasa2', 'Nombre de rol cambiado de \"misiwasa2\" a \"misiwasa244\"; ', '2024-11-08 03:11:40'),
(163, '22222222', 'Modificar', 'Roles', 'misiwasa24', 'Nombre de rol cambiado de \"misiwasa244\" a \"misiwasa\"; ', '2024-11-08 03:13:35'),
(164, '22222222', 'Eliminar', 'Roles', 'misiwasa', 'Se eliminó el Rol: misiwasa', '2024-11-08 03:24:19'),
(165, '22222222', 'Eliminar', 'Roles', 'cacala', 'Se eliminó el Rol: cacala', '2024-11-08 03:26:17'),
(166, '22222222', 'Eliminar', 'Roles', 'cacapitola', 'Se eliminó el Rol: cacapitola', '2024-11-08 03:26:26'),
(167, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo bitacora - crear: Si -> No;  Modulo bitacora - actualizar: Si -> No;  Modulo bitacora - eliminar: Si -> No; ', '2024-11-08 03:49:06'),
(168, '22222222', 'Incluir', 'Roles', 'mamanadas', 'Se agregó el Rol: mamanadas', '2024-11-08 03:54:04'),
(169, '22222222', 'Eliminar', 'Roles', 'mamanadas', 'Se eliminó el Rol: mamanadas', '2024-11-08 03:54:11'),
(170, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo entrenadores - crear: Si -> No;  Modulo entrenadores - leer: Si -> No;  Modulo entrenadores - actualizar: Si -> No;  Modulo entrenadores - eliminar: Si -> No;  Modulo atletas - crear: Si -> No;  Modulo atletas - leer: Si -> No;  Modulo atletas - actualizar: Si -> No;  Modulo atletas - eliminar: Si -> No;  Modulo rolespermisos - crear: Si -> No;  Modulo rolespermisos - leer: Si -> No;  Modulo rolespermisos - actualizar: Si -> No;  Modulo rolespermisos - eliminar: Si -> No;  Modulo asistencias - crear: Si -> No;  Modulo asistencias - leer: Si -> No;  Modulo asistencias - actualizar: Si -> No;  Modulo asistencias - eliminar: Si -> No;  Modulo eventos - crear: Si -> No;  Modulo eventos - leer: Si -> No;  Modulo eventos - actualizar: Si -> No;  Modulo eventos - eliminar: Si -> No;  Modulo mensualidad - crear: Si -> No;  Modulo mensualidad - leer: Si -> No;  Modulo mensualidad - actualizar: Si -> No;  Modulo mensualidad - eliminar: Si -> No;  Modulo wada - crear: Si -> No;  Modulo wada - leer: Si -> No;  Modulo wada - actualizar: Si -> No;  Modulo wada - eliminar: Si -> No;  Modulo reportes - crear: Si -> No;  Modulo reportes - leer: Si -> No;  Modulo reportes - actualizar: Si -> No;  Modulo reportes - eliminar: Si -> No;  Modulo bitacora - leer: Si -> No; ', '2024-11-08 03:57:27'),
(171, '22222222', 'Modificar', 'Roles', 'rol prueba', ' Modulo entrenadores - crear: No -> Si;  Modulo entrenadores - leer: No -> Si;  Modulo entrenadores - actualizar: No -> Si;  Modulo entrenadores - eliminar: No -> Si;  Modulo atletas - crear: No -> Si;  Modulo atletas - leer: No -> Si;  Modulo atletas - actualizar: No -> Si;  Modulo atletas - eliminar: No -> Si;  Modulo asistencias - crear: No -> Si;  Modulo asistencias - leer: No -> Si;  Modulo asistencias - actualizar: No -> Si;  Modulo asistencias - eliminar: No -> Si;  Modulo eventos - crear: No -> Si;  Modulo eventos - leer: No -> Si;  Modulo eventos - actualizar: No -> Si;  Modulo eventos - eliminar: No -> Si;  Modulo mensualidad - crear: No -> Si;  Modulo mensualidad - leer: No -> Si;  Modulo mensualidad - actualizar: No -> Si;  Modulo mensualidad - eliminar: No -> Si;  Modulo wada - crear: No -> Si;  Modulo wada - leer: No -> Si;  Modulo wada - actualizar: No -> Si;  Modulo wada - eliminar: No -> Si;  Modulo reportes - crear: No -> Si;  Modulo reportes - leer: No -> Si;  Modulo reportes - actualizar: No -> Si;  Modulo reportes - eliminar: No -> Si;  Modulo bitacora - leer: No -> Si; ', '2024-11-08 04:01:59'),
(172, '22222222', 'Incluir', 'Roles', 'compaye', 'Se agregó el Rol: compaye', '2024-11-08 04:49:30'),
(173, '22222222', 'Eliminar', 'Roles', 'compaye', 'Se eliminó el Rol: compaye', '2024-11-08 04:49:52');

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
('22222222', 'cacaca'),
('2313657', 'Elit similique faci'),
('4765884', 'Sapiente quam qui vo'),
('8578689', 'Incidunt nulla labo'),
('8676719', 'Saepe nostrud numqua');

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
  `crear` tinyint(1) NOT NULL,
  `leer` tinyint(1) NOT NULL,
  `actualizar` tinyint(1) NOT NULL,
  `eliminar` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_rol`, `modulo`, `crear`, `leer`, `actualizar`, `eliminar`) VALUES
(0, 1, 0, 0, 0, 0),
(0, 2, 0, 1, 0, 0),
(0, 3, 0, 0, 0, 0),
(0, 4, 0, 1, 0, 0),
(0, 5, 0, 1, 0, 0),
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
(30, 1, 1, 1, 1, 1),
(30, 2, 1, 1, 1, 1),
(30, 3, 1, 1, 1, 1),
(30, 4, 1, 1, 1, 1),
(30, 5, 1, 1, 1, 1),
(30, 6, 1, 1, 1, 1),
(30, 7, 1, 1, 1, 1),
(30, 8, 1, 1, 1, 1),
(30, 9, 0, 1, 0, 0);

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

--
-- Volcado de datos para la tabla `representantes`
--

INSERT INTO `representantes` (`cedula`, `nombre_completo`, `telefono`, `parentesco`) VALUES
('28609560', 'YOOOO', '04245681343', 'saddfdsfds'),
('28609560', 'YOOOO', '04245681343', 'saddfdsfds');

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
(0, 'Atleta'),
(1, 'entrenador'),
(30, 'rol prueba');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_atleta`
--

CREATE TABLE `tipo_atleta` (
  `id_tipo_atleta` int(25) NOT NULL,
  `nombre_tipo_atleta` varchar(25) NOT NULL,
  `tipo_cobro` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_atleta`
--

INSERT INTO `tipo_atleta` (`id_tipo_atleta`, `nombre_tipo_atleta`, `tipo_cobro`) VALUES
(1, 'sdfdsffds', 30),
(2, 'hahahah', 10);

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
('1328547', 'Pariatur Cumque est', 'Do eaque duis vel la', 'Femenino', '1991-11-28', 'Dolor corrupti exer', 'Divorciado', '04414890127', 'folukobyzu@mailinator.com'),
('22222222', 'Jugney', 'Vargas', 'Femenino', '2002-07-15', 'sdfdsfdfds', 'Casado', '04245681341', 'dsfdsfd@gmail.com'),
('23124144', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('2313657', 'Est in minim cupidit', 'Aut dolore velit qui', 'Masculino', '1995-11-18', 'Perspiciatis do obc', 'Soltero', '04741773854', 'sifavawoj@mailinator.com'),
('24244444', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('2517624', 'Facilis officia quo ', 'Illum sit nostrud d', 'Masculino', '1987-09-15', 'Nisi officiis explic', 'Casado', '04744313188', 'webyrajic@mailinator.com'),
('2594894', 'Repudiandae harum do', 'Voluptatem et labori', 'Masculino', '1992-09-17', 'Molestiae officia ad', 'Divorciado', '04844940895', 'sikylydig@mailinator.com'),
('28609560', 'jugney', 'vargas', 'Masculino', '2002-07-15', 'dsdj', 'Soltero', '04245681343', 'KJSHJSHKJH@GMAIL.COM'),
('3376883', 'Id voluptas rerum c', 'Velit in blanditiis ', 'Masculino', '2004-12-27', 'Maiores fugiat aut ', 'Casado', '04534055751', 'zazehoz@mailinator.com'),
('42342344', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('4412968', 'Lorem nisi nobis id', 'Ratione cupiditate e', 'Masculino', '2010-06-07', 'Accusamus do sed nat', 'Casado', '04193003130', 'tewuvijo@mailinator.com'),
('4765884', 'Aut dolores nihil vo', 'Voluptate expedita e', 'Femenino', '1994-09-23', 'Autem dolorem est mo', 'Soltero', '04229869792', 'jyca@mailinator.com'),
('66456842', 'Est pariatur Nihil ', 'Non et non molestias', 'Femenino', '2003-01-13', 'Ex qui architecto to', 'Viudo', '04823255865', 'nudob@mailinator.com'),
('664568422', 'Est pariatur Nihil ', 'Non et non molestias', 'Femenino', '2003-01-13', 'Ex qui architecto to', 'Viudo', '04823255865', 'nudob@mailinator.com'),
('6759472', 'Minima adipisci anim', 'Non aliquam voluptat', 'Masculino', '1995-11-03', 'Sed deserunt quis as', 'Casado', '04418277535', 'pise@mailinator.com'),
('6828158', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('68281580', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('68281581', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('682815811', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-20', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('68281582', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('8578689', 'Ipsa non modi eum p', 'Beatae perferendis v', 'Femenino', '2015-03-28', 'Quis sequi illo et p', 'Soltero', '04954211873', 'wylin@mailinator.com'),
('8676719', 'Minim dolores molest', 'Veniam eos explicab', 'Masculino', '1999-05-04', 'Rerum velit distinct', 'Casado', '04495740638', 'nejixyxosy@mailinator.com'),
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
('1328547', 0, '$2y$10$zieTv2FLNKVZg90iSxt3ie/za39OPiSF.kM73cH7R8XvTtCPQgcSC', '0'),
('22222222', 30, '$2y$10$syf4uVv4j1iML9whitgx2.ylZwVlWhUHrA7zFhvMyP0qqpzD6yNWO', '0'),
('23124144', 0, '$2y$10$.U6xdgWQPqo4HJbgPmi.u.j.6XZzLH46HiGXdB5lIS19BATJIKaCa', '0'),
('2313657', 1, '$2y$10$K0twqG63A5JyJw6l6Nbv5OC2ft1oP/DzysbgIPslCjVdK6HdgKABm', '0'),
('24244444', 0, '$2y$10$AlnDESIrQ20GjFP2bL5G5eXc.FAXQsbITN.Z1VSxpOf2EtaIaU6Oq', '0'),
('2517624', 1, '$2y$10$FgILLleAxEHPjdia6vXFx.7EZ96T7utWeeCeAmgypvd0E4lx9Dww6', '0'),
('2594894', 0, '$2y$10$XWoALDkOSs/n2fT30oBlluAS9RPUNKozcntpT/Tk9b4zJvR.mYZCO', '0'),
('3376883', 0, '$2y$10$ltWDsUEwgZ94BjvHY7tHMO7oJM6bEzPqoyzeqhQDExl6.tfRR5MeG', '0'),
('42342344', 0, '$2y$10$oPNkW491S4A4p7dKf2ngSePU2L4oBz/iezYPFKXxNagC6hduogbZ.', '0'),
('4412968', 1, '$2y$10$de8lZBRdxcBCEjk4vpINsOJrSQxrzKTA8Owtz9gHhR/1Ma.4mxxwC', '0'),
('4765884', 1, '$2y$10$XlApueEnfV2jJY.JDUbusuBt11pmfY4aLJqJr4KbHgR0YELgpy/g6', '0'),
('66456842', 0, '$2y$10$wdnIzo5Js4PI8TInBAhL.ORc1siZNVKbbaNg9ir99GZ3fuR24cTou', '0'),
('664568422', 0, '$2y$10$P6vxsbx8q8ITDfhHqu9VaOU310ZTDjdPoBFIn5AKZtMiMIMB91adS', '0'),
('6759472', 0, '$2y$10$OulaL.OOEq7fwxJmig51rugXt4UHPPywK/R5oMclKs7jvytCSYrUu', '0'),
('6828158', 0, '$2y$10$md4PwPLFMXm6RQf8gine2OLunXu4Y/l75cAik4GrKNk.FDAMqSm.G', '0'),
('68281580', 0, '$2y$10$hvCMlvd9BEFiyXbdghtz.eikWnVVyv.0XRGYLPIX/dlwhOAd6oia6', '0'),
('68281581', 0, '$2y$10$QxgU9C2kdQnmURdS.YhOkORUbk33RyNPXBS6MCjfgOCBpF1fY6yY2', '0'),
('682815811', 0, '$2y$10$JQ4ytSeERzWg5630.qYZRuDLmRBM219/ABuAAAoJve52TsELtR1tW', '0'),
('68281582', 0, '$2y$10$IegZMzWD3iDEV7Zxu5Z8k.rX.pg1ib8jPSd2k57kz4QXzar8XiuWO', '0'),
('8578689', 1, '$2y$10$C6zd3o4ikZE2MxHij97kYuZgV66Z8tRzI8kDseAh7CoU0vampCEuS', '0'),
('8676719', 1, '$2y$10$jlU7ZUd6yCqXY5/2L3yt1OV.y7i6amHJxDL0JFw06x.hyh4TDcW1u', '0'),
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
  ADD PRIMARY KEY (`id_atleta`,`fecha`);

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
  ADD PRIMARY KEY (`id_rol`,`modulo`),
  ADD KEY `permisos_ibfk_2` (`modulo`);

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
  MODIFY `id_accion` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

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
  MODIFY `id_mensualidad` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id_rol` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `subs`
--
ALTER TABLE `subs`
  MODIFY `id_sub` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_atleta`
--
ALTER TABLE `tipo_atleta`
  MODIFY `id_tipo_atleta` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`modulo`) REFERENCES `modulos` (`id_modulo`) ON DELETE CASCADE ON UPDATE CASCADE;

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
