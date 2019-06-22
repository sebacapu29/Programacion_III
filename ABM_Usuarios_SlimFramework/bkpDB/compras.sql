CREATE TABLE compra (articulo varchar(20), 
                    fecha varchar(12), 
                    precio float);

 ALTER TABLE compra ADD COLUMN usuario varchar(20);

 INSERT INTO compra (articulo,fecha,precio,usuario) values
 ('Cargador Portatil 2200mA + Auriculares x2','22/06/2019',1225.50,'user1');

 INSERT INTO compra (articulo,fecha,precio,usuario) values
 ('Monitor LG 24 x2','22/06/2019',5200,'admin');

 ALTER TABLE compra ADD COLUMN id int PRIMARY KEY AUTO_INCREMENT NOT null;