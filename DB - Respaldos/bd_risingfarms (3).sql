-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-10-2025 a las 22:57:05
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
-- Base de datos: `bd_risingfarms`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id_cargo` int(11) NOT NULL,
  `id_sede_u` int(11) NOT NULL,
  `nombre_completo` varchar(50) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `departamento_c` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id_cargo`, `id_sede_u`, `nombre_completo`, `cargo`, `departamento_c`) VALUES
(1, 3, 'JOSE ANTONIO RUIZ ARGUELLO', 'DESARROLLADOR', 'TI'),
(2, 3, 'LORETO HERNANDEZ CHAVEZ', 'JEFE DE PESAJE', 'EMPAQUE'),
(3, 3, 'ASHLEY YOSELIN SILVA MADRIGAL', 'ENCARGADA PALLETS', 'EMPAQUE'),
(4, 3, 'TITULAR PENDIENTE - MEZCLAS', 'ENCARGADA MEZCLAS', 'EMPAQUE'),
(6, 3, 'MAGDALENO', 'ENCARGADO EMPAQUE', 'EMPAQUE');

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
(1, 'PRODUCCIÓN-NACIONAL', 'DEFORMIDAD'),
(2, 'PRODUCCIÓN-NACIONAL', 'FIRMEZA'),
(3, 'PRODUCCIÓN-NACIONAL', 'COLORACIÓN'),
(4, 'PRODUCCIÓN-NACIONAL', 'TAMAÑO'),
(5, 'PRODUCCIÓN-NACIONAL', 'DAÑOS POR PLAGA O ENFERMEDAD'),
(6, 'PRODUCCIÓN-NACIONAL', 'SUCIEDAD DE MATERIA EXTRAÑA'),
(7, 'PRODUCCIÓN-NACIONAL', 'DAÑOS MECÁNICOS'),
(8, 'PRODUCCIÓN-NACIONAL', 'OTROS'),
(9, 'EMPAQUE-NACIONAL', 'DEFORMIDAD'),
(10, 'EMPAQUE-NACIONAL', 'FIRMEZA'),
(11, 'EMPAQUE-NACIONAL', 'COLORACIÓN'),
(12, 'EMPAQUE-NACIONAL', 'TAMAÑO'),
(13, 'EMPAQUE-NACIONAL', 'DAÑOS POR PLAGA O ENFERMEDAD'),
(14, 'EMPAQUE-NACIONAL', 'SUCIEDAD DE MATERIA EXTRAÑA'),
(15, 'EMPAQUE-NACIONAL', 'CRACKING'),
(16, 'EMPAQUE-NACIONAL', 'HONGO VERDE'),
(17, 'EMPAQUE-NACIONAL', 'CICATRIZ'),
(18, 'EMPAQUE-NACIONAL', 'OTROS'),
(19, 'EMPAQUE-MERMA', 'APLASTADO'),
(20, 'EMPAQUE-MERMA', 'GOLPEADO'),
(21, 'EMPAQUE-MERMA', 'RAJADO'),
(22, 'EMPAQUE-MERMA', 'CORTADO'),
(23, 'PRODUCCIÓN-MERMA', 'BASURA'),
(24, 'PRODUCCIÓN-MERMA', 'VERDE'),
(25, 'PRODUCCIÓN-MERMA', 'ARRASTRE'),
(26, 'PRODUCCIÓN-MERMA', 'OPACO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `id_sede_c` int(11) NOT NULL,
  `nombre_cliente` varchar(50) NOT NULL,
  `abreviatura` varchar(10) NOT NULL,
  `activo_c` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `id_sede_c`, `nombre_cliente`, `abreviatura`, `activo_c`) VALUES
