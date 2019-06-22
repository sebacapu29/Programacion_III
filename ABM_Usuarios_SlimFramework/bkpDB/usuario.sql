create	table usuario(usuario varchar(50),
                      clave varchar(20),
                      sexo varchar(10),
                      perfil varchar(15));

insert into usuario (usuario,clave,sexo,perfil)
values ('admin','admin','femenino','admin');

ALTER TABLE usuario ADD COLUMN id int PRIMARY KEY AUTO_INCREMENT NOT null;
