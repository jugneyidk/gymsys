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

INSERT INTO `asistencias` (`id_atleta`, `asistio`, `fecha`, `comentario`) VALUES
('9252463', 1, '2024-07-20', '');

CREATE TABLE `atleta` (
  `cedula` varchar(10) NOT NULL,
  `entrenador` varchar(10) NOT NULL,
  `tipo_atleta` int(11) NOT NULL,
  `peso` decimal(6,2) NOT NULL,
  `altura` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('682815813', '22222222', 1, 75.00, 24.00),
('682815815', '22222222', 1, 75.00, 24.00),
('682815818', '22222222', 1, 75.00, 24.00),
('682815819', '22222222', 1, 75.00, 24.00),
('68281582', '22222222', 1, 75.00, 24.00),
('9252463', '22222222', 0, 73.00, 100.00);

CREATE TABLE `bitacora` (
  `id_accion` int(50) NOT NULL,
  `id_usuario` varchar(10) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `usuario_modificado` varchar(10) DEFAULT NULL,
  `valor_cambiado` varchar(100) DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bitacora` (`id_accion`, `id_usuario`, `accion`, `usuario_modificado`, `valor_cambiado`, `fecha`) VALUES
(3, '22222222', 'elimino', '28609560', NULL, '2024-07-20'),
(16, '22222222', 'Agregó', '42342344', NULL, '2024-07-20'),
(17, '22222222', 'Agregó', '3376883', NULL, '2024-07-20'),
(18, '22222222', 'Agregó un atleta', '6759472', NULL, '2024-07-20');

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
(3, '9252463', '2024-07-26', 0, 23.00, 0);

CREATE TABLE `modulos` (
  `id_modulo` int(50) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `permisos` (
  `id_rol` int(50) NOT NULL,
  `modulo` int(50) NOT NULL,
  `crear` tinyint(4) NOT NULL,
  `leer` tinyint(4) NOT NULL,
  `actualizar` tinyint(4) NOT NULL,
  `eliminar` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'entrenador'),
(30, 'rol prueba');

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
('23124144', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('24244444', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('2594894', 'Repudiandae harum do', 'Voluptatem et labori', 'Masculino', '1992-09-17', 'Molestiae officia ad', 'Divorciado', '04844940895', 'sikylydig@mailinator.com'),
('28609560', 'jugney', 'vargas', 'Masculino', '2002-07-15', 'dsdj', 'Soltero', '04245681343', 'KJSHJSHKJH@GMAIL.COM'),
('3376883', 'Id voluptas rerum c', 'Velit in blanditiis ', 'Masculino', '2004-12-27', 'Maiores fugiat aut ', 'Casado', '04534055751', 'zazehoz@mailinator.com'),
('42342344', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('6645684', 'Est pariatur Nihil ', 'Non et non molestias', 'Femenino', '2003-01-13', 'Ex qui architecto to', 'Viudo', '04823255865', 'nudob@mailinator.com'),
('66456842', 'Est pariatur Nihil ', 'Non et non molestias', 'Femenino', '2003-01-13', 'Ex qui architecto to', 'Viudo', '04823255865', 'nudob@mailinator.com'),
('664568422', 'Est pariatur Nihil ', 'Non et non molestias', 'Femenino', '2003-01-13', 'Ex qui architecto to', 'Viudo', '04823255865', 'nudob@mailinator.com'),
('6759472', 'Minima adipisci anim', 'Non aliquam voluptat', 'Masculino', '1995-11-03', 'Sed deserunt quis as', 'Casado', '04418277535', 'pise@mailinator.com'),
('6828158', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('68281580', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('68281581', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('682815811', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('682815813', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('682815815', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('682815818', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('682815819', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('68281582', 'Et magni est odio m', 'Ea velit impedit o', 'Masculino', '1996-03-04', 'Ut quaerat eveniet ', 'Viudo', '04436386697', 'zuda@mailinator.com'),
('9252463', 'Reprehenderit fuga ', 'Sit impedit vero in', 'Masculino', '1989-03-13', 'Et accusantium maior', 'Viudo', '04559403067', 'lodujobyqa@mailinator.com');

CREATE TABLE `usuarios_roles` (
  `id_usuario` varchar(10) NOT NULL,
  `id_rol` int(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios_roles` (`id_usuario`, `id_rol`, `password`, `token`) VALUES
('22222222', 30, '$2y$10$syf4uVv4j1iML9whitgx2.ylZwVlWhUHrA7zFhvMyP0qqpzD6yNWO', '0'),
('23124144', 0, '$2y$10$.U6xdgWQPqo4HJbgPmi.u.j.6XZzLH46HiGXdB5lIS19BATJIKaCa', '0'),
('24244444', 0, '$2y$10$AlnDESIrQ20GjFP2bL5G5eXc.FAXQsbITN.Z1VSxpOf2EtaIaU6Oq', '0'),
('2594894', 0, '$2y$10$XWoALDkOSs/n2fT30oBlluAS9RPUNKozcntpT/Tk9b4zJvR.mYZCO', '0'),
('3376883', 0, '$2y$10$ltWDsUEwgZ94BjvHY7tHMO7oJM6bEzPqoyzeqhQDExl6.tfRR5MeG', '0'),
('42342344', 0, '$2y$10$oPNkW491S4A4p7dKf2ngSePU2L4oBz/iezYPFKXxNagC6hduogbZ.', '0'),
('6645684', 0, '$2y$10$84Huw1bt0oXtZ8mnIeNtPu9D0Qt0zhYLMkocsA413vMWX5YxP2tii', '0'),
('66456842', 0, '$2y$10$wdnIzo5Js4PI8TInBAhL.ORc1siZNVKbbaNg9ir99GZ3fuR24cTou', '0'),
('664568422', 0, '$2y$10$P6vxsbx8q8ITDfhHqu9VaOU310ZTDjdPoBFIn5AKZtMiMIMB91adS', '0'),
('6759472', 0, '$2y$10$OulaL.OOEq7fwxJmig51rugXt4UHPPywK/R5oMclKs7jvytCSYrUu', '0'),
('6828158', 0, '$2y$10$md4PwPLFMXm6RQf8gine2OLunXu4Y/l75cAik4GrKNk.FDAMqSm.G', '0'),
('68281580', 0, '$2y$10$hvCMlvd9BEFiyXbdghtz.eikWnVVyv.0XRGYLPIX/dlwhOAd6oia6', '0'),
('68281581', 0, '$2y$10$QxgU9C2kdQnmURdS.YhOkORUbk33RyNPXBS6MCjfgOCBpF1fY6yY2', '0'),
('682815811', 0, '$2y$10$JQ4ytSeERzWg5630.qYZRuDLmRBM219/ABuAAAoJve52TsELtR1tW', '0'),
('682815813', 0, '$2y$10$56Ny5itgre8c7qEyejeGf.cN64g0MOWK0oEm5461cR1UXCpgQ5d4S', '0'),
('682815815', 0, '$2y$10$gyjv.k/2GA8PVklPv8VtcOylHuUrvtF2ykym2vrzMK/rbazY7ZmPS', '0'),
('682815818', 0, '$2y$10$s9EfBbzTfXgumE5NZAKzvu8QshZq.Ic1O0uuZ7ytk7V6L4GyZhEcC', '0'),
('682815819', 0, '$2y$10$t3YdC2hn7OuC0a6yzWFf/O8D6w8Z5C5ty6.F6jLT/EplLOna3NUCa', '0'),
('68281582', 0, '$2y$10$IegZMzWD3iDEV7Zxu5Z8k.rX.pg1ib8jPSd2k57kz4QXzar8XiuWO', '0'),
('9252463', 0, '$2y$10$HKTnPY5Ndj4ljvylWoszouAsfl8RyRll5pSpZOUmQI7Wb9i.9SibO', '0');

CREATE TABLE `wada` (
  `id_atleta` varchar(10) NOT NULL,
  `inscrito` date NOT NULL,
  `vencimiento` date NOT NULL,
  `ultima_actualizacion` date NOT NULL,
  `estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wada` (`id_atleta`, `inscrito`, `vencimiento`, `ultima_actualizacion`, `estado`) VALUES
('9252463', '2024-07-20', '2024-08-10', '2024-07-20', 1);


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
  MODIFY `id_accion` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

ALTER TABLE `categorias`
  MODIFY `id_categoria` int(5) NOT NULL AUTO_INCREMENT;

ALTER TABLE `competencia`
  MODIFY `id_competencia` int(50) NOT NULL AUTO_INCREMENT;

ALTER TABLE `marcas`
  MODIFY `id_marca` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mensualidades`
  MODIFY `id_mensualidad` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `modulos`
  MODIFY `id_modulo` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `resultado_competencia`
  MODIFY `id_competencia` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `roles`
  MODIFY `id_rol` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

ALTER TABLE `subs`
  MODIFY `id_sub` int(5) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tipo_competencia`
  MODIFY `id_tipo_competencia` int(5) NOT NULL AUTO_INCREMENT;


ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atleta` (`cedula`) ON UPDATE CASCADE;

ALTER TABLE `atleta`
  ADD CONSTRAINT `atleta_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`) ON UPDATE CASCADE,
  ADD CONSTRAINT `atleta_ibfk_2` FOREIGN KEY (`entrenador`) REFERENCES `entrenador` (`cedula`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
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
