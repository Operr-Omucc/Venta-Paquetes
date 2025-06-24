-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-06-2025 a las 21:01:02
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
(3, 'Pendiente', 3200.00, 9),
(4, 'Pendiente', 17200.00, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_item`
--

CREATE TABLE `carrito_item` (
  `ID_Item` int(11) NOT NULL,
  `ID_Carrito` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL DEFAULT 1,
  `Subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito_item`
--

INSERT INTO `carrito_item` (`ID_Item`, `ID_Carrito`, `ID_Producto`, `Cantidad`, `Subtotal`) VALUES
(34, 4, 2, 1, 3200.00),
(35, 4, 1, 2, 3000.00),
(36, 4, 3, 5, 11000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `ID_Cliente` int(11) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Historial_Compras` text NOT NULL,
  `Foto_Perfil` varchar(255) DEFAULT NULL,
  `Rol` enum('cliente','admin','jefe') DEFAULT 'cliente',
  `Activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`ID_Cliente`, `Nombre`, `Email`, `Contraseña`, `Historial_Compras`, `Foto_Perfil`, `Rol`, `Activo`) VALUES
(1, 'Carlos Pérez', 'carlos.perez@example.com', 'clave123', '[]', NULL, 'cliente', 1),
(2, 'María López', 'maria.lopez@example.com', 'mypass2024', '[]', NULL, 'cliente', 1),
(3, 'José Ramírez', 'jose.ramirez@example.com', 'contraseña1', '[]', NULL, 'cliente', 1),
(4, 'Lucía Fernández', 'lucia.fernandez@example.com', 'lucia2025', '[]', NULL, 'cliente', 1),
(5, 'Andrés Gómez', 'andres.gomez@example.com', 'andrespass', '[]', NULL, 'cliente', 1),
(6, 'Laura Morales', 'laura.morales@example.com', 'laura2023', '[]', NULL, 'cliente', 1),
(7, 'Juan Castillo', 'juan.castillo@example.com', 'juanc123', '[]', NULL, 'cliente', 1),
(8, 'Ana Torres', 'ana.torres@example.com', 'ana_secure', '[]', NULL, 'cliente', 0),
(9, 'Pedro Díaz', 'pedro.diaz@example.com', 'p3dr0diaz', '[]', NULL, 'cliente', 1),
(10, 'Sofía Herrera', 'sofia.herrera@example.com', 'sofiaH!2025', '[]', NULL, 'cliente', 1),
(13, 'pepe', 'pepe@gmail.com', '$2y$10$P6yA2F5H6/zG.clvN2ZUK.QL0EM0UD5VCyEp7OO7tHUQ9WYiB6AE2', '', NULL, 'cliente', 1),
(14, 'Manolo', 'manolo@gmail.com', '$2y$10$uGpa6Fh8xbPks7LVaM0heej9.t2zTWhjJauhc7v5eMYNuUZa.CzxS', '', 'uploads/perfil_14_1750188507.jpg', 'jefe', 1),
(15, 'oa', 'oa@gmail.com', '$2y$10$ExoYWDSS.Z.dTcefVGj6j.VojX8zZB34midaR8K3yE9at5ZRm5xE6', '', NULL, 'cliente', 1);

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
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `ID_Pago` int(11) NOT NULL,
  `ID_Cliente` int(11) DEFAULT NULL,
  `Monto` decimal(10,2) DEFAULT NULL,
  `Fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `ID_Pedido` int(11) NOT NULL,
  `Fecha_Creacion` date NOT NULL,
  `Estado` enum('Pendiente','Cancelado','Entregado','') NOT NULL,
  `ID_Cliente` int(11) NOT NULL,
  `Motivo` varchar(255) DEFAULT NULL,
  `Total_Pagar` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`ID_Pedido`, `Fecha_Creacion`, `Estado`, `ID_Cliente`, `Motivo`, `Total_Pagar`) VALUES
