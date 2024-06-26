-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2024 a las 17:01:03
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
-- Base de datos: `equip_corp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `id_area` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`id_area`, `nombre`, `activo`) VALUES
(1, 'BI', 1),
(2, 'Cobranza', 1),
(3, 'Compras', 1),
(4, 'Contabilidad', 1),
(5, 'Facturación', 1),
(6, 'Mercadeo ', 1),
(7, 'Mercadeo & Tecnologia', 1),
(8, 'Operaciones', 1),
(9, 'Talento Humano', 1),
(10, 'Tesoreria', 1),
(11, 'Ventas', 1),
(12, 'Administración', 1),
(13, 'RRHH', 1),
(14, 'Almacén', 1),
(16, 'mesi', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo_ruta`
--

CREATE TABLE `cargo_ruta` (
  `id_cargoruta` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargo_ruta`
--

INSERT INTO `cargo_ruta` (`id_cargoruta`, `nombre`, `activo`) VALUES
(1, 'Analista Comercial', 1),
(2, 'Analista de Cobranza 1', 1),
(3, 'Analista de Cobranza 2', 1),
(4, 'Analista de Cobranza 3', 1),
(5, 'Analista de Contabilidad 1', 1),
(6, 'Analista de Facturacion 1', 1),
(7, 'Analista de Facturacion 2', 1),
(8, 'Analista de Talento Humano', 1),
(9, 'Analista de Tesoreria', 1),
(10, 'CUADRANTE 1', 1),
(11, 'CUADRANTE 2', 1),
(12, 'CUADRANTE 3', 1),
(13, 'Gerente de Ventas', 1),
(14, 'Inteligencia Comercial', 1),
(15, 'Jefe de Operaciones', 1),
(16, 'RRHH', 1),
(17, 'Soporte Técnico', 1),
(18, 'Supervisor Almacén', 1),
(19, 'Supervisor de Cobranza', 1),
(20, 'Supervisor de Compras', 1),
(21, 'Supervisor de Contabilidad', 1),
(22, 'Supervisor de Facturación', 1),
(23, 'Supervisor de Tesoreria', 1),
(24, 'Supervisor de Trade', 1),
(25, 'Supervisor de Transporte', 1),
(26, 'Transporte 1', 1),
(27, 'Transporte 2', 1),
(28, 'Transporte 3', 1),
(29, 'Transporte 4', 1),
(30, 'Transporte 5', 1),
(31, 'Transporte 6', 1),
(32, 'Transporte 7', 1),
(33, 'Transporte 8', 1),
(34, 'Vigilante', 1),
(35, '0301', 1),
(36, 'A301', 1),
(37, 'P105', 1),
(38, 'P106', 1),
(39, 'P107', 1),
(40, 'P301', 1),
(41, 'P302', 1),
(42, 'P303', 1),
(43, 'P304', 1),
(44, 'P305', 1),
(45, 'P306', 1),
(46, 'P307', 1),
(47, 'P308', 1),
(48, 'P311', 1),
(49, 'P312', 1),
(50, 'P313', 1),
(51, 'P314', 1),
(52, 'P315', 1),
(53, 'P316', 1),
(54, 'P317', 1),
(55, 'P318', 1),
(56, 'P320', 1),
(57, 'P321', 1),
(58, 'P323', 1),
(59, 'P331', 1),
(60, 'P332', 1),
(61, 'P333', 1),
(62, 'P336', 1),
(63, 'PVAC', 1),
(64, 'Supervisor de Promotores', 1),
(67, 'Analista de Contabilidad 2', 1),
(68, 'Analista de Contabilidad 3', 1),
(69, 'Jefe de Finanzas', 1),
(70, 'P334', 1),
(72, 'Recepcionista', 1),
(73, 'Seguridad laboral', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `computadoras`
--

CREATE TABLE `computadoras` (
  `id_pc` int(10) NOT NULL,
  `id_tipo_equipo` int(10) NOT NULL,
  `id_fabricante` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `user_admin` varchar(30) NOT NULL,
  `motherboard` varchar(30) NOT NULL,
  `serial` varchar(50) NOT NULL,
  `procesador` varchar(50) NOT NULL,
  `ram` varchar(30) NOT NULL,
  `almacenamiento` varchar(30) NOT NULL,
  `id_almacentipo` int(10) NOT NULL,
  `id_red` int(10) NOT NULL,
  `id_pcso` int(10) NOT NULL,
  `clave_win` varchar(30) NOT NULL,
  `pin` varchar(30) NOT NULL,
  `resp_seguridad` varchar(30) NOT NULL,
  `id_personal` int(10) NOT NULL,
  `status` enum('Operativo','Dañado','Descontinuado') NOT NULL,
  `prio_sus` enum('ALTA','MEDIA','BAJA') NOT NULL,
  `id_sisadmin` int(10) NOT NULL,
  `ups` varchar(30) NOT NULL,
  `potencia_ups` varchar(30) NOT NULL,
  `bateria_ups` varchar(30) DEFAULT NULL,
  `bateria_reemplazada` date DEFAULT NULL,
  `estado_ups` enum('BACKUP','SIN BACKUP','NO TIENE') NOT NULL,
  `mouse` enum('BUENO','DAÑADO','NO TIENE','OTRO') NOT NULL,
  `pantalla_monitor` enum('BUENO','DAÑADO','PARTIDO','RAYADO','NO TIENE','OTRO') NOT NULL,
  `programas` set('AnyDesk','AVG Antivirus','Crystal Reports','Google Chrome','Microsoft Edge','Office','WinRAR','Framework','Sistema ADN','Adobe Acrobat','INT Nómina','INT Administrativo','WhatsApp') NOT NULL,
  `accesorios` set('Cargador','Cable mickey','Guaya de seguridad','Mouse','Estuche','Adaptador red','Cubreteclado','Funda') NOT NULL,
  `estado_teclado` enum('BUENO','DAÑADO','PARTIDO','NO SE VEN TECLAS','NO TIENE','OTRO') NOT NULL,
  `compra_teclado` date NOT NULL,
  `cargador` enum('BUENO','DAÑADO','NO TIENE','NO USA','OTRO') NOT NULL,
  `cable_mickey` enum('BUENO','DAÑADO','NO TIENE','NO USA','OTRO') NOT NULL,
  `camara` enum('BUENO','DAÑADO','NO TIENE','OTRO') NOT NULL,
  `anydesk` varchar(50) NOT NULL,
  `clave_anydesk` varchar(30) NOT NULL,
  `mac_lan` varchar(30) NOT NULL,
  `mac_wifi` varchar(30) NOT NULL,
  `nota` varchar(500) NOT NULL,
  `id_sucursal` int(10) NOT NULL,
  `fecha_ult_mant` date DEFAULT NULL,
  `fecha_ult_rev` date DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `computadoras`
--

INSERT INTO `computadoras` (`id_pc`, `id_tipo_equipo`, `id_fabricante`, `nombre`, `user_admin`, `motherboard`, `serial`, `procesador`, `ram`, `almacenamiento`, `id_almacentipo`, `id_red`, `id_pcso`, `clave_win`, `pin`, `resp_seguridad`, `id_personal`, `status`, `prio_sus`, `id_sisadmin`, `ups`, `potencia_ups`, `bateria_ups`, `bateria_reemplazada`, `estado_ups`, `mouse`, `pantalla_monitor`, `programas`, `accesorios`, `estado_teclado`, `compra_teclado`, `cargador`, `cable_mickey`, `camara`, `anydesk`, `clave_anydesk`, `mac_lan`, `mac_wifi`, `nota`, `id_sucursal`, `fecha_ult_mant`, `fecha_ult_rev`, `activo`) VALUES
(1, 1, 1, 'N/A', 'N/A', 'H61M-VG3', 'N/A', 'Intel Pentium g2030 3Ghz', '4GB', '500GB', 1, 1, 1, 'N/A', 'N/A', 'loro', 0, 'Operativo', 'BAJA', 1, 'Explore AI 700', '700VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', 'AnyDesk,AVG Antivirus,Crystal Reports,Google Chrome,Microsoft Edge,Office,WinRAR,Framework,Sistema ADN,Adobe Acrobat,INT Nómina,INT Administrativo', 'Mouse', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '2024-06-17', '0000-00-00', 1),
(2, 1, 1, 'N/A', 'N/A', 'H61M-VG3', 'N/A', 'AMD SEMPRON 2650 1.45GHz', '4GB', '500GB', 1, 1, 1, 'N/A', 'N/A', 'loro', 85, 'Operativo', 'MEDIA', 0, 'Forza nt501 500VA', '500VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', 'AnyDesk,AVG Antivirus,Google Chrome,Microsoft Edge,Office,WinRAR,Framework,Adobe Acrobat', 'Mouse', 'NO SE VEN TECLAS', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '2024-06-24', '2024-06-24', 1),
(3, 1, 2, '', '0', 'AM1ML', '', 'AMD SEMPRON 2650 1.45GHz', '4GB', '500GB', 1, 1, 1, '', '', 'loro', 0, 'Descontinuado', 'MEDIA', 4, 'Nexcom 1000VA', '1000VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(4, 1, 2, '', '0', 'AM1ML', '', 'AMD SEMPRON 2650 1.45GHz', '4GB', '500GB', 1, 1, 1, '', '', 'loro', 68, 'Descontinuado', 'ALTA', 1, 'Forza nt501 500VA', '500VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(5, 1, 2, '', '0', 'A780L3C', '', 'AMD SEMPRON X2 2.50GHz', '4GB', '500GB', 1, 1, 1, '', '', 'loro', 5, 'Descontinuado', 'BAJA', 1, 'Nexcom 1000VA', '1000VA', '12V 7AH', '0000-00-00', 'SIN BACKUP', 'BUENO', 'BUENO', '', '', 'NO SE VEN TECLAS', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(6, 1, 2, '', '0', 'A780L3C', '', 'AMD SEMPRON X2 2.50GHz', '4GB', '500GB', 1, 1, 1, '', '', 'loro', 0, 'Descontinuado', 'BAJA', 1, 'Forza nt501 500VA', '500VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(7, 1, 3, '', '0', 'OPTIPLEX 390', '', 'Intel i5 2.90 GHz', '8GB', '500GB', 2, 2, 1, '', '', 'loro', 1, 'Operativo', '', 1, 'Forza nt501 500VA', '500VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, '2024-06-18', '2024-06-18', 1),
(8, 1, 3, '', '0', 'OPTIPLEX 390', '', 'Intel i5 2.90 GHz', '8GB', '500GB', 2, 2, 1, '', '', 'loro', 72, 'Operativo', '', 1, 'Nexcom 1000VA', '1000VA', '12V 5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, '2024-06-18', '2024-06-18', 1),
(9, 1, 3, '', '0', 'OPTIPLEX 390', '', 'Intel i5 2.90 GHz', '8GB', '500GB', 2, 2, 1, '', '', 'loro', 70, 'Operativo', '', 1, 'Forza nt501 500VA', '500VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(10, 1, 3, '', '0', 'OPTIPLEX 390', '', 'Intel i5 2.90 GHz', '8GB', '500GB', 2, 2, 1, '', '', 'loro', 7, 'Operativo', '', 1, 'Explore AI 700', '700VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(11, 1, 3, '', '0', 'OPTIPLEX 1090', '', 'Intel i5 2.90 GHz', '8GB', '500GB', 2, 2, 1, '', '', 'loro', 71, 'Operativo', '', 1, 'KODE 1000VA', '1000VA', '12V 7AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(12, 1, 4, '', '0', 'COMPAQ ELITE 8300 SFF', '', 'Intel i5 2.90 GHz', '4GB', '500GB', 2, 2, 1, '', '', 'loro', 35, 'Operativo', '', 1, 'Nexcom 1000VA', '1000VA', '12V 7AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'NO SE VEN TECLAS', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, '2024-06-17', '2024-06-17', 1),
(13, 1, 4, 'N/A', 'N/A', 'COMPAQ ELITE 8300 USDT', 'N/A', 'Intel i5 2.90 GHz', '8GB', '500GB', 2, 2, 1, 'N/A', 'N/A', 'loro', 84, 'Operativo', 'BAJA', 1, 'Nexcom nex800', '800VA', '12V 5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', 'AnyDesk,AVG Antivirus,Crystal Reports,Google Chrome,Microsoft Edge,Office,WinRAR,Framework,Adobe Acrobat,INT Nómina', 'Mouse', 'BUENO', '0000-00-00', 'NO USA', 'NO USA', 'NO TIENE', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '2024-06-24', '0000-00-00', 1),
(14, 1, 4, '', '0', 'COMPAQ ELITE 8300 USDT', '', 'Intel i5 2.90 GHz', '4GB', '500GB', 2, 2, 1, '', '', 'loro', 0, 'Operativo', '', 1, 'Explore AI 1000', '1000VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(15, 1, 4, '', '0', 'COMPAQ ELITE 8300 SFF', '', 'Intel i5 2.90 GHz', '8GB', '500GB', 2, 2, 1, '', '', 'loro', 52, 'Operativo', '', 1, 'Nexcom nex800', '800VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'NO SE VEN TECLAS', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(16, 2, 4, '', '0', '14-BS011LA', '', 'Intel i5 2.50 GHz', '4GB', '500GB', 1, 2, 1, '', '', 'loro', 0, 'Operativo', '', 1, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(17, 2, 4, 'N/A', 'N/A', '14-cf2520la', 'N/A', 'Intel i3-10110U 2.10 GHz', '8GB', '500GB', 2, 2, 2, 'N/A', 'N/A', 'loro', 62, 'Operativo', 'BAJA', 4, 'Forza nt501 500VA', '500VA', '12V 4.5AH', '0000-00-00', 'SIN BACKUP', 'BUENO', 'BUENO', 'AnyDesk,AVG Antivirus,Crystal Reports,Google Chrome,Microsoft Edge,Office,WinRAR,Framework,Adobe Acrobat,INT Nómina', 'Cargador,Cable mickey,Mouse,Cubreteclado', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '0000-00-00', '0000-00-00', 1),
(18, 2, 4, '', '0', '14-cf2520la', '', 'Intel i3-10110U 2.10 GHz', '8GB', '500GB', 2, 2, 2, '', '', 'loro', 64, 'Operativo', '', 0, 'NO USA', 'NA', 'NA', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', 'AnyDesk,AVG Antivirus,Crystal Reports,Google Chrome,Microsoft Edge,Office,WinRAR,Framework,Sistema ADN,Adobe Acrobat,INT Administrativo,WhatsApp', 'Cargador,Cable mickey,Mouse', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', 'El teclado tiene algunos residuos entre las teclas. Se le recuerda al usuario que debe evitar el consumo de alimentos cerca del equipo.', 1, '2024-06-19', '2024-06-19', 1),
(19, 2, 4, '', '0', '14-cf2520la', '', 'Intel i3-10110U 2.10 GHz', '8GB', '500GB', 2, 2, 2, 'Ventas2*Lara1234', '2023', 'loro', 0, 'Operativo', '', 0, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '1 757 364 759', '', '', '', '', 1, NULL, NULL, 1),
(20, 1, 1, '1', '2', '3', '4', '5', '2 GB', '12 TB', 1, 1, 1, '6', '7', '8', 75, 'Descontinuado', 'MEDIA', 0, '9', '10', '11', '2024-06-19', 'SIN BACKUP', 'OTRO', 'PARTIDO', 'AnyDesk,AVG Antivirus,Crystal Reports,Google Chrome,Microsoft Edge,Office,WinRAR,Framework,Sistema ADN,Adobe Acrobat,INT Nómina,INT Administrativo', 'Cargador,Cable mickey,Guaya de seguridad,Mouse,Estuche,Adaptador red,Cubreteclado', 'NO TIENE', '2024-06-19', 'DAÑADO', 'NO USA', 'DAÑADO', '12', '13', '14', '15', '<p>hola</p>\r\n', 1, '2024-06-19', '2024-06-19', 0),
(21, 2, 4, '', '0', '14-cf2520la', '', 'Intel i3-10110U 2.10 GHz', '8GB', '500GB', 2, 2, 2, '', '', 'loro', 0, 'Operativo', '', 3, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(22, 2, 4, '', '0', '14-cf2520la', '', 'Intel i3-10110U 2.10 GHz', '8GB', '500GB', 2, 2, 2, '', '', 'loro', 3, 'Operativo', '', 3, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(23, 1, 4, '', '0', 'COMPAQ ELITE 8300 USDT', '', 'Intel i5 2.90 GHz', '8GB', '500GB', 2, 2, 1, '', '', 'loro', 0, 'Operativo', '', 1, 'Forza nt501 500VA', '500VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 0, NULL, NULL, 1),
(24, 2, 4, '', '0', '14-cf2520la', '', 'Intel i3-10110U 2.10 GHz', '8GB', '500GB', 2, 2, 2, '', '', 'loro', 0, 'Operativo', '', 0, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(25, 2, 4, '', '0', '14-cf2520la', '', 'Intel i3-10110U 2.10 GHz', '8GB', '500GB', 2, 2, 3, 'LoroLara23**', 'NA', 'loro', 74, 'Operativo', '', 0, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(26, 2, 4, '', '0', '14-cf2520la', '', 'Intel i3-10110U 2.10 GHz', '8GB', '500GB', 2, 2, 2, '', '', 'loro', 8, 'Operativo', '', 3, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(27, 2, 4, '', '0', '14-cf2520la', '', 'Intel i3-10110U 2.10 GHz', '8GB', '500GB', 2, 2, 3, 'LoroLara23**', 'NA', 'loro', 15, 'Operativo', '', 3, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(28, 2, 5, '', '0', 'A315-58-350L', '', 'Intel i5-1035G1 1.00GHz', '8GB', '500GB', 2, 2, 2, '', '', 'loro', 2, 'Operativo', '', 0, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'NO TIENE', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(29, 2, 4, '', '0', 'CL0011LA', '', 'Intel i5-8250U 1.6 GHz', '8GB', '500GB', 2, 2, 1, '', '', 'loro', 69, 'Operativo', '', 1, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(30, 1, 6, '', '0', '35981Q9', '', 'Intel i5 2.90 GHz', '8GB', '500GB', 2, 2, 1, '', '', 'loro', 34, 'Operativo', '', 1, 'Nexcom 1000VA', '1000VA', '12V 7AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'NO SE VEN TECLAS', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, '2024-06-17', '2024-06-17', 1),
(31, 1, 6, '', '0', '35981Q9', '', 'Intel i5 2.90 GHz', '8GB', '500GB', 2, 2, 1, '', '', 'loro', 32, 'Operativo', '', 1, 'Forza nt501 500VA', '500VA', '12V 4.5AH', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', '', '', 'NO SE VEN TECLAS', '0000-00-00', '', '', 'BUENO', '', '', '', '', '', 1, '2024-06-17', '2024-06-17', 1),
(32, 1, 6, 'N/A', 'N/A', 'M71e', 'N/A', 'Intel i3 3.10 GHz', '4GB', '500GB', 1, 2, 1, 'N/A', 'N/A', 'loro', 65, 'Descontinuado', 'ALTA', 0, 'Forza nt501 500VA', '500VA', '12V 4.5AH', '0000-00-00', 'SIN BACKUP', 'DAÑADO', 'BUENO', 'AnyDesk,AVG Antivirus,Google Chrome,Microsoft Edge,Office,WinRAR,Framework,Adobe Acrobat', 'Mouse', 'BUENO', '0000-00-00', 'NO USA', 'NO USA', 'NO TIENE', 'N/A', 'N/A', 'N/A', 'N/A', '<p>Click izquierdo el mouse no funciona correctamente.</p>\r\n', 1, '2024-06-24', '0000-00-00', 1),
(33, 2, 5, '', '0', 'A315-58-350L', '', 'Intel i5-1035G1 1.00GHz', '8GB', '500GB', 2, 2, 2, '', '', 'loro', 67, 'Operativo', '', 0, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'NO TIENE', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 2, NULL, NULL, 1),
(34, 2, 6, '', '0', 'IdeaPad 3', '', 'Intel i3 1.20GHz', '4GB', '120GB', 2, 2, 1, '', '', 'loro', 0, 'Operativo', '', 1, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '', '', '', '', '', 1, NULL, NULL, 1),
(35, 2, 7, '', '0', 'X515E-BR3955', 'R6N0CV080955248', 'Intel I3-1115G4 3.00 GHz', '8GB', '500GB', 2, 2, 3, 'LoroLara23**', 'NA', 'loro', 9, 'Operativo', '', 1, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'NO USA', 'BUENO', '1 701 430 849', 'LoroLara22**', '', '', '', 1, NULL, NULL, 1),
(36, 2, 7, '', '0', 'X515E-BR3955', 'R6N0CV08110424E', 'Intel I3-1115G4 3.00 GHz', '8GB', '500GB', 2, 2, 3, 'LoroLara23**', 'NA', 'loro', 17, 'Operativo', '', 1, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'NO USA', 'BUENO', '1 860 256 057', 'LoroLara22**', '', '', '', 1, NULL, NULL, 1),
(37, 2, 7, '', '0', 'X515E-BR3955', 'R6N0CV08101024A', 'Intel I3-1115G4 3.00 GHz', '8GB', '500GB', 2, 2, 3, 'LoroLara23**', 'NA', 'loro', 12, 'Operativo', '', 1, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'NO USA', 'BUENO', '', 'LoroLara22**', '', '', '', 1, NULL, NULL, 1),
(38, 2, 7, '', '0', 'X515E-BR3955', 'R6N0CV08112224D', 'Intel I3-1115G4 3.00 GHz', '8GB', '500GB', 2, 2, 3, 'LoroLara23**', 'NA', 'loro', 14, 'Operativo', '', 1, 'NO USA', 'NA', 'NA', '0000-00-00', '', 'BUENO', 'BUENO', '', '', 'BUENO', '0000-00-00', 'BUENO', 'NO USA', 'BUENO', '', 'LoroLara22**', '', '', '', 1, NULL, NULL, 1),
(42, 2, 5, 'FINANZAS', 'Soporte', 'A315-510P-34LK', 'NXKDHAL00A334010912N00', 'Intel Core i3-N305', '8 GB', '512 GB', 2, 2, 3, 'LoroLara23**', 'N/A', 'loro', 8, 'Operativo', 'BAJA', 3, 'N/A', 'N/A', 'N/A', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', 'AnyDesk,AVG Antivirus,Crystal Reports,Google Chrome,Microsoft Edge,Office,WinRAR,Framework,Sistema ADN,Adobe Acrobat,INT Administrativo', 'Cargador,Cable mickey,Mouse,Adaptador red,Cubreteclado', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '1 848 900 062', 'LoroLara22**', '00:E0:4C:68:08:5F', 'F4:3B:D8:FE:20:E0', 'hola esto es una observación', 1, '2024-06-18', '2024-06-18', 1),
(50, 1, 1, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '21 TB', '12 TB', 2, 1, 3, 'N/A', 'N/A', 'N/A', 1, 'Operativo', 'BAJA', 3, 'N/A', 'N/A', 'N/A', '0000-00-00', 'NO TIENE', 'DAÑADO', 'RAYADO', 'AnyDesk,Sistema ADN,Adobe Acrobat,INT Administrativo,WhatsApp', '', 'NO SE VEN TECLAS', '0000-00-00', 'NO TIENE', 'DAÑADO', 'NO TIENE', 'N/A', 'N/A', 'N/A', 'N/A', '<p>hola</p>\r\n', 2, '2024-06-19', '2024-06-19', 0),
(51, 1, 6, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '21 GB', '12 TB', 2, 2, 3, 'N/A', 'N/A', 'N/A', 1, 'Dañado', 'BAJA', 4, 'N/A', 'N/A', 'N/A', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', 'Adobe Acrobat', 'Cargador,Cable mickey,Guaya de seguridad,Mouse,Estuche,Adaptador red,Cubreteclado', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', 'N/A', 'N/A', 'N/A', 'N/A', '<p>aaa</p>\r\n', 3, '2024-06-19', '2024-06-19', 0),
(52, 2, 3, '1', '2', '3', '4', '5', 'N/A', 'N/A', 2, 1, 3, '6', '7', '8', 3, 'Descontinuado', 'MEDIA', 4, '9', '10', '12', '2024-06-19', 'NO TIENE', 'DAÑADO', 'RAYADO', 'Crystal Reports,Google Chrome,Office,Sistema ADN', 'Cable mickey', 'NO TIENE', '2024-06-19', 'NO USA', 'NO USA', '', '13', '14', '15', '16', '<p>esto es una nota editada</p>\r\n', 3, '2024-06-19', '2024-06-19', 0),
(53, 2, 3, '1', '2', '3', '4', '5', 'N/A', 'N/A', 2, 1, 3, '6', '7', '8', 3, 'Descontinuado', 'MEDIA', 4, '9', '10', '12', '2024-06-19', 'NO TIENE', 'DAÑADO', 'RAYADO', 'Crystal Reports,Google Chrome,Office,Sistema ADN', 'Cable mickey', 'NO TIENE', '2024-06-19', 'NO USA', 'NO USA', 'NO TIENE', '13', '14', '15', '16', '<p>esto es una nota editada</p>\r\n', 3, '2024-06-19', '2024-06-19', 0),
(54, 2, 3, '1', '2', '3', '4', '5', '50 GB', '2 TB', 2, 1, 3, '6', '7', '8', 3, 'Descontinuado', 'MEDIA', 4, '9', '10', '12', '2024-06-19', 'NO TIENE', 'DAÑADO', 'RAYADO', 'Crystal Reports,Google Chrome,Office,Sistema ADN', 'Cable mickey', 'NO TIENE', '2024-06-19', 'NO USA', 'NO USA', 'NO TIENE', '13', '14', '15', '16', '<p>esto es una nota editada</p>\r\n', 3, '2024-06-19', '2024-06-19', 0),
(55, 2, 3, '1', '2', '3', '4', '5', '50 GB', '2 TB', 2, 1, 3, '6', '7', '8', 3, 'Descontinuado', 'MEDIA', 4, '9', '10', '12', '2024-06-19', 'NO TIENE', 'DAÑADO', 'RAYADO', 'Crystal Reports,Google Chrome,Office,Sistema ADN', 'Cable mickey', 'NO TIENE', '2024-06-19', 'NO USA', 'NO USA', 'NO TIENE', '13', '14', '15', '16', '<p>esto es una nota editada</p>\r\n', 3, '2024-06-19', '2024-06-19', 0),
(56, 2, 3, '1', '2', '3', '4', '5', '122 GB', '114 GB', 2, 1, 3, '6', '7', '8', 3, 'Descontinuado', 'MEDIA', 4, '9', '10', '12', '2024-06-19', 'NO TIENE', 'DAÑADO', 'RAYADO', 'Crystal Reports,Google Chrome,Office,Sistema ADN', 'Cable mickey', 'NO TIENE', '2024-06-19', 'NO USA', 'NO USA', 'NO TIENE', '13', '14', '15', '16', '<p>esto es una nota editada</p>\r\n', 3, '2024-06-19', '2024-06-19', 0),
(57, 2, 4, 'PROMOTORES', 'Soporte', '14-cf2520la', '5CG2164YLM', 'Intel i3-10110U 2.10 GHz', '8GB', '500GB', 2, 2, 3, 'LoroLara23**', 'NA', 'loro', 75, 'Operativo', 'BAJA', 0, 'NO USA', 'NA', 'NA', '0000-00-00', 'NO TIENE', 'BUENO', 'BUENO', 'AnyDesk,AVG Antivirus,Google Chrome,Microsoft Edge,Office,WinRAR,Adobe Acrobat', 'Cargador,Cable mickey,Guaya de seguridad,Mouse', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '1006801127', 'LoroLara22**', 'N/A', 'N/A', '', 1, '2024-06-19', '2024-06-19', 1),
(58, 2, 5, 'VENTAS', 'Soporte', 'A315-510P-34LK', 'NXKDHAL00A33401EEF2N00', 'Intel Core i3-N305', '8 GB', '512 GB', 2, 2, 3, 'LoroLara23**', 'N/A', 'loro', 18, 'Operativo', 'BAJA', 0, 'N/A', 'N/A', 'N/A', '0000-00-00', 'NO TIENE', 'BUENO', 'BUENO', 'AnyDesk,AVG Antivirus,Crystal Reports,Google Chrome,Microsoft Edge,Office,WinRAR,Framework,Adobe Acrobat,WhatsApp', 'Cargador,Cable mickey,Mouse,Adaptador red,Cubreteclado', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '1039874773', 'LoroLara22**', '00:E0:4C:68:0E:A4', '68:7A:64:56:A9:69', '', 1, '2024-06-19', '2024-06-19', 1),
(59, 2, 4, 'N/A', 'N/A', '14-cf2520la', 'N/A', 'Intel i3-10110U 2.10 GHz', '8 GB', '500 GB', 2, 2, 3, 'LoroLara23**', 'NA', 'loro', 75, 'Operativo', 'BAJA', 0, 'NO USA', 'NA', 'NA', '0000-00-00', 'BACKUP', 'BUENO', 'BUENO', 'Google Chrome', 'Mouse', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '0000-00-00', '0000-00-00', 0),
(60, 2, 5, 'OPERACIONES', 'Soporte', 'A315-510P-34LK', 'NXKDHAL00A33401F572N00', 'Intel Core i3-N305', '8 GB', '512 GB', 2, 2, 3, 'LoroLara23**', 'N/A', 'loro', 6, 'Operativo', 'BAJA', 1, 'N/A', 'N/A', 'N/A', '0000-00-00', 'NO TIENE', 'BUENO', 'BUENO', 'AnyDesk,AVG Antivirus,Google Chrome,Microsoft Edge,Office,WinRAR,Framework,Sistema ADN,Adobe Acrobat,WhatsApp', 'Cargador,Cable mickey,Mouse,Adaptador red,Cubreteclado,Funda', 'BUENO', '0000-00-00', 'BUENO', 'BUENO', 'BUENO', '1701746999', 'LoroLara22**', '00:E0:4C:68:03:23', '68:7A:64:0A:66:08', '', 1, '2024-06-21', '2024-06-21', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fabricante`
--

CREATE TABLE `fabricante` (
  `id_fabricante` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `equipo` enum('PC','Teléfono','Impresora') NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fabricante`
--

INSERT INTO `fabricante` (`id_fabricante`, `nombre`, `equipo`, `activo`) VALUES
(1, 'ASROCK', 'PC', 1),
(2, 'BIOSTAR', 'PC', 1),
(3, 'DELL', 'PC', 1),
(4, 'HP', 'PC', 1),
(5, 'ACER', 'PC', 1),
(6, 'LENOVO', 'PC', 1),
(7, 'ASUS', 'PC', 1),
(8, 'Xiaomi', 'Teléfono', 1),
(9, 'Samsung', 'Teléfono', 1),
(10, 'Tecno', 'Teléfono', 1),
(11, 'Alcatel', 'Teléfono', 1),
(12, 'EPSON', 'Impresora', 1),
(13, 'GOOGLE', 'Teléfono', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impresoras`
--

CREATE TABLE `impresoras` (
  `id_impresora` int(10) NOT NULL,
  `id_fabricante` int(11) NOT NULL,
  `modelo` varchar(30) NOT NULL,
  `estado` enum('Operativa','Dañada') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_area` int(10) NOT NULL,
  `serial` varchar(30) NOT NULL,
  `mac_lan` varchar(30) NOT NULL,
  `ult_mantenimiento` date NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `impresoras`
--

INSERT INTO `impresoras` (`id_impresora`, `id_fabricante`, `modelo`, `estado`, `id_area`, `serial`, `mac_lan`, `ult_mantenimiento`, `activo`) VALUES
(1, 12, 'L3110', 'Operativa', 12, '6623', '0', '0000-00-00', 1),
(2, 12, 'L3110', 'Operativa', 5, '8178', '0', '0000-00-00', 1),
(3, 12, 'L3150', 'Operativa', 13, '5913', '0', '0000-00-00', 1),
(4, 12, 'L220', 'Operativa', 14, '4688', '0', '0000-00-00', 1),
(5, 12, 'FX890', 'Operativa', 13, '6083', '0', '0000-00-00', 1),
(6, 12, 'FX890', 'Operativa', 5, '7845', '0', '0000-00-00', 1),
(7, 12, 'L3110', 'Dañada', 0, '7580', '0', '0000-00-00', 1),
(8, 12, 'L3210', 'Dañada', 0, '0', '0', '0000-00-00', 1),
(9, 12, '321', 'Operativa', 9, '321', '231', '0000-00-00', 0),
(10, 12, '1234', 'Operativa', 2, '1234', '1234', '0000-00-00', 0),
(11, 12, '1234', 'Dañada', 16, '1234', '1234', '0000-00-00', 0),
(12, 12, '123', '', 2, '123', '12312', '0000-00-00', 0),
(13, 12, '1234', 'Dañada', 2, '1234', '124', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo_marca`
--

CREATE TABLE `modelo_marca` (
  `id_modelo` int(10) NOT NULL,
  `id_fabricante` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `ram` varchar(10) NOT NULL,
  `rom` varchar(10) NOT NULL,
  `tipo` enum('Smartphone','Tablet') NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modelo_marca`
--

INSERT INTO `modelo_marca` (`id_modelo`, `id_fabricante`, `nombre`, `ram`, `rom`, `tipo`, `activo`) VALUES
(1, 11, '5001J', '2 GB', '16 GB', 'Smartphone', 1),
(2, 11, '5028A', '3 GB', '32 GB', 'Smartphone', 1),
(3, 11, '8094M V2e1w', '2 GB', '32 GB', 'Tablet', 1),
(4, 9, 'A21s', '4 GB', '64 GB', 'Smartphone', 1),
(5, 11, 'Cricket', '2 GB', '16 GB', 'Smartphone', 1),
(6, 8, 'Redmi 10C', '4 GB', '64 GB', 'Smartphone', 1),
(7, 8, 'Redmi 13C', '4 GB', '128 GB', 'Smartphone', 1),
(8, 8, 'Redmi 9A ', '2 GB', '32 GB', 'Smartphone', 1),
(9, 9, 'SM-A045', '4 GB', '64 GB', 'Smartphone', 1),
(10, 9, 'SM-J260M', '1 GB', '8 GB', 'Smartphone', 1),
(11, 10, 'Spark 20C', '4 GB', '128 GB', 'Smartphone', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operadora`
--

CREATE TABLE `operadora` (
  `id_operadora` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `operadora`
--

INSERT INTO `operadora` (`id_operadora`, `nombre`, `activo`) VALUES
(1, 'DIGITEL', 1),
(2, 'MOVISTAR', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pc_sis_op`
--

CREATE TABLE `pc_sis_op` (
  `id_pcso` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pc_sis_op`
--

INSERT INTO `pc_sis_op` (`id_pcso`, `nombre`, `activo`) VALUES
(1, 'Windows 10', 1),
(2, 'Windows 11', 1),
(3, 'Windows 11 Pro', 1),
(4, 'Debian 8', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id_personal` int(11) NOT NULL,
  `id_cargoruta` int(10) NOT NULL,
  `id_area` int(10) NOT NULL,
  `nombre` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id_personal`, `id_cargoruta`, `id_area`, `nombre`, `activo`) VALUES
(1, 68, 4, 'Sofia Salazar', 1),
(2, 1, 6, 'Luis Garcia', 1),
(3, 25, 8, 'Cesar Urrutia', 1),
(4, 34, 8, 'Giovanny Santella', 1),
(5, 18, 8, 'Raimon Redondo', 1),
(6, 15, 8, 'Gustavo Agüero', 1),
(7, 6, 8, 'Ruth Prado', 1),
(8, 69, 2, 'Abdiel Ramos', 1),
(9, 21, 4, 'Johanni Piña', 1),
(10, 11, 11, 'Edgar Gonzalez', 1),
(11, 14, 6, 'Jose Corredor', 1),
(12, 17, 6, 'David Sira', 1),
(13, 7, 8, 'Francisco Gonzalez', 1),
(14, 22, 8, 'Norbely Perez', 1),
(15, 20, 3, 'Fatima Gutierrez', 1),
(16, 26, 8, 'Daniel Alvarado', 1),
(17, 23, 10, 'Yamileth Alejos', 1),
(18, 13, 11, 'Maria Pereira', 1),
(19, 37, 11, 'Jonny Javier Varela Duque', 1),
(20, 27, 8, 'Harlen Caldera', 1),
(21, 38, 11, 'Luis Ramon Garrido Rivero', 1),
(22, 39, 11, 'Josue Vladimir Valderrama', 1),
(23, 28, 8, 'Adolfo Morles', 1),
(24, 24, 11, 'Carlos Pereira', 1),
(25, 30, 8, 'Miguel Ereu', 1),
(26, 40, 11, 'Argenis Barco', 1),
(27, 41, 11, 'Rosanna Lanza', 1),
(28, 35, 11, 'Raul Loyo', 1),
(29, 42, 11, 'Simon Perez', 1),
(30, 10, 0, 'Dayalenis Briceno', 1),
(31, 29, 8, 'Jorge Gonzalez', 1),
(32, 2, 2, 'Maria Mendoza', 1),
(33, 12, 11, 'Kleiver Ojeda', 1),
(34, 3, 2, 'Maria Gomez', 1),
(35, 4, 2, 'Alis Paez', 1),
(36, 36, 11, 'Junior Salcedo', 1),
(37, 45, 11, 'Rhona Molletone', 1),
(38, 43, 11, 'Janet Gardedieu', 1),
(39, 44, 11, 'Jesus Tovar', 1),
(40, 32, 8, 'Eduardo Lopez', 1),
(41, 46, 11, 'Gerardo Mora', 1),
(42, 48, 11, 'Juan Aricuco', 1),
(43, 47, 11, 'Ruben Castellanos', 1),
(44, 49, 11, 'Alexis Rodriguez', 1),
(45, 50, 11, 'Jose Diaz', 1),
(46, 51, 11, 'Angel Sarabia', 1),
(47, 52, 11, 'Rosmary Herrera', 1),
(48, 53, 11, 'Ymmer Camacaro', 1),
(49, 54, 11, 'Jose Montes', 1),
(50, 58, 11, 'Reinaldo Guerrero', 1),
(51, 8, 9, 'Josselin Linarez', 1),
(52, 9, 10, 'Francis Gonzales', 1),
(53, 31, 8, 'Elver Lopez', 1),
(54, 60, 11, 'Jose Sanchez', 1),
(55, 55, 11, 'Vincen Marquez', 1),
(56, 56, 11, 'Moraima Mora', 1),
(57, 33, 8, 'Jose Jimenez', 1),
(58, 57, 11, 'Gabriel Sira', 1),
(59, 59, 11, 'Freddy Garces', 1),
(60, 61, 11, 'Jose Lopez', 1),
(61, 62, 11, 'Carlos Paredes', 1),
(62, 16, 9, 'Genesis Ortiz', 1),
(63, 51, 11, 'Anderson Marquez', 1),
(64, 5, 4, 'Giselle Cuicas', 1),
(65, 72, 9, 'Daniela Vargas', 1),
(66, 0, 9, 'Adriana Araujo', 1),
(67, 0, 11, 'Alirio Galindez', 1),
(68, 0, 3, 'Deilyn Perez', 1),
(69, 0, 10, 'Flor Rodriguez', 1),
(70, 0, 8, 'Karen Cordero', 1),
(71, 0, 11, 'Maivellyc Melendez', 1),
(72, 67, 4, 'Maria Colmenares', 1),
(73, 0, 8, 'Mary Carmen Guevara', 1),
(74, 0, 11, 'Viviana Fonseca', 1),
(75, 64, 11, 'Wilderson Eviez', 1),
(82, 52, 11, 'Pedro García', 1),
(83, 70, 11, 'Martin Garcia', 1),
(84, 8, 9, 'Marycarmen Rodríguez', 1),
(85, 73, 9, 'Derwin Henriquez', 1),
(86, 72, 1, 'Hugo Chávez Frias', 0),
(87, 2, 1, 'test', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `red_lan`
--

CREATE TABLE `red_lan` (
  `id_red` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `red_lan`
--

INSERT INTO `red_lan` (`id_red`, `nombre`, `activo`) VALUES
(1, 'FE', 1),
(2, 'GB', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_mantenimiento`
--

CREATE TABLE `registro_mantenimiento` (
  `id_mantenimiento` int(10) NOT NULL,
  `fecha_mantenimiento` date NOT NULL,
  `id_pc` int(10) NOT NULL,
  `realizador` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_mantenimiento`
--

INSERT INTO `registro_mantenimiento` (`id_mantenimiento`, `fecha_mantenimiento`, `id_pc`, `realizador`, `activo`) VALUES
(12, '2024-06-17', 1, 'David Sira', 1),
(13, '2024-05-16', 1, 'David Sira', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sistema_admin`
--

CREATE TABLE `sistema_admin` (
  `id_sisadmin` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sistema_admin`
--

INSERT INTO `sistema_admin` (`id_sisadmin`, `nombre`, `activo`) VALUES
(1, 'ADN', 1),
(3, 'ADN/INT', 1),
(4, 'INT', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `id_sucursal` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `activo` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `nombre`, `activo`) VALUES
(1, 'LARA', 1),
(2, 'TÁCHIRA', 1),
(3, 'VALERA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefonos`
--

CREATE TABLE `telefonos` (
  `id_telefono` int(10) NOT NULL,
  `fecha_recep` date DEFAULT NULL,
  `id_modelo` int(10) NOT NULL,
  `accesorios` set('cabezal cargador','adaptador','cable usb','forro','vidrio templado','hidrogel','estuche') NOT NULL,
  `imei1` varchar(30) DEFAULT 'N/A',
  `imei2` varchar(30) DEFAULT 'N/A',
  `imei_adn` varchar(30) DEFAULT 'N/A',
  `serial` varchar(50) DEFAULT 'N/A',
  `vidrio_hidrogel` enum('BUENO','DAÑADO','PARTIDO','ROTO','RAYADO','NO TIENE','OTRO') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `forro` enum('BUENO','DAÑADO','NO TIENE','OTRO') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `pantalla` enum('BUENO','DAÑADO','PARTIDO','RAYADO','OTRO') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `camara` enum('BUENO','DAÑADO','MICA PARTIDA','MICA RAYADA','OTRO') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `cargador` enum('BUENO','DAÑADO','NO TIENE','NO TRAE A REVISIÓN','OTRO') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `cable_usb` enum('BUENO','DAÑADO','NO TIENE','NO TRAE A REVISIÓN','OTRO') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `adaptador` enum('BUENO','DAÑADO','NO TIENE','NO USA','NO TRAE A REVISIÓN','OTRO') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_sisver` int(10) NOT NULL,
  `almacenamiento_ocupado` varchar(10) DEFAULT 'N/A',
  `consumo_datos` varchar(10) DEFAULT 'N/A',
  `id_operadora` int(10) NOT NULL,
  `numero` varchar(30) DEFAULT 'N/A',
  `cuenta_google` varchar(100) DEFAULT 'N/A',
  `clave_google` varchar(30) DEFAULT 'N/A',
  `correo_corporativo` varchar(50) DEFAULT 'N/A',
  `clave_corporativo` varchar(30) DEFAULT 'N/A',
  `anydesk` varchar(50) DEFAULT 'N/A',
  `pin` varchar(30) DEFAULT 'N/A',
  `cuenta_mi` varchar(30) DEFAULT 'N/A',
  `clave_mi` varchar(30) DEFAULT 'N/A',
  `id_sucursal` int(10) NOT NULL,
  `precio` varchar(10) DEFAULT 'N/A',
  `mac_lan` varchar(30) DEFAULT 'N/A',
  `mac_wifi` varchar(30) DEFAULT 'N/A',
  `app_conf` set('whatsapp','gmail','adn','facebook','instagram','netflix','youtube','tiktok','ubicacion','tema por defecto','otra') NOT NULL,
  `otra_app` varchar(100) NOT NULL,
  `nota` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT 'Mantiene la configuración inicial.',
  `fecha_ult_mant` date DEFAULT current_timestamp(),
  `fecha_ult_rev` date DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `telefonos`
--

INSERT INTO `telefonos` (`id_telefono`, `fecha_recep`, `id_modelo`, `accesorios`, `imei1`, `imei2`, `imei_adn`, `serial`, `vidrio_hidrogel`, `forro`, `pantalla`, `camara`, `cargador`, `cable_usb`, `adaptador`, `id_sisver`, `almacenamiento_ocupado`, `consumo_datos`, `id_operadora`, `numero`, `cuenta_google`, `clave_google`, `correo_corporativo`, `clave_corporativo`, `anydesk`, `pin`, `cuenta_mi`, `clave_mi`, `id_sucursal`, `precio`, `mac_lan`, `mac_wifi`, `app_conf`, `otra_app`, `nota`, `fecha_ult_mant`, `fecha_ult_rev`, `activo`) VALUES
(1, '2021-06-29', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '860823051545006', '860823051545014', 'N/A', '34491/61RV17535', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, '30GB', '123MB', 1, '0412-0491274', 'contabilidad3.', '@Loro1234', 'N/A', 'N/A', 'N/A', 'N/A', 'NA', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,gmail,ubicacion,tema por defecto', '', '<p>Mantiene la configuraci&oacute;n inicial.</p>\r\n', '2024-06-07', '2024-06-07', 1),
(2, '2022-10-14', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '863135060640166', '863135060640174', '', '37566/62TW11629', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, '23.9', '', 1, '0412-1722183', 'analistabi.lara@gmail.com', '@Loro1234*', '', '', '', '', '6655704168', '@Loro1234', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(3, '2021-05-13', 1, 'cabezal cargador,cable usb', '356310101146416', '356310101146424', '', 'NA', 'BUENO', 'NO TIENE', 'OTRO', 'BUENO', 'BUENO', 'BUENO', 'NO USA', 3, '12', '', 1, '0412-0412219', 'suptranslorolara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(4, '2021-05-13', 1, '', ' 356310101127325', '356310101127333', '', 'NA', 'NO TIENE', 'NO TIENE', 'BUENO', 'MICA RAYADA', 'NO TIENE', 'NO TIENE', 'NO USA', 3, '15.21', '', 1, '0412-5845637', '', '', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(5, '2023-09-26', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867243060975925', '867243060975933', '', '38619/62YE07538', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '25.2', '', 1, '0412-4836802', 'almacen.distlorolara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(6, '2024-02-27', 7, 'cabezal cargador,cable usb,hidrogel', '860820077967768', '860820077967776', '', '51111/63ZW02990', '', '', '', '', '', '', '', 0, '', '', 1, '0412-9564472', 'operaciones1.lara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '110', '', '', '', '', '', NULL, NULL, 1),
(7, '2022-07-01', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '866106064463829', '866106064463837', '', '36552/62T501295', 'NO TIENE', 'NO TIENE', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 5, '28.6', '', 2, '0414-5310239', 'facturacionlorocanalbajo@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(8, '2022-04-28', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '863392068397832', '863392068397840', '', '34490/61ZF74661', 'BUENO', 'BUENO', 'BUENO', 'MICA PARTIDA', 'BUENO', 'BUENO', 'BUENO', 4, '28.7', '', 1, '0412-3500351', 'soportelorolara@gmail.com', '@Loro1234*', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(9, '2022-04-28', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867722060413821', '867722060413839', '', '34489/61ZD06683', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, '24.6', '', 1, '0412-1337020', 'soportelorolara@gmail.com', '@Loro1234*', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(10, '2024-04-12', 11, 'cabezal cargador,cable usb,forro,hidrogel', '358776979864926', '358776979864934', '', '547880', '', '', '', '', '', '', '', 0, '', '', 0, '0412-0491272', 'ventas2.lara@gmail.com', '@Loro1234', '', '', '', '1234', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(11, '0000-00-00', 4, 'cabezal cargador,cable usb,forro,vidrio templado', '350179384467849', '350768254467846', '', 'R58NC4F3PFK', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', '', 6, '42.8', '', 1, '0412-1722181', 'grupoelloro@gmail.com', 'Gruloro*98', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(12, '2021-09-24', 8, 'forro,vidrio templado', '866616055075325', '866616055075333', '', '29227/61SQ14819', 'PARTIDO', 'BUENO', 'BUENO', '', 'NO TIENE', 'NO TIENE', '', 5, '29', '', 1, '0412-1722186', 'soportelorolara@gmail.com', '@Loro1234*', '', '', '', '', '6655704168', '@Loro1234', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(13, '2022-04-28', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '863392067669553', '863392067669561', '', '34490/61ZH70063', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, '30.2', '', 1, '', '', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(14, '2022-07-01', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '863976068690707', '863976068690715', '', '36552/62T501281', 'NO TIENE', 'NO TIENE', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 5, '26.5', '', 2, '0414-3576193', 'facturacionlorocanalmixto@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(15, '2022-07-01', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '866106064482183', '866106064482191', '', '36552/62T500696', 'DAÑADO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 5, '31', '', 2, '0414-5310522', 'facturacionloromy@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(16, '2022-07-01', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '866106065284679', '866106065284661', '', '36552/62T500807', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 5, '31.3', '', 1, '0412-1722187', 'compras.lorolara@gmail.com', 'Lorolara2023.', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(17, '2022-04-28', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '866507068692067', '866507068692075', '', '36548/62VF01324', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'NO USA', 5, '24.9', '', 1, '0412-8258746', 'transporte1.lara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(18, '2022-07-01', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '866106065285726', '866106065285734', '', '36552/62T501264', 'RAYADO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, '31.6', '', 1, '0412-1213336', 'adconfiterialorolara@gmail.com', 'Tesoreria*Lara1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(19, '2023-09-26', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867243060987243', '867243060987250', '', '38619/62YE07562', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '26.3', '', 1, '0412-2798770', 'ventas.distlorolara@gmail.com', '@Loro1234', '', '', '350 994 953', '1234', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(20, '2023-10-16', 9, 'cabezal cargador,cable usb', '353829854838526', '355847184838527', '', 'R92W80F3WVR', '', '', '', '', '', '', '', 0, '', '', 1, '0412-7482977', 'avp105.tachira@gmail.com', 'avp105*Tachira1234', '', '', '', '1234', '', '', 2, '0', '', '', '', '', '', NULL, NULL, 1),
(21, '2022-09-15', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867722060657203', '867722060657211', '', '34489/61ZD18270', 'BUENO', 'BUENO', 'BUENO', 'MICA PARTIDA', 'BUENO', 'BUENO', 'BUENO', 4, '22.8', '', 1, '0412-8258737', 'transporte2.lara@gmail.com', '@Loro1234', '', '', '1 236 448 419', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(22, '0000-00-00', 9, 'cabezal cargador,cable usb', '351390810077776', '355042360077773', '', 'RF8W70HV77X', '', '', '', '', '', '', '', 0, '', '', 1, '0412-3096428', 'avp106.tachira@gmail.com', 'avp106*Tachira1234', '', '', '', '1234', '', '', 2, '0', '', '', '', '', '', NULL, NULL, 1),
(23, '0000-00-00', 9, 'cabezal cargador,cable usb', '351390810081471', '355042360081478', '', 'RF8W70Q6BQL', '', '', '', '', '', '', '', 0, '', '', 1, '0412-1806234', 'avp107.tachira@gmail.com', '@Loro1234', '', '', '', '1234', '', '', 2, '0', '', '', '', '', '', NULL, NULL, 1),
(24, '2022-10-21', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '864087061966398', '864087061966406', '', '36553/62U675591', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'DAÑADO', 5, '21.3', '', 1, '0412-8258715', 'transporte3.lara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(25, '2023-06-26', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867619060983342', '867619060983359', 'N/A', '38619/62U701587', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '61.3GB', '11.86GB', 1, '0412-0412238', 'trademarketing01.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', 'N/A', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,instagram,youtube,tiktok,ubicacion,otra', 'Dinero rápido, Collage de fotos', '<p><span class=\"marker\">Consumo de datos excesivo en punto de acceso m&oacute;vil (7.58 GB). L&iacute;mite mensual excedido a 17 d&iacute;as del mes (11.86GB / 6GB)</span>. No mantiene el tema por defecto. Se eliminaron aplicaciones no permitidas.</p>\r\n', '2024-06-17', '2024-06-17', 1),
(26, '2022-10-21', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '864087062215357', '864087062215365', '', '36553/62U672448', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 5, '29', '', 1, '0412-0539652', 'transporte5.distlorolara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(27, '0000-00-00', 3, 'cabezal cargador,cable usb,estuche', '357153340457171/01', 'N/A', 'N/A', 'AUEUKVA68LKF5T7X ', 'NO TIENE', 'BUENO', 'RAYADO', 'OTRO', 'BUENO', 'BUENO', 'NO USA', 4, '28.8GB', '5.80GB', 1, '0412-1337016', 'av301.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', '1 202 518 708', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto,otra', 'PowerPoint, AnyDesk, Adobe Acrobat', '<p>Consumo excesivo de datos en punto de acceso m&oacute;vil. (3.68GB). L&iacute;mite de datos mensual casi excedido a 21 d&iacute;as del mes (5.80 GB/6 GB). C&aacute;mara frontal no funciona</p>\r\n', '2024-06-14', '2024-06-21', 1),
(28, '0000-00-00', 3, 'cabezal cargador,cable usb,estuche', '357153340459813/01', 'N/A', 'N/A', 'LZ45PBMFQ8VKRWGM', 'PARTIDO', 'BUENO', 'PARTIDO', 'BUENO', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 4, '18.5GB', '3.63GB', 1, '0412-4370472', 'distribuidoraelloromercadeo@gmail.com', '@Loro1234', 'N/A', 'N/A', '416 280 929', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,adn,ubicacion,tema por defecto', '', 'Consumo elevado de datos en punto de acceso móvil. (2.65GB)', '2024-06-14', '2024-06-14', 1),
(29, '2023-03-02', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867619060993689', '867619060993697', '', '38619/62U701597', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '40.5', '', 1, '0412-5844673', 'av301distloro@gmail.com', '@Loro1234', '', '', '', '1234', '6655704168', '@Loro1234', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(30, '2023-07-18', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867243060972682', '867243060972690', 'N/A', '38619/62YE06980', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '47.3GB', '1.34GB', 1, '0412-6279262', 'ventas4.lorolara@gmail.com', '@Loro1234', 'N/A', 'N/A', 'N/A', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto,otra', 'Al cambio, Excel', '<p>Mantiene la configuraci&oacute;n inicial.</p>\r\n', '2024-06-14', '2024-06-21', 1),
(31, '2022-09-15', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '866507068692380', '866507068692398', '', '36548/62VF03922', 'BUENO', 'BUENO', 'BUENO', 'MICA PARTIDA', 'BUENO', 'BUENO', 'BUENO', 5, '26.1', '', 1, '0412-4905703', 'supventas1.lara@gmail.com', '@Loro1234', '', '', '', '1234', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(32, '2022-09-15', 8, 'cabezal cargador,cable usb,forro', '863135060574472', '863135060574464', '', '37566/62TW13816', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'NO USA', 4, '22.2', '', 1, '0412-4905707', 'transporte4.lara@gmail.com', '@Loro1234', '', '', '1 437 993 300', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(33, '2023-06-26', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867619060992046', '867619060992053', '', '38619/62U701616', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '37.5', '', 1, '0412-3500311', 'Cobranza1.lorolara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(34, '2021-06-29', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '8608230515662220', '8608230515662233', '', '34491/61RV18800', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 5, '28.2', '', 1, '0412-0491271', 'supervisoracblorolara@gmail.com; ventas3.lara@gmail.com', '@Loro1234', '', '', '', '1234', '6655704168', '@Loro1234', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(35, '2023-06-27', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867619060993366', '867619060993374', '', '38619/62U701590', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '38.5', '', 1, '0412-1722184', 'Cobranza2.lorolara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(36, '2023-06-28', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867619060993903', '867619060993911', '', '38619/62U701624', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '50.2', '', 1, '0412-0167520', 'Cobranza3lorolara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(37, '2021-12-08', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '862156055876285', '862156055876293', 'N/A', '34491/61WR21660', 'PARTIDO', 'BUENO', 'PARTIDO', 'BUENO', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 5, '24.2GB', '6.71GB', 1, '0412-4377196', 'autoventalorolara@gmail.com', '@Loro1234', 'N/A', 'N/A', 'instalar', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,adn,ubicacion,tema por defecto', '', '<p>Consumo elevado de datos en punto de acceso m&oacute;vil (3.60GB). <span class=\"marker\">L&iacute;mite mensual de datos excedido a 21 d&iacute;as del mes (6.71 GB/6 GB).&nbsp;</span></p>\r\n', '2024-06-14', '2024-06-14', 1),
(38, '2022-04-28', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '863392067644416', '863392067644424', '', '34490/61ZH70100', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, '27', '', 1, '0412-1722180', 'vendedoreslorolara@gmail.com', '@Loro1234', '', '', '1 529 629 332', '1234', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(39, '2023-04-28', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867619060995866', '867619060995874', '', '38619/62U701582', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '33.4', '', 1, '0412-0142383', 'avp314.lara@gmail.com', '@Loro1234', '', '', '1 601 437 575', '1234', '6655704168', '@Loro1234', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(40, '2024-02-27', 7, 'cabezal cargador,cable usb,forro,hidrogel', '860820077973683', '860820077973691', 'N/A', '51111/63ZW00583', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'NO USA', 10, '34.2GB', '3.78GB', 1, '0412-4905706', 'av305.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', 'N/A', '1234', 'N/A', 'N/A', 1, '110', 'N/A', 'N/A', 'whatsapp,adn,ubicacion,tema por defecto', '', 'Consumo de datos elevado en punto de acceso móvil. (2.98GB)', '0000-00-00', '0000-00-00', 1),
(41, '2022-04-28', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '863392067546579', '863392067546587', '', '34490/61ZH70068', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, '29', '', 1, '0412-0696229', 'transporte7.lara@gmail.com', '@Loro1234', '', '', '', '', '6718204713', '@Loro1234', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(42, '2024-04-12', 11, 'cabezal cargador,cable usb,forro,hidrogel', '358776979864041', '358776979864058', '', '738707', '', '', '', '', '', '', '', 0, '', '', 0, '0412-4905708', 'av310.lara@gmail.com', '@Loro1234', '', '', '', '1234', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(43, '2023-03-04', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867512060305337', '867512060305337', 'N/A', '38591/62S400610', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 7, '40.7GB', '813.4MB', 1, '0412-4905714', 'avp319.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', '1 946 582 766', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,adn,ubicacion,tema por defecto', '', 'Mantiene la configuración inicial.', '2024-06-14', '2024-06-14', 1),
(44, '2022-04-28', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '863392068399077', '863392068399085', '', '34490/61ZH70103', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, '22', '', 1, '0412-3031695', 'avp308.lara@gmail.com', '@Loro1234', '', '', '1 688 123 248', '1234', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(45, '2024-02-27', 7, 'cabezal cargador,cable usb,forro,hidrogel', '860820077898468', '860820077898476', 'N/A', '51111/63ZW01340', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'NO USA', 10, '33.5GB', '1.18GB', 1, '0412-1210730', 'av316.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', 'N/A', '1234', 'N/A', 'N/A', 1, '110', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto', '', '<p>La pantalla tiene manchas de tinta.</p>\r\n', '2024-06-21', '2024-06-21', 1),
(46, '2024-04-12', 11, 'cabezal cargador,cable usb,forro,hidrogel', '358776979739987', '358776979739995', 'N/A', '538266', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '30.94GB', '6.44GB', 1, '0412-4905704', 'av303.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', 'N/A', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto', '', '<p>L&iacute;mite de datos mensual excedido a 21 d&iacute;as del mes. (6.44 GB/6 GB)</p>\r\n', '2024-06-21', '2024-06-21', 1),
(47, '2024-01-24', 7, 'cabezal cargador,cable usb,forro', '868369068795947', '868369068795954', 'N/A', '51148/63YT05282', 'NO TIENE', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'NO USA', 7, '36.2GB', '4.58GB', 1, '0412-0315898', 'avp314.dislorlara@gmail.com', '@Loro1234', 'N/A', 'N/A', '1 620 940 677', '1234', 'N/A', 'N/A', 1, '120', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto,otra', 'AnyDesk', '<p>Consumo de datos elevado en punto de acceso m&oacute;vil (2.51GB).</p>\r\n', '2024-06-14', '2024-06-21', 1),
(48, '2023-04-28', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867619060992145', '867619060992152', 'N/A', '38619/62U701605', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 7, '46.6GB', '1.58GB', 1, '0412-4905717', 'avp318.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', '1 161 086 559', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,adn,ubicacion,tema por defecto', '', 'Mantiene la configuración inicial.', '2024-06-14', '2024-06-14', 1),
(49, '2023-03-03', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867512060206303', '867512060206311', 'N/A', '38591/62S400519', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 7, '52.5GB', '9.26GB', 1, '0412-7379068', 'avp321.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', '1 517 073 280', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto', '', '<p><span class=\"marker\">Cuota mensual excedida a 21 d&iacute;as del mes. (9.26 GB/6 GB)</span></p>\r\n', '2024-06-14', '2024-06-21', 1),
(50, '2024-01-24', 7, 'cabezal cargador,cable usb,forro,hidrogel', '868369065978306', '868369065978314', 'N/A', '51148/63YT02211', 'NO TIENE', 'BUENO', 'BUENO', 'BUENO', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 7, '37.5GB', '3.65GB', 1, '0412-0316007', 'avp317.dislorlara@gmail.com', '@Loro1234', 'N/A', 'N/A', '1 566 979 937', '1234', 'N/A', 'N/A', 1, '120', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto,otra', 'XLSX Reader, AnyDesk', '<p>Hidrogel en mal estado y con manchas de tinta.</p>\r\n', '2024-06-14', '2024-06-21', 1),
(51, '2022-07-13', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '863392067673639', '863392067673647', 'N/A', '34490/61ZF73665', 'PARTIDO', 'BUENO', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, '21.5GB', '3.45GB', 1, '0412-4370482', 'av307.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', '767 317 394 ', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,adn,ubicacion,tema por defecto', '', 'Mantiene la configuración inicial.', '2024-06-14', '2024-06-14', 1),
(52, '2021-01-27', 2, 'cabezal cargador,cable usb', '351548111630581', '', '', 'SVN:01001', 'RAYADO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'DAÑADO', 'NO USA', 3, '28.07', '', 1, '0412-3500400', '', '', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(53, '2021-05-13', 5, '', '015320001426746', '', '', 'SVN:04', 'NO TIENE', 'NO TIENE', 'OTRO', 'BUENO', 'NO TIENE', 'NO TIENE', 'NO USA', 1, '14.4', '', 0, 'NINGUNA', 'canalmixtoloro@gmail.com', 'Tesoreria2*Lara1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(54, '0000-00-00', 10, 'vidrio templado', '359060097299466', '359061097299464', '', 'R28M22RF9LD', 'PARTIDO', 'NO TIENE', 'BUENO', 'BUENO', 'NO TIENE', 'NO TIENE', 'NO USA', 2, '7.2', '', 1, '0412-1722179', 'cxploro@gmail.com', 'Actualizar', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(55, '2022-10-21', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '864087062130952', '864087062130960', '', '36553/62U672636', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 5, '22.1', '', 1, '0412-0585753', 'transporte6.lara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(56, '2022-04-28', 8, 'cabezal cargador,cable usb,forro,vidrio templado', '866507068592580', '866507068592598', 'N/A', '36548/62VF02646', 'PARTIDO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'NO USA', 5, '25.9GB', '475.6MB', 1, '0412-4905711', 'av312.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', '372 750 617', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto', '', 'Mantiene la configuración inicial.', '0000-00-00', '0000-00-00', 1),
(57, '2024-01-24', 7, 'cabezal cargador,cable usb,forro', '868369068554245', '868369068554252', 'N/A', '51148/63YT06678', 'NO TIENE', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 10, '36.3GB', '3.16GB', 1, '0412-0315879', 'avp318.dislorlara@gmail.com', '@Loro1234', 'N/A', 'N/A', 'N/A', '1234', '6722384157', 'N/A', 1, '120', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto,otra', 'DólarYa!, CamScanner', '<p>Mantiene la configuraci&oacute;n inicial.</p>\r\n', '2024-06-14', '2024-06-21', 1),
(58, '2023-05-09', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867619060993721', '867619060993739', '', '38619/62U701621', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '37.8', '', 1, '0412-0167270', 'avp320.lara@gmail.com', '@Loro1234', '', '', '1 874 559 336', '1234', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(59, '2022-10-21', 8, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '864087062294295', '864087062294303', '', '36553/62U675141', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 5, '22.5', '', 1, '0412-0696873', 'transporte8.lara@gmail.com', '@Loro1234', '', '', '1 115 052 303', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(60, '2023-03-02', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867619060995601', '867619060995619', 'N/A', '38619/62U701577', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 7, '36.1GB', '3.05GB', 1, '0412-4905715', 'av315.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', '1 100 672 501', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto,otra', 'AnyDesk', '<p>Consumo de datos elevado en punto de acceso m&oacute;vil. (1.59GB) y aplicaciones eliminadas. (722.7MB)</p>\r\n', '2024-06-14', '2024-06-21', 1),
(61, '2024-04-12', 11, 'cabezal cargador,cable usb,forro,hidrogel', '358776979739961', '358776979739979', 'N/A', '558582', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '28.28GB', '5.22GB', 1, '0412-4905709', 'av311.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', 'N/A', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,gmail,adn,tema por defecto,otra', 'Lector PDF', '<p>Consumo de datos elevado (5.22 GB/6 GB) a 21 d&iacute;as del mes.</p>\r\n', '2024-06-14', '2024-06-21', 1),
(62, '2023-04-28', 6, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado', '867619060996088', '867619060996096', 'N/A', '38619/62U701594', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '39.3GB', '2GB', 1, '0412-0142681', 'avp313.lara@gmail.com', '@Loro1234', 'N/A', 'N/A', '1 353 408 757', '1234', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,adn,ubicacion,tema por defecto', '', 'Consumo de datos elevado en punto de acceso móvil. (928.5MB)', '2024-06-17', '2024-06-17', 1),
(63, '2024-05-16', 11, 'cabezal cargador,cable usb,forro', '351534290626681', '351534290626699', '', '11424153CF006209', '', '', '', '', '', '', '', 0, '', '', 0, '', 'avp336.lara@gmail.com', '@Loro1234', '', '', '1 783 307 186', '1234', '', '', 3, '0', '', '', '', '', '', NULL, NULL, 1),
(64, '2024-05-07', 11, 'cabezal cargador,cable usb,forro', '311534290090706', '351534290090714', '', '11424153CF011834', '', '', '', '', '', '', '', 0, '', '', 0, '0412-4905716', 'rrhh.distlorolara@gmail.com', '@Loro1234', '', '', '', '', '', '', 1, '0', '', '', '', '', '', NULL, NULL, 1),
(65, '2024-02-27', 7, 'cabezal cargador,cable usb,forro,hidrogel', '860820077959682', '860820077959690', '', '51111/63ZW02822', '', '', '', '', '', '', '', 0, '', '', 1, '0412-8258737', 'transporte2.lara@gmail.com', '@Loro1234', '', '', '', '1234', '', '', 1, '110', '', '', '', '', '', NULL, NULL, 1),
(201, '2024-06-04', 11, 'cabezal cargador,cable usb,forro,vidrio templado', '351534295271509', '351534295271517', '', '11424153CT011693', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'NO TIENE', 7, '22.33 GB', '', 1, '04121959415', 'promotores.distlorolara@gmail.com', '@Loro1234', 'promotores.lara@grupoelloro.com.ve', 'promotores*Lara1234', '', '1234', '', '', 1, '90', '', '', '', '', '', '2024-06-05', '2024-06-05', 1),
(206, '2024-06-05', 10, 'cabezal cargador', '', '', '', '', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, '', '', 0, '', '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '0000-00-00', '0000-00-00', 0),
(207, NULL, 4, '', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'DAÑADO', 'BUENO', 'BUENO', 'BUENO', 'DAÑADO', 6, 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', '', '', 'Mantiene la configuración inicial.', '2024-06-11', '2024-06-11', 0),
(208, NULL, 3, '', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'DAÑADO', 'BUENO', 1, 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', '', '', 'Mantiene la configuración inicial.', '2024-06-11', '2024-06-11', 0),
(209, '2024-06-08', 7, 'cable usb', NULL, NULL, NULL, NULL, 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '', '', NULL, '2024-06-11', '2024-06-11', 0),
(210, '0000-00-00', 4, 'adaptador', '', '', '', '', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 2, '', '', 0, '', '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '2024-06-11', '2024-06-11', 0),
(211, '0000-00-00', 3, 'vidrio templado', '', '', '', '', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 7, '', '', 0, '', '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '2024-06-11', '2024-06-11', 0),
(212, '0000-00-00', 5, 'forro', '', '', '', '', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 3, '', '', 0, '', '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '', '2024-06-11', '2024-06-11', 0),
(213, '0000-00-00', 8, 'forro,hidrogel', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 3, 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', '', '', 'N/A', '2024-06-11', '2024-06-11', 0),
(214, '0000-00-00', 2, 'adaptador,forro', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 3, 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', '', '', 'Mantiene la configuración inicial.', '2024-06-11', '2024-06-11', 0),
(215, '2024-06-06', 5, 'cabezal cargador,adaptador,cable usb,forro,vidrio templado,hidrogel,estuche', '123', '321', '333', '1211', 'PARTIDO', 'DAÑADO', 'RAYADO', '', 'NO TRAE A REVISIÓN', 'OTRO', 'NO TRAE A REVISIÓN', 7, '50 GB', 'N/A', 1, '12313', 'hols', '1234', 'hola', '1234', '12331231231', '12345', 'hols', '12345', 3, '40', '1b2b', '2b1b', '', '', 'hola esto es una observación', '2024-06-11', '2024-06-11', 0),
(216, '0000-00-00', 3, 'forro', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, 'N/A', '', 0, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', '', '', 'Mantiene la configuración inicial.', '2024-06-11', '2024-06-11', 0),
(217, '0000-00-00', 3, 'adaptador', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 2, 'N/A', '1321 GB', 0, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', '', '', 'Mantiene la configuración inicial.', '2024-06-11', '2024-06-11', 0),
(218, '0000-00-00', 10, 'cable usb', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 4, 'N/A', '', 0, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', '', '', 'Mantiene la configuración inicial.', '2024-06-11', '2024-06-11', 0),
(219, '0000-00-00', 4, 'cable usb', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 3, 'N/A', '', 0, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', 'whatsapp,gmail,adn,facebook,instagram,netflix,youtube,ubicacion', '', 'Mantiene la configuración inicial.', '2024-06-11', '2024-06-11', 0),
(220, '2024-05-30', 6, 'adaptador,forro,hidrogel,estuche', '1', '2', '3', '4', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 5, 'N/A', '435 GB', 2, '7', '8', '9', '10', '11', '12', '13', '14', '15', 2, '16', '5', '6', 'whatsapp,adn,facebook,youtube', '', 'hols', '2024-06-11', '2024-06-11', 0),
(221, '2024-06-24', 2, 'forro', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 2, '213 MB', '145 GB', 2, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 2, 'N/A', 'N/A', 'N/A', 'adn', '', 'holoolol', '2024-06-11', '2024-06-11', 0),
(222, '0000-00-00', 3, 'adaptador', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 3, '213 GB', '66 GB', 1, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', 'adn', '', 'fff', '2024-06-11', '2024-06-11', 0),
(223, '0000-00-00', 4, 'vidrio templado', 'N/A', 'N/A', 'N/A', 'N/A', 'DAÑADO', 'DAÑADO', 'RAYADO', 'MICA PARTIDA', 'OTRO', 'DAÑADO', 'NO TRAE A REVISIÓN', 2, '323GB', '123321MB', 1, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 0, 'N/A', 'N/A', 'N/A', 'instagram', '', '313', '2024-06-11', '2024-06-11', 0),
(224, '0000-00-00', 11, 'forro,vidrio templado', 'N/A', 'N/A', 'N/A', 'N/A', 'BUENO', 'BUENO', 'BUENO', 'BUENO', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 'NO TRAE A REVISIÓN', 7, '28.07GB', '5.02GB', 1, 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 1, 'N/A', 'N/A', 'N/A', 'whatsapp,gmail,adn,ubicacion,tema por defecto', '', 'Límite de datos mensual casi excedido a 21 días del mes. (5.06 GB/ 6 GB).', '2024-06-21', '2024-06-21', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_almacenamiento`
--

CREATE TABLE `tipo_almacenamiento` (
  `id_almacentipo` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_almacenamiento`
--

INSERT INTO `tipo_almacenamiento` (`id_almacentipo`, `nombre`, `activo`) VALUES
(1, 'HDD', 1),
(2, 'SSD', 1),
(3, 'M.2', 1),
(4, 'M.2', 1),
(5, 'hoka', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_equipo`
--

CREATE TABLE `tipo_equipo` (
  `id_tipo_equipo` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `teclado` enum('USB','Incorporado') NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_equipo`
--

INSERT INTO `tipo_equipo` (`id_tipo_equipo`, `nombre`, `teclado`, `activo`) VALUES
(1, 'DESKTOP', 'USB', 1),
(2, 'LAPTOP', 'Incorporado', 1),
(3, 'VR', 'Incorporado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tlf_asignado`
--

CREATE TABLE `tlf_asignado` (
  `id_asignado` int(11) NOT NULL,
  `id_personal` int(11) NOT NULL,
  `id_telefono` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tlf_asignado`
--

INSERT INTO `tlf_asignado` (`id_asignado`, `id_personal`, `id_telefono`, `activo`) VALUES
(1, 1, 1, 1),
(2, 64, 1, 1),
(3, 2, 2, 1),
(4, 3, 3, 1),
(5, 4, 4, 1),
(6, 5, 5, 1),
(7, 6, 6, 1),
(8, 7, 7, 1),
(9, 8, 8, 1),
(10, 9, 9, 1),
(11, 10, 10, 1),
(12, 11, 11, 1),
(13, 12, 12, 1),
(14, 13, 14, 1),
(15, 14, 15, 1),
(16, 15, 16, 1),
(17, 16, 17, 1),
(18, 17, 18, 1),
(19, 18, 19, 1),
(20, 19, 20, 1),
(21, 20, 21, 1),
(22, 21, 22, 1),
(23, 22, 23, 1),
(24, 23, 24, 1),
(25, 24, 25, 1),
(26, 25, 26, 1),
(27, 26, 27, 1),
(28, 27, 28, 1),
(29, 28, 29, 1),
(30, 29, 30, 1),
(31, 31, 32, 1),
(32, 32, 33, 1),
(33, 33, 34, 1),
(34, 34, 35, 1),
(35, 35, 36, 1),
(36, 36, 37, 1),
(37, 37, 38, 1),
(38, 38, 39, 1),
(39, 39, 40, 1),
(40, 40, 41, 1),
(41, 41, 42, 1),
(42, 42, 43, 1),
(43, 43, 44, 1),
(44, 44, 45, 1),
(45, 45, 46, 1),
(46, 46, 47, 0),
(47, 47, 48, 0),
(48, 48, 49, 1),
(49, 49, 50, 1),
(50, 50, 51, 1),
(51, 51, 52, 1),
(52, 65, 52, 1),
(53, 52, 53, 1),
(54, 52, 54, 1),
(55, 53, 55, 1),
(56, 54, 56, 1),
(57, 55, 57, 1),
(58, 56, 58, 1),
(59, 57, 59, 1),
(60, 58, 60, 1),
(61, 59, 61, 1),
(62, 60, 62, 1),
(63, 61, 63, 1),
(64, 62, 64, 1),
(65, 63, 65, 0),
(86, 1, 200, 1),
(87, 75, 201, 1),
(88, 3, 215, 0),
(91, 63, 47, 1),
(92, 82, 48, 0),
(93, 82, 48, 1),
(94, 83, 224, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tlf_sisver`
--

CREATE TABLE `tlf_sisver` (
  `id_sisver` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tlf_sisver`
--

INSERT INTO `tlf_sisver` (`id_sisver`, `nombre`, `activo`) VALUES
(1, 'Android 7', 1),
(2, 'Android 8.1', 1),
(3, 'Android 9', 1),
(4, 'Android 10', 1),
(5, 'Android 11', 1),
(6, 'Android 12', 1),
(7, 'Android 13', 1),
(10, 'Android 14', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `user` varchar(30) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `clave` varchar(30) NOT NULL,
  `permisos` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `user`, `nombre`, `clave`, `permisos`, `activo`) VALUES
(1, 'soporte', 'David Sira', '1234', 1, 1),
(2, 'prueba', '', '1234', 0, 1),
(3, 'presidente loro', 'leo messi', '321', 1, 1),
(4, 'presidente loro', 'cr7', '321', 1, 0),
(5, 'jcorredor', 'José Corredor', '2303', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `cargo_ruta`
--
ALTER TABLE `cargo_ruta`
  ADD PRIMARY KEY (`id_cargoruta`);

--
-- Indices de la tabla `computadoras`
--
ALTER TABLE `computadoras`
  ADD PRIMARY KEY (`id_pc`),
  ADD KEY `id_tipo` (`id_fabricante`,`id_almacentipo`,`id_red`,`id_pcso`,`id_personal`,`id_sisadmin`,`id_sucursal`),
  ADD KEY `id_tipo_equipo` (`id_tipo_equipo`);

--
-- Indices de la tabla `fabricante`
--
ALTER TABLE `fabricante`
  ADD PRIMARY KEY (`id_fabricante`);

--
-- Indices de la tabla `impresoras`
--
ALTER TABLE `impresoras`
  ADD PRIMARY KEY (`id_impresora`),
  ADD KEY `id_area` (`id_area`),
  ADD KEY `id_fabricante` (`id_fabricante`);

--
-- Indices de la tabla `modelo_marca`
--
ALTER TABLE `modelo_marca`
  ADD PRIMARY KEY (`id_modelo`),
  ADD KEY `id_marca` (`id_fabricante`);

--
-- Indices de la tabla `operadora`
--
ALTER TABLE `operadora`
  ADD PRIMARY KEY (`id_operadora`);

--
-- Indices de la tabla `pc_sis_op`
--
ALTER TABLE `pc_sis_op`
  ADD PRIMARY KEY (`id_pcso`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id_personal`),
  ADD KEY `id_cargoruta` (`id_cargoruta`),
  ADD KEY `id_area` (`id_area`);

--
-- Indices de la tabla `red_lan`
--
ALTER TABLE `red_lan`
  ADD PRIMARY KEY (`id_red`);

--
-- Indices de la tabla `registro_mantenimiento`
--
ALTER TABLE `registro_mantenimiento`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `id_pc` (`id_pc`);

--
-- Indices de la tabla `sistema_admin`
--
ALTER TABLE `sistema_admin`
  ADD PRIMARY KEY (`id_sisadmin`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`id_sucursal`);

--
-- Indices de la tabla `telefonos`
--
ALTER TABLE `telefonos`
  ADD PRIMARY KEY (`id_telefono`),
  ADD KEY `id_modelo` (`id_modelo`,`id_sisver`,`id_operadora`,`id_sucursal`);

--
-- Indices de la tabla `tipo_almacenamiento`
--
ALTER TABLE `tipo_almacenamiento`
  ADD PRIMARY KEY (`id_almacentipo`);

--
-- Indices de la tabla `tipo_equipo`
--
ALTER TABLE `tipo_equipo`
  ADD PRIMARY KEY (`id_tipo_equipo`);

--
-- Indices de la tabla `tlf_asignado`
--
ALTER TABLE `tlf_asignado`
  ADD PRIMARY KEY (`id_asignado`),
  ADD KEY `id_personal` (`id_personal`),
  ADD KEY `id_telefono` (`id_telefono`);

--
-- Indices de la tabla `tlf_sisver`
--
ALTER TABLE `tlf_sisver`
  ADD PRIMARY KEY (`id_sisver`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `id_area` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `cargo_ruta`
--
ALTER TABLE `cargo_ruta`
  MODIFY `id_cargoruta` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `computadoras`
--
ALTER TABLE `computadoras`
  MODIFY `id_pc` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `fabricante`
--
ALTER TABLE `fabricante`
  MODIFY `id_fabricante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `impresoras`
--
ALTER TABLE `impresoras`
  MODIFY `id_impresora` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `modelo_marca`
--
ALTER TABLE `modelo_marca`
  MODIFY `id_modelo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `operadora`
--
ALTER TABLE `operadora`
  MODIFY `id_operadora` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pc_sis_op`
--
ALTER TABLE `pc_sis_op`
  MODIFY `id_pcso` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de la tabla `red_lan`
--
ALTER TABLE `red_lan`
  MODIFY `id_red` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `registro_mantenimiento`
--
ALTER TABLE `registro_mantenimiento`
  MODIFY `id_mantenimiento` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `sistema_admin`
--
ALTER TABLE `sistema_admin`
  MODIFY `id_sisadmin` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `id_sucursal` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `telefonos`
--
ALTER TABLE `telefonos`
  MODIFY `id_telefono` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT de la tabla `tipo_almacenamiento`
--
ALTER TABLE `tipo_almacenamiento`
  MODIFY `id_almacentipo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_equipo`
--
ALTER TABLE `tipo_equipo`
  MODIFY `id_tipo_equipo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tlf_asignado`
--
ALTER TABLE `tlf_asignado`
  MODIFY `id_asignado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `tlf_sisver`
--
ALTER TABLE `tlf_sisver`
  MODIFY `id_sisver` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
