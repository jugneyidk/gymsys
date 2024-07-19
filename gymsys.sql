SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `gymsys` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gymsys`;

CREATE TABLE `asistencias` (
  `id_atleta` varchar(10) NOT NULL,
  `asistio` tinyint(1) NOT NULL,
  `fecha` date NOT NULL,
  `comentario` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `atleta` (
  `cedula` varchar(10) NOT NULL,
  `entrenador` varchar(10) NOT NULL,
  `tipo_atleta` int(11) NOT NULL,
  `peso` decimal(6,2) NOT NULL,
  `altura` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `atleta` (`cedula`, `entrenador`, `tipo_atleta`, `peso`, `altura`) VALUES
('28609560', '28609561', 0, 59.00, 169.00);

CREATE TABLE `bitacora` (
  `id_accion` int(50) NOT NULL,
  `id_usuario` varchar(10) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `usuario_modificado` varchar(10) DEFAULT NULL,
  `valor_cambiado` varchar(100) DEFAULT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categorias` (
  `id_categoria` int(5) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `peso_minimo` decimal(10,2) NOT NULL,
  `peso_maximo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `entrenador` (
  `cedula` varchar(10) NOT NULL,
  `grado_instruccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `entrenador` (`cedula`, `grado_instruccion`) VALUES
('22222222', 'dsffdfsdf');

CREATE TABLE `marcas` (
  `id_marca` int(10) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `arranque` decimal(10,2) NOT NULL,
  `envion` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `mensualidades` (
  `id_mensualidad` int(50) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `tipo` int(2) NOT NULL,
  `monto` decimal(20,2) NOT NULL,
  `cobro` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mensualidades` (`id_mensualidad`, `id_atleta`, `fecha`, `tipo`, `monto`, `cobro`) VALUES
(2, '28609560', '2024-07-15', 0, 0.00, 0);

CREATE TABLE `modulos` (
  `id_modulo` int(50) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `permisos` (
  `id_rol` int(50) NOT NULL,
  `modulo` int(50) NOT NULL,
  `crear` tinyint(4) NOT NULL,
  `leer` tinyint(4) NOT NULL,
  `actualizar` tinyint(4) NOT NULL,
  `eliminar` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `representantes` (
  `cedula` varchar(10) NOT NULL,
  `id_atleta` varchar(10) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `roles` (
  `id_rol` int(50) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(0, 'atleta'),
(1, 'entrenador');

CREATE TABLE `subs` (
  `id_sub` int(5) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `edad_minima` int(3) NOT NULL,
  `edad_maxima` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipo_competencia` (
  `id_tipo_competencia` int(5) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

INSERT INTO `usuarios` (`cedula`, `nombre`, `apellido`, `genero`, `fecha_nacimiento`, `lugar_nacimiento`, `estado_civil`, `telefono`, `correo_electronico`) VALUES
('22222222', 'jugneys', 'dfdfdf', 'Masculino', '2002-07-15', 'sdfdsfdfds', 'Soltero', '04245681343', 'dsfdsfd@gmail.com'),
('28609560', 'jugney', 'vargas', 'Masculino', '2002-07-15', 'dsdj', 'Soltero', '04245681343', 'KJSHJSHKJH@GMAIL.COM');

CREATE TABLE `usuarios_roles` (
  `id_usuario` varchar(10) NOT NULL,
  `id_rol` int(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios_roles` (`id_usuario`, `id_rol`, `password`, `token`) VALUES
('22222222', 1, '$2y$10$syf4uVv4j1iML9whitgx2.ylZwVlWhUHrA7zFhvMyP0qqpzD6yNWO', '0'),
('28609560', 0, 'jugney28609560', '0');

CREATE TABLE `wada` (
  `id_atleta` varchar(10) NOT NULL,
  `inscrito` date NOT NULL,
  `vencimiento` date NOT NULL,
  `ultima_actualizacion` date NOT NULL,
  `estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `asistencias`
  ADD KEY `id_atleta` (`id_atleta`);

ALTER TABLE `atleta`
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `entrenador` (`entrenador`);

ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id_accion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `usuario_modificado` (`usuario_modificado`);

ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

ALTER TABLE `competencia`
  ADD PRIMARY KEY (`id_competencia`),
  ADD KEY `categoria` (`categoria`),
  ADD KEY `subs` (`subs`),
  ADD KEY `tipo_competicion` (`tipo_competicion`);

ALTER TABLE `entrenador`
  ADD UNIQUE KEY `cedula` (`cedula`);

ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id_marca`),
  ADD KEY `id_atleta` (`id_atleta`);

ALTER TABLE `mensualidades`
  ADD PRIMARY KEY (`id_mensualidad`),
  ADD KEY `id_atleta` (`id_atleta`);

ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulo`);

ALTER TABLE `permisos`
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `modulo` (`modulo`);

ALTER TABLE `representantes`
  ADD UNIQUE KEY `id_atleta` (`id_atleta`);

ALTER TABLE `resultado_competencia`
  ADD PRIMARY KEY (`id_competencia`),
  ADD KEY `id_atleta` (`id_atleta`);

ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

ALTER TABLE `subs`
  ADD PRIMARY KEY (`id_sub`);

ALTER TABLE `tipo_competencia`
  ADD PRIMARY KEY (`id_tipo_competencia`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cedula`);

ALTER TABLE `usuarios_roles`
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

ALTER TABLE `wada`
  ADD UNIQUE KEY `id_atleta` (`id_atleta`) USING BTREE;


ALTER TABLE `bitacora`
  MODIFY `id_accion` int(50) NOT NULL AUTO_INCREMENT;

ALTER TABLE `categorias`
  MODIFY `id_categoria` int(5) NOT NULL AUTO_INCREMENT;

ALTER TABLE `competencia`
  MODIFY `id_competencia` int(50) NOT NULL AUTO_INCREMENT;

ALTER TABLE `marcas`
  MODIFY `id_marca` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mensualidades`
  MODIFY `id_mensualidad` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `modulos`
  MODIFY `id_modulo` int(50) NOT NULL AUTO_INCREMENT;

ALTER TABLE `resultado_competencia`
  MODIFY `id_competencia` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `roles`
  MODIFY `id_rol` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `subs`
  MODIFY `id_sub` int(5) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tipo_competencia`
  MODIFY `id_tipo_competencia` int(5) NOT NULL AUTO_INCREMENT;


ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

ALTER TABLE `atleta`
  ADD CONSTRAINT `atleta_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`) ON UPDATE CASCADE;

ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bitacora_ibfk_2` FOREIGN KEY (`usuario_modificado`) REFERENCES `usuarios` (`cedula`) ON UPDATE CASCADE;

ALTER TABLE `competencia`
  ADD CONSTRAINT `competencia_ibfk_1` FOREIGN KEY (`tipo_competicion`) REFERENCES `tipo_competencia` (`id_tipo_competencia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `competencia_ibfk_2` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`id_categoria`) ON UPDATE CASCADE,
  ADD CONSTRAINT `competencia_ibfk_3` FOREIGN KEY (`subs`) REFERENCES `subs` (`id_sub`) ON UPDATE CASCADE;

ALTER TABLE `entrenador`
  ADD CONSTRAINT `entrenador_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`) ON UPDATE CASCADE;

ALTER TABLE `marcas`
  ADD CONSTRAINT `marcas_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

ALTER TABLE `mensualidades`
  ADD CONSTRAINT `mensualidades_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`modulo`) REFERENCES `modulos` (`id_modulo`) ON UPDATE CASCADE;

ALTER TABLE `representantes`
  ADD CONSTRAINT `representantes_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

ALTER TABLE `resultado_competencia`
  ADD CONSTRAINT `resultado_competencia_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `resultado_competencia_ibfk_2` FOREIGN KEY (`id_competencia`) REFERENCES `competencia` (`id_competencia`) ON UPDATE CASCADE;

ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`cedula`) ON UPDATE CASCADE;

ALTER TABLE `wada`
  ADD CONSTRAINT `wada_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
