CREATE TABLE empleado (id INT NOT NULL AUTO_INCREMENT,
                       usuario VARCHAR(30),
                       clave VARCHAR(30),
                       tipo INT,
                       estado INT,
                       nombre VARCHAR(30),
                       apellido VARCHAR(30),
                       sector INT,
                       fechaingreso DATE,
                       constraint pk_id PRIMARY KEY (id));

                       INSERT INTO `empleado`( `usuario`, `clave`,`tipo`,`estado`) VALUES ('usertest@gmail.com','abc123',1,1);
                       
                       alter table empleado add COLUMN (usuario varchar(50),
                                  clave varchar(40));
