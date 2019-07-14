create	table usuario(id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
                      usuario varchar(50),
                      clave varchar(20),
                      perfil varchar(15));

alter table usuario add COLUMN idtipo int;