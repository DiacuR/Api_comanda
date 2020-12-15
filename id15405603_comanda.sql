-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 15-12-2020 a las 22:12:00
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `id15405603_comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `email`, `usuario`, `clave`) VALUES
(1, 'jose@mail.com', 'jose', '$2y$10$yY5UJrhb856eWKzsREcnYOkGw80ESDdNz2e5Tai/djN29khFUwluS'),
(2, 'deme@mail.com', 'demetrio', '$2y$10$LtY4MR5CLoh/RE1qXdvFUeWsHISmNJyZExpydl9VaN8RlABBFxGUW'),
(3, 'nelida@mail.com', 'neli', '$2y$10$NZ.y8EhVV7Azc9hax9nPmuWNJ8PdQSx.Q8WMZxLyPcp/bRZyQ6h.e');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `id` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `total` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`id`, `idPedido`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 830, '2020-12-15 20:21:24', '2020-12-15 20:21:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criticas`
--

CREATE TABLE `criticas` (
  `id` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `restaurante` int(2) NOT NULL,
  `mozo` int(2) NOT NULL,
  `mesa` int(2) NOT NULL,
  `cocinero` int(2) NOT NULL,
  `experiencia` varchar(66) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `criticas`
--

INSERT INTO `criticas` (`id`, `idPedido`, `restaurante`, `mozo`, `mesa`, `cocinero`, `experiencia`) VALUES
(1, 1, 8, 8, 6, 9, 'Hola a Todos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(70) NOT NULL,
  `tipo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `email`, `usuario`, `clave`, `tipo`) VALUES
(1, 'iaq@mail.com', 'Diacu', '$2y$10$vbcw0LWP/j7c9ApTO1Es1.YXX6QC3QyMoEZyLPSz111q3rvO982MS', 'socio'),
(2, 'fran@mail.com', 'Franco', '$2y$10$VNEe6erBv7Q5/I2QVe53Be8Qg8tEaVcTX7QFfrrASFAvm2yQuaYEC', 'cocinero'),
(3, 'mati@mail.com', 'Matias', '$2y$10$gXJ07n2gEsJXp6tYoy27qecNb5tzHmImwVysD5ECBjQgrdmj9jLEG', 'cervecero'),
(4, 'marce@mail.com', 'Marcelo', '$2y$10$Ji4lVpR98GQHnj2tkyvaDOcAYMr036UVa64yEUysBl5lw4SFcfri.', 'bartender'),
(5, 'juli@mail.com', 'Julia', '$2y$10$As9ugoNu27BARHja.VN4i..u7bhz/wea/ii5cwbUMx9/Ug.pv5LMC', 'mozo'),
(6, 'esteban@mail.com', 'Esteban', '$2y$10$TZ77rqtCGQdjbZoD.Nf5nOKjuJOpFTXGy2ctgVIFuZYOBHp1/T94a', 'mozo'),
(7, 'dani@mail.com', 'dani', '$2y$10$3Ra1NevL1LWy1cFDQuo4rOiM9dkyAEwMeM1sj74uNFgcLZ3v59p.i', 'bartender'),
(8, 'cami@mail.com', 'camila', '$2y$10$L0iCmyLYSuqYtG8SDuHy0u9JjGJz09zWNoQjRDZDOORoFgt8UtZyC', 'bartender'),
(9, 'diacu@mail.com', 'iaq', '$2y$10$nXkYPb/zoCWQeHkhGm9mn.pO/i/OWhQgNbXnHtieDyKtd2AGM1O3G', 'cocinero'),
(10, 'seba@mail.com', 'Sebas', '$2y$10$48IHkhPzw3yD31ErgOtwg.syOlrRGLNtjo9EHvaNVKdFEDyCLvU0O', 'socio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_mesa`
--

CREATE TABLE `estado_mesa` (
  `id` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estado_mesa`
--

INSERT INTO `estado_mesa` (`id`, `estado`) VALUES
(1, 'con cliente esperando pedido'),
(2, 'con clientes comiendo'),
(3, 'con clientes esperando la cuenta'),
(4, 'con clientes pagando'),
(5, 'con clientes retirandose'),
(6, 'cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `pidieronCuenta` varchar(2) NOT NULL DEFAULT '--',
  `idEstado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo`, `pidieronCuenta`, `idEstado`) VALUES
(1, '154TR', 'No', 1),
(2, '577AS', 'No', 1),
(3, '786HF', 'No', 1),
(4, '797CB', 'No', 1),
(5, '345DX', 'No', 1),
(6, '719MZ', 'No', 1),
(7, '985PO', '--', 6),
(8, '496LA', '--', 6),
(9, '275UP', '--', 6),
(10, '389YU', '--', 6),
(11, '602TU', '--', 6),
(12, '337LI', '--', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_mesero` int(11) NOT NULL,
  `codigo_mesa` varchar(5) NOT NULL,
  `codigo_pedido` varchar(5) NOT NULL,
  `items` varchar(100) NOT NULL,
  `precioTotal` float DEFAULT 0,
  `tiempo` time DEFAULT NULL,
  `estado` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_cliente`, `id_mesero`, `codigo_mesa`, `codigo_pedido`, `items`, `precioTotal`, `tiempo`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 5, '154TR', 'DDK87', 'tacos;lager;', 830, '00:00:25', 'Cobrado', '2020-12-15 20:05:32', '2020-12-15 20:21:24'),
(2, 1, 5, '577AS', 'PJM16', 'ravioles;vino blanco;', 0, NULL, 'Pendiente', '2020-12-15 20:08:46', '2020-12-15 20:08:46'),
(3, 2, 5, '786HF', 'KBU89', 'tacos;roja;', 0, NULL, 'Pendiente', '2020-12-15 20:10:06', '2020-12-15 20:10:06'),
(4, 2, 5, '797CB', 'RHX58', 'hamburguesa;lager;', 0, NULL, 'Pendiente', '2020-12-15 20:10:56', '2020-12-15 20:10:56'),
(5, 3, 5, '345DX', 'AFG77', 'hamburguesa;tiramisu;', 0, NULL, 'Pendiente', '2020-12-15 20:12:30', '2020-12-15 20:12:30'),
(6, 3, 5, '154TR', 'CGM40', 'flan;tiramisu;', 0, NULL, 'Pendiente', '2020-12-15 21:00:01', '2020-12-15 21:00:01'),
(7, 3, 5, '719MZ', 'WON41', 'flan;rosado;', 0, NULL, 'Pendiente', '2020-12-15 21:11:28', '2020-12-15 21:11:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pendientes`
--

CREATE TABLE `pendientes` (
  `id` int(11) NOT NULL,
  `codigoPedido` varchar(5) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `tiempo` varchar(10) DEFAULT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pendientes`
--

INSERT INTO `pendientes` (`id`, `codigoPedido`, `idProducto`, `idEmpleado`, `tiempo`, `estado`) VALUES
(3, 'PJM16', 2, 2, NULL, 'Pendiente'),
(4, 'KBU89', 11, 3, NULL, 'Pendiente'),
(5, 'KBU89', 11, 3, NULL, 'Pendiente'),
(6, 'RHX58', 4, 2, NULL, 'Pendiente'),
(7, 'RHX58', 13, 3, NULL, 'Pendiente'),
(8, 'RHX58', 13, 3, NULL, 'Pendiente'),
(9, 'AFG77', 4, 2, NULL, 'Pendiente'),
(10, 'CGM40', 14, 2, NULL, 'Pendiente'),
(11, 'CGM40', 15, 2, NULL, 'Pendiente'),
(12, 'WON41', 14, 2, NULL, 'Pendiente'),
(13, 'WON41', 9, 4, NULL, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `item` varchar(30) NOT NULL,
  `precio` float NOT NULL,
  `cantVendida` int(3) NOT NULL DEFAULT 0,
  `idSector` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `item`, `precio`, `cantVendida`, `idSector`) VALUES
(1, 'milanesa', 300, 0, 3),
(2, 'ravioles', 250, 2, 3),
(3, 'tacos', 550, 1, 3),
(4, 'hamburguesa', 850, 0, 3),
(5, 'arepa', 400, 6, 3),
(6, 'vino tinto', 500, 0, 1),
(7, 'vino blanco', 500, 0, 1),
(8, 'malbec', 720, 0, 1),
(9, 'rosado', 680, 0, 1),
(10, 'rubia', 300, 0, 2),
(11, 'roja', 320, 0, 2),
(12, 'negra', 450, 0, 2),
(13, 'lager', 280, 1, 2),
(14, 'flan', 250, 0, 4),
(15, 'tiramisu', 300, 0, 4),
(16, 'chocotorta', 300, 0, 4),
(17, 'helado', 500, 0, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sectores`
--

CREATE TABLE `sectores` (
  `id` int(11) NOT NULL,
  `sector` varchar(30) NOT NULL,
  `tipoEmpleado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `sectores`
--

INSERT INTO `sectores` (`id`, `sector`, `tipoEmpleado`) VALUES
(1, 'Barra de tragos y vinos', 'bartender'),
(2, 'Barra chopera', 'cervecero'),
(3, 'Cocina', 'cocinero'),
(4, 'Candy bar', 'cocinero'),
(5, 'Salon', 'mozo'),
(6, 'Administrador', 'socio');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `criticas`
--
ALTER TABLE `criticas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_mesa`
--
ALTER TABLE `estado_mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sectores`
--
ALTER TABLE `sectores`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `criticas`
--
ALTER TABLE `criticas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estado_mesa`
--
ALTER TABLE `estado_mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
