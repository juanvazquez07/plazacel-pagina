-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2020 a las 19:25:31
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sitio_plazacell`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Categoria 1'),
(2, 'Categoria 2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventas`
--

CREATE TABLE `detalleventas` (
  `id` int(11) NOT NULL,
  `id_prod` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,0) NOT NULL,
  `subtotal` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8 NOT NULL,
  `descripcion` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `precio` decimal(10,0) NOT NULL,
  `imagen` varchar(100) CHARACTER SET utf8 NOT NULL,
  `stock` int(10) NOT NULL,
  `categoria_id` int(10) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `stock`, `categoria_id`, `usuario_id`) VALUES
(1, 'Funda / Case Shield para iPhone 11', 'description', '399', 'https://pisces.bbystatic.com/image2/BestBuy_MX/images/products/1000/1000222876cv14a.jpg', 10, 1, 1),
(2, 'Funda Crystal Palace para Samsung Galaxy S20', 'Funda Crystal Palace para Samsung Galaxy S20', '699', 'https://pisces.bbystatic.com/image2/BestBuy_MX/images/products/1000/1000228323_sd.jpg', 2, 1, 1),
(3, 'Funda/Case Clear para iPhone 11', 'Funda/Case Clear para iPhone 11', '479', 'https://pisces.bbystatic.com/image2/BestBuy_MX/images/products/1000/1000223103_sd.jpg', 6, 1, 1),
(4, 'Funda/Case Clear para iPhone 11 Pro', 'Funda/Case Clear para iPhone 11 Pro', '479', 'https://pisces.bbystatic.com/image2/BestBuy_MX/images/products/1000/1000223090_sd.jpg', 5, 1, 1),
(5, 'Funda Silicon para Samsung Galaxy S20', 'Funda Silicon para Samsung Galaxy S20', '599', 'https://pisces.bbystatic.com/image2/BestBuy_MX/images/products/1000/1000227341_sd.jpg', 6, 1, 1),
(6, 'Mica Protectora iPhone 6 Plus/6S Plus/7', 'Mica Protectora iPhone 6 Plus/6S Plus/7', '299', 'https://pisces.bbystatic.com/image2/BestBuy_MX/images/products/1000/1000221881cv12d.jpg', 10, 2, 1),
(7, 'Mica protectora Samsung Galaxy S10', 'Mica protectora Samsung Galaxy S10', '399', 'https://pisces.bbystatic.com/image2/BestBuy_MX/images/products/1000/1000217776cv11d.jpg', 8, 2, 1),
(8, 'Mica protectora Invisible Shield', 'Mica protectora Invisible Shield', '399', 'https://pisces.bbystatic.com/image2/BestBuy_MX/images/products/1000/1000227508_rd.jpg', 7, 2, 1),
(9, 'Mica Privacidad iPhone 11 Pro', 'Mica Privacidad iPhone 11 Pro', '599', 'https://pisces.bbystatic.com/image2/BestBuy_MX/images/products/1000/1000227226_rd.jpg', 3, 2, 1),
(10, 'Mica protectora Samsung Galaxy S10 Plus', 'Mica protectora Samsung Galaxy S10 Plus', '399', 'https://pisces.bbystatic.com/image2/BestBuy_MX/images/products/1000/1000217777cv11d.jpg', 9, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id` int(10) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token_acceso` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `caducidad_token_acceso` datetime NOT NULL,
  `token_actualizacion` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `caducidad_token_actualizacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8 NOT NULL,
  `tipo` enum('cliente','administrador','','') CHARACTER SET utf8 NOT NULL DEFAULT 'cliente',
  `apellido` varchar(50) CHARACTER SET utf8 NOT NULL,
  `direccion` varchar(100) CHARACTER SET utf8 NOT NULL,
  `fecha_nacimiento` varchar(50) CHARACTER SET utf8 NOT NULL,
  `telefono` varchar(15) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `activo` enum('SI','NO') CHARACTER SET utf8 NOT NULL DEFAULT 'SI'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `tipo`, `apellido`, `direccion`, `fecha_nacimiento`, `telefono`, `email`, `password`, `activo`) VALUES
(1, 'juan carlos', 'cliente', 'vazquez garcia', 'san luis potosi', '10-09-97', '123456789', 'juan@gmail.com', '$2y$10$BtdAVvejPTTNl1ohNcc3wu3HSuzZ3haEik6sXJ0YDmDrcLRzUX5P2', 'SI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `fecha` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalleventas`
--
ALTER TABLE `detalleventas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productos_categorias_FK` (`categoria_id`),
  ADD KEY `productos_usuarios_FK` (`usuario_id`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `token_acceso` (`token_acceso`),
  ADD UNIQUE KEY `token_actualizacion` (`token_actualizacion`),
  ADD KEY `sesion_usuario_FK` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalleventas`
--
ALTER TABLE `detalleventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categorias_FK` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `productos_usuarios_FK` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