(1, 1, 'MASTRONARDI', 'MAS', 1),
(2, 2, 'TOPLINE', 'TOP', 1),
(3, 2, 'VILLAGE', 'VIL', 1),
(4, 3, 'MASTRONARDI', 'MAS', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destinos_embarque`
--

CREATE TABLE `destinos_embarque` (
  `id_destino` int(11) NOT NULL,
  `folio_d` varchar(25) NOT NULL,
  `id_sede_d` int(11) NOT NULL,
  `lugar_d` varchar(25) NOT NULL,
  `estado_d` varchar(25) DEFAULT NULL,
  `estado_ab` varchar(5) DEFAULT NULL,
  `pais_d` varchar(25) DEFAULT NULL,
  `pais_ab` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `destinos_embarque`
--

INSERT INTO `destinos_embarque` (`id_destino`, `folio_d`, `id_sede_d`, `lugar_d`, `estado_d`, `estado_ab`, `pais_d`, `pais_ab`) VALUES
(1, 'MPL1', 3, 'Kingsville', 'Ontario', 'ON', 'Canadá', 'CA'),
(2, 'MPL2', 3, 'Livonia', 'Michigan', 'MI', 'Estados Unidos', 'US'),
(3, 'MPL3', 3, 'Lakeland', 'Florida', 'FL', 'Estados Unidos', 'US'),
(4, 'MPL6', 3, 'Laredo', 'Texas', 'TX', 'Estados Unidos', 'US'),
(5, 'MPL8', 3, 'Jonestown', 'Pennsylvania', 'PA', 'Estados Unidos', 'US'),
(6, 'MPL9', 3, 'Surrey', 'British Columbia', 'BC', 'Canadá', 'CA'),
(7, 'MPLT', 3, '(Transbordo)', NULL, NULL, NULL, NULL),
(8, 'MPL West', 3, 'Castroville', 'California', 'CA', 'Estados Unidos', 'US');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `embarques_pallets`
--

CREATE TABLE `embarques_pallets` (
  `id_embarque` int(11) NOT NULL,
  `id_sede_em` int(11) NOT NULL,
  `folio_em` varchar(25) NOT NULL,
  `po_em` varchar(25) NOT NULL,
  `cajas_em` int(11) NOT NULL,
  `kilos_em` decimal(10,3) NOT NULL,
  `cajas_emt` int(11) NOT NULL,
  `kilos_emt` decimal(10,3) NOT NULL,
  `id_destino_em` int(11) NOT NULL,
  `fecha_em` date NOT NULL,
  `hora_em` time NOT NULL,
  `fecha_c_em` date NOT NULL,
  `semana_em` varchar(15) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado_em` tinyint(1) NOT NULL,
  `activo_em` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `embarques_pallets`
--

INSERT INTO `embarques_pallets` (`id_embarque`, `id_sede_em`, `folio_em`, `po_em`, `cajas_em`, `kilos_em`, `cajas_emt`, `kilos_emt`, `id_destino_em`, `fecha_em`, `hora_em`, `fecha_c_em`, `semana_em`, `usuario_id`, `estado_em`, `activo_em`) VALUES
(1, 3, 'RF3-0001', '123456789', 1200, 9000.000, 0, 0.000, 1, '2025-09-24', '00:00:00', '2025-09-24', '2025-39', 17, 0, 1),
(2, 3, 'RF3-0002', '564546', 23, 256.000, 0, 0.000, 2, '2025-09-26', '07:44:32', '2025-09-26', '2025-39', 17, 0, 1),
(3, 3, 'RF3-0003', '564546', 23, 256.000, 0, 0.000, 2, '2025-09-26', '07:45:05', '2025-09-26', '2025-39', 17, 0, 1),
(4, 3, 'RF3-0004', '123456789', 12, 12.000, 0, 0.000, 1, '2025-09-26', '07:46:59', '2025-09-26', '2025-39', 17, 0, 1),
(5, 3, 'RF3-0005', '123456', 12, 12.000, 0, 0.000, 7, '2025-10-06', '07:15:56', '2025-09-29', '2025-41', 25, 0, 1),
(6, 3, 'RF3-0006', '1523', 12, 12.000, 0, 0.000, 5, '2025-10-02', '12:36:49', '2025-10-02', '2025-40', 17, 0, 1),
(7, 3, 'RF3-0007', '939639', 600, 1200.000, 0, 0.000, 3, '2025-10-03', '14:38:51', '2025-10-03', '2025-40', 17, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empaque_lineas`
--

CREATE TABLE `empaque_lineas` (
  `id_linea` int(11) NOT NULL,
  `id_sede_l` int(11) NOT NULL,
  `linea` int(11) NOT NULL,
  `selladora` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empaque_lineas`
--

INSERT INTO `empaque_lineas` (`id_linea`, `id_sede_l`, `linea`, `selladora`) VALUES
(1, 3, 1, 'GT2'),
(2, 3, 2, 'GT2');

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
(7, 2, 7, 14876);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mezclas`
--

CREATE TABLE `mezclas` (
  `id_mezcla` int(11) NOT NULL,
  `folio_m` varchar(30) NOT NULL,
  `id_sede_m` int(11) NOT NULL,
  `id_cliente_m` int(11) NOT NULL,
  `cajas_t` int(11) NOT NULL,
  `kilos_t` decimal(10,3) NOT NULL,
  `fecha_c` date NOT NULL,
  `fecha_m` date NOT NULL,
  `hora_m` time NOT NULL,
  `id_usuario_m` int(11) NOT NULL,
  `estado_m` tinyint(1) NOT NULL,
  `activo_m` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mezclas`
--

INSERT INTO `mezclas` (`id_mezcla`, `folio_m`, `id_sede_m`, `id_cliente_m`, `cajas_t`, `kilos_t`, `fecha_c`, `fecha_m`, `hora_m`, `id_usuario_m`, `estado_m`, `activo_m`) VALUES
(1, 'RF3-MAS-AH3825-01', 3, 4, 11, 379.140, '2025-09-15', '2025-10-06', '07:34:54', 17, 1, 1),
(2, 'RF3-MAS-AH3825-02', 3, 4, 4, 114.240, '2025-09-18', '2025-09-18', '10:32:38', 17, 1, 1),
(3, 'RF3-MAS-AH3825-03', 3, 4, 6, 56.250, '2025-09-18', '2025-09-18', '10:35:03', 17, 1, 1),
(4, 'RF3-MAS-AR3825-01', 3, 4, 2, 95.010, '2025-09-19', '2025-09-19', '07:05:30', 17, 1, 1),
(5, 'RF3-MAS-AR3825-02', 3, 4, 1, 47.510, '2025-09-19', '2025-09-19', '07:45:51', 17, 1, 1),
(6, 'RF3-MAS-AR3825-03', 3, 4, 1, 47.510, '2025-09-19', '2025-09-19', '07:47:05', 17, 1, 1),
(7, 'RF3-MAS-AR3825-04', 3, 4, 11, 333.110, '2025-09-19', '2025-09-19', '08:34:28', 17, 1, 1),
(8, 'RF3-MAS-AR3825-05', 3, 4, 2, 95.010, '2025-09-19', '2025-09-19', '12:46:42', 17, 0, 1),
(9, 'RF3-MAS-AO3925-01', 3, 4, 12, 115.390, '2025-09-22', '2025-09-22', '07:09:08', 17, 1, 1),
(10, 'RF3-MAS-AO3925-02', 3, 4, 3, 28.850, '2025-09-22', '2025-09-22', '09:56:32', 17, 1, 1),
(11, 'RF3-MAS-AH4025-01', 3, 4, 16, 158.100, '2025-10-02', '2025-10-02', '12:32:00', 17, 1, 1),
(12, 'RF3-MAS-AR4025-01', 3, 4, 15, 148.220, '2025-10-03', '2025-10-03', '14:30:15', 17, 1, 1),
(13, 'RF3-MAS-AO4125-01', 3, 4, 5, 49.410, '2025-10-06', '2025-10-06', '07:08:32', 17, 0, 1),
(14, 'RF3-MAS-AO4125-02', 3, 4, 3, 143.310, '2025-10-06', '2025-10-06', '07:10:50', 17, 0, 1),
(15, 'RF3-MAS-AO4125-03', 3, 4, 3, 143.310, '2025-10-06', '2025-10-06', '07:11:55', 17, 0, 1),
(16, 'RF3-MAS-AO4125-04', 3, 4, 2, 16.870, '2025-10-06', '2025-10-06', '09:38:07', 24, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mezcla_lotes`
--

CREATE TABLE `mezcla_lotes` (
  `id_mezclas` int(11) NOT NULL,
  `id_mezcla_l` int(11) NOT NULL,
  `id_lote_l` int(11) NOT NULL,
  `cajas_m` int(11) NOT NULL,
  `kilos_m` decimal(10,3) NOT NULL,
  `fecha_m` date NOT NULL,
  `hora_m` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mezcla_lotes`
--

INSERT INTO `mezcla_lotes` (`id_mezclas`, `id_mezcla_l`, `id_lote_l`, `cajas_m`, `kilos_m`, `fecha_m`, `hora_m`) VALUES
(9, 1, 5, 5, 92.525, '2025-09-18', '07:57:07'),
(12, 2, 6, 2, 19.232, '2025-09-18', '09:27:01'),
(14, 3, 3, 1, 8.172, '2025-09-18', '10:35:03'),
(15, 3, 6, 5, 48.081, '2025-09-18', '10:35:03'),
(16, 4, 4, 2, 95.010, '2025-09-19', '07:05:30'),
(17, 5, 4, 1, 47.505, '2025-09-19', '07:45:51'),
(18, 6, 4, 1, 47.505, '2025-09-19', '07:46:24'),
(19, 7, 4, 6, 285.030, '2025-09-19', '08:34:28'),
(20, 7, 6, 5, 48.081, '2025-09-19', '08:34:28'),
(21, 8, 4, 2, 95.010, '2025-09-19', '12:46:42'),
(22, 9, 6, 12, 115.393, '2025-09-22', '07:09:08'),
(23, 10, 6, 3, 28.848, '2025-09-22', '09:56:32'),
(24, 11, 6, 16, 158.098, '2025-10-02', '12:31:49'),
(25, 12, 6, 15, 148.217, '2025-10-03', '14:30:15'),
(26, 1, 4, 6, 286.620, '2025-10-03', '14:31:14'),
(27, 13, 6, 5, 49.405, '2025-10-06', '07:08:32'),
(28, 14, 4, 3, 143.310, '2025-10-06', '07:10:50'),
(29, 15, 4, 3, 143.310, '2025-10-06', '07:11:55'),
(30, 16, 1, 2, 16.873, '2025-10-06', '09:37:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mezcla_lotes_temp`
--

CREATE TABLE `mezcla_lotes_temp` (
  `id_mezcla_temp` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `id_lote` int(11) NOT NULL,
  `cajas_m` int(11) NOT NULL,
  `kilos_m` decimal(10,3) NOT NULL,
  `confirmado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'Empaque/Pallets'),
(4, 'Empaque/Embarque'),
(5, 'Config/Usuarios'),
(6, 'Produccion/Codigos'),
(7, 'Empaque/CamaraFria'),
(8, 'Empaque/Merma'),
(9, 'RRHH/Ingreso'),
(10, 'RRHH/Personal'),
(11, 'RRHH/Asistencia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pallets`
--

CREATE TABLE `pallets` (
  `id_pallet` int(11) NOT NULL,
  `folio_p` varchar(25) NOT NULL,
  `id_sede_p` int(11) NOT NULL,
  `fecha_c` date NOT NULL,
  `fecha_p` date NOT NULL,
  `hora_p` time NOT NULL,
  `id_embarque_p` int(11) NOT NULL,
  `fecha_e` date NOT NULL,
  `cajas_p` int(11) NOT NULL,
  `id_tarima_p` int(11) NOT NULL,
  `id_presen_p` int(11) NOT NULL,
  `id_usuario_p` int(11) NOT NULL,
  `mapeo` varchar(3) NOT NULL,
  `ubicacion` varchar(3) NOT NULL,
  `estado_p` tinyint(1) NOT NULL,
  `activo_p` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pallets`
--

INSERT INTO `pallets` (`id_pallet`, `folio_p`, `id_sede_p`, `fecha_c`, `fecha_p`, `hora_p`, `id_embarque_p`, `fecha_e`, `cajas_p`, `id_tarima_p`, `id_presen_p`, `id_usuario_p`, `mapeo`, `ubicacion`, `estado_p`, `activo_p`) VALUES
(1, 'RF3-0001', 3, '2025-09-23', '2025-09-23', '09:01:57', 1, '2025-09-24', 12, 2, 1, 22, '8', '8', 0, 1),
(2, 'RF3-0002', 3, '2025-09-23', '2025-09-23', '09:05:23', 1, '2025-09-23', 12, 1, 1, 22, '4', '3', 0, 1),
(3, 'RF3-0003', 3, '2025-09-23', '2025-09-23', '07:47:55', 1, '2025-09-24', 26, 2, 1, 17, '41', '5', 0, 1),
(4, 'RF3-0004', 3, '2025-09-23', '2025-09-23', '13:49:57', 1, '2025-09-23', 100, 2, 14, 17, '70', '30', 0, 1),
(5, 'RF3-0005', 3, '2025-09-25', '2025-09-25', '12:18:52', 1, '2025-09-26', 120, 2, 12, 17, '28', '28', 0, 1),
(6, 'RF3-0006', 3, '2025-09-25', '2025-09-25', '12:19:00', 1, '2025-09-25', 23, 2, 14, 17, '9', '6', 0, 1),
(7, 'RF3-0007', 3, '2025-10-02', '2025-10-02', '07:49:13', 1, '2025-10-02', 120, 2, 4, 17, '24', '10', 0, 1),
(8, 'RF3-0008', 3, '2025-10-02', '2025-10-02', '08:44:02', 1, '2025-10-03', 90, 2, 15, 17, '50', '23', 0, 1),
(9, 'RF3-0009', 3, '2025-10-03', '2025-10-03', '14:34:33', 1, '2025-10-09', 120, 2, 5, 17, '0', '0', 0, 1),
(10, 'RF3-0010', 3, '2025-10-06', '2025-10-06', '06:47:18', 6, '2025-10-06', 120, 2, 4, 17, '0', '0', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pallet_mezclas`
--

CREATE TABLE `pallet_mezclas` (
  `id_pallets` int(11) NOT NULL,
  `id_pallet_m` int(11) NOT NULL,
  `id_mezcla_m` int(11) NOT NULL,
  `cajas_m` int(11) NOT NULL,
  `id_linea_m` int(11) NOT NULL,
  `fecha_m` date NOT NULL,
  `hora_m` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pallet_mezclas`
--

INSERT INTO `pallet_mezclas` (`id_pallets`, `id_pallet_m`, `id_mezcla_m`, `cajas_m`, `id_linea_m`, `fecha_m`, `hora_m`) VALUES
(1, 2, 1, 12, 1, '2025-09-23', '07:31:40'),
(2, 3, 9, 13, 2, '2025-09-23', '07:32:28'),
(3, 3, 2, 13, 2, '0000-00-00', '07:47:55'),
(5, 4, 3, 100, 1, '2025-09-23', '13:49:57'),
(7, 1, 7, 12, 1, '2025-09-24', '07:41:40'),
(8, 5, 5, 120, 1, '2025-09-25', '09:16:56'),
(9, 6, 10, 23, 1, '2025-09-25', '12:12:36'),
(10, 7, 4, 120, 1, '2025-10-02', '07:49:13'),
(11, 8, 6, 90, 1, '2025-10-02', '08:44:02'),
(12, 9, 11, 120, 2, '2025-10-03', '14:34:33'),
(13, 10, 12, 120, 1, '2025-10-06', '06:47:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pallet_mezclas_temp`
--

CREATE TABLE `pallet_mezclas_temp` (
  `id_pallet_temp` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `id_mezcla_t` int(11) NOT NULL,
  `cajas_t` int(11) NOT NULL,
  `id_linea_t` int(11) NOT NULL,
  `confirmado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(45, 17, 1, 1),
(78, 24, 2, 1),
(79, 24, 2, 2),
(80, 24, 2, 3),
(81, 24, 2, 4),
(103, 23, 8, 1),
(104, 23, 8, 2),
(105, 23, 8, 3),
(106, 23, 8, 4),
(107, 23, 1, 1),
(108, 23, 1, 2),
(109, 23, 1, 3),
(110, 23, 1, 4),
(111, 25, 4, 1),
(112, 25, 4, 2),
(113, 25, 4, 3),
(114, 25, 4, 4),
(139, 22, 7, 1),
(140, 22, 7, 2),
(141, 22, 7, 3),
(142, 22, 7, 4),
(143, 22, 3, 1),
(144, 22, 3, 2),
(145, 22, 3, 3),
(146, 22, 3, 4),
(147, 19, 7, 1),
(148, 19, 7, 2),
(149, 19, 7, 3),
(150, 19, 7, 4),
(151, 19, 4, 1),
(152, 19, 4, 2),
(153, 19, 4, 3),
(154, 19, 4, 4),
(155, 19, 8, 1),
(156, 19, 8, 2),
(157, 19, 8, 3),
(158, 19, 8, 4),
(159, 19, 2, 1),
(160, 19, 2, 2),
(161, 19, 2, 3),
(162, 19, 2, 4),
(163, 19, 3, 1),
(164, 19, 3, 2),
(165, 19, 3, 3),
(166, 19, 3, 4),
(167, 19, 1, 1),
(168, 19, 1, 2),
(169, 19, 1, 3),
(170, 19, 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentaciones_pallet`
--

CREATE TABLE `presentaciones_pallet` (
  `id_presentacion_p` int(11) NOT NULL,
  `sede_id` int(11) NOT NULL,
  `presentacion` varchar(50) NOT NULL,
  `peso_cha` decimal(10,3) DEFAULT NULL,
  `peso_req` decimal(10,3) NOT NULL,
  `item_local` varchar(20) NOT NULL,
  `item_cliente` varchar(20) NOT NULL,
  `origen` varchar(10) NOT NULL,
  `cajas` int(11) NOT NULL,
  `empacado` varchar(25) NOT NULL,
  `cliente_id` varchar(10) NOT NULL,
  `peso_rango_i` decimal(10,3) DEFAULT NULL,
  `peso_rango_f` decimal(10,3) DEFAULT NULL,
  `tarima_id` int(11) NOT NULL,
  `fleje` varchar(20) NOT NULL,
  `gtin` varchar(50) NOT NULL,
  `upc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `presentaciones_pallet`
--

INSERT INTO `presentaciones_pallet` (`id_presentacion_p`, `sede_id`, `presentacion`, `peso_cha`, `peso_req`, `item_local`, `item_cliente`, `origen`, `cajas`, `empacado`, `cliente_id`, `peso_rango_i`, `peso_rango_f`, `tarima_id`, `fleje`, `gtin`, `upc`) VALUES
(1, 3, '12X2 LB SC TS MEDLEY (SAM\'S)', 0.029, 0.908, 'RFASS001', 'RTGO1097', '1,2,3,4', 55, 'TOPSEAL', 'SUNSET', 0.978, 0.982, 2, 'FLEJE', '10057836427881', '57836020696'),
(2, 3, '6X1.5 LB TOPSEAL WILD WONDERS', 0.026, 0.681, 'RFASS002', 'RTGO1033', '1,2,3,4', 100, 'TOPSEAL', 'SUNSET', 0.736, 0.742, 2, 'FLEJE', '10057836420646', '57836021174'),
(3, 3, '10X12 OZ TOPSEAL WILD WONDERS', 0.016, 0.341, 'RFASS003', 'RTGO1028', '1,2,3,4', 120, 'TOPSEAL', 'SUNSET', 0.372, 0.374, 2, 'FLEJE', '10057836160269', '57836020856'),
(4, 3, '12X1.5 LB TOPSEAL WILD WONDERS', 0.026, 0.681, 'RFASS004', 'RTGO1021', '1,2,3,4', 55, 'TOPSEAL', 'SUNSET', 0.736, 0.742, 2, 'FLEJE', '10057836160221', '57836021174'),
(5, 3, '12X2 LB COSTCO TS WILD WONDER', 0.029, 0.908, 'RFASS005', 'RTGO1054', '1,2,3,4', 55, 'TOPSEAL', 'SUNSET', 0.978, 0.982, 2, 'FLEJE', '10057836421711', '57836020696'),
(6, 3, '14X16 OZ FLAVOR BOWL MEDLEY', 0.038, 0.454, 'RFASS006', 'RTGO1068', '1,2,3,4', 55, 'CLAM', 'SUNSET', 0.512, 0.516, 2, 'FLEJE', '10057836424361', '57836022785'),
(7, 3, '15X1 PINT TOPSEAL ORANGE', 0.014, 0.474, 'RFASS007', 'RTZM1025', '1,2,3,4', 100, 'TOPSEAL', 'SUNSET', 0.310, 0.314, 2, 'FLEJE', '10057836390239', '57836021789'),
(8, 3, '10X1 LB CLAM LOOSE', 0.003, 0.454, 'RFASS008', 'RTCA1011', '4,5,6,7', 100, 'CLAM OTV', 'SUNSET', 0.502, 0.506, 2, 'FLEJE', '10057836080116', '57836021258'),
(9, 3, '8X1 LB KROGER (ROUNDY\'S)', 0.026, 0.454, 'RFASS009', 'RTCA1024', '4,5,6,7', 120, 'CLAM OTV', 'SUNSET', 0.502, 0.506, 2, 'FLEJE', '10011110916874', '57836020641'),
(10, 3, '8X1 LB CLAM OTV RED', 0.060, 0.454, 'RFASS010', 'RTCA1007', '4,5,6,7', 120, 'CLAM OTV', 'SUNSET', 0.502, 0.506, 2, 'FLEJE', '10057836080079', '11110915877'),
(11, 3, '8X2 LB CLAM OTV (COSTCO)', 0.030, 0.908, 'RFASS011', 'RTCA1010', '4,5,6,7', 80, 'CLAM OTV', 'SUNSET', 1.006, 1.010, 2, 'FLEJE', '10057836080109', '57836020641'),
(12, 3, '10X1 LB CLAM OTV (GENERIC)', 0.014, 0.454, 'RFASS012', 'RTCA1012', '4,5,6,7', 100, 'CLAM OTV', 'SUNSET', 0.502, 0.506, 2, 'FLEJE', '10057836080123', '57836020653'),
(13, 3, '15X1 DP TOPSEAL WILD WONDERS', 0.026, 0.551, 'RFASS013', 'RTGO1052', '1,2,3,4', 100, 'TOPSEAL', 'SUNSET', 0.310, 0.314, 2, 'FLEJE', '10057836424636', '57836020641'),
(14, 3, '6X24 OZ PS TOPSEAL MEDLEY', 0.014, 0.680, 'RFASS014', 'RTGO1114', '1,2,3,4', 100, 'TOPSEAL', 'SUNSET', 0.736, 0.742, 2, 'FLEJE', '10011110223026', '57836021525'),
(15, 3, '15X10 OZ SIG SELECT WILD WONDERS', 0.000, 0.284, 'RFASS015', 'RTGO1111', '1,2,3,4', 100, 'TOPSEAL', 'SUNSET', 0.310, 0.314, 2, 'FLEJE', '10021130150554', '11110213022'),
(16, 3, '8X1 LB CAMP OTV SIG', 0.000, 0.454, 'RFASS016', 'RTCA1122', '4,5,6,7', 120, 'CLAM OTV', 'SUNSET', 0.000, 0.000, 2, 'FLEJE', '20021130133288', '21130110357'),
(17, 3, '20X1 LB CAMP TJ NO.1', 0.000, 0.454, 'RFASS017', 'RTCA1016', '4,5,6,7', 55, 'CLAM OTV', 'SUNSET', 0.000, 0.000, 2, 'FLEJE', '0000000955546', '21130113284'),
(18, 3, '15X1 DP WW PS TS NO1', 0.000, 0.283, 'RFASS018', 'RTGO1086', '1,2,3,4', 100, 'TOPSEAL', 'SUNSET', 0.000, 0.000, 2, 'FLEJE', '10011110911930', '11110911933');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_empaque`
--

CREATE TABLE `registro_empaque` (
  `id_registro_r` int(11) NOT NULL,
  `id_codigo_r` int(11) NOT NULL,
  `id_presentacion_r` int(11) NOT NULL,
  `id_tipo_caja` int(11) NOT NULL,
  `id_tipo_tarima` int(11) NOT NULL,
  `id_tipo_carro` int(11) NOT NULL,
  `p_bruto` decimal(10,3) NOT NULL,
  `p_taraje` decimal(10,3) NOT NULL,
  `p_neto` decimal(10,3) NOT NULL,
  `cantidad_caja` int(11) NOT NULL,
  `cantidad_tarima` int(11) NOT NULL,
  `usuario_r` int(11) NOT NULL,
  `fecha_reg` date NOT NULL,
  `fecha_r` date NOT NULL,
  `hora_r` time NOT NULL,
  `activo_r` tinyint(1) NOT NULL,
  `kilos_dis` decimal(10,3) NOT NULL,
  `cajas_dis` int(11) NOT NULL,
  `no_serie_r` varchar(50) NOT NULL,
  `semana_r` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_empaque`
--

INSERT INTO `registro_empaque` (`id_registro_r`, `id_codigo_r`, `id_presentacion_r`, `id_tipo_caja`, `id_tipo_tarima`, `id_tipo_carro`, `p_bruto`, `p_taraje`, `p_neto`, `cantidad_caja`, `cantidad_tarima`, `usuario_r`, `fecha_reg`, `fecha_r`, `hora_r`, `activo_r`, `kilos_dis`, `cajas_dis`, `no_serie_r`, `semana_r`) VALUES
(1, 15, 2, 1, 3, 1, 123.000, 21.760, 101.240, 12, 1, 17, '2025-09-13', '2025-09-29', '07:19:21', 1, 84.367, 10, '3-3-XAC-290925-002', '2025-37'),
(2, 15, 2, 1, 2, 1, 123.000, 14.760, 108.240, 12, 1, 17, '2025-09-13', '2025-09-18', '07:49:30', 1, 108.240, 12, '3-3-XAC-290925-003', '2025-37'),
(3, 15, 2, 1, 2, 1, 123.000, 14.760, 108.240, 12, 1, 17, '2025-09-24', '2025-09-19', '08:27:30', 1, 108.240, 12, '3-3-XAC-290925-003', '2025-39'),
(4, 17, 1, 1, 1, 1, 588.000, 14.760, 573.240, 12, 0, 17, '2025-09-13', '2025-09-18', '07:49:34', 1, 0.000, 0, '3-4-CNL-290925-001', '2025-37'),
(5, 16, 2, 1, 1, 1, 500.000, 37.375, 462.625, 25, 0, 17, '2025-09-13', '2025-09-18', '07:49:16', 0, 0.000, 0, '3-1-768-180925-002', '2025-38'),
(6, 17, 2, 1, 1, 1, 400.000, 44.280, 355.720, 36, 0, 17, '2025-09-15', '2025-09-18', '07:49:38', 1, 0.000, 0, '3-4-CNL-290925-001', '2025-38'),
(7, 15, 2, 1, 3, 1, 500.000, 21.760, 478.240, 12, 1, 17, '2025-09-17', '2025-09-19', '08:26:59', 1, 478.240, 12, '3-3-XAC-290925-003', '2025-38'),
(8, 15, 2, 1, 1, 1, 122.000, 14.760, 107.240, 12, 0, 17, '2025-09-23', '2025-09-23', '07:50:57', 1, 107.240, 12, '3-3-XAC-290925-003', '2025-39'),
(9, 13, 1, 9, 3, 1, 123.000, 21.784, 101.216, 12, 1, 23, '2025-09-29', '2025-09-29', '07:16:29', 1, 101.216, 12, '3-3-184-061025-001', '2025-40'),
(10, 15, 1, 2, 1, 1, 345.000, 25.608, 319.392, 12, 0, 17, '2025-10-02', '2025-10-02', '12:19:20', 1, 319.392, 12, '3-3-XAC-021025-001', '2025-40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_merma`
--

CREATE TABLE `registro_merma` (
  `id_registro_m` int(11) NOT NULL,
  `id_codigo_m` int(11) DEFAULT NULL,
  `id_sede_m` int(11) NOT NULL,
  `id_presentacion_m` int(11) DEFAULT NULL,
  `id_clasificacion` int(11) NOT NULL,
  `id_tipo_caja` int(11) NOT NULL,
  `id_tipo_tarima` int(11) NOT NULL,
  `id_tipo_carro` int(11) NOT NULL,
  `p_bruto` decimal(10,3) NOT NULL,
  `p_taraje` decimal(10,3) NOT NULL,
  `p_neto` decimal(10,3) NOT NULL,
  `cantidad_caja` int(11) NOT NULL,
  `cantidad_tarima` int(11) NOT NULL,
  `usuario_m` int(11) NOT NULL,
  `fecha_reg` date NOT NULL,
  `fecha_m` date NOT NULL,
  `hora_m` time NOT NULL,
  `activo_m` tinyint(1) NOT NULL,
  `kilos_dis` decimal(10,3) NOT NULL,
  `cajas_dis` int(11) NOT NULL,
  `no_serie_m` varchar(50) NOT NULL,
  `semana_m` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_merma`
--

INSERT INTO `registro_merma` (`id_registro_m`, `id_codigo_m`, `id_sede_m`, `id_presentacion_m`, `id_clasificacion`, `id_tipo_caja`, `id_tipo_tarima`, `id_tipo_carro`, `p_bruto`, `p_taraje`, `p_neto`, `cantidad_caja`, `cantidad_tarima`, `usuario_m`, `fecha_reg`, `fecha_m`, `hora_m`, `activo_m`, `kilos_dis`, `cajas_dis`, `no_serie_m`, `semana_m`) VALUES
(2, 13, 1, 1, 2, 1, 2, 1, 345.000, 24.940, 320.060, 12, 1, 17, '2017-09-25', '2025-09-17', '00:00:00', 1, 320.060, 12, '3-3-184-170925-001', '2025-38'),
(4, 16, 3, 2, 3, 1, 1, 1, 120.000, 17.220, 102.780, 14, 0, 23, '2025-10-06', '2025-10-06', '10:12:48', 1, 102.780, 14, '3-1-768-061025-001', '2025-41'),
(6, NULL, 3, NULL, 25, 8, 1, 1, 235.000, 16.608, 218.392, 12, 0, 17, '2025-09-24', '2025-09-24', '15:26:53', 1, 218.392, 12, '3-PRM-240925-003', '2025-39'),
(7, NULL, 3, NULL, 20, 1, 1, 1, 858.000, 143.520, 714.480, 96, 0, 17, '2025-09-17', '2025-09-17', '08:23:54', 1, 714.480, 96, '3-EPM-170925-001', '2025-38'),
(8, NULL, 3, NULL, 21, 1, 1, 1, 123.000, 17.940, 105.060, 12, 0, 17, '2025-09-17', '2025-09-17', '10:17:05', 1, 105.060, 12, '3-EPM-170925-003', '2025-38'),
(9, 15, 3, NULL, 25, 1, 1, 1, 123.000, 17.940, 105.060, 12, 0, 17, '2025-09-19', '2025-09-19', '08:29:11', 1, 105.060, 12, '3-3-XAC-190925-001', '2025-38'),
(10, NULL, 3, NULL, 24, 2, 2, 1, 123.000, 25.608, 97.392, 12, 12, 17, '2025-09-24', '2025-09-24', '08:02:33', 0, 97.392, 12, '-240925-001', '2025-39'),
(11, NULL, 3, NULL, 12, 1, 1, 1, 230.000, 30.750, 199.250, 25, 0, 17, '2025-09-24', '2025-09-24', '11:14:00', 1, 199.250, 25, '3-EPN-240925-002', '2025-39'),
(13, NULL, 3, NULL, 10, 5, 1, 1, 45.000, 3.028, 41.972, 2, 0, 17, '2025-09-24', '2025-09-24', '15:23:44', 1, 41.972, 2, '3-EPN-240925-003', '2025-39'),
(14, NULL, 3, NULL, 12, 4, 1, 1, 123.000, 16.488, 106.512, 12, 0, 17, '2025-09-24', '2025-09-24', '11:10:40', 1, 106.512, 12, '3-EPN-240925-001', '2025-39'),
(15, 15, 3, 1, 25, 1, 1, 1, 123.000, 14.760, 108.240, 12, 0, 17, '2025-09-24', '2025-09-24', '15:22:12', 1, 108.240, 12, '3-3-XAC-240925-001', '2025-39'),
(16, NULL, 3, NULL, 23, 1, 1, 1, 123.000, 14.760, 108.240, 12, 0, 17, '2025-09-24', '2025-09-24', '15:22:24', 1, 108.240, 12, '3-PRM-240925-001', '2025-39'),
(17, NULL, 3, NULL, 12, 2, 1, 1, 567.000, 25.608, 541.392, 12, 0, 17, '2025-10-02', '2025-10-02', '12:26:59', 1, 541.392, 12, '3-EPN-021025-003', '2025-40'),
(18, NULL, 3, NULL, 20, 3, 2, 1, 263.000, 31.772, 231.228, 26, 26, 17, '2025-10-13', '2025-10-02', '13:24:57', 1, 231.228, 26, '3-EPM-021025-001', '2025-40'),
(19, NULL, 3, NULL, 23, 6, 2, 1, 500.000, 23.784, 476.216, 12, 12, 17, '2025-10-03', '2025-10-03', '14:26:51', 1, 476.216, 12, '3-PRM-031025-001', '2025-40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_check`
--

CREATE TABLE `rh_check` (
  `id_check` int(11) NOT NULL,
  `badge` varchar(15) NOT NULL,
  `registro_check` datetime NOT NULL,
  `id_dptipo` varchar(25) NOT NULL,
  `id_dispositivo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rh_check`
--

INSERT INTO `rh_check` (`id_check`, `badge`, `registro_check`, `id_dptipo`, `id_dispositivo`) VALUES
(1, '0001', '2025-10-10 12:39:21', '0', 1),
(2, '0001', '2025-10-10 09:10:02', '0', 1),
(3, '0001', '2025-10-10 08:44:43', '0', 1),
(4, '0003', '2025-10-10 07:26:30', '0', 1),
(5, '0001', '2025-10-10 06:41:58', '0', 1),
(6, '0001', '2025-10-09 13:52:47', '0', 1),
(7, '0003', '2025-10-09 13:41:50', '0', 1),
(8, '0003', '2025-10-09 13:41:01', '0', 1),
(9, '0001', '2025-10-09 11:26:08', '0', 1),
(10, '0002', '2025-10-09 11:10:20', '0', 1),
(11, '0001', '2025-10-11 13:39:20', '0', 2),
(12, '0001', '2025-10-11 09:03:25', '0', 2),
(13, '0001', '2025-10-11 09:00:10', '0', 2),
(14, '0001', '2025-10-10 08:53:22', '0', 2),
(15, '0001', '2025-10-10 08:52:51', '0', 2),
(16, '0001', '2025-10-10 08:52:03', '0', 2),
(17, '0001', '2025-10-10 13:42:59', '0', 1),
(18, '0001', '2025-10-12 13:48:50', '0', 2),
(19, '0001', '2025-10-12 13:49:09', '0', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_departamentos`
--

CREATE TABLE `rh_departamentos` (
  `id_departamento` int(11) NOT NULL,
  `departamento` varchar(35) NOT NULL,
  `prefijo` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rh_departamentos`
--

INSERT INTO `rh_departamentos` (`id_departamento`, `departamento`, `prefijo`) VALUES
(1, 'SISTEMAS DE INFORMACIÓN', 'SIS'),
(2, 'ALMACÉN', 'ALM'),
(3, 'EMPAQUE', 'EMP'),
(4, 'FITOSANIDAD', 'FIT'),
(5, 'FERTIRRIGACIÓN', 'FER'),
(6, 'MANTENIMIENTO', 'MAN'),
(7, 'CONSTRUCCIÓN', 'CON'),
(8, 'CALIDAD', 'CAL'),
(9, 'INOCUIDAD', 'INO'),
(10, 'ADMINISTRACIÓN', 'ADM'),
(11, 'EXPORTACIÓN', 'EXP'),
(12, 'RECURSOS HUMANOS', 'RRHH'),
(13, 'OPERATIVA', 'OPE'),
(14, 'COMPRAS', 'COM'),
(15, 'CONTABILIDAD', 'CON'),
(16, 'FINANZAS', 'FIN'),
(17, 'SEGURIDAD', 'SEG'),
(18, 'DIRECCIÓN', 'DIR'),
(19, 'INVERNADERO 1', 'INV 1'),
(20, 'INVERNADERO 2', 'INV 2'),
(21, 'INVERNADERO 3', 'INV 3'),
(22, 'INVERNADERO 4', 'INV 4'),
(23, 'INVERNADERO 5', 'INV 5'),
(24, 'INVERNADERO 6', 'INV 6'),
(25, 'INVERNADERO 7', 'INV 7');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_dpbiometrico`
--

CREATE TABLE `rh_dpbiometrico` (
  `id_dpbiometrico` int(11) NOT NULL,
  `id_sede_dp` int(11) NOT NULL,
  `dispositivo` varchar(25) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `puerto` int(10) NOT NULL,
  `usuarios` int(11) NOT NULL,
  `registros` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rh_dpbiometrico`
--

INSERT INTO `rh_dpbiometrico` (`id_dpbiometrico`, `id_sede_dp`, `dispositivo`, `ip`, `puerto`, `usuarios`, `registros`) VALUES
(1, 3, 'ADMON1', '192.168.1.113', 4370, 0, 0),
(2, 3, 'ADMON2', '192.168.1.114', 4370, 0, 0),
(3, 3, 'EMPAQUE', '192.168.1.117', 4370, 0, 0),
(4, 3, 'MOD2A', '192.168.1.115', 4370, 0, 0),
(5, 3, 'MOD2B', '192.168.1.120', 4370, 0, 0),
(6, 3, 'MOD3', '192.168.1.116', 4370, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_generos`
--

CREATE TABLE `rh_generos` (
  `id_genero` int(11) NOT NULL,
  `genero` varchar(30) NOT NULL,
  `prefijo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rh_generos`
--

INSERT INTO `rh_generos` (`id_genero`, `genero`, `prefijo`) VALUES
(1, 'MASCULINO', 'M'),
(2, 'FEMENINO', 'F'),
(3, 'SIN ESPECIFICAR', 'SE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_personal`
--

CREATE TABLE `rh_personal` (
  `id_personal` int(11) NOT NULL,
  `id_sede_pl` int(11) NOT NULL,
  `badge` varchar(10) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `apellido_p` varchar(25) NOT NULL,
  `apellido_m` varchar(25) NOT NULL,
  `id_genero_pl` int(11) NOT NULL,
  `id_te_pl` int(11) NOT NULL,
  `id_depto_pl` int(11) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_registro` date NOT NULL,
  `id_user_p` int(11) NOT NULL,
  `status_pl` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rh_personal`
--

INSERT INTO `rh_personal` (`id_personal`, `id_sede_pl`, `badge`, `nombre`, `apellido_p`, `apellido_m`, `id_genero_pl`, `id_te_pl`, `id_depto_pl`, `fecha_ingreso`, `fecha_registro`, `id_user_p`, `status_pl`) VALUES
(1, 3, '0001', 'JOSÉ ANTONIO', 'RUIZ', 'ARGÜELLO', 1, 4, 1, '2025-08-25', '2025-10-09', 17, 1),
(2, 3, '0002', 'ELVIA GPE', 'FLORES', 'GARCÍA', 2, 4, 12, '2025-04-11', '2025-10-09', 17, 1),
(3, 3, '0003', 'VICTOR MANUEL', 'TORRES', 'CRUCES', 1, 4, 1, '2025-04-11', '2025-10-09', 17, 1),
(4, 3, '0004', 'JUAN', 'DE LOS', 'TOMATES', 3, 3, 4, '2025-10-10', '2025-10-10', 17, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_tipos_empleados`
--

CREATE TABLE `rh_tipos_empleados` (
  `id_tipo_rh` int(11) NOT NULL,
  `tipo_rh` varchar(25) NOT NULL,
  `prefijo_te` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rh_tipos_empleados`
--

INSERT INTO `rh_tipos_empleados` (`id_tipo_rh`, `tipo_rh`, `prefijo_te`) VALUES
(1, 'APOYO', 'APY'),
(2, 'MENOR', 'MEN'),
(3, 'PENDIENTE', 'PEN'),
(4, 'NÓMINA', 'NOM'),
(5, 'HONTEM', 'HOT'),
(6, 'BOXEL', 'BOX');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh_tipos_horarios`
--

CREATE TABLE `rh_tipos_horarios` (
  `id_thorario` int(11) NOT NULL,
  `id_sede_h` int(11) NOT NULL,
  `tipo_h` varchar(25) NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_salida` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rh_tipos_horarios`
--

INSERT INTO `rh_tipos_horarios` (`id_thorario`, `id_sede_h`, `tipo_h`, `hora_entrada`, `hora_salida`) VALUES
(1, 3, 'RF3 - EMPAQUE EXTRA', '09:00:00', '18:00:00');

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
  `nombre_s` varchar(25) NOT NULL,
  `codigo_s` varchar(10) NOT NULL,
  `ubicacion` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`id_sede`, `nombre_s`, `codigo_s`, `ubicacion`) VALUES
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
(1, 'NARANJA COSECHA INV.5', 1.230),
(2, 'BASURA GRANDE INV.5', 2.134),
(3, 'REGULAR INV.5', 1.222),
(4, 'COSECHA INV.7', 1.374),
(5, 'CHICA BASURA INV.7', 1.514),
(6, 'GRANDE BASURA INV.7', 1.982),
(7, 'REGULAR COSECHA INV.4', 1.384),
(8, 'COSECHA INV.4', 1.384),
(9, 'REGULAR VACIO INV.4', 1.232),
(10, 'GRANDE BASURA INV.4', 2.134),
(11, 'BASURA INV.6', 1.528),
(12, 'GRANDE INV.6', 1.982),
(13, 'COSECHA INV.6', 1.374);

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
  `nombre_p` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_presentacion`
--

INSERT INTO `tipos_presentacion` (`id_presentacion`, `nombre_p`) VALUES
(1, 'INDIVIDUAL'),
(2, 'RACIMO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_tarimas`
--

CREATE TABLE `tipos_tarimas` (
  `id_tarima` int(11) NOT NULL,
  `id_sede_t` int(11) NOT NULL,
  `nombre_tarima` varchar(25) NOT NULL,
  `peso_tarima` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_tarimas`
--

INSERT INTO `tipos_tarimas` (`id_tarima`, `id_sede_t`, `nombre_tarima`, `peso_tarima`) VALUES
(1, 3, 'NO APLICA', 0.000),
(2, 3, 'CHEP', 0.000),
(3, 3, 'MADERA', 7.000);

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
(10, '3511', 9, 'ROMA', 'CAFÉ', 342.000, 2, 2, 7),
(11, '3-5-XAC', 8, 'COOCKTAIL', 'ROJO', 345.000, 1, 2, 5),
(12, '3-3-CNL', 6, 'CHERRY', 'CAFÉ', 344.000, 1, 3, 3),
(13, '3-3-184', 9, 'CHERRY', 'ROJO', 36.000, 1, 2, 3),
(14, '3-2-BAL', 10, 'ROMA', 'ROJO', 455.000, 1, 3, 2),
(15, '3-3-XAC', 8, 'ROMA', 'CAFÉ', 344.000, 1, 2, 3),
(16, '3-1-768', 4, 'CHERRY', 'ROJO', 12.000, 1, 2, 1),
(17, '3-4-CNL', 6, 'CHERRY', 'ROJO', 12234.000, 1, 2, 4),
(18, '3-5-ADO', 2, 'ROMA', 'AMARILLO', 12000.000, 1, 2, 5),
(19, '3-5-ADO', 2, 'ROMA', 'AMARILLO', 12000.000, 1, 2, 5),
(20, '3-4-768', 4, 'COOCKTAIL', 'CAFÉ', 25000.000, 1, 2, 4),
(21, '3-4-768', 4, 'COOCKTAIL', 'CAFÉ', 25000.000, 1, 2, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `password`, `clave`, `id_cargo`, `id_rol`, `estado`) VALUES
(17, 'prueba', '$2y$15$IHtRNC7TMmSGb8E7IfZZIO8AbiLL.NtRHRMU.Hj7mM1tCm1wpfAXG', '1234', 1, 2, 1),
(19, 'rf3.empaque', '$2y$15$QglfDk.iUuNOFp5eI3pxlO8PTL9n9.7CBj0s0RgOtlGA2XvBkKKeS', '4503', 6, 3, 0),
(22, 'rf3.pallets', '$2y$15$djO197ij2qmVo7eDtoJiBu6BhOmXRdF0QjSOFEsChlAEiPrZz9.Ja', '2606', 3, 4, 0),
(23, 'rf3.pesaje', '$2y$15$4GAdwR9DmQEaLc2m5MVP.O9.gGaRH/vLjICWUdJ6q2bZBiJwE2Ti6', '1012', 2, 4, 0),
(24, 'rf3.mezclas', '$2y$15$ZC8KaxqEiPoO22M4VPFgcOM331lZAeiG64RIAWbXyzBpWxYtIN/be', '1504', 4, 4, 0),
(25, 'rf3.embarque', '$2y$15$jvwOuk4VSg5U071nims0rexP9sVFHpcbxXDMlkjy52NRn5g/8zLzO', '4503', 6, 4, 0);

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

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_asistencia`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_asistencia` (
`codigo_s` varchar(10)
,`badge` varchar(10)
,`tipo_empleado` varchar(25)
,`empleado` varchar(21)
,`nombre_completo` varchar(77)
,`departamento` varchar(35)
,`dia` date
,`entrada` datetime
,`salida` datetime
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_pendientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_pendientes` (
`id_personal` int(11)
,`id_sede_pl` int(11)
,`badge` varchar(10)
,`nombre` varchar(25)
,`apellido_p` varchar(25)
,`apellido_m` varchar(25)
,`id_genero_pl` int(11)
,`id_te_pl` int(11)
,`id_depto_pl` int(11)
,`fecha_ingreso` date
,`fecha_registro` date
,`id_user_p` int(11)
,`status_pl` tinyint(1)
,`codigo_s` varchar(10)
,`nombre_completo` varchar(50)
,`genero` varchar(30)
,`tipo_rh` varchar(25)
,`departamento` varchar(35)
,`nombre_personal` varchar(77)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_asistencia`
--
DROP TABLE IF EXISTS `vw_asistencia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_asistencia`  AS SELECT `s`.`codigo_s` AS `codigo_s`, `p`.`badge` AS `badge`, `te`.`tipo_rh` AS `tipo_empleado`, concat(`te`.`prefijo_te`,'-',`p`.`badge`) AS `empleado`, concat(`p`.`nombre`,' ',`p`.`apellido_p`,' ',`p`.`apellido_m`) AS `nombre_completo`, `d`.`departamento` AS `departamento`, cast(`c`.`registro_check` as date) AS `dia`, min(`c`.`registro_check`) AS `entrada`, CASE WHEN min(`c`.`registro_check`) = max(`c`.`registro_check`) THEN NULL ELSE max(`c`.`registro_check`) END AS `salida` FROM ((((`rh_personal` `p` left join `rh_check` `c` on(`p`.`badge` = `c`.`badge`)) left join `rh_tipos_empleados` `te` on(`p`.`id_te_pl` = `te`.`id_tipo_rh`)) left join `rh_departamentos` `d` on(`p`.`id_depto_pl` = `d`.`id_departamento`)) left join `sedes` `s` on(`p`.`id_sede_pl` = `s`.`id_sede`)) WHERE `p`.`status_pl` = 1 GROUP BY `p`.`badge`, cast(`c`.`registro_check` as date) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_pendientes`
--
DROP TABLE IF EXISTS `vw_pendientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_pendientes`  AS SELECT `p`.`id_personal` AS `id_personal`, `p`.`id_sede_pl` AS `id_sede_pl`, `p`.`badge` AS `badge`, `p`.`nombre` AS `nombre`, `p`.`apellido_p` AS `apellido_p`, `p`.`apellido_m` AS `apellido_m`, `p`.`id_genero_pl` AS `id_genero_pl`, `p`.`id_te_pl` AS `id_te_pl`, `p`.`id_depto_pl` AS `id_depto_pl`, `p`.`fecha_ingreso` AS `fecha_ingreso`, `p`.`fecha_registro` AS `fecha_registro`, `p`.`id_user_p` AS `id_user_p`, `p`.`status_pl` AS `status_pl`, `s`.`codigo_s` AS `codigo_s`, `c`.`nombre_completo` AS `nombre_completo`, `g`.`genero` AS `genero`, `te`.`tipo_rh` AS `tipo_rh`, `d`.`departamento` AS `departamento`, concat(`p`.`nombre`,' ',`p`.`apellido_p`,' ',`p`.`apellido_m`) AS `nombre_personal` FROM (((((((`rh_personal` `p` left join `usuarios` `u` on(`p`.`id_user_p` = `u`.`id_usuario`)) left join `cargos` `c` on(`u`.`id_cargo` = `c`.`id_cargo`)) left join `rh_generos` `g` on(`p`.`id_genero_pl` = `g`.`id_genero`)) left join `rh_tipos_empleados` `te` on(`p`.`id_te_pl` = `te`.`id_tipo_rh`)) left join `rh_departamentos` `d` on(`p`.`id_depto_pl` = `d`.`id_departamento`)) left join `rh_check` `k` on(`p`.`badge` = `k`.`badge`)) left join `sedes` `s` on(`p`.`id_sede_pl` = `s`.`id_sede`)) WHERE `p`.`status_pl` = 1 AND `k`.`badge` is null ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id_cargo`),
  ADD KEY `id_sede_u` (`id_sede_u`);

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
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_sede_c` (`id_sede_c`);

--
-- Indices de la tabla `destinos_embarque`
--
ALTER TABLE `destinos_embarque`
  ADD PRIMARY KEY (`id_destino`),
  ADD KEY `id_sede_d` (`id_sede_d`);

--
-- Indices de la tabla `embarques_pallets`
--
ALTER TABLE `embarques_pallets`
  ADD PRIMARY KEY (`id_embarque`),
  ADD KEY `id_destino_em` (`id_destino_em`),
  ADD KEY `id_sede_em` (`id_sede_em`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `empaque_lineas`
--
ALTER TABLE `empaque_lineas`
  ADD PRIMARY KEY (`id_linea`),
  ADD KEY `sede_id` (`id_sede_l`);

--
-- Indices de la tabla `invernaderos`
--
ALTER TABLE `invernaderos`
  ADD PRIMARY KEY (`id_invernadero`),
  ADD KEY `id_sede_i` (`id_sede_i`);

--
-- Indices de la tabla `mezclas`
--
ALTER TABLE `mezclas`
  ADD PRIMARY KEY (`id_mezcla`),
  ADD KEY `id_sede_m` (`id_sede_m`),
  ADD KEY `id_cliente_m` (`id_cliente_m`),
  ADD KEY `id_usuario_m` (`id_usuario_m`);

--
-- Indices de la tabla `mezcla_lotes`
--
ALTER TABLE `mezcla_lotes`
  ADD PRIMARY KEY (`id_mezclas`),
  ADD KEY `id_mezcla_l` (`id_mezcla_l`),
  ADD KEY `id_pesaje_l` (`id_lote_l`);

--
-- Indices de la tabla `mezcla_lotes_temp`
--
ALTER TABLE `mezcla_lotes_temp`
  ADD PRIMARY KEY (`id_mezcla_temp`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `id_lote` (`id_lote`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_seccion`);

--
-- Indices de la tabla `pallets`
--
ALTER TABLE `pallets`
  ADD PRIMARY KEY (`id_pallet`),
  ADD KEY `id_tarima` (`id_tarima_p`),
  ADD KEY `id_sede_p` (`id_sede_p`),
  ADD KEY `id_presen_p` (`id_presen_p`),
  ADD KEY `usuario_id` (`id_usuario_p`),
  ADD KEY `id_embarque_p` (`id_embarque_p`);

--
-- Indices de la tabla `pallet_mezclas`
--
ALTER TABLE `pallet_mezclas`
  ADD PRIMARY KEY (`id_pallets`),
  ADD KEY `id_pallet_m` (`id_pallet_m`),
  ADD KEY `id_mezcla_m` (`id_mezcla_m`),
  ADD KEY `id_linea_m` (`id_linea_m`);

--
-- Indices de la tabla `pallet_mezclas_temp`
--
ALTER TABLE `pallet_mezclas_temp`
  ADD PRIMARY KEY (`id_pallet_temp`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `id_mezcla_t` (`id_mezcla_t`),
  ADD KEY `id_linea_t` (`id_linea_t`);

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
  ADD KEY `id_modulo_p` (`id_modulo_u`),
  ADD KEY `id_permisos_u` (`id_permisos_u`),
  ADD KEY `permisos_usuarios_ibfk_1` (`id_usuario_u`);

--
-- Indices de la tabla `presentaciones_pallet`
--
ALTER TABLE `presentaciones_pallet`
  ADD PRIMARY KEY (`id_presentacion_p`),
  ADD KEY `sede_id` (`sede_id`),
  ADD KEY `tarima_id` (`tarima_id`);

--
-- Indices de la tabla `registro_empaque`
--
ALTER TABLE `registro_empaque`
  ADD PRIMARY KEY (`id_registro_r`),
  ADD KEY `id_tipo_caja` (`id_tipo_caja`),
  ADD KEY `id_tipo_tarima` (`id_tipo_tarima`),
  ADD KEY `id_tipo_carro` (`id_tipo_carro`),
  ADD KEY `registro_empaque_ibfk_1` (`id_codigo_r`),
  ADD KEY `id_presentacion_r` (`id_presentacion_r`),
  ADD KEY `usuario_r` (`usuario_r`);

--
-- Indices de la tabla `registro_merma`
--
ALTER TABLE `registro_merma`
  ADD PRIMARY KEY (`id_registro_m`),
  ADD KEY `id_codigo_m` (`id_codigo_m`),
  ADD KEY `id_tipo_caja` (`id_tipo_caja`),
  ADD KEY `id_tipo_tarima` (`id_tipo_tarima`),
  ADD KEY `id_tipo_carro` (`id_tipo_carro`),
  ADD KEY `id_presentacion_m` (`id_presentacion_m`),
  ADD KEY `usuario_r` (`usuario_m`),
  ADD KEY `id_clasificacion` (`id_clasificacion`),
  ADD KEY `id_sede_m` (`id_sede_m`);

--
-- Indices de la tabla `rh_check`
--
ALTER TABLE `rh_check`
  ADD PRIMARY KEY (`id_check`);

--
-- Indices de la tabla `rh_departamentos`
--
ALTER TABLE `rh_departamentos`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `rh_dpbiometrico`
--
ALTER TABLE `rh_dpbiometrico`
  ADD PRIMARY KEY (`id_dpbiometrico`),
  ADD KEY `id_sede_dp` (`id_sede_dp`);

--
-- Indices de la tabla `rh_generos`
--
ALTER TABLE `rh_generos`
  ADD PRIMARY KEY (`id_genero`);

--
-- Indices de la tabla `rh_personal`
--
ALTER TABLE `rh_personal`
  ADD PRIMARY KEY (`id_personal`),
  ADD KEY `id_sede_pl` (`id_sede_pl`),
  ADD KEY `id_genero_pl` (`id_genero_pl`),
  ADD KEY `id_te_pl` (`id_te_pl`),
  ADD KEY `id_depto_pl` (`id_depto_pl`),
  ADD KEY `id_user_p` (`id_user_p`);

--
-- Indices de la tabla `rh_tipos_empleados`
--
ALTER TABLE `rh_tipos_empleados`
  ADD PRIMARY KEY (`id_tipo_rh`);

--
-- Indices de la tabla `rh_tipos_horarios`
--
ALTER TABLE `rh_tipos_horarios`
  ADD PRIMARY KEY (`id_thorario`),
  ADD KEY `id_sede_h` (`id_sede_h`);

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
  ADD PRIMARY KEY (`id_tarima`),
  ADD KEY `id_sede_t` (`id_sede_t`);

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
  ADD KEY `usuarios_ibfk_1` (`id_cargo`),
  ADD KEY `usuarios_ibfk_2` (`id_rol`);

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
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ciclos`
--
ALTER TABLE `ciclos`
  MODIFY `id_ciclo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clasificacion_merma`
--
ALTER TABLE `clasificacion_merma`
  MODIFY `id_merma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `destinos_embarque`
--
ALTER TABLE `destinos_embarque`
  MODIFY `id_destino` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `embarques_pallets`
--
ALTER TABLE `embarques_pallets`
  MODIFY `id_embarque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empaque_lineas`
--
ALTER TABLE `empaque_lineas`
  MODIFY `id_linea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `invernaderos`
--
ALTER TABLE `invernaderos`
  MODIFY `id_invernadero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mezclas`
--
ALTER TABLE `mezclas`
  MODIFY `id_mezcla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `mezcla_lotes`
--
ALTER TABLE `mezcla_lotes`
  MODIFY `id_mezclas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `mezcla_lotes_temp`
--
ALTER TABLE `mezcla_lotes_temp`
  MODIFY `id_mezcla_temp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pallets`
--
ALTER TABLE `pallets`
  MODIFY `id_pallet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pallet_mezclas`
--
ALTER TABLE `pallet_mezclas`
  MODIFY `id_pallets` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `pallet_mezclas_temp`
--
ALTER TABLE `pallet_mezclas_temp`
  MODIFY `id_pallet_temp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `permisos_usuarios`
--
ALTER TABLE `permisos_usuarios`
  MODIFY `id_permiso_u` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT de la tabla `presentaciones_pallet`
--
ALTER TABLE `presentaciones_pallet`
  MODIFY `id_presentacion_p` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `registro_empaque`
--
ALTER TABLE `registro_empaque`
  MODIFY `id_registro_r` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `registro_merma`
--
ALTER TABLE `registro_merma`
  MODIFY `id_registro_m` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `rh_check`
--
ALTER TABLE `rh_check`
  MODIFY `id_check` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `rh_departamentos`
--
ALTER TABLE `rh_departamentos`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `rh_dpbiometrico`
--
ALTER TABLE `rh_dpbiometrico`
  MODIFY `id_dpbiometrico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `rh_generos`
--
ALTER TABLE `rh_generos`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rh_personal`
--
ALTER TABLE `rh_personal`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rh_tipos_empleados`
--
ALTER TABLE `rh_tipos_empleados`
  MODIFY `id_tipo_rh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `rh_tipos_horarios`
--
ALTER TABLE `rh_tipos_horarios`
  MODIFY `id_thorario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id_tarima` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_variaciones`
--
ALTER TABLE `tipo_variaciones`
  MODIFY `id_variedad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `variedades`
--
ALTER TABLE `variedades`
  MODIFY `id_nombre_v` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD CONSTRAINT `cargos_ibfk_1` FOREIGN KEY (`id_sede_u`) REFERENCES `sedes` (`id_sede`);

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_sede_c`) REFERENCES `sedes` (`id_sede`);

--
-- Filtros para la tabla `destinos_embarque`
--
ALTER TABLE `destinos_embarque`
  ADD CONSTRAINT `destinos_embarque_ibfk_1` FOREIGN KEY (`id_sede_d`) REFERENCES `sedes` (`id_sede`);

--
-- Filtros para la tabla `embarques_pallets`
--
ALTER TABLE `embarques_pallets`
  ADD CONSTRAINT `embarques_pallets_ibfk_1` FOREIGN KEY (`id_destino_em`) REFERENCES `destinos_embarque` (`id_destino`),
  ADD CONSTRAINT `embarques_pallets_ibfk_2` FOREIGN KEY (`id_sede_em`) REFERENCES `sedes` (`id_sede`),
  ADD CONSTRAINT `embarques_pallets_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `empaque_lineas`
--
ALTER TABLE `empaque_lineas`
  ADD CONSTRAINT `empaque_lineas_ibfk_1` FOREIGN KEY (`id_sede_l`) REFERENCES `sedes` (`id_sede`);

--
-- Filtros para la tabla `invernaderos`
--
ALTER TABLE `invernaderos`
  ADD CONSTRAINT `invernaderos_ibfk_1` FOREIGN KEY (`id_sede_i`) REFERENCES `sedes` (`id_sede`);

--
-- Filtros para la tabla `mezclas`
--
ALTER TABLE `mezclas`
  ADD CONSTRAINT `mezclas_ibfk_1` FOREIGN KEY (`id_sede_m`) REFERENCES `sedes` (`id_sede`),
  ADD CONSTRAINT `mezclas_ibfk_2` FOREIGN KEY (`id_cliente_m`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `mezclas_ibfk_3` FOREIGN KEY (`id_usuario_m`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `mezcla_lotes`
--
ALTER TABLE `mezcla_lotes`
  ADD CONSTRAINT `mezcla_lotes_ibfk_1` FOREIGN KEY (`id_mezcla_l`) REFERENCES `mezclas` (`id_mezcla`),
  ADD CONSTRAINT `mezcla_lotes_ibfk_2` FOREIGN KEY (`id_lote_l`) REFERENCES `registro_empaque` (`id_registro_r`);

--
-- Filtros para la tabla `mezcla_lotes_temp`
--
ALTER TABLE `mezcla_lotes_temp`
  ADD CONSTRAINT `mezcla_lotes_temp_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `mezcla_lotes_temp_ibfk_2` FOREIGN KEY (`id_lote`) REFERENCES `registro_empaque` (`id_registro_r`);

--
-- Filtros para la tabla `pallets`
--
ALTER TABLE `pallets`
  ADD CONSTRAINT `pallets_ibfk_1` FOREIGN KEY (`id_tarima_p`) REFERENCES `tipos_tarimas` (`id_tarima`),
  ADD CONSTRAINT `pallets_ibfk_2` FOREIGN KEY (`id_sede_p`) REFERENCES `sedes` (`id_sede`),
  ADD CONSTRAINT `pallets_ibfk_4` FOREIGN KEY (`id_presen_p`) REFERENCES `presentaciones_pallet` (`id_presentacion_p`),
  ADD CONSTRAINT `pallets_ibfk_5` FOREIGN KEY (`id_usuario_p`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `pallets_ibfk_6` FOREIGN KEY (`id_embarque_p`) REFERENCES `embarques_pallets` (`id_embarque`);

--
-- Filtros para la tabla `pallet_mezclas`
--
ALTER TABLE `pallet_mezclas`
  ADD CONSTRAINT `pallet_mezclas_ibfk_1` FOREIGN KEY (`id_pallet_m`) REFERENCES `pallets` (`id_pallet`),
  ADD CONSTRAINT `pallet_mezclas_ibfk_2` FOREIGN KEY (`id_mezcla_m`) REFERENCES `mezclas` (`id_mezcla`),
  ADD CONSTRAINT `pallet_mezclas_ibfk_3` FOREIGN KEY (`id_linea_m`) REFERENCES `empaque_lineas` (`id_linea`);

--
-- Filtros para la tabla `pallet_mezclas_temp`
--
ALTER TABLE `pallet_mezclas_temp`
  ADD CONSTRAINT `pallet_mezclas_temp_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `pallet_mezclas_temp_ibfk_2` FOREIGN KEY (`id_mezcla_t`) REFERENCES `mezclas` (`id_mezcla`),
  ADD CONSTRAINT `pallet_mezclas_temp_ibfk_3` FOREIGN KEY (`id_linea_t`) REFERENCES `empaque_lineas` (`id_linea`);

--
-- Filtros para la tabla `permisos_usuarios`
--
ALTER TABLE `permisos_usuarios`
  ADD CONSTRAINT `permisos_usuarios_ibfk_1` FOREIGN KEY (`id_usuario_u`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permisos_usuarios_ibfk_2` FOREIGN KEY (`id_modulo_u`) REFERENCES `modulos` (`id_seccion`),
  ADD CONSTRAINT `permisos_usuarios_ibfk_3` FOREIGN KEY (`id_permisos_u`) REFERENCES `permisos` (`id_permiso`);

--
-- Filtros para la tabla `presentaciones_pallet`
--
ALTER TABLE `presentaciones_pallet`
  ADD CONSTRAINT `presentaciones_pallet_ibfk_1` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id_sede`),
  ADD CONSTRAINT `presentaciones_pallet_ibfk_2` FOREIGN KEY (`tarima_id`) REFERENCES `tipos_tarimas` (`id_tarima`);

--
-- Filtros para la tabla `registro_empaque`
--
ALTER TABLE `registro_empaque`
  ADD CONSTRAINT `registro_empaque_ibfk_1` FOREIGN KEY (`id_codigo_r`) REFERENCES `tipo_variaciones` (`id_variedad`),
  ADD CONSTRAINT `registro_empaque_ibfk_2` FOREIGN KEY (`id_tipo_caja`) REFERENCES `tipos_cajas` (`id_caja`),
  ADD CONSTRAINT `registro_empaque_ibfk_3` FOREIGN KEY (`id_tipo_tarima`) REFERENCES `tipos_tarimas` (`id_tarima`),
  ADD CONSTRAINT `registro_empaque_ibfk_4` FOREIGN KEY (`id_tipo_carro`) REFERENCES `tipos_carros` (`id_carro`),
  ADD CONSTRAINT `registro_empaque_ibfk_5` FOREIGN KEY (`id_presentacion_r`) REFERENCES `tipos_presentacion` (`id_presentacion`),
  ADD CONSTRAINT `registro_empaque_ibfk_6` FOREIGN KEY (`usuario_r`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `registro_merma`
--
ALTER TABLE `registro_merma`
  ADD CONSTRAINT `registro_merma_ibfk_1` FOREIGN KEY (`id_codigo_m`) REFERENCES `tipo_variaciones` (`id_variedad`),
  ADD CONSTRAINT `registro_merma_ibfk_2` FOREIGN KEY (`id_tipo_caja`) REFERENCES `tipos_cajas` (`id_caja`),
  ADD CONSTRAINT `registro_merma_ibfk_3` FOREIGN KEY (`id_tipo_tarima`) REFERENCES `tipos_tarimas` (`id_tarima`),
  ADD CONSTRAINT `registro_merma_ibfk_4` FOREIGN KEY (`id_tipo_carro`) REFERENCES `tipos_carros` (`id_carro`),
  ADD CONSTRAINT `registro_merma_ibfk_5` FOREIGN KEY (`id_presentacion_m`) REFERENCES `tipos_presentacion` (`id_presentacion`),
  ADD CONSTRAINT `registro_merma_ibfk_6` FOREIGN KEY (`usuario_m`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `registro_merma_ibfk_7` FOREIGN KEY (`id_clasificacion`) REFERENCES `clasificacion_merma` (`id_merma`),
  ADD CONSTRAINT `registro_merma_ibfk_8` FOREIGN KEY (`id_sede_m`) REFERENCES `sedes` (`id_sede`);

--
-- Filtros para la tabla `rh_dpbiometrico`
--
ALTER TABLE `rh_dpbiometrico`
  ADD CONSTRAINT `rh_dpbiometrico_ibfk_1` FOREIGN KEY (`id_sede_dp`) REFERENCES `sedes` (`id_sede`);

--
-- Filtros para la tabla `rh_personal`
--
ALTER TABLE `rh_personal`
  ADD CONSTRAINT `rh_personal_ibfk_1` FOREIGN KEY (`id_sede_pl`) REFERENCES `sedes` (`id_sede`),
  ADD CONSTRAINT `rh_personal_ibfk_2` FOREIGN KEY (`id_genero_pl`) REFERENCES `rh_generos` (`id_genero`),
  ADD CONSTRAINT `rh_personal_ibfk_3` FOREIGN KEY (`id_te_pl`) REFERENCES `rh_tipos_empleados` (`id_tipo_rh`),
  ADD CONSTRAINT `rh_personal_ibfk_4` FOREIGN KEY (`id_depto_pl`) REFERENCES `rh_departamentos` (`id_departamento`),
  ADD CONSTRAINT `rh_personal_ibfk_5` FOREIGN KEY (`id_user_p`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `rh_tipos_horarios`
--
ALTER TABLE `rh_tipos_horarios`
  ADD CONSTRAINT `rh_tipos_horarios_ibfk_1` FOREIGN KEY (`id_sede_h`) REFERENCES `sedes` (`id_sede`);

--
-- Filtros para la tabla `tipos_tarimas`
--
ALTER TABLE `tipos_tarimas`
  ADD CONSTRAINT `tipos_tarimas_ibfk_1` FOREIGN KEY (`id_sede_t`) REFERENCES `sedes` (`id_sede`);

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
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
