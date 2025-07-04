-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2025 a las 21:56:39
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
-- Base de datos: `paqueviaje`
--
CREATE DATABASE IF NOT EXISTS `paqueviaje` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `paqueviaje`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `ID_Carrito` int(11) NOT NULL,
  `Estado` enum('Pendiente','Confirmado','Entregado','') NOT NULL,
  `Total_Pagar` decimal(10,2) NOT NULL,
  `ID_Cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`ID_Carrito`, `Estado`, `Total_Pagar`, `ID_Cliente`) VALUES
(1, 'Pendiente', 1500.00, 2),
(2, 'Pendiente', 2200.00, 5),
(3, 'Pendiente', 3200.00, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `ID_Cliente` int(11) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `Contraseña` varchar(45) NOT NULL,
  `Historial_Compras` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`ID_Cliente`, `Nombre`, `Email`, `Contraseña`, `Historial_Compras`) VALUES
(1, 'Carlos Pérez', 'carlos.perez@example.com', 'clave123', '[]'),
(2, 'María López', 'maria.lopez@example.com', 'mypass2024', '[]'),
(3, 'José Ramírez', 'jose.ramirez@example.com', 'contraseña1', '[]'),
(4, 'Lucía Fernández', 'lucia.fernandez@example.com', 'lucia2025', '[]'),
(5, 'Andrés Gómez', 'andres.gomez@example.com', 'andrespass', '[]'),
(6, 'Laura Morales', 'laura.morales@example.com', 'laura2023', '[]'),
(7, 'Juan Castillo', 'juan.castillo@example.com', 'juanc123', '[]'),
(8, 'Ana Torres', 'ana.torres@example.com', 'ana_secure', '[]'),
(9, 'Pedro Díaz', 'pedro.diaz@example.com', 'p3dr0diaz', '[]'),
(10, 'Sofía Herrera', 'sofia.herrera@example.com', 'sofiaH!2025', '[]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `ID_Detalle` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Subtotal` decimal(10,2) NOT NULL,
  `ID_Pedido` int(11) NOT NULL,
  `ID_Cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`ID_Detalle`, `Cantidad`, `Subtotal`, `ID_Pedido`, `ID_Cliente`) VALUES
(1, 1, 1500.00, 1, 1),
(2, 1, 3200.00, 2, 2),
(3, 1, 2200.00, 3, 3),
(4, 1, 2800.00, 4, 4),
(5, 1, 1900.00, 5, 5),
(6, 1, 1500.00, 6, 6),
(7, 2, 3000.00, 7, 7),
(8, 1, 2800.00, 8, 8),
(9, 1, 3200.00, 9, 9),
(10, 1, 1900.00, 10, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_pedido`
--

CREATE TABLE `historial_pedido` (
  `Fecha_Entrega` date NOT NULL,
  `Total_Pagado` decimal(10,2) NOT NULL,
  `ID_Pedido` int(11) NOT NULL,
  `ID_Cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_pedido`
--

INSERT INTO `historial_pedido` (`Fecha_Entrega`, `Total_Pagado`, `ID_Pedido`, `ID_Cliente`) VALUES
('2025-06-05', 1500.00, 1, 1),
('2025-06-06', 2200.00, 3, 3),
('2025-06-07', 2800.00, 4, 4),
('2025-06-08', 3000.00, 7, 7),
('2025-06-08', 2800.00, 8, 8),
('2025-06-11', 1900.00, 10, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `ID_Notificacion` int(11) NOT NULL,
  `Email_Destinatario` varchar(50) NOT NULL,
  `ID_Pedido` int(11) NOT NULL,
  `ID_Cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificacion`
--

INSERT INTO `notificacion` (`ID_Notificacion`, `Email_Destinatario`, `ID_Pedido`, `ID_Cliente`) VALUES
(1, 'carlos.perez@example.com', 1, 1),
(2, 'maria.lopez@example.com', 2, 2),
(3, 'jose.ramirez@example.com', 3, 3),
(4, 'lucia.fernandez@example.com', 4, 4),
(5, 'andres.gomez@example.com', 5, 5),
(6, 'laura.morales@example.com', 6, 6),
(7, 'juan.castillo@example.com', 7, 7),
(8, 'ana.torres@example.com', 8, 8),
(9, 'pedro.diaz@example.com', 9, 9),
(10, 'sofia.herrera@example.com', 10, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `ID_Pedido` int(11) NOT NULL,
  `Fecha_Creacion` date NOT NULL,
  `Estado` enum('Pendiente','Cancelado','Entregado','') NOT NULL,
  `ID_Cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`ID_Pedido`, `Fecha_Creacion`, `Estado`, `ID_Cliente`) VALUES
(1, '2025-06-01', 'Entregado', 1),
(2, '2025-06-03', 'Pendiente', 2),
(3, '2025-06-05', 'Entregado', 3),
(4, '2025-06-06', 'Entregado', 4),
(5, '2025-06-07', 'Pendiente', 5),
(6, '2025-06-07', 'Cancelado', 6),
(7, '2025-06-08', 'Entregado', 7),
(8, '2025-06-09', 'Entregado', 8),
(9, '2025-06-10', 'Pendiente', 9),
(10, '2025-06-10', 'Entregado', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `ID_Producto` int(11) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `Precio_Unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`ID_Producto`, `Nombre`, `Descripcion`, `Precio_Unitario`) VALUES
(1, 'Paquete Caribe Todo Incluido', 'Vuelo + Hotel 5 estrellas + Auto por 3 días', 1500.00),
(2, 'Tour por Europa Clásica', 'Vuelos entre ciudades + Hoteles 4 estrellas + Traslados', 3200.00),
(3, 'Escapada a la Patagonia', 'Vuelo ida y vuelta + Hotel boutique + 4x4 por 5 días', 2200.00),
(4, 'Crucero Caribeño', 'Vuelo a puerto + Crucero 7 noches + Excursiones', 2800.00),
(5, 'Aventura en Perú', 'Vuelo + Hotel + Excursiones guiadas por Cusco y Machu Picchu', 1900.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`ID_Carrito`),
  ADD KEY `fk_carrito_cliente` (`ID_Cliente`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`ID_Cliente`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`ID_Detalle`),
  ADD KEY `fk_detalle_pedido` (`ID_Pedido`),
  ADD KEY `fk_detalle_cliente` (`ID_Cliente`);

--
-- Indices de la tabla `historial_pedido`
--
ALTER TABLE `historial_pedido`
  ADD KEY `fk_pedido` (`ID_Pedido`),
  ADD KEY `fk_cliente` (`ID_Cliente`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD KEY `fk_notificacion_pedido` (`ID_Pedido`),
  ADD KEY `fk_notificacion_cliente` (`ID_Cliente`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`ID_Pedido`),
  ADD KEY `fk_cliente_pedido` (`ID_Cliente`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`ID_Producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `ID_Carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `ID_Cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `ID_Detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `ID_Pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `ID_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `fk_carrito_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `fk_detalle_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detalle_pedido` FOREIGN KEY (`ID_Pedido`) REFERENCES `pedido` (`ID_Pedido`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial_pedido`
--
ALTER TABLE `historial_pedido`
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`),
  ADD CONSTRAINT `fk_pedido` FOREIGN KEY (`ID_Pedido`) REFERENCES `pedido` (`ID_Pedido`);

--
-- Filtros para la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD CONSTRAINT `fk_notificacion_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`),
  ADD CONSTRAINT `fk_notificacion_pedido` FOREIGN KEY (`ID_Pedido`) REFERENCES `pedido` (`ID_Pedido`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_cliente_pedido` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
