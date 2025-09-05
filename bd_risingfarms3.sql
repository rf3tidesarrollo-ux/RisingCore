-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-08-2025 a las 23:11:09
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
-- Estructura de tabla para la tabla `codigos`
--

CREATE TABLE `codigos` (
  `id_codigo` int(11) NOT NULL,
  `codigo` varchar(15) NOT NULL,
  `sede` varchar(3) NOT NULL,
  `id_variedad_c` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `codigos`
--

INSERT INTO `codigos` (`id_codigo`, `codigo`, `sede`, `id_variedad_c`) VALUES
(1, '2-5-Ba', '3', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invernaderos`
--

CREATE TABLE `invernaderos` (
  `id_invernadero` int(11) NOT NULL,
  `invernadero` int(11) NOT NULL,
  `superficie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `invernaderos`
--

INSERT INTO `invernaderos` (`id_invernadero`, `invernadero`, `superficie`) VALUES
(1, 1, 19828),
(2, 2, 19584),
(3, 3, 20155),
(4, 4, 19732),
(5, 5, 20142),
(6, 6, 16198),
(7, 7, 14876);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_empaque`
--

CREATE TABLE `registro_empaque` (
  `id_registro_r` int(11) NOT NULL,
  `id_codigo_r` int(11) NOT NULL,
  `folio_r` varchar(50) NOT NULL,
  `id_tipo_caja` int(11) NOT NULL,
  `id_tipo_tarima` int(11) NOT NULL,
  `id_tipo_carro` int(11) NOT NULL,
  `p_bruto` decimal(10,3) NOT NULL,
  `p_taraje` decimal(10,3) NOT NULL,
  `p_neto` decimal(10,3) NOT NULL,
  `cantidad_caja` int(11) NOT NULL,
  `usuario_r` varchar(100) NOT NULL,
  `fecha_r` date NOT NULL,
  `hora_r` time NOT NULL,
  `activo_r` tinyint(1) NOT NULL,
  `no_serie_r` varchar(50) NOT NULL,
  `semana_r` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_empaque`
--

INSERT INTO `registro_empaque` (`id_registro_r`, `id_codigo_r`, `folio_r`, `id_tipo_caja`, `id_tipo_tarima`, `id_tipo_carro`, `p_bruto`, `p_taraje`, `p_neto`, `cantidad_caja`, `usuario_r`, `fecha_r`, `hora_r`, `activo_r`, `no_serie_r`, `semana_r`) VALUES
(2, 1, '56', 1, 1, 1, 55.000, 1549.000, -1494.000, 56, 'prueba', '2025-08-27', '13:35:51', 1, '2-5-Ba-270825-001', '35'),
(3, 1, 'asdf', 1, 1, 1, 56.000, 249.000, -193.000, 6, 'prueba', '2025-08-27', '14:07:58', 1, '2-5-Ba-270825-002', '2025-35'),
(22, 1, '777', 1, 1, 1, 777.000, 208.315, 568.685, 77, 'prueba', '2025-08-27', '14:46:17', 1, '2-5-Ba--003', '2025-35'),
(23, 1, '643', 1, 1, 1, 333.000, 144.030, 188.970, 34, 'prueba', '2025-08-27', '14:48:44', 1, '2-5-Ba-270825-003', '2025-35'),
(24, 1, '643', 1, 1, 1, 333.000, 144.030, 188.970, 34, 'prueba', '2025-08-27', '14:58:59', 1, '2-5-Ba-270825-004', '2025-35'),
(25, 1, '643', 1, 1, 1, 333.000, 144.030, 188.970, 34, 'prueba', '2025-08-27', '15:01:30', 1, '2-5-Ba-270825-005', '2025-35'),
(26, 1, '643', 1, 1, 1, 333.000, 144.030, 188.970, 34, 'prueba', '2025-08-27', '15:01:48', 1, '2-5-Ba-270825-006', '2025-35'),
(27, 1, '643', 1, 1, 1, 333.000, 144.030, 188.970, 34, 'prueba', '2025-08-27', '15:02:11', 1, '2-5-Ba-270825-007', '2025-35');

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
(1, 'Racimo', 'RC'),
(2, 'Suelto', 'ST');

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
  `variedad` varchar(50) NOT NULL,
  `tipo` varchar(40) NOT NULL,
  `color` varchar(25) NOT NULL,
  `id_modulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_variaciones`
--

INSERT INTO `tipo_variaciones` (`id_variedad`, `variedad`, `tipo`, `color`, `id_modulo`) VALUES
(2, 'TT826', 'GRAPE', 'AMARILLO', 1);

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

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `codigos`
--
ALTER TABLE `codigos`
  ADD PRIMARY KEY (`id_codigo`),
  ADD KEY `id_variedad_c` (`id_variedad_c`);

--
-- Indices de la tabla `invernaderos`
--
ALTER TABLE `invernaderos`
  ADD PRIMARY KEY (`id_invernadero`);

--
-- Indices de la tabla `registro_empaque`
--
ALTER TABLE `registro_empaque`
  ADD PRIMARY KEY (`id_registro_r`),
  ADD KEY `id_codigo_r` (`id_codigo_r`),
  ADD KEY `id_tipo_caja` (`id_tipo_caja`),
  ADD KEY `id_tipo_tarima` (`id_tipo_tarima`),
  ADD KEY `id_tipo_carro` (`id_tipo_carro`);

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
  ADD KEY `id_modulo` (`id_modulo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `codigos`
--
ALTER TABLE `codigos`
  MODIFY `id_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `invernaderos`
--
ALTER TABLE `invernaderos`
  MODIFY `id_invernadero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `registro_empaque`
--
ALTER TABLE `registro_empaque`
  MODIFY `id_registro_r` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
  MODIFY `id_variedad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `codigos`
--
ALTER TABLE `codigos`
  ADD CONSTRAINT `codigos_ibfk_1` FOREIGN KEY (`id_variedad_c`) REFERENCES `tipo_variaciones` (`id_variedad`);

--
-- Filtros para la tabla `registro_empaque`
--
ALTER TABLE `registro_empaque`
  ADD CONSTRAINT `registro_empaque_ibfk_1` FOREIGN KEY (`id_codigo_r`) REFERENCES `codigos` (`id_codigo`),
  ADD CONSTRAINT `registro_empaque_ibfk_2` FOREIGN KEY (`id_tipo_caja`) REFERENCES `tipos_cajas` (`id_caja`),
  ADD CONSTRAINT `registro_empaque_ibfk_3` FOREIGN KEY (`id_tipo_tarima`) REFERENCES `tipos_tarimas` (`id_tarima`),
  ADD CONSTRAINT `registro_empaque_ibfk_4` FOREIGN KEY (`id_tipo_carro`) REFERENCES `tipos_carros` (`id_carro`);

--
-- Filtros para la tabla `tipo_variaciones`
--
ALTER TABLE `tipo_variaciones`
  ADD CONSTRAINT `tipo_variaciones_ibfk_1` FOREIGN KEY (`id_modulo`) REFERENCES `invernaderos` (`id_invernadero`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
