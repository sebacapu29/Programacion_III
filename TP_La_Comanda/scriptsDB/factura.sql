CREATE TABLE factura(id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
                    fecha varchar(11),
                    idresponsable int,
                    idpedido int,
                    idmesa int,
                    importe decimal(9,2));