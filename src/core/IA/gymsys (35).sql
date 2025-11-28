-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2025 a las 12:45:10
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
  `estado_asistencia` enum('presente','ausente','justificado') NOT NULL DEFAULT 'presente' COMMENT 'Estado de la asistencia',
  `tipo_sesion` enum('entrenamiento','competencia','evaluacion','otro') NOT NULL DEFAULT 'entrenamiento' COMMENT 'Tipo de sesión realizada',
  `rpe` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Escala RPE 1-10 para carga percibida',
  `observaciones` varchar(500) DEFAULT NULL COMMENT 'Observaciones generales de la sesión',
  `registrado_por` varchar(10) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha y hora del registro',
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL COMMENT 'Hora de entrada del atleta',
  `hora_salida` time DEFAULT NULL COMMENT 'Hora de salida del atleta (opcional)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id_atleta`, `estado_asistencia`, `tipo_sesion`, `rpe`, `observaciones`, `registrado_por`, `fecha_registro`, `fecha`, `hora_entrada`, `hora_salida`) VALUES
('12772086', 'presente', 'entrenamiento', NULL, 'Entrenamiento completo', '28609560', '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('12772086', 'ausente', 'entrenamiento', NULL, 'Falta justificada', '28609560', '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, 'Trabajó técnica', '28609560', '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, 'Sesión de fuerza', '28609560', '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('12772086', 'ausente', 'entrenamiento', NULL, 'Problema de salud', '28609560', '2025-11-26 19:29:59', '2025-08-21', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, 'Entrenamiento intenso', '28609560', '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-27', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-29', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-02', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('12772086', 'ausente', 'entrenamiento', NULL, 'Asuntos personales', '28609560', '2025-11-26 19:29:59', '2025-09-04', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-09', NULL, NULL),
('12772086', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('12772086', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('12772086', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, 'Atleta ejemplar', '28609560', '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, 'Excelente actitud', '28609560', '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-21', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-27', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-29', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-02', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-04', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-09', NULL, NULL),
('12917213', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('12917213', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('12917213', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('12942617', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('12942617', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('12975478', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('12975478', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('12977096', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('12977096', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13005500', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13005500', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, 'Sin justificación', '28609560', '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, 'Llegó tarde', '28609560', '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, 'Falta justificada', '28609560', '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-21', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, 'Problema de salud', '28609560', '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-27', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, 'Asuntos personales', '28609560', '2025-11-26 19:29:59', '2025-08-29', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-02', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-04', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('13051888', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-09', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13051888', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13052282', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13052282', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13056599', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13056599', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13120290', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13120290', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13131998', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13131998', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13149274', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13149274', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13278802', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13278802', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13313513', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13313513', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13320136', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13320136', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13337271', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13337271', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13346901', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13346901', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13700390', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13700390', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13754392', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13754392', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13959008', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13959008', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('13998602', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13998602', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, 'Trabajó técnica', '28609560', '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-21', NULL, NULL),
('14085766', 'ausente', 'entrenamiento', NULL, 'Falta justificada', '28609560', '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-27', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-29', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-02', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-04', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-09', NULL, NULL),
('14085766', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('14085766', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14085766', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('14106849', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14106849', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('14239818', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14239818', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('14306498', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14306498', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('14417017', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14417017', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('14439365', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14439365', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('14481000', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14481000', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('14485245', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14485245', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('14578971', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14578971', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('14862407', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14862407', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, 'Sesión de fuerza', '28609560', '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('15056699', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('15056699', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('15056699', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15056699', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15103377', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15103377', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15269720', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15269720', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15272925', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15272925', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15360999', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15360999', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15430279', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15430279', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15471522', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15471522', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15613896', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15613896', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15616092', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15616092', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15636478', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15636478', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15714461', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15714461', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15913594', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15913594', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('15997716', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15997716', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('16050530', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16050530', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('16120584', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16120584', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('16233536', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16233536', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('16286427', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16286427', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('16543552', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16543552', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('16662887', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16662887', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('16689305', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16689305', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('16739444', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16739444', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('16955797', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16955797', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, 'Entrenamiento completo', '28609560', '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-21', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('17073909', 'ausente', 'entrenamiento', NULL, 'Llegó tarde', '28609560', '2025-11-26 19:29:59', '2025-08-27', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-02', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-04', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('17073909', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-09', NULL, NULL),
('17073909', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17073909', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17094957', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17094957', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17150171', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17150171', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17181012', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17181012', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17196042', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17196042', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17220889', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17220889', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17228491', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17228491', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17335197', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17335197', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17407353', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17407353', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17527328', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17527328', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17548749', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17548749', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17562322', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17562322', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17671385', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17671385', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17683547', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17683547', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17739303', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17739303', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('17832642', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17832642', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('18152145', 'ausente', 'entrenamiento', NULL, 'Problema de salud', '28609560', '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('18152145', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('18152145', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18152145', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18163473', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18163473', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18165359', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18165359', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18358283', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18358283', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18417978', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18417978', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18469940', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18469940', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18594972', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18594972', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18634610', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18634610', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18676054', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18676054', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18702442', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18702442', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18726152', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18726152', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18842656', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18842656', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18891596', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18891596', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18916667', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18916667', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('18945098', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18945098', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('19481112', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19481112', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('19634029', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19634029', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('19675898', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19675898', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('19810099', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19810099', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('19825917', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19825917', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('19904626', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19904626', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('20003401', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20003401', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('20061612', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20061612', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('20321618', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20321618', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('20479437', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20479437', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('20533811', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20533811', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('20633272', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20633272', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('20736882', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20736882', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21056393', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21056393', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21081413', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21081413', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21134614', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21134614', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21196756', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21196756', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21391993', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21391993', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21410145', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21410145', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21658752', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21658752', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21705826', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21705826', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21717051', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21717051', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21754724', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21754724', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21772305', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21772305', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21790190', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21790190', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21843587', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21843587', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('21861752', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21861752', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('22184089', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22184089', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('22247221', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22247221', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('224563763', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('22616883', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22616883', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('22663799', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22663799', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('22684489', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22684489', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('22734664', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22734664', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('23007385', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23007385', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('23162294', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23162294', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('23262058', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23262058', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('23357320', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23357320', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('23444636', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23444636', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('23550969', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23550969', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('23779482', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23779482', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('23861615', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23861615', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('24033933', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24033933', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('24056944', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24056944', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('24175203', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24175203', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('24301635', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24301635', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('24386209', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24386209', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('24576850', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24576850', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('24711762', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24711762', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('24718127', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24718127', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('24795116', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24795116', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('24814613', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24814613', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25038718', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25038718', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25078359', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25078359', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25165706', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25165706', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25276244', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25276244', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25292400', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25292400', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25528818', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25528818', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25719526', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25719526', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25848796', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25848796', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25892158', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25892158', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25892495', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25892495', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('25982843', 'ausente', 'entrenamiento', NULL, 'e34323434', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25982843', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('26040166', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('26040166', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('26417092', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('26417092', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('26638210', 'ausente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('26638210', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('26643236', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('26643236', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL),
('29831802', 'presente', 'entrenamiento', NULL, '', '28609560', '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('29831802', 'ausente', 'entrenamiento', NULL, NULL, '28609560', '2025-11-27 01:44:15', '2025-11-26', NULL, NULL);

--
-- Disparadores `asistencias`
--
DELIMITER $$
CREATE TRIGGER `after_asistencias_delete` AFTER DELETE ON `asistencias` FOR EACH ROW BEGIN
    IF @registro_bitacora IS NULL THEN
        SET @registro_bitacora = 1;
        INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
        VALUES ('Eliminar', 'Asistencias', @usuario_actual, OLD.fecha, CONCAT('Se eliminaron las asistencias para la fecha: ', OLD.fecha));
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
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_asistencias_update` AFTER UPDATE ON `asistencias` FOR EACH ROW BEGIN
    IF @cambios IS NULL THEN
        SET @cambios = '';
    END IF;
    
    IF OLD.estado_asistencia != NEW.estado_asistencia THEN
        SET @cambios = CONCAT(@cambios, 'Estado: ', OLD.estado_asistencia, ' -> ', NEW.estado_asistencia, '; ');
    END IF;
    
    IF OLD.observaciones != NEW.observaciones THEN
        SET @cambios = CONCAT(@cambios, 'Observaciones: ', COALESCE(OLD.observaciones, '(vacío)'), ' -> ', COALESCE(NEW.observaciones, '(vacío)'), '; ');
    END IF;
    
    IF OLD.tipo_sesion != NEW.tipo_sesion THEN
        SET @cambios = CONCAT(@cambios, 'Tipo sesión: ', OLD.tipo_sesion, ' -> ', NEW.tipo_sesion, '; ');
    END IF;
    
    IF (OLD.rpe IS NULL AND NEW.rpe IS NOT NULL) OR (OLD.rpe IS NOT NULL AND NEW.rpe IS NULL) OR (OLD.rpe != NEW.rpe) THEN
        SET @cambios = CONCAT(@cambios, 'RPE: ', COALESCE(OLD.rpe, '-'), ' -> ', COALESCE(NEW.rpe, '-'), '; ');
    END IF;
    
    IF (OLD.hora_entrada IS NULL AND NEW.hora_entrada IS NOT NULL) OR (OLD.hora_entrada IS NOT NULL AND NEW.hora_entrada IS NULL) OR (OLD.hora_entrada != NEW.hora_entrada) THEN
        SET @cambios = CONCAT(@cambios, 'Hora entrada: ', COALESCE(OLD.hora_entrada, '-'), ' -> ', COALESCE(NEW.hora_entrada, '-'), '; ');
    END IF;
    
    IF (OLD.hora_salida IS NULL AND NEW.hora_salida IS NOT NULL) OR (OLD.hora_salida IS NOT NULL AND NEW.hora_salida IS NULL) OR (OLD.hora_salida != NEW.hora_salida) THEN
        SET @cambios = CONCAT(@cambios, 'Hora salida: ', COALESCE(OLD.hora_salida, '-'), ' -> ', COALESCE(NEW.hora_salida, '-'), '; ');
    END IF;
    
    INSERT INTO gymsys_secure.bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Modificar', 'Asistencias', @usuario_actual, OLD.fecha, @cambios);
    SET @cambios = NULL;  
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias_backup`
--

CREATE TABLE `asistencias_backup` (
  `id_atleta` varchar(10) NOT NULL,
  `asistio` tinyint(1) NOT NULL,
  `estado_asistencia` enum('presente','ausente','justificado') NOT NULL DEFAULT 'presente' COMMENT 'Estado de la asistencia',
  `tipo_sesion` enum('entrenamiento','competencia','evaluacion','otro') NOT NULL DEFAULT 'entrenamiento' COMMENT 'Tipo de sesión realizada',
  `rpe` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Escala RPE 1-10 para carga percibida',
  `observaciones` varchar(500) DEFAULT NULL COMMENT 'Observaciones generales de la sesión',
  `registrado_por` varchar(10) DEFAULT NULL COMMENT 'Cédula del usuario que registró',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha y hora del registro',
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL COMMENT 'Hora de entrada del atleta',
  `hora_salida` time DEFAULT NULL COMMENT 'Hora de salida del atleta (opcional)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencias_backup`
--

INSERT INTO `asistencias_backup` (`id_atleta`, `asistio`, `estado_asistencia`, `tipo_sesion`, `rpe`, `observaciones`, `registrado_por`, `fecha_registro`, `fecha`, `hora_entrada`, `hora_salida`) VALUES
('12772086', 1, 'presente', 'entrenamiento', NULL, 'Entrenamiento completo', NULL, '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('12772086', 0, 'presente', 'entrenamiento', NULL, 'Falta justificada', NULL, '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, 'Trabajó técnica', NULL, '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, 'Sesión de fuerza', NULL, '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('12772086', 0, 'presente', 'entrenamiento', NULL, 'Problema de salud', NULL, '2025-11-26 19:29:59', '2025-08-21', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, 'Entrenamiento intenso', NULL, '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-27', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-29', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-02', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('12772086', 0, 'presente', 'entrenamiento', NULL, 'Asuntos personales', NULL, '2025-11-26 19:29:59', '2025-09-04', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-09', NULL, NULL),
('12772086', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('12772086', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, 'Atleta ejemplar', NULL, '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, 'Excelente actitud', NULL, '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-21', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-27', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-29', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-02', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-04', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-09', NULL, NULL),
('12917213', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('12917213', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('12942617', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('12975478', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('12977096', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13005500', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, 'Sin justificación', NULL, '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, 'Llegó tarde', NULL, '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, 'Falta justificada', NULL, '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-21', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, 'Problema de salud', NULL, '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-27', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, 'Asuntos personales', NULL, '2025-11-26 19:29:59', '2025-08-29', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-02', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-04', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('13051888', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-09', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('13051888', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13052282', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13056599', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13120290', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13131998', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13149274', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13278802', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13313513', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13320136', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13337271', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13346901', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13700390', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13754392', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13959008', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('13998602', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, 'Trabajó técnica', NULL, '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-21', NULL, NULL),
('14085766', 0, 'presente', 'entrenamiento', NULL, 'Falta justificada', NULL, '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-27', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-29', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-02', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-04', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-09', NULL, NULL),
('14085766', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('14085766', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14106849', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14239818', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14306498', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14417017', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14439365', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14481000', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14485245', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14578971', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('14862407', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, 'Sesión de fuerza', NULL, '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('15056699', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('15056699', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('15056699', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15103377', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15269720', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15272925', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15360999', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15430279', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15471522', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15613896', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15616092', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15636478', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15714461', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15913594', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('15997716', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16050530', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16120584', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16233536', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16286427', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16543552', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16662887', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16689305', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16739444', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('16955797', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-13', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-16', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, 'Entrenamiento completo', NULL, '2025-11-26 19:29:59', '2025-08-18', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-21', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('17073909', 0, 'presente', 'entrenamiento', NULL, 'Llegó tarde', NULL, '2025-11-26 19:29:59', '2025-08-27', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-02', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-04', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('17073909', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-09', NULL, NULL),
('17073909', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17094957', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17150171', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17181012', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17196042', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17220889', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17228491', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17335197', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17407353', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17527328', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17548749', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17562322', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17671385', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17683547', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17739303', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('17832642', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-12', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-14', NULL, NULL),
('18152145', 0, 'presente', 'entrenamiento', NULL, 'Problema de salud', NULL, '2025-11-26 19:29:59', '2025-08-15', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-19', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-20', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-22', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-25', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-26', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-08-28', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-01', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-03', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-05', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-08', NULL, NULL),
('18152145', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-09-10', NULL, NULL),
('18152145', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18163473', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18165359', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18358283', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18417978', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18469940', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18594972', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18634610', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18676054', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18702442', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18726152', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18842656', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18891596', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18916667', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('18945098', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19481112', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19634029', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19675898', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19810099', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19825917', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('19904626', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20003401', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20061612', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20321618', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20479437', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20533811', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20633272', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('20736882', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21056393', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21081413', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21134614', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21196756', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21391993', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21410145', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21658752', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21705826', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21717051', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21754724', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21772305', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21790190', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21843587', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('21861752', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22184089', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22247221', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22616883', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22663799', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22684489', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('22734664', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23007385', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23162294', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23262058', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23357320', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23444636', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23550969', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23779482', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('23861615', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24033933', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24056944', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24175203', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24301635', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24386209', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24576850', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24711762', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24718127', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24795116', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('24814613', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25038718', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25078359', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25165706', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25276244', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25292400', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25528818', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25719526', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25848796', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25892158', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25892495', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('25982843', 0, 'presente', 'entrenamiento', NULL, 'e34323434', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('26040166', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('26417092', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('26638210', 0, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('26643236', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL),
('29831802', 1, 'presente', 'entrenamiento', NULL, '', NULL, '2025-11-26 19:29:59', '2025-10-06', NULL, NULL);

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
('12772086', '25924556', 3, 88.55, 1.90, NULL),
('12917213', '20034871', 3, 80.35, 1.82, NULL),
('12942617', '24376807', 3, 80.56, 1.59, NULL),
('12975478', '17826689', 3, 92.70, 1.93, NULL),
('12977096', '26563569', 3, 51.16, 1.83, NULL),
('13005500', '17667945', 3, 72.55, 1.61, NULL),
('13051888', '23134815', 3, 73.78, 1.87, NULL),
('13052282', '21565350', 3, 79.74, 1.75, NULL),
('13056599', '18530893', 3, 67.05, 1.99, NULL),
('13120290', '16094001', 3, 79.92, 1.51, NULL),
('13131998', '20860658', 3, 53.80, 1.64, NULL),
('13149274', '21263089', 3, 62.47, 1.51, NULL),
('13278802', '17987723', 3, 91.38, 1.60, NULL),
('13313513', '20705401', 3, 75.61, 1.93, NULL),
('13320136', '16981721', 3, 65.62, 1.91, NULL),
('13337271', '24038739', 3, 94.91, 1.56, NULL),
('13346901', '15914592', 3, 54.84, 1.98, NULL),
('13700390', '16981721', 3, 71.09, 1.77, NULL),
('13754392', '14201844', 3, 76.99, 1.75, NULL),
('13959008', '24897566', 3, 71.34, 1.88, NULL),
('13998602', '13458903', 3, 50.59, 1.85, NULL),
('14085766', '25924556', 3, 54.77, 1.67, NULL),
('14106849', '15362108', 3, 50.25, 1.57, NULL),
('14239818', '21855423', 3, 52.72, 1.85, NULL),
('14306498', '18897679', 3, 63.93, 1.84, NULL),
('14417017', '16621255', 3, 99.23, 2.02, NULL),
('14439365', '16363840', 3, 89.70, 1.68, NULL),
('14481000', '24227773', 3, 94.18, 2.01, NULL),
('14485245', '17241194', 3, 62.18, 1.72, NULL),
('14578971', '14449140', 3, 52.13, 1.59, NULL),
('14862407', '15362108', 3, 79.52, 1.88, NULL),
('15056699', '16529884', 3, 78.68, 1.96, NULL),
('15103377', '19585138', 3, 99.70, 1.86, NULL),
('15269720', '16536744', 3, 90.25, 1.77, NULL),
('15272925', '24349302', 3, 83.20, 1.66, NULL),
('15360999', '17725255', 3, 54.83, 1.88, NULL),
('15430279', '24885277', 3, 55.90, 1.59, NULL),
('15471522', '24191211', 3, 89.80, 1.69, NULL),
('15613896', '25854179', 3, 76.20, 1.86, NULL),
('15616092', '24562672', 3, 68.91, 1.85, NULL),
('15636478', '13704121', 3, 71.99, 1.74, NULL),
('15714461', '14828391', 3, 85.36, 1.97, NULL),
('15913594', '14273713', 3, 65.47, 1.69, NULL),
('15997716', '13482880', 3, 65.99, 1.90, NULL),
('16050530', '20047181', 3, 91.10, 1.90, NULL),
('16120584', '17987723', 3, 82.92, 1.79, NULL),
('16233536', '20237927', 3, 92.54, 1.86, NULL),
('16286427', '26430881', 3, 97.91, 1.95, NULL),
('16543552', '22751168', 3, 82.01, 1.89, NULL),
('16662887', '20927873', 3, 98.28, 1.77, NULL),
('16689305', '15361563', 3, 57.77, 1.57, NULL),
('16739444', '17154731', 3, 97.53, 1.75, NULL),
('16955797', '25055721', 3, 93.09, 1.80, NULL),
('17073909', '15334496', 3, 72.18, 1.98, NULL),
('17094957', '17118608', 3, 63.63, 1.80, NULL),
('17150171', '26022315', 3, 94.73, 1.98, NULL),
('17181012', '24038739', 3, 60.68, 1.99, NULL),
('17196042', '17850436', 3, 75.18, 1.89, NULL),
('17220889', '25593118', 3, 91.08, 1.75, NULL),
('17228491', '13267487', 3, 56.22, 1.93, NULL),
('17335197', '18246821', 3, 100.19, 1.53, NULL),
('17407353', '13267487', 3, 85.54, 1.96, NULL),
('17527328', '25260575', 3, 62.56, 1.91, NULL),
('17548749', '19820199', 3, 73.46, 1.72, NULL),
('17562322', '17850436', 3, 68.98, 1.81, NULL),
('17671385', '26424376', 3, 64.02, 1.89, NULL),
('17683547', '13259053', 3, 52.56, 1.76, NULL),
('17739303', '18035338', 3, 97.61, 2.01, NULL),
('17832642', '26052633', 3, 93.74, 1.91, NULL),
('18152145', '24399545', 3, 58.20, 1.63, NULL),
('18163473', '17350751', 3, 58.10, 1.68, NULL),
('18165359', '24897566', 3, 52.12, 1.98, NULL),
('18358283', '13144228', 3, 53.53, 1.63, NULL),
('18417978', '24610840', 3, 99.13, 1.67, NULL),
('18469940', '15361563', 3, 57.63, 1.83, NULL),
('18594972', '13144228', 3, 78.71, 1.78, NULL),
('18634610', '13144228', 3, 92.21, 1.80, NULL),
('18676054', '12795097', 3, 90.20, 1.88, NULL),
('18702442', '22751168', 3, 90.73, 1.62, NULL),
('18726152', '20237927', 3, 64.85, 1.57, NULL),
('18842656', '19820199', 3, 101.97, 1.76, NULL),
('18891596', '16529884', 3, 89.20, 1.64, NULL),
('18916667', '22775982', 3, 55.70, 1.52, NULL),
('18945098', '25675755', 3, 51.75, 1.74, NULL),
('19481112', '19163112', 3, 80.02, 1.52, NULL),
('19634029', '26430881', 3, 50.71, 1.75, NULL),
('19675898', '20047181', 3, 71.99, 1.82, NULL),
('19810099', '17118608', 3, 73.48, 1.59, NULL),
('19825917', '26052633', 3, 60.89, 1.66, NULL),
('19904626', '22364569', 3, 74.61, 1.60, NULL),
('20003401', '17154731', 3, 67.15, 1.61, NULL),
('20061612', '14449140', 3, 57.34, 1.92, NULL),
('20321618', '12747411', 3, 80.26, 2.00, NULL),
('20479437', '19042586', 3, 86.28, 1.50, NULL),
('20533811', '21194294', 3, 61.90, 1.95, NULL),
('20633272', '24871985', 3, 52.98, 1.96, NULL),
('20736882', '17241194', 3, 86.89, 1.60, NULL),
('21056393', '20679040', 3, 53.57, 1.89, NULL),
('21081413', '14435394', 3, 85.04, 1.64, NULL),
('21134614', '12795097', 3, 66.02, 1.56, NULL),
('21196756', '26563569', 3, 91.43, 1.72, NULL),
('21391993', '18811320', 3, 70.21, 1.83, NULL),
('21410145', '15452025', 3, 65.34, 1.79, NULL),
('21658752', '26563569', 3, 66.25, 1.61, NULL),
('21705826', '14139353', 3, 59.26, 1.94, NULL),
('21717051', '16094001', 3, 52.84, 1.79, NULL),
('21754724', '22775982', 3, 93.16, 1.76, NULL),
('21772305', '13194737', 3, 77.31, 1.77, NULL),
('21790190', '23278998', 3, 77.59, 1.86, NULL),
('21843587', '24191211', 3, 77.22, 1.78, NULL),
('21861752', '24943956', 3, 81.87, 1.71, NULL),
('22184089', '18811320', 3, 69.83, 1.58, NULL),
('22247221', '21253782', 3, 78.02, 1.64, NULL),
('224563763', '28609560', 4, 72.00, 169.00, NULL),
('22616883', '24191211', 3, 92.60, 1.65, NULL),
('22663799', '21926146', 3, 83.61, 1.52, NULL),
('22684489', '14704222', 3, 63.01, 1.87, NULL),
('22734664', '15091314', 3, 53.37, 1.55, NULL),
('23007385', '26018689', 3, 74.11, 1.59, NULL),
('23162294', '13145243', 3, 82.46, 1.69, NULL),
('23262058', '14201844', 3, 92.35, 1.58, NULL),
('23357320', '25214604', 3, 68.94, 1.91, NULL),
('23444636', '16981721', 3, 51.05, 1.50, NULL),
('23550969', '19178987', 3, 79.15, 1.71, NULL),
('23779482', '19042586', 3, 53.62, 1.65, NULL),
('23861615', '25013149', 3, 89.06, 1.94, NULL),
('24033933', '22304427', 3, 81.57, 1.68, NULL),
('24056944', '14073912', 3, 57.09, 1.88, NULL),
('24175203', '13144228', 3, 50.43, 1.81, NULL),
('24301635', '20259367', 3, 64.30, 1.83, NULL),
('24386209', '23334805', 3, 91.79, 1.58, NULL),
('24576850', '19820199', 3, 51.38, 1.75, NULL),
('24711762', '19178987', 3, 91.81, 1.91, NULL),
('24718127', '23484602', 3, 87.82, 1.66, NULL),
('24795116', '23134815', 3, 57.35, 1.61, NULL),
('24814613', '23481215', 3, 66.64, 1.62, NULL),
('25038718', '16277770', 3, 88.38, 1.54, NULL),
('25078359', '18530893', 3, 74.33, 1.95, NULL),
('25165706', '25260575', 3, 94.39, 1.58, NULL),
('25276244', '18035338', 3, 92.07, 1.59, NULL),
('25292400', '14427487', 3, 64.81, 1.95, NULL),
('25528818', '14427487', 3, 84.91, 1.99, NULL),
('25719526', '14704222', 3, 83.72, 1.76, NULL),
('25848796', '18035338', 3, 62.78, 2.00, NULL),
('25892158', '19042586', 3, 94.58, 2.02, NULL),
('25892495', '25675755', 3, 81.63, 1.89, NULL),
('25982843', '19585138', 3, 83.01, 1.70, NULL),
('26040166', '14273713', 3, 75.98, 1.66, NULL),
('26417092', '14435394', 3, 60.85, 1.67, NULL),
('26638210', '22510901', 3, 55.12, 1.62, NULL),
('26643236', '21194294', 3, 81.61, 1.79, NULL),
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
(12, '81M', 81.00, 88.99),
(20, '48F', 48.00, 52.99),
(21, '53F', 53.00, 57.99),
(22, '58F', 58.00, 62.99),
(23, '63F', 63.00, 68.99),
(24, '69F', 69.00, 76.99),
(25, '77F', 77.00, 85.99),
(26, '86F', 86.00, 999.99),
(27, '60M', 60.00, 64.99),
(28, '65M', 65.00, 70.99),
(29, '71M', 71.00, 78.99),
(30, '79M', 79.00, 87.99),
(31, '88M', 88.00, 93.99),
(32, '94M', 94.00, 109.99),
(33, '110M', 110.00, 999.99);

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
-- Volcado de datos para la tabla `competencia`
--

INSERT INTO `competencia` (`id_competencia`, `nombre`, `lugar_competencia`, `fecha_inicio`, `fecha_fin`, `categoria`, `subs`, `tipo_competicion`, `estado`) VALUES
(27, 'Competencia Nacional Juvenil Yaracuy 2024', 'San Felipe Yaracuy', '2024-08-17', '2024-08-17', 6, 9, 17, 'inactivo'),
(28, 'Competencia Nacional Jose Carballo 2025', 'Caracas Distrito Capital', '2025-05-24', '2025-05-24', 7, 10, 17, 'activo');

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
('12747411', 'TSU'),
('12792892', 'TSU'),
('12795097', 'TSU'),
('13144228', 'TSU'),
('13145243', 'TSU'),
('13194737', 'TSU'),
('13259053', 'TSU'),
('13267487', 'TSU'),
('13399766', 'TSU'),
('13458903', 'TSU'),
('13482880', 'TSU'),
('13616797', 'TSU'),
('13704121', 'TSU'),
('13811661', 'TSU'),
('13863438', 'TSU'),
('13906858', 'TSU'),
('14073912', 'TSU'),
('14139353', 'TSU'),
('14201844', 'TSU'),
('14273713', 'TSU'),
('14275196', 'TSU'),
('14427487', 'TSU'),
('14435394', 'TSU'),
('14449140', 'TSU'),
('14508739', 'TSU'),
('14704222', 'TSU'),
('14828391', 'TSU'),
('14900667', 'TSU'),
('14928215', 'TSU'),
('15091314', 'TSU'),
('15334496', 'TSU'),
('15361563', 'TSU'),
('15362108', 'TSU'),
('15452025', 'TSU'),
('15914592', 'TSU'),
('16072883', 'TSU'),
('16094001', 'TSU'),
('16231993', 'TSU'),
('16277770', 'TSU'),
('16363840', 'TSU'),
('16404097', 'TSU'),
('16529884', 'TSU'),
('16536744', 'TSU'),
('16621255', 'TSU'),
('16652128', 'TSU'),
('16718688', 'TSU'),
('16981721', 'TSU'),
('17118608', 'TSU'),
('17145474', 'TSU'),
('17154731', 'TSU'),
('17241194', 'TSU'),
('17280176', 'TSU'),
('17314401', 'TSU'),
('17350751', 'TSU'),
('17667945', 'TSU'),
('17704948', 'TSU'),
('17725255', 'TSU'),
('17767315', 'TSU'),
('17792897', 'TSU'),
('17826689', 'TSU'),
('17850436', 'TSU'),
('17987723', 'TSU'),
('18035338', 'TSU'),
('18201989', 'TSU'),
('18246821', 'TSU'),
('18530893', 'TSU'),
('18811320', 'TSU'),
('18854101', 'TSU'),
('18881297', 'TSU'),
('18897679', 'TSU'),
('18993219', 'TSU'),
('19019982', 'TSU'),
('19042586', 'TSU'),
('19163112', 'TSU'),
('19178987', 'TSU'),
('19235548', 'TSU'),
('19585138', 'TSU'),
('19813548', 'TSU'),
('19820199', 'TSU'),
('19894659', 'TSU'),
('20034871', 'TSU'),
('20047181', 'TSU'),
('20237927', 'TSU'),
('20259367', 'TSU'),
('20679040', 'TSU'),
('20705401', 'TSU'),
('20860658', 'TSU'),
('20898650', 'TSU'),
('20927873', 'TSU'),
('21194294', 'TSU'),
('21208039', 'TSU'),
('21253782', 'TSU'),
('21263089', 'TSU'),
('21442504', 'TSU'),
('21478650', 'TSU'),
('21565350', 'TSU'),
('21623964', 'TSU'),
('21650876', 'TSU'),
('21855423', 'TSU'),
('21926146', 'TSU'),
('22304427', 'TSU'),
('22364569', 'TSU'),
('22431957', 'TSU'),
('22510901', 'TSU'),
('22751168', 'TSU'),
('22775982', 'TSU'),
('22969599', 'TSU'),
('23101888', 'TSU'),
('23134815', 'TSU'),
('23265818', 'TSU'),
('23278998', 'TSU'),
('23295079', 'Lic.'),
('23334805', 'TSU'),
('23382216', 'TSU'),
('23481215', 'TSU'),
('23484602', 'TSU'),
('24038739', 'TSU'),
('24191211', 'TSU'),
('24227773', 'TSU'),
('24349302', 'TSU'),
('24376807', 'TSU'),
('24399545', 'TSU'),
('24562672', 'TSU'),
('24610840', 'TSU'),
('24871985', 'TSU'),
('24885277', 'TSU'),
('24897566', 'TSU'),
('24943956', 'TSU'),
('24973655', 'TSU'),
('25013149', 'TSU'),
('25055721', 'TSU'),
('25214604', 'TSU'),
('25252233', 'TSU'),
('25260575', 'TSU'),
('25480999', 'TSU'),
('25506357', 'TSU'),
('25585441', 'TSU'),
('25593118', 'TSU'),
('25675755', 'TSU'),
('25827573', 'TSU'),
('25854179', 'TSU'),
('25924556', 'TSU'),
('26018689', 'TSU'),
('26022315', 'TSU'),
('26052633', 'TSU'),
('26232780', 'TSU'),
('26424376', 'TSU'),
('26430881', 'TSU'),
('26462772', 'TSU'),
('26487422', 'TSU'),
('26563569', 'TSU'),
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
-- Estructura de tabla para la tabla `lesiones`
--

CREATE TABLE `lesiones` (
  `id_lesion` int(11) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `fecha_lesion` date NOT NULL,
  `tipo_lesion` enum('muscular','articular','osea','ligamentosa','tendinosa','otra') NOT NULL,
  `zona_afectada` varchar(100) NOT NULL,
  `gravedad` enum('leve','moderada','severa') NOT NULL,
  `mecanismo_lesion` enum('entrenamiento','competencia','accidente','otro') DEFAULT 'entrenamiento',
  `tiempo_estimado_recuperacion` int(5) DEFAULT NULL COMMENT 'Días estimados de recuperación',
  `fecha_recuperacion` date DEFAULT NULL,
  `tratamiento_realizado` varchar(300) DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `registrado_por` varchar(10) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lesiones`
--

INSERT INTO `lesiones` (`id_lesion`, `id_atleta`, `fecha_lesion`, `tipo_lesion`, `zona_afectada`, `gravedad`, `mecanismo_lesion`, `tiempo_estimado_recuperacion`, `fecha_recuperacion`, `tratamiento_realizado`, `observaciones`, `registrado_por`, `fecha_registro`) VALUES
(2, '224563763', '2025-11-27', 'muscular', 'cabeza', 'severa', 'entrenamiento', 20, '2025-12-01', 'dsfdsfdsfdsfds', 'dsfdsfsd', '28609560', '2025-11-27 22:30:10'),
(3, '224563763', '2025-11-28', 'articular', 'cabeza', 'severa', 'competencia', 20, NULL, 'dsfdsfdsfdsfds', 'dsfdsfsd', '28609560', '2025-11-28 04:35:47');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `lista_asistencias`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `lista_asistencias` (
`id_atleta` varchar(10)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`fecha` date
,`hora_entrada` time
,`hora_salida` time
,`estado_asistencia` enum('presente','ausente','justificado')
,`tipo_sesion` enum('entrenamiento','competencia','evaluacion','otro')
,`rpe` tinyint(3) unsigned
,`observaciones` varchar(500)
,`registrado_por` varchar(10)
,`nombre_registrador` varchar(50)
,`apellido_registrador` varchar(50)
,`fecha_registro` timestamp
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
  `id` int(11) NOT NULL,
  `id_competencia` int(10) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `arranque` varchar(255) NOT NULL,
  `arranque_intento1_peso` decimal(5,2) DEFAULT NULL,
  `arranque_intento1_exito` tinyint(1) DEFAULT NULL,
  `arranque_intento2_peso` decimal(5,2) DEFAULT NULL,
  `arranque_intento2_exito` tinyint(1) DEFAULT NULL,
  `arranque_intento3_peso` decimal(5,2) DEFAULT NULL,
  `arranque_intento3_exito` tinyint(1) DEFAULT NULL,
  `mejor_arranque` decimal(5,2) DEFAULT NULL,
  `mejor_envion` decimal(5,2) DEFAULT NULL,
  `intentos_arranque_exitosos` int(11) DEFAULT 0,
  `efectividad_arranque` decimal(5,2) DEFAULT NULL,
  `efectividad_envion` decimal(5,2) DEFAULT NULL,
  `intentos_envion_exitosos` int(11) DEFAULT 0,
  `envion` varchar(255) NOT NULL,
  `envion_intento1_peso` decimal(5,2) DEFAULT NULL,
  `envion_intento1_exito` tinyint(1) DEFAULT NULL,
  `envion_intento2_peso` decimal(5,2) DEFAULT NULL,
  `envion_intento2_exito` tinyint(1) DEFAULT NULL,
  `envion_intento3_peso` decimal(5,2) DEFAULT NULL,
  `envion_intento3_exito` tinyint(1) DEFAULT NULL,
  `medalla_arranque` enum('oro','plata','bronce','ninguna') DEFAULT 'ninguna',
  `medalla_envion` enum('oro','plata','bronce','ninguna') DEFAULT 'ninguna',
  `medalla_total` enum('oro','plata','bronce','ninguna') DEFAULT 'ninguna',
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resultado_competencia`
--

INSERT INTO `resultado_competencia` (`id`, `id_competencia`, `id_atleta`, `arranque`, `arranque_intento1_peso`, `arranque_intento1_exito`, `arranque_intento2_peso`, `arranque_intento2_exito`, `arranque_intento3_peso`, `arranque_intento3_exito`, `mejor_arranque`, `mejor_envion`, `intentos_arranque_exitosos`, `efectividad_arranque`, `efectividad_envion`, `intentos_envion_exitosos`, `envion`, `envion_intento1_peso`, `envion_intento1_exito`, `envion_intento2_peso`, `envion_intento2_exito`, `envion_intento3_peso`, `envion_intento3_exito`, `medalla_arranque`, `medalla_envion`, `medalla_total`, `total`) VALUES
(1, 27, '25038718', '71', 66.00, 1, 69.00, 1, 71.00, 0, 69.00, 89.00, 2, 66.67, 100.00, 3, '89', 84.00, 1, 87.00, 1, 89.00, 1, 'plata', 'plata', 'plata', 158.00),
(2, 27, '25078359', '59', 54.00, 1, 57.00, 1, 59.00, 1, 59.00, 74.00, 3, 100.00, 100.00, 3, '74', 69.00, 1, 72.00, 1, 74.00, 1, 'plata', 'plata', 'plata', 133.00),
(3, 27, '25165706', '76', 71.00, 1, 74.00, 1, 76.00, 1, 76.00, 94.00, 3, 100.00, 100.00, 3, '94', 89.00, 1, 92.00, 1, 94.00, 1, 'oro', 'oro', 'oro', 170.00),
(4, 27, '25276244', '74', 69.00, 1, 72.00, 0, 74.00, 0, 69.00, 90.00, 1, 33.33, 66.67, 2, '92', 87.00, 1, 90.00, 1, 92.00, 0, 'oro', 'oro', 'oro', 159.00),
(12, 27, '25292400', '52', 47.00, 1, 50.00, 1, 52.00, 0, 50.00, 65.00, 2, 66.67, 100.00, 3, '65', 60.00, 1, 63.00, 1, 65.00, 1, 'oro', 'oro', 'oro', 115.00),
(5, 27, '25528818', '68', 63.00, 1, 66.00, 1, 68.00, 1, 68.00, 85.00, 3, 100.00, 100.00, 3, '85', 80.00, 1, 83.00, 1, 85.00, 1, 'bronce', 'bronce', 'bronce', 153.00),
(6, 27, '25719526', '67', 62.00, 1, 65.00, 1, 67.00, 1, 67.00, 84.00, 3, 100.00, 66.67, 2, '84', 79.00, 1, 82.00, 0, 84.00, 1, 'oro', 'oro', 'oro', 151.00),
(13, 27, '25848796', '50', 45.00, 1, 48.00, 0, 50.00, 1, 50.00, 63.00, 2, 66.67, 100.00, 3, '63', 58.00, 1, 61.00, 1, 63.00, 1, 'oro', 'oro', 'oro', 113.00),
(7, 27, '25892158', '76', 71.00, 1, 74.00, 1, 76.00, 1, 76.00, 95.00, 3, 100.00, 100.00, 3, '95', 90.00, 1, 93.00, 1, 95.00, 1, 'oro', 'oro', 'oro', 171.00),
(8, 27, '25892495', '65', 60.00, 1, 63.00, 1, 65.00, 1, 65.00, 82.00, 3, 100.00, 100.00, 3, '82', 77.00, 1, 80.00, 1, 82.00, 1, 'bronce', 'bronce', 'bronce', 147.00),
(9, 27, '25982843', '66', 61.00, 1, 64.00, 1, 66.00, 0, 64.00, 78.00, 2, 66.67, 33.33, 1, '83', 78.00, 1, 81.00, 0, 83.00, 0, 'plata', 'plata', 'plata', 142.00),
(10, 27, '26040166', '61', 56.00, 1, 59.00, 0, 61.00, 1, 61.00, 71.00, 2, 66.67, 33.33, 1, '76', 71.00, 1, 74.00, 0, 76.00, 0, 'oro', 'oro', 'oro', 132.00),
(14, 27, '26417092', '49', 44.00, 1, 47.00, 1, 49.00, 0, 47.00, 61.00, 2, 66.67, 100.00, 3, '61', 56.00, 1, 59.00, 1, 61.00, 1, 'oro', 'oro', 'oro', 108.00),
(15, 27, '26638210', '44', 39.00, 1, 42.00, 0, 44.00, 0, 39.00, 55.00, 1, 33.33, 100.00, 3, '55', 50.00, 1, 53.00, 1, 55.00, 1, 'oro', 'oro', 'oro', 94.00),
(11, 27, '26643236', '65', 60.00, 1, 63.00, 1, 65.00, 1, 65.00, 82.00, 3, 100.00, 100.00, 3, '82', 77.00, 1, 80.00, 1, 82.00, 1, 'ninguna', 'ninguna', 'ninguna', 147.00),
(28, 28, '13149274', '60', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, '80', NULL, NULL, NULL, NULL, NULL, NULL, 'oro', 'oro', 'oro', 140.00),
(29, 28, '13320136', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, 'ninguna', 'ninguna', 'ninguna', 0.00),
(24, 28, '22184089', '56', 51.00, 1, 54.00, 1, 56.00, 1, 56.00, 68.00, 3, 100.00, 66.67, 2, '70', 65.00, 1, 68.00, 1, 70.00, 0, 'oro', 'oro', 'oro', 124.00),
(16, 28, '22616883', '74', 69.00, 1, 72.00, 0, 74.00, 1, 74.00, 91.00, 2, 66.67, 66.67, 2, '93', 88.00, 1, 91.00, 1, 93.00, 0, 'oro', 'oro', 'oro', 165.00),
(17, 28, '22663799', '67', 62.00, 1, 65.00, 1, 67.00, 1, 67.00, 84.00, 3, 100.00, 100.00, 3, '84', 79.00, 1, 82.00, 1, 84.00, 1, 'plata', 'plata', 'plata', 151.00),
(25, 28, '22684489', '50', 45.00, 1, 48.00, 1, 50.00, 1, 50.00, 58.00, 3, 100.00, 33.33, 1, '63', 58.00, 1, 61.00, 0, 63.00, 0, 'oro', 'oro', 'oro', 108.00),
(26, 28, '22734664', '43', 38.00, 1, 41.00, 0, 43.00, 1, 43.00, 52.00, 2, 66.67, 66.67, 2, '54', 49.00, 1, 52.00, 1, 54.00, 0, 'oro', 'oro', 'oro', 95.00),
(18, 28, '23162294', '66', 61.00, 1, 64.00, 1, 66.00, 1, 66.00, 83.00, 3, 100.00, 100.00, 3, '83', 78.00, 1, 81.00, 1, 83.00, 1, 'bronce', 'bronce', 'bronce', 149.00),
(19, 28, '23262058', '74', 69.00, 1, 72.00, 1, 74.00, 1, 74.00, 93.00, 3, 100.00, 100.00, 3, '93', 88.00, 1, 91.00, 1, 93.00, 1, 'plata', 'plata', 'plata', 167.00),
(27, 28, '23444636', '41', 36.00, 1, 39.00, 1, 41.00, 1, 41.00, 51.00, 3, 100.00, 100.00, 3, '51', 46.00, 1, 49.00, 1, 51.00, 1, 'oro', 'oro', 'oro', 92.00),
(20, 28, '23550969', '63', 58.00, 1, 61.00, 1, 63.00, 0, 61.00, 79.00, 2, 66.67, 66.67, 2, '79', 74.00, 1, 77.00, 0, 79.00, 1, 'oro', 'oro', 'oro', 140.00),
(21, 28, '24033933', '65', 60.00, 1, 63.00, 1, 65.00, 1, 65.00, 82.00, 3, 100.00, 100.00, 3, '82', 77.00, 1, 80.00, 1, 82.00, 1, 'ninguna', 'ninguna', 'ninguna', 147.00),
(22, 28, '24718127', '70', 65.00, 1, 68.00, 1, 70.00, 1, 70.00, 88.00, 3, 100.00, 66.67, 2, '88', 83.00, 1, 86.00, 0, 88.00, 1, 'oro', 'oro', 'oro', 158.00),
(23, 28, '24814613', '53', 48.00, 1, 51.00, 1, 53.00, 0, 51.00, 62.00, 2, 66.67, 33.33, 1, '67', 62.00, 1, 65.00, 0, 67.00, 0, 'oro', 'oro', 'oro', 113.00);

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
(10, 'U23', 17, 23),
(18, 'U11', 9, 11),
(19, 'U17', 15, 17),
(22, 'U20', 15, 20),
(23, 'U23', 17, 23),
(24, 'U20', 15, 20),
(25, 'U23', 17, 23);

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
-- Estructura de tabla para la tabla `test_fms`
--

CREATE TABLE `test_fms` (
  `id_test_fms` int(11) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `fecha_evaluacion` date NOT NULL,
  `sentadilla_profunda` tinyint(1) NOT NULL COMMENT 'Puntuación 0-3',
  `paso_valla` tinyint(1) NOT NULL COMMENT 'Puntuación 0-3',
  `estocada_en_linea` tinyint(1) NOT NULL COMMENT 'Puntuación 0-3',
  `movilidad_hombro` tinyint(1) NOT NULL COMMENT 'Puntuación 0-3',
  `elevacion_pierna_recta` tinyint(1) NOT NULL COMMENT 'Puntuación 0-3',
  `estabilidad_tronco` tinyint(1) NOT NULL COMMENT 'Puntuación 0-3',
  `estabilidad_rotacional` tinyint(1) NOT NULL COMMENT 'Puntuación 0-3',
  `puntuacion_total` tinyint(2) NOT NULL COMMENT 'Suma total, máximo 21',
  `observaciones` varchar(500) DEFAULT NULL,
  `evaluador` varchar(10) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `test_fms`
--

INSERT INTO `test_fms` (`id_test_fms`, `id_atleta`, `fecha_evaluacion`, `sentadilla_profunda`, `paso_valla`, `estocada_en_linea`, `movilidad_hombro`, `elevacion_pierna_recta`, `estabilidad_tronco`, `estabilidad_rotacional`, `puntuacion_total`, `observaciones`, `evaluador`, `fecha_registro`) VALUES
(2, '224563763', '2025-11-27', 3, 0, 0, 0, 0, 0, 0, 3, 'dsfsdfs', '28609560', '2025-11-27 12:13:52'),
(3, '224563763', '2025-11-27', 1, 0, 3, 0, 2, 0, 2, 8, 'dsfdsfs', '28609560', '2025-11-27 22:29:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `test_postural`
--

CREATE TABLE `test_postural` (
  `id_test_postural` int(11) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `fecha_evaluacion` date NOT NULL,
  `cifosis_dorsal` enum('ninguna','leve','moderada','severa') DEFAULT 'ninguna',
  `lordosis_lumbar` enum('ninguna','leve','moderada','severa') DEFAULT 'ninguna',
  `escoliosis` enum('ninguna','leve','moderada','severa') DEFAULT 'ninguna',
  `inclinacion_pelvis` enum('ninguna','leve','moderada','severa') DEFAULT 'ninguna',
  `valgo_rodilla` enum('ninguna','leve','moderada','severa') DEFAULT 'ninguna',
  `varo_rodilla` enum('ninguna','leve','moderada','severa') DEFAULT 'ninguna',
  `rotacion_hombros` enum('ninguna','leve','moderada','severa') DEFAULT 'ninguna',
  `desnivel_escapulas` enum('ninguna','leve','moderada','severa') DEFAULT 'ninguna',
  `observaciones` varchar(500) DEFAULT NULL,
  `evaluador` varchar(10) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `test_postural`
--

INSERT INTO `test_postural` (`id_test_postural`, `id_atleta`, `fecha_evaluacion`, `cifosis_dorsal`, `lordosis_lumbar`, `escoliosis`, `inclinacion_pelvis`, `valgo_rodilla`, `varo_rodilla`, `rotacion_hombros`, `desnivel_escapulas`, `observaciones`, `evaluador`, `fecha_registro`) VALUES
(4, '224563763', '2025-11-27', 'leve', 'ninguna', 'ninguna', 'ninguna', 'ninguna', 'ninguna', 'ninguna', 'ninguna', 'dsfdsf', '28609560', '2025-11-27 12:13:17'),
(6, '224563763', '2025-11-27', 'leve', 'ninguna', 'ninguna', 'ninguna', 'ninguna', 'ninguna', 'ninguna', 'ninguna', 'dsfdsfdsfdsf', '28609560', '2025-11-27 22:54:52'),
(7, '26417092', '2025-11-28', 'moderada', 'severa', 'leve', 'leve', 'ninguna', 'ninguna', 'ninguna', 'ninguna', 'dsfdfds', '28609560', '2025-11-28 04:13:30');

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
(18, 'Municipal'),
(25, 'Nacional'),
(26, 'Nacional');

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
-- Estructura Stand-in para la vista `vista_resultados_detallados`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_resultados_detallados` (
`competencia` varchar(100)
,`nombre` varchar(50)
,`apellido` varchar(50)
,`genero` varchar(30)
,`peso_corporal` decimal(6,2)
,`arr1_peso` decimal(5,2)
,`arr1_resultado` varchar(6)
,`arr2_peso` decimal(5,2)
,`arr2_resultado` varchar(6)
,`arr3_peso` decimal(5,2)
,`arr3_resultado` varchar(6)
,`mejor_arranque` decimal(5,2)
,`env1_peso` decimal(5,2)
,`env1_resultado` varchar(6)
,`env2_peso` decimal(5,2)
,`env2_resultado` varchar(6)
,`env3_peso` decimal(5,2)
,`env3_resultado` varchar(6)
,`mejor_envion` decimal(5,2)
,`total` decimal(10,2)
,`intentos_arranque_exitosos` int(11)
,`intentos_envion_exitosos` int(11)
,`efectividad_arranque` varchar(8)
,`efectividad_envion` varchar(8)
,`medalla_arranque` enum('oro','plata','bronce','ninguna')
,`medalla_envion` enum('oro','plata','bronce','ninguna')
,`medalla_total` enum('oro','plata','bronce','ninguna')
);

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
('12772086', '2024-03-15', '2025-03-15', '2025-08-01', 0),
('12917213', '2024-11-20', '2025-11-20', '2025-09-01', 1),
('12942617', '2024-06-10', '2025-06-10', '2025-05-01', 0),
('12975478', '2025-01-08', '2026-01-08', '2025-08-15', 1),
('12977096', '2024-12-05', '2025-12-24', '2025-09-01', 1),
('13005500', '2024-02-20', '2025-02-20', '2025-01-15', 0),
('13051888', '2024-09-12', '2025-09-12', '2025-08-20', 1),
('13052282', '2025-02-14', '2026-02-14', '2025-09-05', 1),
('13056599', '2024-04-18', '2025-04-18', '2025-03-01', 0),
('13120290', '2024-10-30', '2025-10-30', '2025-08-25', 1),
('13131998', '2025-03-22', '2026-03-22', '2025-09-01', 1),
('13149274', '2024-07-15', '2025-07-15', '2025-06-01', 0),
('13278802', '2024-08-08', '2025-08-08', '2025-07-01', 0),
('13313513', '2025-01-12', '2026-01-12', '2025-09-08', 1),
('13320136', '2024-05-25', '2025-05-25', '2025-04-01', 0),
('13337271', '2024-11-18', '2025-11-18', '2025-09-01', 1),
('13346901', '2024-09-30', '2025-09-30', '2025-08-15', 1),
('13700390', '2024-12-12', '2025-12-12', '2025-09-05', 1),
('13754392', '2024-01-20', '2025-01-20', '2024-12-01', 0),
('13959008', '2024-10-05', '2025-10-05', '2025-08-20', 1),
('13998602', '2025-02-28', '2026-02-28', '2025-09-01', 1),
('14085766', '2024-04-12', '2025-04-12', '2025-03-15', 0),
('14106849', '2024-11-08', '2025-11-08', '2025-09-08', 1),
('14239818', '2024-06-22', '2025-06-22', '2025-05-15', 0),
('14306498', '2025-01-15', '2026-01-15', '2025-09-05', 1),
('14417017', '2024-08-30', '2025-08-30', '2025-07-20', 0),
('14439365', '2024-12-20', '2025-12-20', '2025-09-01', 1),
('14481000', '2024-03-05', '2025-03-05', '2025-02-01', 0),
('14485245', '2024-09-18', '2025-09-18', '2025-08-25', 1),
('14578971', '2025-03-10', '2026-03-10', '2025-09-08', 1),
('14862407', '2024-07-25', '2025-07-25', '2025-06-15', 0),
('15103377', '2024-05-08', '2025-05-08', '2025-04-10', 0),
('15269720', '2025-01-22', '2026-01-22', '2025-09-05', 1),
('15272925', '2024-11-12', '2025-11-12', '2025-09-08', 1),
('15360999', '2024-02-18', '2025-02-18', '2025-01-10', 0),
('15430279', '2024-08-14', '2025-08-14', '2025-07-25', 0),
('15471522', '2024-12-28', '2025-12-28', '2025-09-01', 1),
('15613896', '2024-04-20', '2025-04-20', '2025-03-20', 0),
('15616092', '2024-09-25', '2025-09-25', '2025-08-30', 1),
('15636478', '2025-02-08', '2026-02-08', '2025-09-05', 1),
('15714461', '2024-06-15', '2025-06-15', '2025-05-20', 0),
('15913594', '2024-10-22', '2025-10-22', '2025-09-01', 1),
('15997716', '2025-01-30', '2026-01-30', '2025-09-08', 1),
('16050530', '2024-03-12', '2025-03-12', '2025-02-15', 0),
('16120584', '2024-11-25', '2025-11-25', '2025-09-05', 1),
('16233536', '2024-07-08', '2025-07-08', '2025-06-10', 0),
('16286427', '2025-03-18', '2026-03-18', '2025-09-01', 1),
('29831802', '2025-10-05', '2026-10-05', '2025-10-05', 1);

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `lista_asistencias`  AS SELECT `a`.`id_atleta` AS `id_atleta`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `a`.`fecha` AS `fecha`, `a`.`hora_entrada` AS `hora_entrada`, `a`.`hora_salida` AS `hora_salida`, `a`.`estado_asistencia` AS `estado_asistencia`, `a`.`tipo_sesion` AS `tipo_sesion`, `a`.`rpe` AS `rpe`, `a`.`observaciones` AS `observaciones`, `a`.`registrado_por` AS `registrado_por`, `reg`.`nombre` AS `nombre_registrador`, `reg`.`apellido` AS `apellido_registrador`, `a`.`fecha_registro` AS `fecha_registro` FROM ((`asistencias` `a` join `gymsys_secure`.`usuarios` `u` on(`a`.`id_atleta` = `u`.`cedula`)) left join `gymsys_secure`.`usuarios` `reg` on(`a`.`registrado_por` = `reg`.`cedula`)) ;

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

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_resultados_detallados`
--
DROP TABLE IF EXISTS `vista_resultados_detallados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_resultados_detallados`  AS SELECT `c`.`nombre` AS `competencia`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `u`.`genero` AS `genero`, `a`.`peso` AS `peso_corporal`, `rc`.`arranque_intento1_peso` AS `arr1_peso`, CASE WHEN `rc`.`arranque_intento1_exito` = 1 THEN 'VÁLIDO' ELSE 'FALLO' END AS `arr1_resultado`, `rc`.`arranque_intento2_peso` AS `arr2_peso`, CASE WHEN `rc`.`arranque_intento2_exito` = 1 THEN 'VÁLIDO' ELSE 'FALLO' END AS `arr2_resultado`, `rc`.`arranque_intento3_peso` AS `arr3_peso`, CASE WHEN `rc`.`arranque_intento3_exito` = 1 THEN 'VÁLIDO' ELSE 'FALLO' END AS `arr3_resultado`, `rc`.`mejor_arranque` AS `mejor_arranque`, `rc`.`envion_intento1_peso` AS `env1_peso`, CASE WHEN `rc`.`envion_intento1_exito` = 1 THEN 'VÁLIDO' ELSE 'FALLO' END AS `env1_resultado`, `rc`.`envion_intento2_peso` AS `env2_peso`, CASE WHEN `rc`.`envion_intento2_exito` = 1 THEN 'VÁLIDO' ELSE 'FALLO' END AS `env2_resultado`, `rc`.`envion_intento3_peso` AS `env3_peso`, CASE WHEN `rc`.`envion_intento3_exito` = 1 THEN 'VÁLIDO' ELSE 'FALLO' END AS `env3_resultado`, `rc`.`mejor_envion` AS `mejor_envion`, `rc`.`total` AS `total`, `rc`.`intentos_arranque_exitosos` AS `intentos_arranque_exitosos`, `rc`.`intentos_envion_exitosos` AS `intentos_envion_exitosos`, concat(`rc`.`efectividad_arranque`,'%') AS `efectividad_arranque`, concat(`rc`.`efectividad_envion`,'%') AS `efectividad_envion`, `rc`.`medalla_arranque` AS `medalla_arranque`, `rc`.`medalla_envion` AS `medalla_envion`, `rc`.`medalla_total` AS `medalla_total` FROM (((`resultado_competencia` `rc` join `competencia` `c` on(`rc`.`id_competencia` = `c`.`id_competencia`)) join `atleta` `a` on(`rc`.`id_atleta` = `a`.`cedula`)) join `gymsys_secure`.`usuarios` `u` on(`a`.`cedula` = `u`.`cedula`)) ORDER BY `c`.`nombre` ASC, `rc`.`total` DESC ;

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
-- Indices de la tabla `lesiones`
--
ALTER TABLE `lesiones`
  ADD PRIMARY KEY (`id_lesion`),
  ADD KEY `id_atleta` (`id_atleta`),
  ADD KEY `fecha_lesion` (`fecha_lesion`),
  ADD KEY `registrado_por` (`registrado_por`);

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
  ADD PRIMARY KEY (`id_competencia`,`id_atleta`),
  ADD UNIQUE KEY `unique_id` (`id`),
  ADD KEY `fk_resultado_atleta` (`id_atleta`);

--
-- Indices de la tabla `subs`
--
ALTER TABLE `subs`
  ADD PRIMARY KEY (`id_sub`);

--
-- Indices de la tabla `test_fms`
--
ALTER TABLE `test_fms`
  ADD PRIMARY KEY (`id_test_fms`),
  ADD KEY `id_atleta` (`id_atleta`),
  ADD KEY `fecha_evaluacion` (`fecha_evaluacion`),
  ADD KEY `evaluador` (`evaluador`);

--
-- Indices de la tabla `test_postural`
--
ALTER TABLE `test_postural`
  ADD PRIMARY KEY (`id_test_postural`),
  ADD KEY `id_atleta` (`id_atleta`),
  ADD KEY `fecha_evaluacion` (`fecha_evaluacion`),
  ADD KEY `evaluador` (`evaluador`);

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
  MODIFY `id_categoria` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `competencia`
--
ALTER TABLE `competencia`
  MODIFY `id_competencia` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `lesiones`
--
ALTER TABLE `lesiones`
  MODIFY `id_lesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensualidades`
--
ALTER TABLE `mensualidades`
  MODIFY `id_mensualidad` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resultado_competencia`
--
ALTER TABLE `resultado_competencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `subs`
--
ALTER TABLE `subs`
  MODIFY `id_sub` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `test_fms`
--
ALTER TABLE `test_fms`
  MODIFY `id_test_fms` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `test_postural`
--
ALTER TABLE `test_postural`
  MODIFY `id_test_postural` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipo_atleta`
--
ALTER TABLE `tipo_atleta`
  MODIFY `id_tipo_atleta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `tipo_competencia`
--
ALTER TABLE `tipo_competencia`
  MODIFY `id_tipo_competencia` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

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
-- Filtros para la tabla `lesiones`
--
ALTER TABLE `lesiones`
  ADD CONSTRAINT `lesiones_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `lesiones_ibfk_2` FOREIGN KEY (`registrado_por`) REFERENCES `gymsys_secure`.`usuarios` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensualidades`
--
ALTER TABLE `mensualidades`
  ADD CONSTRAINT `mensualidades_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `resultado_competencia`
--
ALTER TABLE `resultado_competencia`
  ADD CONSTRAINT `fk_resultado_atleta` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_resultado_competencia` FOREIGN KEY (`id_competencia`) REFERENCES `competencia` (`id_competencia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `test_fms`
--
ALTER TABLE `test_fms`
  ADD CONSTRAINT `test_fms_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `test_fms_ibfk_2` FOREIGN KEY (`evaluador`) REFERENCES `gymsys_secure`.`usuarios` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `test_postural`
--
ALTER TABLE `test_postural`
  ADD CONSTRAINT `test_postural_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `test_postural_ibfk_2` FOREIGN KEY (`evaluador`) REFERENCES `gymsys_secure`.`usuarios` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `wada`
--
ALTER TABLE `wada`
  ADD CONSTRAINT `wada_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
