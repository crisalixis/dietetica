-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2024 a las 23:37:15
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
-- Base de datos: `factura-dietetica`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_precio_producto` (`n_cantidad` INT, `n_precio` DECIMAL(10,2), `codigo` INT)   BEGIN
    	DECLARE nueva_existencia int;
        DECLARE nuevo_total decimal(10,2);
        DECLARE nuevo_precio decimal(10,2);
        
        DECLARE cant_actual int;
        DECLARE pre_actual decimal(10,2);
        
        DECLARE actual_existencia int;
        DECLARE actual_precio decimal(10,2);
        
        SELECT precio,existencia INTO actual_precio, actual_existencia FROM producto WHERE codproducto = codigo;
        SET nueva_existencia = actual_existencia + n_cantidad;
        SET nuevo_total = (actual_existencia * actual_precio) + (n_cantidad * n_precio);
        SET nuevo_precio = nuevo_total / nueva_existencia;
        
        UPDATE producto SET existencia = nueva_existencia, precio = nuevo_precio WHERE codproducto = codigo;
        
        SELECT nueva_existencia, nuevo_precio;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallefactura`
--

CREATE TABLE `detallefactura` (
  `correlativo` bigint(11) NOT NULL,
  `nofactura` bigint(11) DEFAULT NULL,
  `codproducto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `preciototal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `nofactura` bigint(11) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `preciototal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `correlativo` int(11) NOT NULL,
  `codproducto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`correlativo`, `codproducto`, `fecha`, `cantidad`, `precio`, `usuario_id`) VALUES
