-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-09-2025 a las 00:00:21
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_risingfarms3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id_cargo` int(11) NOT NULL,
  `nombre_completo` varchar(50) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `departamento` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id_cargo`, `nombre_completo`, `cargo`, `departamento`) VALUES
(1, 'ANTONIO GÓMEZ BOLAÑOS', 'DON COMEDIA', 'DISQUE MEJORA CONINUA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciclos`
--

CREATE TABLE `ciclos` (
  `id_ciclo` int(11) NOT NULL,
  `ciclo` varchar(30) NOT NULL,
  `activo_c` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ciclos`
--

INSERT INTO `ciclos` (`id_ciclo`, `ciclo`, `activo_c`) VALUES
(1, '2024-2025', 1),
(2, '2025-2026', 1),
(3, '2026-2027', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion_merma`
--

CREATE TABLE `clasificacion_merma` (
  `id_merma` int(11) NOT NULL,
  `tipo_merma` varchar(40) NOT NULL,
  `motivo` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clasificacion_merma`
--

INSERT INTO `clasificacion_merma` (`id_merma`, `tipo_merma`, `motivo`) VALUES
(1, 'Producción', 'Deformidad'),
(2, 'Producción', 'Firmeza'),
(3, 'Producción', 'Coloración'),
(4, 'Producción', 'Tamaño'),
(5, 'Producción', 'Daños por Plaga o Enfermedad'),
(6, 'Producción', 'Suciedad de Materia extraña'),
(7, 'Producción', 'Daños Mecánicos'),
(8, 'Producción', 'Otros'),
(9, 'Nacional', 'Deformidad'),
(10, 'Nacional', 'Firmeza'),
(11, 'Nacional', 'Coloración'),
(12, 'Nacional', 'Tamaño'),
(13, 'Nacional', 'Daños por Plaga o Enfermedad'),
(14, 'Nacional', 'Suciedad de Materia extraña'),
(15, 'Nacional', 'Cracking'),
(16, 'Nacional', 'Hombro verde'),
(17, 'Nacional', 'Cicatriz'),
(18, 'Nacional', 'Otros'),
(19, 'Empaque', 'Aplastado'),
(20, 'Empaque', 'Golpeado'),
(21, 'Empaque', 'Rajado'),
(22, 'Empaque', 'Cortado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invernaderos`
--

CREATE TABLE `invernaderos` (
  `id_invernadero` int(11) NOT NULL,
  `id_sede_i` int(11) NOT NULL,
  `invernadero` int(11) NOT NULL,
  `superficie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `invernaderos`
--

INSERT INTO `invernaderos` (`id_invernadero`, `id_sede_i`, `invernadero`, `superficie`) VALUES
(1, 3, 1, 19828),
(2, 3, 2, 19584),
(3, 3, 3, 20155),
(4, 3, 4, 19732),
(5, 3, 5, 20142),
(6, 3, 6, 16198),
(7, 3, 7, 14876);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_seccion` int(11) NOT NULL,
  `nombre_seccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_seccion`, `nombre_seccion`) VALUES
(1, 'Empaque/Pesaje'),
(2, 'Empaque/Mezcla'),
(3, 'Empaque/Pallet'),
(4, 'Empaque/Embarque');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permiso` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `nombre`) VALUES
(1, 'VER'),
(2, 'CREAR'),
(3, 'EDITAR'),
(4, 'ELIMINAR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_usuarios`
--

CREATE TABLE `permisos_usuarios` (
  `id_permiso_u` int(11) NOT NULL,
  `id_usuario_u` int(11) NOT NULL,
  `id_modulo_u` int(11) NOT NULL,
  `id_permisos_u` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos_usuarios`
--

INSERT INTO `permisos_usuarios` (`id_permiso_u`, `id_usuario_u`, `id_modulo_u`, `id_permisos_u`) VALUES
(1, 4, 1, 1),
(2, 5, 1, 1),
(3, 5, 2, 2),
(4, 5, 3, 3),
(5, 5, 4, 1),
(6, 5, 4, 4),
(7, 6, 1, 1),
(8, 6, 1, 2),
(9, 6, 1, 3),
(10, 6, 1, 4),
(11, 6, 2, 1),
(12, 6, 2, 2),
(13, 6, 2, 3),
(14, 6, 2, 4),
(15, 6, 3, 1),
(16, 6, 3, 2),
(17, 6, 3, 3),
(18, 6, 3, 4),
(19, 6, 4, 1),
(20, 6, 4, 2),
(21, 6, 4, 3),
(22, 6, 4, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_empaque`
--

CREATE TABLE `registro_empaque` (
  `id_registro_r` int(11) NOT NULL,
  `id_codigo_r` int(11) DEFAULT NULL,
  `folio_r` varchar(50) NOT NULL,
  `id_tipo_caja` int(11) NOT NULL,
  `id_tipo_tarima` int(11) DEFAULT NULL,
  `id_tipo_carro` int(11) DEFAULT NULL,
  `p_bruto` decimal(10,3) NOT NULL,
  `p_taraje` decimal(10,3) NOT NULL,
  `p_neto` decimal(10,3) NOT NULL,
  `cantidad_caja` int(11) NOT NULL,
  `cantidad_tarima` int(11) NOT NULL,
  `usuario_r` varchar(100) NOT NULL,
  `fecha_r` date NOT NULL,
  `hora_r` time NOT NULL,
  `activo_r` tinyint(1) NOT NULL,
  `tipo_registro` varchar(40) NOT NULL,
  `id_tipo_merma` int(11) DEFAULT NULL,
  `no_serie_r` varchar(50) DEFAULT NULL,
  `semana_r` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_empaque`
--

INSERT INTO `registro_empaque` (`id_registro_r`, `id_codigo_r`, `folio_r`, `id_tipo_caja`, `id_tipo_tarima`, `id_tipo_carro`, `p_bruto`, `p_taraje`, `p_neto`, `cantidad_caja`, `cantidad_tarima`, `usuario_r`, `fecha_r`, `hora_r`, `activo_r`, `tipo_registro`, `id_tipo_merma`, `no_serie_r`, `semana_r`) VALUES
(9, 11, 'fghf', 1, 1, 1, 642.000, 224.760, 417.240, 88, 0, 'prueba', '2025-09-02', '15:22:25', 1, 'NORMAL', NULL, '3-5-XAC-020925-004', '2025-36'),
(10, 11, 'fghf', 1, 1, 1, 642.000, 157.560, 484.440, 88, 0, 'prueba', '2025-09-03', '06:34:29', 1, 'NORMAL', NULL, '3-5-XAC-030925-001', '2025-36'),
(11, 11, 'fghf', 1, 1, 1, 642.000, 157.560, 484.440, 88, 0, 'prueba', '2025-09-03', '06:35:22', 1, 'NORMAL', NULL, '3-5-XAC-030925-002', '2025-36'),
(12, 11, '1', 1, 1, 1, 13.000, 1.495, 11.505, 1, 0, 'prueba', '2025-09-03', '10:20:09', 1, 'NORMAL', NULL, '3-5-XAC-030925-003', '2025-36'),
(13, 11, '1', 1, 1, 1, 13.000, 1.495, 11.505, 1, 0, 'prueba', '2025-09-03', '10:29:33', 1, 'NORMAL', NULL, '3-5-XAC-030925-004', '2025-36'),
(14, 10, '333', 1, 2, 1, 3366.000, 280.335, 3085.665, 33, 33, 'prueba', '2025-09-03', '10:42:41', 1, 'MERMA', 2, '3511-030925-001', '2025-36'),
(15, 11, '12', 1, 2, 1, 344.000, 24.940, 319.060, 12, 1, 'prueba', '2025-09-03', '10:45:30', 1, 'NORMAL', NULL, '3-5-XAC-030925-005', '2025-36'),
(16, 11, '122', 1, 2, 1, 122.000, 11.485, 110.515, 3, 1, 'prueba', '2025-09-03', '11:09:05', 1, 'NORMAL', NULL, '3-5-XAC-030925-006', '2025-36'),
(17, 11, '122', 1, 2, 1, 122.000, 11.485, 110.515, 3, 1, 'prueba', '2025-09-03', '11:11:52', 1, 'NORMAL', NULL, '3-5-XAC-030925-007', '2025-36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol`) VALUES
(2, 'ADMINISTRADOR'),
(3, 'SUPERVISOR'),
(4, 'USUARIO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sedes`
--

CREATE TABLE `sedes` (
  `id_sede` int(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `ubicacion` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`id_sede`, `nombre`, `codigo`, `ubicacion`) VALUES
(1, 'RISING FARM 1', 'RF1', 'PEDRO ESCOBEDO, QUERÉTARO'),
(2, 'RISING FARM 2', 'RF2', 'TEQUISQUIAPAN, QUERÉTARO'),
(3, 'RISING FARM 3', 'RF3', 'RIOVERDE, SAN LUIS POTOSÍ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_cajas`
--

CREATE TABLE `tipos_cajas` (
  `id_caja` int(11) NOT NULL,
  `tipo_caja` varchar(30) NOT NULL,
  `peso_caja` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_cajas`
--

INSERT INTO `tipos_cajas` (`id_caja`, `tipo_caja`, `peso_caja`) VALUES
(1, 'RPC', 1.495);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_carros`
--

CREATE TABLE `tipos_carros` (
  `id_carro` int(11) NOT NULL,
  `folio_carro` varchar(10) NOT NULL,
  `peso_carro` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_carros`
--

INSERT INTO `tipos_carros` (`id_carro`, `folio_carro`, `peso_carro`) VALUES
(1, 'NO APLICA', 0.000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_presentacion`
--

CREATE TABLE `tipos_presentacion` (
  `id_presentacion` int(11) NOT NULL,
  `nombre_p` varchar(30) NOT NULL,
  `abreviatura_p` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_presentacion`
--

INSERT INTO `tipos_presentacion` (`id_presentacion`, `nombre_p`, `abreviatura_p`) VALUES
(1, 'RACIMO', 'RC'),
(2, 'SUELTO', 'ST');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_tarimas`
--

CREATE TABLE `tipos_tarimas` (
  `id_tarima` int(11) NOT NULL,
  `nombre_tarima` varchar(25) NOT NULL,
  `peso_tarima` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_tarimas`
--

INSERT INTO `tipos_tarimas` (`id_tarima`, `nombre_tarima`, `peso_tarima`) VALUES
(1, 'NO APLICA', 0.000),
(2, 'MADERA', 7.000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_variaciones`
--

CREATE TABLE `tipo_variaciones` (
  `id_variedad` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `id_nombre_v` int(11) NOT NULL,
  `tipo` varchar(40) NOT NULL,
  `color` varchar(25) NOT NULL,
  `superficie` decimal(10,3) NOT NULL,
  `id_presentacion_v` int(11) NOT NULL,
  `id_ciclo_v` int(11) NOT NULL,
  `id_modulo_v` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_variaciones`
--

INSERT INTO `tipo_variaciones` (`id_variedad`, `codigo`, `id_nombre_v`, `tipo`, `color`, `superficie`, `id_presentacion_v`, `id_ciclo_v`, `id_modulo_v`) VALUES
(10, '3511', 9, 'ROMA', 'CAFÉ', 342.000, 2, 2, 5),
(11, '3-5-XAC', 8, 'COOCKTAIL', 'ROJO', 345.000, 1, 2, 5),
(12, '3-3-CNL', 6, 'CHERRY', 'CAFÉ', 344.000, 1, 3, 3),
(13, '3-3-184', 9, 'CHERRY', 'ROJO', 36.000, 1, 2, 3),
(14, '3-2-BAL', 10, 'ROMA', 'ROJO', 455.000, 1, 3, 2),
(15, '3-3-XAC', 8, 'ROMA', 'CAFÉ', 344.000, 1, 2, 3),
(16, '3-1-768', 4, 'CHERRY', 'ROJO', 12.000, 1, 2, 1),
(17, '3-4-CNL', 6, 'CHERRY', 'ROJO', 12234.000, 1, 2, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `password`, `id_cargo`, `id_rol`, `estado`) VALUES
(1, 'sdf', '$2y$15$nnE.Taf4.c7B0RLEEU9YBudRKAgk/Ws/K2We97JO7SUQR2qC5XC7y', 1, 3, 0),
(2, 'sdf', '$2y$15$pa2AXTL0A.MhGN3Ma7vhLeCn388.8q8JH7mkdDoAjGr.e8ZA4iVEC', 1, 3, 0),
(3, 'sdf', '$2y$15$PVCEgPrAYnV05N5H3CUTDevhTht6SyI631yj4eNC0Whj3xOOABw1C', 1, 3, 0),
(4, 'sdf', '$2y$15$sWwfVNfY.xRSfEtGxPuVjOk7VRVgdsVku4338EOjqErBPu2uoaENi', 1, 3, 0),
(5, '54', '$2y$15$O0fuu3Q3v734IKOWb8BW.ecZtXU2qG72I2EH/iiDTsvyBBogk8JZ.', 1, 3, 0),
(6, '1234', '$2y$15$gEyU9n1gUOMKvbT/HKjOIOJ10yyaZOpv7m0/Oa/ahGERO59qFzm/W', 1, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `variedades`
--

CREATE TABLE `variedades` (
  `id_nombre_v` int(11) NOT NULL,
  `nombre_variedad` varchar(25) NOT NULL,
  `abreviatura` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `variedades`
--

INSERT INTO `variedades` (`id_nombre_v`, `nombre_variedad`, `abreviatura`) VALUES
(1, 'TT-826', '826'),
(2, 'ADORELLE', 'ADO'),
(3, 'TATOO', 'TAT'),
(4, 'TT-768', '768'),
(5, 'PRUNAX', 'PRX'),
(6, 'CANELO', 'CNL'),
(7, 'DORMA ROJO', 'DMA'),
(8, 'XACAO', 'XAC'),
(9, '1184', '184'),
(10, 'BALDOMERO', 'BAL'),
(11, 'XANDU', 'XDU'),
(12, 'XENIA', 'XEN'),
(13, 'MINION', 'MIN');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `ciclos`
--
ALTER TABLE `ciclos`
  ADD PRIMARY KEY (`id_ciclo`);

--
-- Indices de la tabla `clasificacion_merma`
--
ALTER TABLE `clasificacion_merma`
  ADD PRIMARY KEY (`id_merma`);

--
-- Indices de la tabla `invernaderos`
--
ALTER TABLE `invernaderos`
  ADD PRIMARY KEY (`id_invernadero`),
  ADD KEY `id_sede_i` (`id_sede_i`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_seccion`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indices de la tabla `permisos_usuarios`
--
ALTER TABLE `permisos_usuarios`
  ADD PRIMARY KEY (`id_permiso_u`),
  ADD KEY `id_usuario_p` (`id_usuario_u`),
  ADD KEY `id_modulo_p` (`id_modulo_u`),
  ADD KEY `id_permisos_u` (`id_permisos_u`);

--
-- Indices de la tabla `registro_empaque`
--
ALTER TABLE `registro_empaque`
  ADD PRIMARY KEY (`id_registro_r`),
  ADD KEY `id_tipo_caja` (`id_tipo_caja`),
  ADD KEY `id_tipo_tarima` (`id_tipo_tarima`),
  ADD KEY `id_tipo_carro` (`id_tipo_carro`),
  ADD KEY `id_tipo_merma` (`id_tipo_merma`),
  ADD KEY `registro_empaque_ibfk_1` (`id_codigo_r`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`id_sede`);

--
-- Indices de la tabla `tipos_cajas`
--
ALTER TABLE `tipos_cajas`
  ADD PRIMARY KEY (`id_caja`);

--
-- Indices de la tabla `tipos_carros`
--
ALTER TABLE `tipos_carros`
  ADD PRIMARY KEY (`id_carro`);

--
-- Indices de la tabla `tipos_presentacion`
--
ALTER TABLE `tipos_presentacion`
  ADD PRIMARY KEY (`id_presentacion`);

--
-- Indices de la tabla `tipos_tarimas`
--
ALTER TABLE `tipos_tarimas`
  ADD PRIMARY KEY (`id_tarima`);

--
-- Indices de la tabla `tipo_variaciones`
--
ALTER TABLE `tipo_variaciones`
  ADD PRIMARY KEY (`id_variedad`),
  ADD KEY `id_ciclo_v` (`id_ciclo_v`),
  ADD KEY `tipo_variaciones_ibfk_1` (`id_modulo_v`),
  ADD KEY `id_nombre_variedad` (`id_nombre_v`),
  ADD KEY `id_presentacion_v` (`id_presentacion_v`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_cargo` (`id_cargo`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `variedades`
--
ALTER TABLE `variedades`
  ADD PRIMARY KEY (`id_nombre_v`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ciclos`
--
ALTER TABLE `ciclos`
  MODIFY `id_ciclo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clasificacion_merma`
--
ALTER TABLE `clasificacion_merma`
  MODIFY `id_merma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `invernaderos`
--
ALTER TABLE `invernaderos`
  MODIFY `id_invernadero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `permisos_usuarios`
--
ALTER TABLE `permisos_usuarios`
  MODIFY `id_permiso_u` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `registro_empaque`
--
ALTER TABLE `registro_empaque`
  MODIFY `id_registro_r` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id_sede` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipos_cajas`
--
ALTER TABLE `tipos_cajas`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipos_carros`
--
ALTER TABLE `tipos_carros`
  MODIFY `id_carro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipos_presentacion`
--
ALTER TABLE `tipos_presentacion`
  MODIFY `id_presentacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipos_tarimas`
--
ALTER TABLE `tipos_tarimas`
  MODIFY `id_tarima` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_variaciones`
--
ALTER TABLE `tipo_variaciones`
  MODIFY `id_variedad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `variedades`
--
ALTER TABLE `variedades`
  MODIFY `id_nombre_v` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `invernaderos`
--
ALTER TABLE `invernaderos`
  ADD CONSTRAINT `invernaderos_ibfk_1` FOREIGN KEY (`id_sede_i`) REFERENCES `sedes` (`id_sede`);

--
-- Filtros para la tabla `permisos_usuarios`
--
ALTER TABLE `permisos_usuarios`
  ADD CONSTRAINT `permisos_usuarios_ibfk_1` FOREIGN KEY (`id_usuario_u`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `permisos_usuarios_ibfk_2` FOREIGN KEY (`id_modulo_u`) REFERENCES `modulos` (`id_seccion`),
  ADD CONSTRAINT `permisos_usuarios_ibfk_3` FOREIGN KEY (`id_permisos_u`) REFERENCES `permisos` (`id_permiso`);

--
-- Filtros para la tabla `registro_empaque`
--
ALTER TABLE `registro_empaque`
  ADD CONSTRAINT `registro_empaque_ibfk_1` FOREIGN KEY (`id_codigo_r`) REFERENCES `tipo_variaciones` (`id_variedad`),
  ADD CONSTRAINT `registro_empaque_ibfk_2` FOREIGN KEY (`id_tipo_caja`) REFERENCES `tipos_cajas` (`id_caja`),
  ADD CONSTRAINT `registro_empaque_ibfk_3` FOREIGN KEY (`id_tipo_tarima`) REFERENCES `tipos_tarimas` (`id_tarima`),
  ADD CONSTRAINT `registro_empaque_ibfk_4` FOREIGN KEY (`id_tipo_carro`) REFERENCES `tipos_carros` (`id_carro`),
  ADD CONSTRAINT `registro_empaque_ibfk_6` FOREIGN KEY (`id_tipo_merma`) REFERENCES `clasificacion_merma` (`id_merma`);

--
-- Filtros para la tabla `tipo_variaciones`
--
ALTER TABLE `tipo_variaciones`
  ADD CONSTRAINT `tipo_variaciones_ibfk_1` FOREIGN KEY (`id_modulo_v`) REFERENCES `invernaderos` (`id_invernadero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tipo_variaciones_ibfk_2` FOREIGN KEY (`id_ciclo_v`) REFERENCES `ciclos` (`id_ciclo`),
  ADD CONSTRAINT `tipo_variaciones_ibfk_3` FOREIGN KEY (`id_nombre_v`) REFERENCES `variedades` (`id_nombre_v`),
  ADD CONSTRAINT `tipo_variaciones_ibfk_4` FOREIGN KEY (`id_presentacion_v`) REFERENCES `tipos_presentacion` (`id_presentacion`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargo`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
