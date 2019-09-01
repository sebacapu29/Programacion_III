create	table usuario(id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
                      usuario varchar(50),
                      clave varchar(20),
                      idempleado varchar(15));

CREATE TABLE PedidoDetalle(id int PRIMARY KEY NOT null AUTO_INCREMENT,
                           codigoPedido varchar(10),    
                           cantidad int(11),
                           descripcion varchar(50));

CREATE TABLE pedido(id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
                        estado int,
                        tiempoestimado varchar(15),
                        tiempoentrega varchar(15),
                        codigo varchar(5),
                        idmesa int,
                        foto varchar(20));

CREATE TABLE `horario` (
  `id` int(11) PRIMARY KEY NOT NULL,
  `fecha` int(11),
  `estado` int(11)
);

CREATE TABLE comentario(id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                        puntaje INT(11),
                        descripcion varchar(50),
                        idMesa INT(11));

CREATE TABLE factura(id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
                    fecha varchar(11),
                    idresponsable int,
                    idpedido int,
                    idmesa int,
                    importe decimal(9,2));

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

CREATE TABLE empleado (id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                       usuario VARCHAR(30),
                       clave VARCHAR(30),
                       tipo INT,
                       estado INT);

INSERT INTO `empleado`( `usuario`, `clave`,`tipo`,`estado`) VALUES ('usertest@gmail.com','abc123',1,1);