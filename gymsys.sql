-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 12:45:36
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
('1328547', 0, '2024-11-12', 'd'),
('23124144', 0, '2024-10-27', ''),
('23124144', 0, '2024-10-28', ''),
('23124144', 0, '2024-11-08', ''),
('23124144', 0, '2024-11-09', ''),
('23124144', 1, '2024-11-12', ''),
('24244444', 0, '2024-10-27', ''),
('24244444', 0, '2024-10-28', ''),
('24244444', 0, '2024-11-08', ''),
('24244444', 0, '2024-11-09', ''),
('24244444', 0, '2024-11-12', ''),
('2594894', 0, '2024-11-12', ''),
('3331917', 0, '2024-11-12', ''),
('3376883', 0, '2024-11-12', ''),
('42194292', 1, '2024-11-08', ''),
('42194292', 1, '2024-11-09', ''),
('42194292', 0, '2024-11-12', ''),
('42342344', 0, '2024-10-27', ''),
('42342344', 0, '2024-10-28', ''),
('42342344', 0, '2024-11-08', ''),
('42342344', 0, '2024-11-09', ''),
('42342344', 0, '2024-11-12', ''),
('66456842', 0, '2024-10-27', ''),
('66456842', 0, '2024-10-28', ''),
('66456842', 0, '2024-11-08', ''),
('66456842', 0, '2024-11-09', ''),
('66456842', 1, '2024-11-12', 'comoguchi'),
('664568422', 0, '2024-10-27', ''),
('664568422', 0, '2024-10-28', 'lerololelole'),
('664568422', 1, '2024-11-08', ''),
('664568422', 1, '2024-11-09', ''),
('664568422', 0, '2024-11-12', ''),
('6759472', 1, '2024-11-12', 'dd'),
('6828158', 0, '2024-11-12', 'vadas'),
('68281580', 0, '2024-10-27', ''),
('68281580', 0, '2024-10-28', ''),
('68281580', 0, '2024-11-08', ''),
('68281580', 0, '2024-11-09', ''),
('68281580', 0, '2024-11-12', ''),
('68281581', 0, '2024-10-27', ''),
('68281581', 1, '2024-10-28', ''),
('68281581', 0, '2024-11-08', ''),
('68281581', 0, '2024-11-09', ''),
('68281581', 0, '2024-11-12', ''),
('682815811', 1, '2024-10-27', ''),
('682815811', 0, '2024-10-28', ''),
('682815811', 1, '2024-11-08', ''),
('682815811', 1, '2024-11-09', ''),
('682815811', 0, '2024-11-12', ''),
('68281582', 1, '2024-10-27', ''),
('68281582', 1, '2024-10-28', ''),
('68281582', 0, '2024-11-08', ''),
('68281582', 0, '2024-11-09', ''),
('68281582', 1, '2024-11-12', ''),
('7342825', 0, '2024-11-12', 'melasclave'),
('9252463', 1, '2024-11-12', ''),
('99389012', 0, '2024-10-27', ''),
('99389012', 0, '2024-10-28', 'jejeje'),
('99389012', 1, '2024-11-08', ''),
('99389012', 0, '2024-11-12', '');

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
('1328547', '22222222', 1, 62.00, 178.00, NULL),
('23124144', '22222222', 1, 75.00, 24.00, NULL),
('24244444', '22222222', 1, 75.00, 24.00, NULL),
('2594894', '22222222', 1, 22.00, 1.00, NULL),
('3331917', '7194639', 0, 22.00, 223.00, '26776883'),
('3376883', '22222222', 1, 92.00, 60.00, NULL),
('42194292', '22222222', 1, 10.00, 99.00, NULL),
('42342344', '22222222', 1, 75.00, 24.00, NULL),
('66456842', '22222222', 0, 21.00, 61.00, NULL),
('664568422', '22222222', 0, 21.00, 61.00, NULL),
('6759472', '22222222', 0, 8.00, 70.00, NULL),
('6828158', '22222222', 1, 75.00, 24.00, NULL),
('68281580', '22222222', 1, 75.00, 24.00, NULL),
('68281581', '22222222', 1, 75.00, 24.00, NULL),
('682815811', '22222222', 1, 75.00, 24.00, NULL),
('68281582', '22222222', 1, 75.00, 24.00, NULL),
('7342825', '22222222', 1, 2313.00, 232.00, NULL),
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
  `usuario_modificado` varchar(20) DEFAULT NULL,
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
(173, '22222222', 'Eliminar', 'Roles', 'compaye', 'Se eliminó el Rol: compaye', '2024-11-08 04:49:52'),
(174, '22222222', 'Incluir', 'Atletas', '42194292', 'Se agregó el Atleta: 42194292 - Deserunt est sit vol Cupiditate cum imped', '2024-11-08 06:43:03'),
(175, '22222222', 'Incluir', 'Entrenadores', '7194639', 'Se agregó el Entrenador: 7194639 - Dolor deleniti non l Ad magnam qui repreh', '2024-11-08 08:05:25'),
(176, '22222222', 'Eliminar', 'Entrenadores', '2313657', 'Se eliminó el entrenador: 2313657', '2024-11-08 08:58:39'),
(177, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:13:03'),
(178, '22222222', 'Eliminar', 'Entrenadores', '4765884', 'Se eliminó el entrenador: 4765884', '2024-11-08 09:14:20'),
(179, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:15:12'),
(180, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:15:19'),
(181, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:15:19'),
(182, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:15:35'),
(183, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:15:35'),
(184, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:15:47'),
(185, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:15:47'),
(186, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:16:07'),
(187, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:16:07'),
(188, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:16:09'),
(189, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:16:09'),
(190, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:17:09'),
(191, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:17:09'),
(192, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:17:12'),
(193, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:17:12'),
(194, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:17:55'),
(195, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:17:55'),
(196, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:18:15'),
(197, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:18:15'),
(198, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:18:22'),
(199, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:18:22'),
(200, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:20:23'),
(201, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:20:23'),
(202, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:20:47'),
(203, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:20:47'),
(204, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:21:10'),
(205, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:21:10'),
(206, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:22:45'),
(207, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:22:45'),
(208, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:23:46'),
(209, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:23:46'),
(210, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:25:05'),
(211, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:25:05'),
(212, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:26:07'),
(213, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:26:07'),
(214, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:26:12'),
(215, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:26:12'),
(216, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:26:46'),
(217, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:26:46'),
(218, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:27:48'),
(219, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:27:48'),
(220, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:29:06'),
(221, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:29:06'),
(222, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:29:18'),
(223, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:29:18'),
(224, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:29:53'),
(225, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:29:53'),
(226, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:30:23'),
(227, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:30:23'),
(228, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:31:17'),
(229, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:31:17'),
(230, '22222222', 'Modificar', 'Entrenadores', '8676719', 'Nombre cambiado de \"Minim dolores molest\" a \"Marcos\"; Apellido cambiado de \"Veniam eos explicab\" a \"Perez\"; Fecha de nacimiento cambiada de \"1999-05-04\" a \"1990-01-04\"; Lugar de nacimiento cambiado de \"Rerum velit distinct\" a \"Ciudad\"; Telefono cambiado de \"04495740638\" a \"04122131222\"; Correo electrónico cambiado de \"nejixyxosy@mailinator.com\" a \"juanperez@example.com\"; Grado de instrucción cambiado de \"Saepe nostrud numqua\" a \"Licenciaturas\"; ', '2024-11-08 09:31:17'),
(231, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:32:32'),
(232, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:32:32'),
(233, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:32:32'),
(234, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:32:49'),
(235, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:32:49'),
(236, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:32:49'),
(237, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:34:26'),
(238, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:34:26'),
(239, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:34:26'),
(240, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:34:48'),
(241, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:34:48'),
(242, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:34:48'),
(243, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:34:51'),
(244, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:34:51'),
(245, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:34:51'),
(246, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:36:30'),
(247, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:36:30'),
(248, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:36:30'),
(249, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:52:08'),
(250, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:52:08'),
(251, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:52:08'),
(252, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:52:37'),
(253, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:52:37'),
(254, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:52:37'),
(255, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:52:55'),
(256, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:52:55'),
(257, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:52:55'),
(258, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:55:29'),
(259, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:55:29'),
(260, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:55:29'),
(261, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:55:42'),
(262, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:55:42'),
(263, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:55:42'),
(264, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:55:54'),
(265, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:55:54'),
(266, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:55:54'),
(267, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 09:57:15'),
(268, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:57:15'),
(269, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:57:15'),
(270, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:57:15'),
(271, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 09:58:56'),
(272, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 09:58:56'),
(273, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 09:58:56'),
(274, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:03:14'),
(275, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:03:15'),
(276, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:03:15'),
(277, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:03:15'),
(278, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:03:24'),
(279, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:03:24'),
(280, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:03:24'),
(281, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:03:24'),
(282, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:03:24'),
(283, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:03:29'),
(284, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:03:29'),
(285, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:03:29'),
(286, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:03:29'),
(287, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:03:29'),
(288, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:06:29'),
(289, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:06:29'),
(290, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:06:29'),
(291, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:06:29'),
(292, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:06:29'),
(293, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:07:39'),
(294, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:07:39'),
(295, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:07:39'),
(296, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:07:39'),
(297, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:07:39'),
(298, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:07:49'),
(299, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:07:49'),
(300, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:07:49'),
(301, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:07:49'),
(302, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:07:49'),
(303, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:10:18'),
(304, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:10:18'),
(305, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:10:18'),
(306, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:10:18'),
(307, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:10:18'),
(308, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:11:14'),
(309, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:11:14'),
(310, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:11:14'),
(311, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:11:14'),
(312, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:11:14'),
(313, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:11:50'),
(314, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:11:50'),
(315, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:11:50'),
(316, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:11:50'),
(317, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:11:50'),
(318, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:12:08'),
(319, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:12:08'),
(320, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:12:08'),
(321, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:12:08'),
(322, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:12:08'),
(323, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:13:55'),
(324, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:13:55'),
(325, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:13:55'),
(326, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:13:55'),
(327, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:13:55'),
(328, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:14:15'),
(329, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:14:15'),
(330, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:14:15'),
(331, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:14:15'),
(332, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:14:15'),
(333, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:15:22'),
(334, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:15:22'),
(335, '22222222', 'Modificar', 'Atletas', '1328547', 'Nombre cambiado de \"Pariatur Cumque est\" a \"Leoleo\"; Apellido cambiado de \"Do eaque duis vel la\" a \"Herrera\"; Genero cambiado de \"Femenino\" a \"Masculino\"; Fecha de nacimiento cambiada de \"1991-11-28\" a \"1990-01-01\"; Lugar de nacimiento cambiado de \"Dolor corrupti exer\" a \"Ciudad\"; Estado civil cambiado de \"Divorciado\" a \"Soltero\"; Telefono cambiado de \"04414890127\" a \"04265538456\"; Correo electrónico cambiado de \"folukobyzu@mailinator.com\" a \"leoleole@example.com\"; Tipo de atleta cambiado de \"0\" a \"1\"; Peso cambiado de \"2.00\" a \"62.00\"; Altura cambiado de \"96.00\" a \"178.00\"; ', '2024-11-08 10:15:22'),
(336, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:15:22'),
(337, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:15:22'),
(338, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:15:22'),
(339, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:16:38'),
(340, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:16:38'),
(341, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:16:38'),
(342, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:16:38'),
(343, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:16:38'),
(344, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:16:38'),
(345, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:18:27'),
(346, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:18:27'),
(347, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:18:27'),
(348, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:18:27'),
(349, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:18:27'),
(350, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:18:27'),
(351, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:19:34'),
(352, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:19:34'),
(353, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:19:34'),
(354, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:19:34'),
(355, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:19:34'),
(356, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:19:34'),
(357, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:24:27'),
(358, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:24:27'),
(359, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:24:27'),
(360, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:24:27'),
(361, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:24:27'),
(362, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:24:27'),
(363, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:24:53'),
(364, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:24:53'),
(365, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:24:53'),
(366, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:24:53'),
(367, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:24:53'),
(368, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:24:53'),
(369, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:28:41'),
(370, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:28:41'),
(371, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:28:41'),
(372, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:28:41'),
(373, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:28:41'),
(374, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:28:41'),
(375, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:28:48'),
(376, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:28:48'),
(377, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:28:48'),
(378, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:28:48'),
(379, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:28:48'),
(380, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:28:49'),
(381, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:31:19'),
(382, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:31:19'),
(383, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:31:19'),
(384, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:31:19'),
(385, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:31:19'),
(386, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:31:19'),
(387, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:38:50'),
(388, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:38:50'),
(389, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:38:50'),
(390, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:38:50'),
(391, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:38:50'),
(392, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:38:50'),
(393, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:39:25');
INSERT INTO `bitacora` (`id_accion`, `id_usuario`, `accion`, `modulo`, `usuario_modificado`, `detalles`, `fecha`) VALUES
(394, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:39:25'),
(395, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:39:25'),
(396, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:39:25'),
(397, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:39:25'),
(398, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:39:25'),
(399, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:39:38'),
(400, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:39:38'),
(401, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:39:38'),
(402, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:39:38'),
(403, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:39:38'),
(404, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:39:38'),
(405, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:41:12'),
(406, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:41:12'),
(407, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:41:13'),
(408, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:41:13'),
(409, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:41:13'),
(410, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:41:13'),
(411, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:41:35'),
(412, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:41:35'),
(413, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:41:35'),
(414, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:41:35'),
(415, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:41:35'),
(416, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:41:35'),
(417, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:43:47'),
(418, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:43:47'),
(419, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:43:47'),
(420, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:43:47'),
(421, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:43:47'),
(422, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:43:47'),
(423, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:53:03'),
(424, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:53:03'),
(425, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:53:03'),
(426, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:53:03'),
(427, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:53:03'),
(428, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:53:03'),
(429, '22222222', 'Incluir', 'Asistencias', '2024-11-08', 'Se agregó asistencias para la fecha: 2024-11-08', '2024-11-08 10:55:13'),
(430, '22222222', 'Modificar', 'Asistencias', '2024-11-08', 'Cambio la asistencia de 42194292: No -> Si; ', '2024-11-08 10:55:56'),
(431, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 10:59:04'),
(432, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 10:59:04'),
(433, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 10:59:04'),
(434, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 10:59:04'),
(435, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 10:59:04'),
(436, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 10:59:04'),
(437, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:00:03'),
(438, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:00:03'),
(439, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:00:03'),
(440, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:00:03'),
(441, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:00:03'),
(442, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:00:03'),
(443, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:00:21'),
(444, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:00:21'),
(445, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:00:21'),
(446, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:00:22'),
(447, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:00:22'),
(448, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:00:22'),
(449, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:00:57'),
(450, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:00:57'),
(451, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:00:57'),
(452, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:00:57'),
(453, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:00:57'),
(454, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:00:57'),
(455, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:02:07'),
(456, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:02:07'),
(457, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:02:07'),
(458, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:02:07'),
(459, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:02:07'),
(460, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:02:07'),
(461, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:04:56'),
(462, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:04:56'),
(463, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:04:56'),
(464, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:04:56'),
(465, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:04:56'),
(466, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:04:56'),
(467, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:06:16'),
(468, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:06:16'),
(469, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:06:16'),
(470, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:06:16'),
(471, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:06:16'),
(472, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:06:16'),
(473, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:06:26'),
(474, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:06:26'),
(475, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:06:26'),
(476, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:06:26'),
(477, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:06:26'),
(478, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:06:27'),
(479, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:07:06'),
(480, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:07:06'),
(481, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:07:06'),
(482, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:07:06'),
(483, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:07:06'),
(484, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:07:06'),
(485, '22222222', 'Incluir', 'Asistencias', '2024-11-09', 'Se agregó asistencias para la fecha: 2024-11-09', '2024-11-08 11:08:18'),
(486, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:08:19'),
(487, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:08:19'),
(488, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:08:19'),
(489, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:08:19'),
(490, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:08:19'),
(491, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:08:19'),
(492, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:08:53'),
(493, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:08:53'),
(494, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:08:53'),
(495, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:08:53'),
(496, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:08:53'),
(497, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:08:53'),
(498, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:10:19'),
(499, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:10:19'),
(500, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:10:19'),
(501, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:10:19'),
(502, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:10:19'),
(503, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:10:19'),
(504, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:11:59'),
(505, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:11:59'),
(506, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:11:59'),
(507, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:12:00'),
(508, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:12:00'),
(509, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:12:00'),
(510, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:14:09'),
(511, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:14:09'),
(512, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:14:09'),
(513, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:14:09'),
(514, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:14:09'),
(515, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:14:09'),
(516, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:17:58'),
(517, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:17:58'),
(518, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:17:58'),
(519, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:17:58'),
(520, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:17:58'),
(521, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:17:58'),
(522, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:18:26'),
(523, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:18:26'),
(524, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:18:26'),
(525, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:18:26'),
(526, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:18:26'),
(527, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:18:26'),
(528, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:19:05'),
(529, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:19:05'),
(530, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:19:05'),
(531, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:19:05'),
(532, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:19:05'),
(533, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:19:05'),
(534, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:28:13'),
(535, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:28:13'),
(536, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:28:13'),
(537, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:28:14'),
(538, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:28:14'),
(539, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:28:14'),
(540, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:28:54'),
(541, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:28:54'),
(542, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:28:54'),
(543, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:28:54'),
(544, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:28:54'),
(545, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:28:54'),
(546, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:29:25'),
(547, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:29:25'),
(548, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:29:25'),
(549, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:29:25'),
(550, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:29:25'),
(551, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:29:25'),
(552, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:30:51'),
(553, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:30:51'),
(554, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:30:51'),
(555, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:30:51'),
(556, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:30:51'),
(557, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:30:51'),
(558, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:31:12'),
(559, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:31:12'),
(560, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:31:12'),
(561, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:31:12'),
(562, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:31:12'),
(563, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:31:12'),
(564, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:32:37'),
(565, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:32:37'),
(566, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:32:37'),
(567, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:32:37'),
(568, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:32:37'),
(569, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:32:37'),
(570, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:33:31'),
(571, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:33:31'),
(572, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:33:31'),
(573, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:33:31'),
(574, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:33:31'),
(575, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:33:31'),
(576, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:34:07'),
(577, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:34:07'),
(578, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:34:07'),
(579, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:34:07'),
(580, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:34:07'),
(581, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:34:07'),
(582, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:46:55'),
(583, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:46:55'),
(584, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:46:55'),
(585, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:46:55'),
(586, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:46:55'),
(587, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:46:55'),
(588, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:47:57'),
(589, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:47:57'),
(590, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:47:57'),
(591, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:47:57'),
(592, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:47:57'),
(593, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:47:57'),
(594, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:53:54'),
(595, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:53:54'),
(596, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:53:54'),
(597, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:53:54'),
(598, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:53:54'),
(599, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:53:54'),
(600, '22222222', 'Incluir', 'Roles', 'Administra', 'Se agregó el Rol: Administrador', '2024-11-08 11:53:54'),
(601, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:55:11'),
(602, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:55:11'),
(603, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:55:11'),
(604, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:55:11'),
(605, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:55:11'),
(606, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:55:11'),
(607, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 11:58:49'),
(608, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 11:58:49'),
(609, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 11:58:49'),
(610, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 11:58:49'),
(611, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 11:58:49'),
(612, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 11:58:49'),
(613, '22222222', 'Eliminar', 'Roles', 'Administra', 'Se eliminó el Rol: Administrador', '2024-11-08 11:58:49'),
(614, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 12:00:24'),
(615, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 12:00:24'),
(616, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 12:00:24'),
(617, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 12:00:24'),
(618, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 12:00:24'),
(619, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 12:00:24'),
(620, '22222222', 'Incluir', 'Roles', 'Administra', 'Se agregó el Rol: Administrador', '2024-11-08 12:00:25'),
(621, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 12:02:50'),
(622, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 12:02:50'),
(623, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 12:02:50'),
(624, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 12:02:50'),
(625, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 12:02:50'),
(626, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 12:02:50'),
(627, '22222222', 'Incluir', 'Roles', 'Rol Modifi', 'Se agregó el Rol: Rol Modificable', '2024-11-08 12:05:39'),
(628, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 12:06:49'),
(629, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 12:06:49'),
(630, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 12:06:49'),
(631, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 12:06:49'),
(632, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 12:06:49'),
(633, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 12:06:49'),
(634, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 12:08:39'),
(635, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 12:08:39'),
(636, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 12:08:39'),
(637, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 12:08:39'),
(638, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 12:08:39'),
(639, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 12:08:39'),
(640, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 12:08:48'),
(641, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 12:08:48'),
(642, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 12:08:48'),
(643, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 12:08:48'),
(644, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 12:08:48'),
(645, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 12:08:48'),
(646, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-08 12:10:05'),
(647, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-08 12:10:05'),
(648, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-08 12:10:05'),
(649, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-08 12:10:05'),
(650, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-08 12:10:05'),
(651, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-08 12:10:05'),
(652, '22222222', 'Incluir', 'Roles', 'rolito', 'Se agregó el Rol: rolito', '2024-11-12 04:04:09'),
(653, '22222222', 'Incluir', 'Roles', 'rolaso', 'Se agregó el Rol: rolaso', '2024-11-12 04:04:40'),
(654, '22222222', 'Eliminar', 'Roles', 'rolaso', 'Se eliminó el Rol: rolaso', '2024-11-12 04:04:57'),
(655, '22222222', 'Eliminar', 'Roles', 'rolito', 'Se eliminó el Rol: rolito', '2024-11-12 04:04:59'),
(656, '22222222', 'Incluir', 'Roles', 'rol', 'Se agregó el Rol: rol', '2024-11-12 04:08:43'),
(657, '22222222', 'Incluir', 'Atletas', '7342825', 'Se agregó el Atleta: 7342825 - Consequat Vero amet Tenetur sapiente non', '2024-11-12 05:14:22'),
(658, '22222222', 'Modificar', 'Atletas', '664568422', 'Nombre cambiado de \"Est pariatur Nihil \" a \"Est pariatur miguel\"; ', '2024-11-12 05:29:04'),
(659, '22222222', 'Incluir', 'Atletas', '3331917', 'Se agregó el Atleta: 3331917 - Tenetur consectetur Reprehenderit et aut', '2024-11-12 05:32:13'),
(660, '22222222', 'Incluir', 'Asistencias', '2024-11-12', 'Se agregó asistencias para la fecha: 2024-11-12', '2024-11-12 22:18:37'),
(661, '22222222', 'Modificar', 'Asistencias', '2024-11-12', 'Cambio la asistencia de 68281580: Si -> No; Cambio el comentario de 68281580: comoguchi -> ; ', '2024-11-12 22:25:23'),
(662, '22222222', 'Modificar', 'Asistencias', '2024-11-12', 'Cambio el comentario de 66456842:  -> comoguchi; ', '2024-11-12 22:25:23'),
(663, '22222222', 'Modificar', 'Asistencias', '2024-11-12', 'Cambio la asistencia de 42342344: Si -> No; ', '2024-11-12 22:25:23'),
(664, '22222222', 'Modificar', 'Asistencias', '2024-11-12', 'Cambio el comentario de 7342825:  -> melasclave; ', '2024-11-12 22:59:37'),
(665, '22222222', 'Incluir', 'Asistencias', '2024-11-13', 'Se agregó asistencias para la fecha: 2024-11-13', '2024-11-13 01:07:51'),
(666, '22222222', 'Modificar', 'Asistencias', '2024-11-12', 'Cambio la asistencia de 68281582: No -> Si; ', '2024-11-13 01:12:29'),
(667, '22222222', 'Modificar', 'Atletas', '682815811', 'Fecha de nacimiento cambiada de \"1996-03-20\" a \"2015-09-15\"; ', '2024-11-15 00:07:24'),
(668, '22222222', 'Incluir', 'WADA', '99389012', 'Se agregó la WADA para el Atleta con ID: 99389012', '2024-11-15 04:08:15'),
(669, '22222222', 'Modificar', 'WADA', '99389012', 'Fecha de inscripción cambiada de \"2024-11-15\" a \"2024-11-14\"; Fecha de última actualización cambiada de \"2024-11-15\" a \"2024-11-16\"; Fecha de vencimiento cambiada de \"2025-02-15\" a \"2025-02-18\"; ', '2024-11-15 04:14:28'),
(670, '22222222', 'Modificar', 'WADA', '99389012', 'Estado cambiado de \"Si cumple\" a \"No cumple\"; ', '2024-11-15 04:14:50'),
(671, '22222222', 'Eliminar', 'WADA', '99389012', 'Se eliminó la WADA del Atleta con ID: 99389012', '2024-11-15 04:16:30'),
(672, '22222222', 'Incluir', 'Tipo de Atleta', 'Atleta UPTAEB', 'Se agregó el Tipo de Atleta: Atleta UPTAEB', '2024-11-15 04:24:42'),
(673, '22222222', 'Incluir', 'WADA', '42342344', 'Se agregó la WADA para el Atleta con ID: 42342344', '2024-11-15 04:40:41'),
(674, '22222222', 'Modificar', 'WADA', '42342344', 'Fecha de última actualización cambiada de \"2024-07-12\" a \"2024-08-12\"; Fecha de vencimiento cambiada de \"2024-10-12\" a \"2024-11-12\"; Estado cambiado de \"No cumple\" a \"Si cumple\"; ', '2024-11-15 04:40:41'),
(675, '22222222', 'Eliminar', 'WADA', '42342344', 'Se eliminó la WADA del Atleta con ID: 42342344', '2024-11-15 04:40:41'),
(676, '22222222', 'Modificar', 'Entrenadores', '22222222', '', '2024-11-15 09:45:42'),
(677, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-15 10:24:17'),
(678, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-15 10:26:33'),
(679, '22222222', 'Incluir', 'Atletas', '5560233', 'Se agregó el Atleta: 5560233 - Alejandro Martinez', '2024-11-15 10:27:53'),
(680, '22222222', 'Elminar', 'Atletas', '5560233', 'Se eliminó el atleta: 5560233', '2024-11-15 10:29:42'),
(681, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-15 10:33:57'),
(682, '22222222', 'Incluir', 'Entrenadores', '3145612', 'Se agregó el Entrenador: 3145612 - Juan Perez', '2024-11-15 10:37:09'),
(683, '22222222', 'Eliminar', 'Entrenadores', '3145612', 'Se eliminó el entrenador: 3145612', '2024-11-15 10:38:17'),
(684, '22222222', 'Modificar', 'Entrenadores', '8676719', '', '2024-11-15 10:40:28'),
(685, '22222222', 'Incluir', 'Asistencias', '2024-11-15', 'Se agregó asistencias para la fecha: 2024-11-15', '2024-11-15 10:53:45'),
(686, '22222222', 'Eliminar', 'Roles', 'Administrador', 'Se eliminó el Rol: Administrador', '2024-11-15 11:20:32'),
(687, '22222222', 'Incluir', 'Roles', 'Administrador', 'Se agregó el Rol: Administrador', '2024-11-15 11:32:50'),
(688, '22222222', 'Eliminar', 'Roles', 'Administrador', 'Se eliminó el Rol: Administrador', '2024-11-15 11:34:20'),
(689, '22222222', 'Incluir', 'WADA', '42342344', 'Se agregó la WADA para el Atleta con ID: 42342344', '2024-11-15 11:38:38'),
(690, '22222222', 'Modificar', 'WADA', '42342344', 'Fecha de última actualización cambiada de \"2024-07-12\" a \"2024-08-12\"; Fecha de vencimiento cambiada de \"2024-10-12\" a \"2024-11-12\"; Estado cambiado de \"No cumple\" a \"Si cumple\"; ', '2024-11-15 11:44:18'),
(691, '22222222', 'Eliminar', 'WADA', '42342344', 'Se eliminó la WADA del Atleta con ID: 42342344', '2024-11-15 11:47:41'),
(692, '22222222', 'Incluir', 'Mensualidad', '66456842', 'Se agregó la mensualidad del Atleta con ID: 66456842 para la fecha 2024-11-20', '2024-11-21 00:35:53'),
(693, '22222222', 'Incluir', 'Mensualidad', '3376883', 'Se agregó la mensualidad del Atleta con la cedula: 3376883 para la fecha 2024-11-19', '2024-11-21 01:24:10'),
(694, '22222222', 'Incluir', 'Mensualidad', '68281581', 'Se agregó la mensualidad del Atleta con la cedula: 68281581 para la fecha 2024-11-20', '2024-11-21 01:26:53'),
(695, '22222222', 'Incluir', 'Asistencias', '2024-11-20', 'Se agregó asistencias para la fecha: 2024-11-20', '2024-11-21 02:15:09'),
(696, '22222222', 'Modificar', 'Roles', '1328547', 'Rol del usuario cambiado de \"Atleta\" a \"rol prueba\"; ', '2024-11-25 04:57:01'),
(697, '22222222', 'Modificar', 'Roles', '1328547', 'Rol del usuario cambiado de \"rol prueba\" a \"Rol Modificable\"; ', '2024-11-25 05:12:24'),
(698, '22222222', 'Modificar', 'Roles', 'rol', ' Modulo rolespermisos - leer: No -> Si;  Modulo asistencias - leer: No -> Si;  Modulo reportes - crear: No -> Si;  Modulo reportes - leer: No -> Si; ', '2024-11-25 05:58:45'),
(699, '22222222', 'Incluir', 'Asistencias', '2024-11-25', 'Se agregó asistencias para la fecha: 2024-11-25', '2024-11-25 06:03:22'),
(700, '22222222', 'Modificar', 'Atletas', '1328547', '', '2024-11-25 06:11:10'),
(701, '22222222', 'Modificar', 'Roles', '1328547', 'Contraseña del usuario fue modificada.; ', '2024-11-25 06:11:10'),
(702, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo mensualidad - crear: Si -> No; ', '2024-11-25 06:11:47'),
(703, '1328547', 'Modificar', 'Asistencias', '2024-11-25', 'Cambio la asistencia de 682815811: No -> Si; ', '2024-11-25 06:19:09'),
(704, '1328547', 'Modificar', 'Asistencias', '2024-11-25', 'Cambio la asistencia de 68281582: No -> Si; ', '2024-11-25 06:19:09'),
(705, '1328547', 'Modificar', 'Asistencias', '2024-11-25', 'Cambio la asistencia de 682815811: Si -> No; ', '2024-11-25 06:19:51'),
(706, '1328547', 'Modificar', 'Asistencias', '2024-11-25', 'Cambio la asistencia de 68281580: No -> Si; ', '2024-11-25 06:19:51'),
(707, '1328547', 'Modificar', 'Asistencias', '2024-11-25', 'Cambio la asistencia de 66456842: No -> Si; ', '2024-11-25 06:19:51'),
(708, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo rolespermisos - actualizar: Si -> No; ', '2024-11-25 06:20:52'),
(709, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo rolespermisos - leer: Si -> No;  Modulo rolespermisos - eliminar: Si -> No; ', '2024-11-25 06:21:05'),
(710, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo bitacora - leer: Si -> No; ', '2024-11-25 06:21:31'),
(711, '22222222', 'Incluir', 'Roles', 'Superusuario', 'Se agregó el Rol: Superusuario', '2024-11-25 06:22:47'),
(712, '1328547', 'Eliminar', 'Asistencias', NULL, 'Se eliminaron las asistencias del día: 2024-11-15', '2024-11-25 07:24:03'),
(713, '1328547', 'Eliminar', 'Asistencias', '2024-11-13', 'Se eliminaron las asistencias del día: 2024-11-13', '2024-11-25 07:25:10'),
(714, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo eventos - crear: No -> Si;  Modulo eventos - leer: No -> Si; ', '2024-11-25 07:48:12'),
(715, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo eventos - actualizar: No -> Si; ', '2024-11-25 07:49:10'),
(716, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo eventos - eliminar: No -> Si; ', '2024-11-25 07:49:18'),
(717, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo eventos - crear: Si -> No; ', '2024-11-25 10:17:54'),
(718, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo eventos - actualizar: Si -> No; ', '2024-11-25 10:19:57'),
(719, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo eventos - eliminar: Si -> No; ', '2024-11-25 10:20:37'),
(720, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo eventos - eliminar: No -> Si; ', '2024-11-25 10:21:07'),
(721, '22222222', 'Modificar', 'Roles', 'Rol Modificable', ' Modulo eventos - crear: No -> Si;  Modulo eventos - actualizar: No -> Si; ', '2024-11-25 10:21:18');

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
(1, 'dsfdsfds', 20.00, 51.00),
(2, 'dsfdsfdsdsd', 50.00, 83.00);

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
(1, 1, 'hola', 1, 1, 'dsfdsdfsfds', '2024-11-09', '2023-11-08', 'activo'),
(10, 1, 'Panamericano', 1, 1, '23232', '2024-11-24', '2024-11-25', 'activo');

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
('7194639', 'Quia qui occaecat om'),
('8578689', 'Incidunt nulla labo'),
('8676719', 'Licenciaturas');

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
-- Volcado de datos para la tabla `mensualidades`
--

INSERT INTO `mensualidades` (`id_mensualidad`, `id_atleta`, `fecha`, `tipo`, `monto`, `cobro`, `detalles`) VALUES
(3, '9252463', '2024-07-26', 0, 23.00, 0, NULL),
(153, '99389012', '2024-11-14', 0, 5.00, 0, NULL),
(154, '7342825', '2024-11-14', 0, 4.00, 0, NULL),
(155, '99389012', '2024-11-14', 0, 4.00, 0, NULL),
(156, '664568422', '2024-11-01', 0, 2.00, 0, NULL),
(157, '42194292', '2024-10-30', 0, 1.00, 0, NULL),
(158, '42342344', '2024-11-21', 0, 2.00, 0, NULL),
(159, '42194292', '2024-11-15', 0, 1.00, 0, NULL),
(160, '68281582', '2024-11-14', 0, 2.00, 0, NULL),
(161, '23124144', '2024-10-10', 0, 20.00, 0, ''),
(162, '23124144', '2024-10-10', 0, 20.00, 0, ''),
(163, '23124144', '2024-10-10', 0, 20.00, 0, ''),
(164, '23124144', '2024-11-15', 0, 1.00, 0, 'pana'),
(165, '682815811', '2024-11-15', 0, 2.00, 0, 'detalle'),
(166, '6759472', '2024-11-17', 0, 10.00, 0, ''),
(167, '66456842', '2024-11-20', 0, 10.00, 0, ''),
(168, '3376883', '2024-11-19', 0, 30.00, 0, ''),
(169, '68281581', '2024-11-20', 0, 30.00, 0, '');

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

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `id_usuario`, `titulo`, `mensaje`, `leida`, `objetivo`, `fecha_creacion`) VALUES
(1, '22222222', 'Se vencerá la WADA', 'La wada del atleta Jugney Vargas se vencerá en 15 dias.', 1, 'wada', '2024-11-21 07:37:24'),
(2, '22222222', 'Yeyeyeyeye', 'el carro en sport pluuuuus', 1, 'asistencias', '2024-11-20 13:33:00'),
(3, '22222222', 'winwonjo', 'el preñao', 1, 'rolespermisos', '2024-11-21 10:40:38'),
(4, '22222222', 'Yeyeyeyeye', 'el carro en sport pluuuuus', 1, 'asistencias', '2024-11-20 13:33:00'),
(5, '22222222', 'winwonjo', 'el preñao', 1, 'rolespermisos', '2024-11-21 10:40:38'),
(6, '22222222', 'winwonjo', 'el preñao', 1, 'rolespermisos', '2024-11-21 10:40:38'),
(7, '22222222', 'Yeyeyeyeye', 'el carro en sport pluuuuus', 1, 'asistencias', '2024-11-20 13:33:00'),
(8, '22222222', 'la vinotinto no sirve pa media mrd', 'sumalda es cumpa de su forme ade actuar ah ah ah solo me acuedo de como era por detras', 1, 'rolespermisos', '2024-11-21 10:40:38'),
(9, '22222222', 'Se vencerá la WADA', 'La wada del atleta Jugney Vargas se vencerá en 15 dias.sada', 1, 'wada', '2024-11-21 07:37:24'),
(10, '22222222', 'Yeyeyeyeyeggg', 'el carro en sport pluuuuusr', 1, 'asistencias', '2024-11-20 13:33:00'),
(11, '22222222', 'winwonjofas', 'el preñao231', 1, 'rolespermisos', '2024-11-21 10:40:38'),
(12, '22222222', 'Yeyeyeyeyesad', 'el carro en sport pluuuuus213123', 1, 'asistencias', '2024-11-20 13:33:00'),
(13, '22222222', 'winwonjodad', 'el preñao31', 1, 'rolespermisos', '2024-11-21 10:40:38'),
(14, '22222222', 'winwonjoasd', 'el preñaovv', 1, 'rolespermisos', '2024-11-21 10:40:38'),
(15, '22222222', 'Yeyeyeyeyemsdmm', 'el carro en sport pluuuuusmdmm', 1, 'asistencias', '2024-11-20 13:33:00'),
(16, '22222222', 'la vinotinto no sirve pa media mrdmdghmdh', 'sumalda es cumpa de su forme ade actuar ah ah ah solo me acuedo de como era podghmmdhgr detras', 1, 'rolespermisos', '2024-11-21 10:40:38'),
(18, '22222222', 'yeyeye', 'spopluuuu', 1, 'asistencias', '2024-11-21 14:13:19');

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
(2, 1, 1, 1, 1, 1),
(2, 2, 1, 1, 1, 1),
(2, 3, 1, 1, 1, 1),
(2, 4, 1, 1, 1, 1),
(2, 5, 1, 1, 1, 1),
(2, 6, 1, 1, 1, 1),
(2, 7, 1, 1, 1, 1),
(2, 8, 1, 1, 0, 0),
(2, 9, 0, 1, 0, 0),
(30, 1, 1, 1, 1, 1),
(30, 2, 1, 1, 1, 1),
(30, 3, 1, 1, 1, 1),
(30, 4, 1, 1, 1, 1),
(30, 5, 1, 1, 1, 1),
(30, 6, 1, 1, 1, 1),
(30, 7, 1, 1, 1, 1),
(30, 8, 1, 1, 1, 1),
(30, 9, 0, 1, 0, 0),
(45, 1, 1, 0, 1, 0),
(45, 2, 0, 0, 0, 1),
(45, 3, 0, 0, 0, 0),
(45, 4, 1, 1, 1, 1),
(45, 5, 1, 1, 1, 1),
(45, 6, 0, 1, 1, 1),
(45, 7, 0, 0, 0, 0),
(45, 8, 1, 1, 0, 0),
(45, 9, 0, 0, 0, 0),
(48, 1, 0, 1, 0, 0),
(48, 2, 1, 1, 0, 0),
(48, 3, 1, 1, 0, 0),
(48, 4, 1, 1, 1, 0),
(48, 5, 1, 1, 1, 1),
(48, 6, 1, 1, 1, 0),
(48, 7, 1, 1, 1, 0),
(48, 8, 1, 1, 0, 0),
(48, 9, 0, 1, 0, 0);

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
('28609560', 'YOOOO', '04245681343', 'saddfdsfds'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo'),
('26776883', 'coliwan', '04244549495', 'papa'),
('25668997', 'Rubencio', '04265789964', 'Hermano'),
('15787522', 'Leonardo', 'Leonardo', 'Leonardo');

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

--
-- Volcado de datos para la tabla `reset`
--

INSERT INTO `reset` (`id`, `email`, `cedula`, `token`, `expira`) VALUES
(28, 'soykuuhaku@gmail.com', '22222222', '06ecf5e03edf15ca22548707fde981d7', '2024-11-24 16:03:56'),
(29, 'soykuuhaku@gmail.com', '22222222', 'c8ac0f1c741d359f78937634df70d472', '2024-11-24 16:04:17'),
(30, 'soykuuhaku@gmail.com', '22222222', '92bb2ab92e0f8b48428ad037c3a148e4', '2024-11-24 16:15:23');

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
-- Volcado de datos para la tabla `resultado_competencia`
--

INSERT INTO `resultado_competencia` (`id_competencia`, `id_atleta`, `arranque`, `envion`, `medalla_arranque`, `medalla_envion`, `medalla_total`, `total`) VALUES
(1, '1328547', '3', '7', 'ninguna', 'ninguna', 'oro', 10.00);

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
(2, 'Superusuario'),
(30, 'rol prueba'),
(45, 'Rol Modificable'),
(48, 'rol');

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
(1, 'dsffdsfds', 15, 17),
(2, 'sub17', 14, 17);

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
(0, 'hahahah', 10),
(1, 'sdfdsffds', 30),
(3, 'Convenio', 5),
(4, 'Atleta UPTAEB', 2);

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
(1, 'fdsfdsfs'),
(4, 'preñao'),
(5, 'Panamericano');

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
('1328547', 'Leoleo', 'Herrera', 'Masculino', '1990-01-01', 'Ciudad', 'Soltero', '04265538456', 'leoleole@example.com'),
('22222222', 'Jugney', 'Vargas', 'Femenino', '2002-07-15', 'sdfdsfdfds', 'Casado', '04245681341', 'soykuuhaku@gmail.com'),
('23124144', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.comdfds'),
('24244444', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.comfdgfdgfd'),
('2517624', 'Facilis officia quo ', 'Illum sit nostrud d', 'Masculino', '1987-09-15', 'Nisi officiis explic', 'Casado', '04744313188', 'webyrajic@mailinator.com'),
('2594894', 'Repudiandae harum do', 'Voluptatem et labori', 'Masculino', '1992-09-17', 'Molestiae officia ad', 'Divorciado', '04844940895', 'sikylydig@mailinator.com'),
('28609560', 'jugney', 'vargas', 'Masculino', '2002-07-15', 'dsdj', 'Soltero', '04245681343', 'KJSHJSHKJH@GMAIL.COM'),
('3331917', 'Tenetur consectetur', 'Reprehenderit et aut', 'Masculino', '2010-10-13', 'Voluptatem Dolorem ', 'Soltero', '04718299227', 'tixazec@mailinator.com'),
('3376883', 'Id voluptas rerum c', 'Velit in blanditiis ', 'Masculino', '2004-12-27', 'Maiores fugiat aut ', 'Casado', '04534055751', 'zazehoz@mailinator.com'),
('42194292', 'Deserunt est sit vol', 'Cupiditate cum imped', 'Masculino', '1995-09-27', 'Exercitation cumque ', 'Casado', '04474026776', 'pobejozasa@mailinator.com'),
('42342344', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.comss'),
('4412968', 'Lorem nisi nobis id', 'Ratione cupiditate e', 'Masculino', '2010-06-07', 'Accusamus do sed nat', 'Casado', '04193003130', 'tewuvijo@mailinator.com'),
('66456842', 'Est pariatur Nihil ', 'Non et non molestias', 'Femenino', '2003-01-13', 'Ex qui architecto to', 'Viudo', '04823255865', 'nudob@mailinator.com'),
('664568422', 'Est pariatur miguel', 'Non et non molestias', 'Femenino', '2003-01-13', 'Ex qui architecto to', 'Viudo', '04823255865', 'nudob@mailinator.comfrgf'),
('6759472', 'Minima adipisci anim', 'Non aliquam voluptat', 'Masculino', '1995-11-03', 'Sed deserunt quis as', 'Casado', '04418277535', 'pise@mailinator.com'),
('6828158', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('68281580', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.comfdgfdgfdcc'),
('68281581', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.comdfgfdg'),
('682815811', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '2015-09-15', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.comvv'),
('68281582', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.comfghnbd'),
('7194639', 'Dolor deleniti non l', 'Ad magnam qui repreh', 'Masculino', '2018-12-01', 'Officiis ducimus co', 'Casado', '04721685737', 'bifuliki@mailinator.com'),
('7342825', 'Consequat Vero amet', 'Tenetur sapiente non', 'Masculino', '1972-03-11', 'Obcaecati quis adipi', 'Soltero', '04879275761', 'votopy@mailinator.com'),
('8578689', 'Ipsa non modi eum p', 'Beatae perferendis v', 'Femenino', '2015-03-28', 'Quis sequi illo et p', 'Soltero', '04954211873', 'wylin@mailinator.com'),
('8676719', 'Marcos', 'Perez', 'Masculino', '1990-01-04', 'Ciudad', 'Casado', '04122131222', 'juanperez@example.com'),
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
('1328547', 45, '$2y$10$h8/xIuMckNhnKiNLF5qonuzci8WXEcznTOPbqoLT05HMWm1up7MSq', '0'),
('22222222', 30, '$2y$10$hsNGdgLpo4hJzbboYhNt9.HEmkJHGJ81RpX6r..Cd05ya0wNCUlJa', '0'),
('23124144', 0, '$2y$10$.U6xdgWQPqo4HJbgPmi.u.j.6XZzLH46HiGXdB5lIS19BATJIKaCa', '0'),
('24244444', 0, '$2y$10$AlnDESIrQ20GjFP2bL5G5eXc.FAXQsbITN.Z1VSxpOf2EtaIaU6Oq', '0'),
('2517624', 1, '$2y$10$FgILLleAxEHPjdia6vXFx.7EZ96T7utWeeCeAmgypvd0E4lx9Dww6', '0'),
('2594894', 0, '$2y$10$XWoALDkOSs/n2fT30oBlluAS9RPUNKozcntpT/Tk9b4zJvR.mYZCO', '0'),
('3331917', 0, '$2y$10$qrvzLu5i6Zu0oCP4rjey5.FJgBym0WG4CwkNeMlkgEDc9ImWve8Qu', '0'),
('3376883', 0, '$2y$10$ltWDsUEwgZ94BjvHY7tHMO7oJM6bEzPqoyzeqhQDExl6.tfRR5MeG', '0'),
('42194292', 0, '$2y$10$GYwnZSuln6Gl7WR7xV3yguifhjRoT07XBLuofuZ3iAYvatWqXbWLO', '0'),
('42342344', 0, '$2y$10$oPNkW491S4A4p7dKf2ngSePU2L4oBz/iezYPFKXxNagC6hduogbZ.', '0'),
('4412968', 1, '$2y$10$de8lZBRdxcBCEjk4vpINsOJrSQxrzKTA8Owtz9gHhR/1Ma.4mxxwC', '0'),
('66456842', 0, '$2y$10$wdnIzo5Js4PI8TInBAhL.ORc1siZNVKbbaNg9ir99GZ3fuR24cTou', '0'),
('664568422', 0, '$2y$10$P6vxsbx8q8ITDfhHqu9VaOU310ZTDjdPoBFIn5AKZtMiMIMB91adS', '0'),
('6759472', 0, '$2y$10$OulaL.OOEq7fwxJmig51rugXt4UHPPywK/R5oMclKs7jvytCSYrUu', '0'),
('6828158', 0, '$2y$10$md4PwPLFMXm6RQf8gine2OLunXu4Y/l75cAik4GrKNk.FDAMqSm.G', '0'),
('68281580', 0, '$2y$10$hvCMlvd9BEFiyXbdghtz.eikWnVVyv.0XRGYLPIX/dlwhOAd6oia6', '0'),
('68281581', 0, '$2y$10$QxgU9C2kdQnmURdS.YhOkORUbk33RyNPXBS6MCjfgOCBpF1fY6yY2', '0'),
('682815811', 0, '$2y$10$JQ4ytSeERzWg5630.qYZRuDLmRBM219/ABuAAAoJve52TsELtR1tW', '0'),
('68281582', 0, '$2y$10$IegZMzWD3iDEV7Zxu5Z8k.rX.pg1ib8jPSd2k57kz4QXzar8XiuWO', '0'),
('7194639', 1, '$2y$10$QjGOkSoJtavZt2u8.bIOdOvnYJtSCIAFdL927rcs1RiczA/WlZfxG', '0'),
('7342825', 0, '$2y$10$I8iOs8Q84IVWjczS9XfBku7XqK7vxIDlZuwze3.CcfPhfQ7/7z13C', '0'),
('8578689', 1, '$2y$10$C6zd3o4ikZE2MxHij97kYuZgV66Z8tRzI8kDseAh7CoU0vampCEuS', '0'),
('8676719', 1, '$2y$10$TcpwA195YSsUIPzuTbW0Yu.40F1I.b1zESGqhAHHvElGO7HrtLJ9q', '0'),
('9252463', 0, '$2y$10$HKTnPY5Ndj4ljvylWoszouAsfl8RyRll5pSpZOUmQI7Wb9i.9SibO', '0'),
('99389012', 0, '$2y$10$BRdgyLi2EP6cQlyfq4eeXugzCaY0QucArbuz2b0MVjSS9dMSOG0yW', '0');

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
('664568422', '2024-11-13', '2024-12-04', '2024-11-13', 1),
('68281580', '2024-10-23', '2024-11-28', '2024-11-08', 1),
('9252463', '2024-07-18', '2024-12-26', '2024-09-12', 0);

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
  MODIFY `id_accion` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=722;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `competencia`
--
ALTER TABLE `competencia`
  MODIFY `id_competencia` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
  MODIFY `id_sub` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_atleta`
--
ALTER TABLE `tipo_atleta`
  MODIFY `id_tipo_atleta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_competencia`
--
ALTER TABLE `tipo_competencia`
  MODIFY `id_tipo_competencia` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