(1, 1, '2024-10-04 15:28:12', 20, 1000.00, 1),
(2, 2, '2024-10-04 15:31:31', 10, 2000.00, 1),
(3, 4, '2024-10-13 17:54:50', 10, 1000.00, 1),
(4, 5, '2024-10-13 17:57:47', 10, 1000.00, 1),
(5, 6, '2024-10-13 17:58:54', 5, 1000.00, 1),
(6, 1, '2024-10-18 15:02:05', 30, 1250.00, 1),
(7, 1, '2024-10-18 15:03:04', 10, 1300.00, 1),
(8, 2, '2024-10-18 15:26:16', 10, 2100.00, 1),
(9, 1, '2024-10-18 15:29:46', 10, 1300.00, 1),
(10, 4, '2024-10-18 15:31:33', 20, 1100.00, 1),
(11, 1, '2024-10-18 15:54:47', 10, 1300.00, 1),
(12, 1, '2024-10-18 15:55:42', 10, 1200.00, 1),
(13, 1, '2024-10-18 15:58:05', 5, 1250.00, 1),
(14, 2, '2024-10-18 19:00:14', 10, 2500.00, 1),
(15, 2, '2024-10-18 19:00:32', 50, 1900.00, 1),
(16, 4, '2024-10-18 19:04:03', 4, 2000.00, 1),
(17, 7, '2024-10-18 21:18:01', 123, 453464.00, 1),
(18, 7, '2024-10-18 21:18:41', 10, 13.00, 1),
(19, 8, '2024-10-21 10:29:42', 50, 2500.00, 1),
(20, 7, '2024-10-21 10:31:16', 5, 1150.00, 1),
(21, 1, '2024-10-21 20:18:40', 10, 5.00, 1),
(22, 1, '2024-10-21 20:19:06', 10, 1500.00, 1),
(23, 1, '2024-10-24 20:38:31', 5, 1150.00, 1),
(24, 1, '2024-10-25 18:36:11', 10, 1200.00, 1),
(25, 1, '2024-10-25 18:38:05', 10, 2000.00, 1),
(26, 1, '2024-10-25 18:38:25', 10, 2000.00, 1),
(27, 9, '2024-10-27 11:29:38', 50, 2000.00, 1),
(28, 9, '2024-10-27 11:30:00', 1, 2100.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `nofactura` bigint(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `usuario` int(11) DEFAULT NULL,
  `totaltactura` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codproducto` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `proveedor` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `existencia` int(11) DEFAULT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codproducto`, `descripcion`, `proveedor`, `precio`, `existencia`, `date_add`, `usuario_id`, `estado`, `foto`) VALUES
(1, 'Harina de Arroz', 1, 1250.15, 105, '2024-10-04 15:28:12', 1, 0, 'img_d5c51d2db4c816c8f9171bc8c6424bca.jpg'),
(2, 'Pan de arroz', 1, 2012.50, 50, '2024-10-04 15:31:31', 1, 1, 'img-producto.png'),
(4, 'Leche de almendra', 3, 1110.47, 50, '2024-10-13 17:54:50', 1, 1, 'img_709a8700e2ad16a89efa18834483386a.jpg'),
(5, 'Chocolate sin azucar', 8, 1000.00, 50, '2024-10-13 17:57:47', 1, 1, 'img-producto.png'),
(6, 'Leche deslactosada', 8, 1000.00, 50, '2024-10-13 17:58:54', 1, 1, 'img-producto.png'),
(7, 'Caja de Cafe', 3, 1104.55, 55, '2024-10-18 21:18:01', 1, 1, 'img-producto.png'),
(8, 'Harina premezcla sin tacc', 7, 2500.00, 50, '2024-10-21 10:29:42', 1, 1, 'img-producto.png'),
(9, 'Qsyo', 8, 2001.96, 51, '2024-10-27 11:29:38', 1, 0, 'img_1b8b6d8410ea4297753a63c4e18d7f32.jpg');

--
-- Disparadores `producto`
--
DELIMITER $$
CREATE TRIGGER `entradas_A_I` AFTER INSERT ON `producto` FOR EACH ROW BEGIN
    INSERT INTO entradas(codproducto, cantidad, precio, usuario_id)
    VALUES(NEW.codproducto, NEW.existencia, NEW.precio, NEW.usuario_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `codproveedor` int(11) NOT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `email` varchar(40) NOT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`codproveedor`, `proveedor`, `contacto`, `telefono`, `direccion`, `email`, `date_add`, `usuario_id`, `estado`) VALUES
(1, 'Santa Maria', 'Ana Hernandez', '2214708998', 'Av. 44 Esq.22 La Plata, Buenos Aires , Argentina', 'contacto@santamariaproductos.com.ar ', '2024-10-02 19:53:56', 1, 1),
(2, 'SMAMS', 'Jorge Herrera', '-', 'Wellington Foods S.A. - Buenos Aires, Argentina', 'info@smams.net    ', '2024-10-02 19:53:56', 1, 1),
(3, 'Dos Hermanos', 'Sofia Vergara', '3454290301', 'Av. Pte. Juan D. Perón y S. Ortiz - CP3201 - Concordia, Entre Ríos, Argentina', '-  ', '2024-10-02 19:53:56', 1, 1),
(4, 'Maitén', 'Roberto Alvarez', ' 1147524381', 'Ecuador 3139 | San Andrés - Bs.As.', 'ventas@maitenalimentossaludables.com.ar', '2024-10-02 19:53:56', 1, 1),
(5, 'La Serenísima', 'Elena Franco Morales', '02374859000', 'Complejo Industrial Pascual Mastellone.\r\nAlte. Brown 957 (B1748KFS), Gral. Rodríguez, Buenos Aires.\r\n', '-', '2024-10-02 19:53:56', 1, 1),
(6, 'Mayorista Diet', 'Fernando Guerrero', '11696075', 'Av Calchaqui 495, Quilmes', ' info@mayoristadiet.com.ar', '2024-10-02 19:53:56', 1, 1),
(7, 'Pureza', 'Jose Marucci', '02226432885', 'John F. Kennedy 160, B1814BKD\r\nCañuelas, Buenos Aires\r\n', 'atclientes@molca.com.ar', '2024-10-02 19:53:56', 1, 1),
(8, 'Dietética Tomy', 'Esteban Gomez', '01147197600', 'Sucursal La Plata II\r\nCalle 48 Nº 675', 'info@dieteticastomy.com.ar', '2024-10-02 19:53:56', 1, 1),
(9, 'VAIO', 'Felix Arnoldo Rojas', '476378276', 'Avenida las Americas Zona 13', '', '2024-10-02 19:53:56', 1, 1),
(10, 'SUMAR', 'Oscar Maldonado', '788376787', 'Colonia San Jose, Zona 5 Guatemala', '', '2024-10-02 19:53:56', 1, 1),
(11, 'HP', 'Angel Cardona', '2147483647', '5ta. calle zona 4 Guatemala', '', '2024-10-02 19:53:56', 1, 1),
(15, 'Yeison', 'Santiago Moron', '1178980234', 'Esteban Echevarria', '', '2024-10-02 21:03:22', 1, 1),
(16, 'havana', 'Jorge Herrer', '+5491123456', 'holaldadkl', '', '2024-10-18 21:20:06', 1, 1),
(17, 'mostaza', 'Juan Perez', '11837183', 'dhsjdhsj', '', '2024-10-18 21:29:29', 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `usuario` varchar(15) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `rol`, `estado`) VALUES
(1, 'Valentin Aguirre', 'info@valenagui.com', 'admin', '123', 1, 1),
(2, 'Lola Fernandez', 'lolafer@gmail.com', 'lolan', '123', 3, 0),
(3, 'Agustin Diaz', 'agustincac@gmail.com', 'supervisor', '123', 2, 1),
(4, 'fer', 'fer@gmail.com', 'master', '123', 2, 1),
(5, 'Tito Bambino', 'titoelcapito@gmail.com', 'mastercapo59', '123', 3, 0),
(6, 'Kevin Gomez', 'kevgo@gmail.com', 'kevincito', '123', 1, 1),
(7, 'Bastian Flores', 'bastifflo@gmail.com', 'basti', '123', 1, 1),
(9, 'Jose Nuñez', 'josesan@gmail.com', 'jos', '123', 1, 1),
(10, 'Mariana Muñoz', 'marnu@gmail.com', 'marnu', '123', 2, 1),
(11, 'Rita Muriel', 'muriels45@gmail', 'muri45', '123', 3, 1),
(12, 'Sebastian Lopez', 'seblopez.45@gmail.com', 'seb12', '123', 3, 1),
(13, 'Alicia Torrez', 'alicethor@gmail.com', 'alice', '123', 3, 1),
(14, 'Valentina Aguirre', 'valenagui@gmail.com', 'valen', '12345', 3, 1),
(15, 'Patricia Muñoz', 'patrigos@gmail.com', 'patr', '123', 3, 1),
(16, 'Ignacio Caceres', 'ignacuicare@gmail.com', 'ignacui', '123', 3, 1),
(17, 'Lucia', 'pepito@gmail.com', 'juanito123', '123', 3, 1),
(18, 'Lola', 'mm@gmail.com', 'lol', '1234', 1, 1),
(19, 'julio', 'juliocesar@gmail.com', 'julio', '123', 3, 1),
(20, 'ferchu', 'ferchu@gmail.com', 'mana', '123', 3, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`),
  ADD KEY `nofactura` (`nofactura`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `nofactura` (`nofactura`),
  ADD KEY `codproducto` (`codproducto`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`codproducto`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`nofactura`),
  ADD KEY `usuario` (`usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codproducto`),
  ADD KEY `proveedor` (`proveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`codproveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  MODIFY `correlativo` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `nofactura` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `codproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `codproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD CONSTRAINT `detallefactura_ibfk_1` FOREIGN KEY (`nofactura`) REFERENCES `factura` (`nofactura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detallefactura_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD CONSTRAINT `detalle_temp_ibfk_1` FOREIGN KEY (`nofactura`) REFERENCES `factura` (`nofactura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_temp_ibfk_2` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`codproducto`) REFERENCES `producto` (`codproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`proveedor`) REFERENCES `proveedor` (`codproveedor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`idrol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
