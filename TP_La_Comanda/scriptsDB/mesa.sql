--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesa` (
  `id` int(11) PRIMARY KEY NOT NULL,
  `sector` int(11),
  `estado` int(11)
);

--
-- Insercion datos
--
INSERT INTO `mesa` (`id`, `sector`, `estado`) VALUES
(1, 2, 3),
(2, 1, 3);