(1, '2025-06-01', 'Entregado', 1, NULL, 0.00),
(2, '2025-06-03', '', 2, NULL, 0.00),
(3, '2025-06-05', 'Entregado', 3, NULL, 0.00),
(4, '2025-06-06', 'Entregado', 4, NULL, 0.00),
(5, '2025-06-07', '', 5, 'gay', 0.00),
(6, '2025-06-07', 'Cancelado', 6, NULL, 0.00),
(7, '2025-06-08', 'Entregado', 7, NULL, 0.00),
(8, '2025-06-09', 'Entregado', 8, NULL, 0.00),
(9, '2025-06-10', 'Entregado', 9, NULL, 0.00),
(10, '2025-06-10', 'Entregado', 10, NULL, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `ID_Producto` int(11) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `Precio_Unitario` decimal(10,2) NOT NULL,
  `Imagen_URL` varchar(255) DEFAULT NULL,
  `Pais` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`ID_Producto`, `Nombre`, `Descripcion`, `Precio_Unitario`, `Imagen_URL`, `Pais`) VALUES
(1, 'Paquete Caribe Todo Incluido', 'Vuelo + Hotel 5 estrellas + Auto por 3 días', 1500.00, 'https://th.bing.com/th/id/OIP.FCJoj0mBsiPMxbxzaDwiYAHaED?r=0&rs=1&pid=ImgDetMain', 'Bahamas'),
(2, 'Tour por Europa Clásica', 'Vuelos entre ciudades + Hoteles 4 estrellas + Traslados', 3200.00, 'https://th.bing.com/th/id/OIP.pmNTHiKJftg_8uGeAunuAAHaEb?r=0&rs=1&pid=ImgDetMain', 'Alemania, Reino Unido, España, Italia, Francia'),
(3, 'Escapada a la Patagonia', 'Vuelo ida y vuelta + Hotel boutique + 4x4 por 5 días', 2200.00, 'https://th.bing.com/th/id/OIP.hYP0qVIloa4FpOb_LUk6jAAAAA?r=0&rs=1&pid=ImgDetMain', 'Argentina'),
(4, 'Crucero Caribeño', 'Vuelo a puerto + Crucero 7 noches + Excursiones', 2800.00, 'https://th.bing.com/th/id/OIP.kA-56uMkpnSya9hRXQSabgHaFj?r=0&rs=1&pid=ImgDetMain', 'Bahamas'),
(5, 'Aventura en Perú', 'Vuelo + Hotel + Excursiones guiadas por Cusco y Machu Picchu', 1900.00, 'https://th.bing.com/th/id/OIP.QV6WXcT1Cn92qxFkoKHJYAHaE8?r=0&rs=1&pid=ImgDetMain', 'Peru'),
(6, 'Aventura en Argentina', 'Disfruta de 7 días en la Patagonia con excursiones y gastronomía local.', 950.00, 'https://th.bing.com/th/id/OIP.Fi3S1cckPLV2aTUJrjuJ7QHaE8?r=0&rs=1&pid=ImgDetMain', 'Argentina'),
(7, 'Italia Romántica', 'Explora Roma, Florencia y Venecia en un viaje inolvidable de 10 días.', 1450.00, 'https://th.bing.com/th/id/R.dfb6793bb76f255851ae6750c6b0c3a4?rik=d4XW9pBgFJfHVQ&pid=ImgRaw&r=0', 'Italia'),
(8, 'Tour por Japón', 'Viaja 12 días por Tokio, Kioto y Osaka con guía y comidas incluidas.', 2250.00, 'https://th.bing.com/th/id/OIP.bNiufGAQsWmoVJ068ne_NwHaDf?r=0&rs=1&pid=ImgDetMain', 'Japon'),
(9, 'Paraíso en Maldivas', '8 días en resort 5 estrellas frente al mar con actividades acuáticas.', 2800.00, 'https://th.bing.com/th/id/OIP.Jje_CDaDGuCmp4QeXm9YmAHaE8?r=0&rs=1&pid=ImgDetMain', 'Maldivas'),
(10, 'Ruta por el Caribe', 'Crucero por el Caribe visitando Bahamas, Jamaica y República Dominicana.', 1900.00, 'https://th.bing.com/th/id/OIP.-5AI1YDsyNOoT7t-4z_mRQHaEb?r=0&rs=1&pid=ImgDetMain', 'Bahamas'),
(11, 'Aventura Chilena', 'Todo incluido en esta fantastica aventura por todo chile', 3000.00, 'images/paquetes/chileeee.jpg', 'Chile');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `ID_Venta` int(11) NOT NULL,
  `ID_Cliente` int(11) NOT NULL,
  `Fecha` datetime DEFAULT current_timestamp(),
  `Total` decimal(10,2) NOT NULL,
  `Estado` enum('Confirmada','Cancelada') DEFAULT 'Confirmada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`ID_Venta`, `ID_Cliente`, `Fecha`, `Total`, `Estado`) VALUES
