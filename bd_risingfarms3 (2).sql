-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-09-2025 a las 00:02:37
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
(9, 11, 'fghf', 1, 1, 1, 642.000, 224.760, 417.240, 88, 0, 'prueba', '2025-09-02', '15:22:25', 1, 'NORMAL', NULL, '3-5-XAC-020925-004', '2025-36');

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
(1, 'D-4', 26.000);

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
  `peso_tarima` decimal(10,3) NOT NULL,
  `cantidad_t` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_tarimas`
--

INSERT INTO `tipos_tarimas` (`id_tarima`, `nombre_tarima`, `peso_tarima`, `cantidad_t`) VALUES
(1, 'Madera', 16.800, 4);

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
(11, '3-5-XAC', 8, 'COOCKTAIL', 'ROJO', 345.000, 1, 2, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_cargo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indices de la tabla `variedades`
--
ALTER TABLE `variedades`
  ADD PRIMARY KEY (`id_nombre_v`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `registro_empaque`
--
ALTER TABLE `registro_empaque`
  MODIFY `id_registro_r` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id_tarima` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipo_variaciones`
--
ALTER TABLE `tipo_variaciones`
  MODIFY `id_variedad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
