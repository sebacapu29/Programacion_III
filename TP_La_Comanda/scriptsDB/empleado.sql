CREATE TABLE empleado (id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                       usuario VARCHAR(30),
                       clave VARCHAR(30),
                       tipo INT,
                       estado INT);

                       INSERT INTO `empleado`( `usuario`, `clave`,`tipo`,`estado`) VALUES ('usertest@gmail.com','abc123',1,1);
                       
                       alter table empleado add COLUMN (usuario varchar(50),
                                  clave varchar(40));