(1, 14, '2025-06-17 16:15:32', 19400.00, 'Confirmada'),
(2, 14, '2025-06-17 16:30:51', 3000.00, 'Confirmada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_item`
--

CREATE TABLE `venta_item` (
  `ID_Item` int(11) NOT NULL,
  `ID_Venta` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Precio_Unitario` decimal(10,2) NOT NULL,
  `Subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta_item`
--

INSERT INTO `venta_item` (`ID_Item`, `ID_Venta`, `ID_Producto`, `Cantidad`, `Precio_Unitario`, `Subtotal`) VALUES
(1, 1, 2, 1, 3200.00, 3200.00),
(2, 1, 1, 2, 1500.00, 3000.00),
(3, 1, 3, 6, 2200.00, 13200.00),
(4, 2, 1, 2, 1500.00, 3000.00);

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
-- Indices de la tabla `carrito_item`
--
ALTER TABLE `carrito_item`
  ADD PRIMARY KEY (`ID_Item`),
  ADD KEY `ID_Carrito` (`ID_Carrito`),
  ADD KEY `ID_Producto` (`ID_Producto`);

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
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`ID_Pago`),
  ADD KEY `ID_Cliente` (`ID_Cliente`);

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
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`ID_Venta`),
  ADD KEY `ID_Cliente` (`ID_Cliente`);

--
-- Indices de la tabla `venta_item`
--
ALTER TABLE `venta_item`
  ADD PRIMARY KEY (`ID_Item`),
  ADD KEY `ID_Venta` (`ID_Venta`),
  ADD KEY `ID_Producto` (`ID_Producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `ID_Carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `carrito_item`
--
ALTER TABLE `carrito_item`
  MODIFY `ID_Item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `ID_Cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `ID_Detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `ID_Pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `ID_Pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `ID_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `ID_Venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `venta_item`
--
ALTER TABLE `venta_item`
  MODIFY `ID_Item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `fk_carrito_cliente` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `carrito_item`
--
ALTER TABLE `carrito_item`
  ADD CONSTRAINT `carrito_item_ibfk_1` FOREIGN KEY (`ID_Carrito`) REFERENCES `carrito` (`ID_Carrito`),
  ADD CONSTRAINT `carrito_item_ibfk_2` FOREIGN KEY (`ID_Producto`) REFERENCES `producto` (`ID_Producto`);

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
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_cliente_pedido` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`ID_Cliente`) REFERENCES `cliente` (`ID_Cliente`);

--
-- Filtros para la tabla `venta_item`
--
ALTER TABLE `venta_item`
  ADD CONSTRAINT `venta_item_ibfk_1` FOREIGN KEY (`ID_Venta`) REFERENCES `venta` (`ID_Venta`),
  ADD CONSTRAINT `venta_item_ibfk_2` FOREIGN KEY (`ID_Producto`) REFERENCES `producto` (`ID_Producto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
