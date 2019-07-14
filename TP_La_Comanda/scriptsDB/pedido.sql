CREATE TABLE pedido(id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
                        estado int,
                        tiempoestimado varchar(15),
                        tiempoentrega varchar(15),
                        codigo varchar(5),
                        idmesa int,
                        foto varchar(20));
                        