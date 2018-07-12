
delete FROM ex4play.actividadusuario where idactividadusuario > 0;
delete FROM ex4play.puntosusuario where idpuntosusuario > 0;
delete FROM ex4play.actsesion where inactsesion > 0;
delete FROM ex4play.sesion where insesion > 0;
delete FROM ex4play.planusuario where idplanusuario > 0;
delete FROM ex4play.ejemplarusuario where idejemplarusuario > 0;
delete FROM ex4play.usuario where idusuario > 0;

alter table ex4play.actividadusuario auto_increment=1;
alter table ex4play.puntosusuario auto_increment=1;
alter table ex4play.actsesion auto_increment=1;
alter table ex4play.sesion auto_increment=1;
alter table ex4play.planusuario auto_increment=1;
alter table ex4play.ejemplarusuario auto_increment=1;
alter table ex4play.usuario auto_increment=1;

SELECT * FROM ex4play.usuario;
commit;

