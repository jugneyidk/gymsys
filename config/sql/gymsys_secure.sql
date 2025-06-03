-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-05-2025 a las 04:10:55
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
-- Base de datos: `gymsys_secure`
--
CREATE DATABASE IF NOT EXISTS `gymsys_secure` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gymsys_secure`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_accion` int(50) NOT NULL,
  `id_usuario` varchar(10) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `registro_modificado` varchar(20) DEFAULT NULL,
  `detalles` varchar(2000) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(23, '28609560', 'Una WADA vencerá pronto', 'La WADA del atleta Diego Salazar se vencerá el 2024-12-10', 1, 'wada', '2024-11-27 13:37:53'),
(24, '28609560', 'Una WADA vencerá pronto', 'La WADA del atleta Diego Salazar se vencerá el 2024-12-07', 1, 'wada', '2024-11-29 09:04:36'),
(33, '28609560', 'Una WADA vencerá pronto', 'La WADA del atleta Diego Salazar se vencerá en 30 días', 1, 'wada', '2024-11-29 10:21:12'),
(34, '28609560', 'Una WADA vencerá pronto', 'La WADA del atleta Diego Salazar se vencerá en 15 días', 1, 'wada', '2024-11-29 10:21:43'),
(35, '28609560', 'Una WADA vencerá pronto', 'La WADA del atleta Diego Salazar se vencerá en 7 días', 1, 'wada', '2024-11-29 10:22:04'),
(36, '28609560', 'Una WADA vencerá pronto', 'La WADA del atleta Diego Salazar se vencerá en 1 día', 1, 'wada', '2024-11-29 10:22:17'),
(37, '28609560', 'La WADA ha vencido hoy', 'La WADA del atleta Diego Salazar se venció', 1, 'wada', '2024-11-29 10:22:38'),
(43, '28609560', 'Atletas con mensualidad pendiente', 'Hay 1 atleta que debe la mensualidad este mes', 0, 'mensualidad', '2024-11-29 10:40:36'),
(45, '7376581', 'Atletas con mensualidad pendiente', 'Hay 1 atleta que debe la mensualidad este mes', 0, 'mensualidad', '2025-05-25 06:02:26');

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
(1, 1, 1, 1, 0, 0),
(1, 2, 1, 1, 1, 0),
(1, 3, 0, 0, 0, 0),
(1, 4, 1, 1, 0, 0),
(1, 5, 1, 1, 1, 0),
(1, 6, 1, 1, 0, 0),
(1, 7, 1, 1, 0, 0),
(1, 8, 1, 1, 0, 0),
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
(56, 1, 1, 0, 0, 0),
(56, 2, 0, 1, 0, 0),
(56, 3, 0, 0, 1, 0),
(56, 4, 0, 0, 0, 1),
(56, 5, 0, 0, 1, 0),
(56, 6, 0, 1, 0, 0),
(56, 7, 1, 0, 0, 0),
(56, 8, 1, 1, 0, 0),
(56, 9, 0, 1, 0, 0);

--
-- Disparadores `permisos`
--
DELIMITER $$
CREATE TRIGGER `after_permisos_update` AFTER UPDATE ON `permisos` FOR EACH ROW BEGIN
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
            INSERT INTO bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
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
-- Estructura de tabla para la tabla `reset`
--

CREATE TABLE `reset` (
  `id` int(11) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expira` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `reset`
--
DELIMITER $$
CREATE TRIGGER `after_reset_insert` AFTER INSERT ON `reset` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Recovery', NEW.cedula, NEW.email, CONCAT('El usuario solicitó un restablecimiento de contraseña, expira el: ', NEW.expira));
END
$$
DELIMITER ;

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
(1, 'Entrenador'),
(2, 'Superusuario'),
(3, 'Administrador');

--
-- Disparadores `roles`
--
DELIMITER $$
CREATE TRIGGER `after_rol_create` AFTER INSERT ON `roles` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
    VALUES ('Incluir', 'Roles', @usuario_actual, NEW.nombre, CONCAT('Se agregó el Rol: ', NEW.nombre));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_rol_delete` AFTER DELETE ON `roles` FOR EACH ROW BEGIN
    INSERT INTO bitacora (accion, modulo, id_usuario, registro_modificado, detalles)
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
('28609560', 'Jugney', 'Vargas', 'Masculino', '2012-06-22', 'El triunfo', 'Casado', '04244034515', 'jugneycontacto@gmail.com'),
('29831802', 'FABIAN JOSE', 'MARQUEZ LUCES', 'Masculino', '2002-07-20', 'San Felipe', 'Soltero', '04142235183', 'fabianmarquezjl@gmail.com'),
('7376581', 'Miguel Felipe', 'Mujica Martinez', 'Masculino', '1964-03-31', 'Barquisimeto', 'Casado', '04262575401', 'mmiguelon@gmail.com');

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
('28609560', 2, '$2y$10$W4olv/Ki52lHBbB3gPtMzuVOe1jkXKjNk.hD/nVWIlGzOvpW0tx9i', ''),
('29831802', 0, '$2y$10$MkSjpa8SiZ4TBiX/kgjlAesaogL/c7LMnufggEQHjGFqdy9k07y8O', ''),
('7376581', 1, '$2y$10$ROREY99F/EY5PNMYYhYh0eB9UADEEa1/v63z0qWbaXLvijBCCgM5y', '');

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
    registro_modificado,
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

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id_accion`),
  ADD KEY `id_usuario` (`id_usuario`);

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
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_accion` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `reset`
--
ALTER TABLE `reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `reset_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`);

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`),
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`cedula`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
