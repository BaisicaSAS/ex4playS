delete from ex4play.plansuscripcion where idplansuscripcion > 0;

alter table ex4play.plansuscripcion auto_increment=1;

INSERT INTO `ex4play`.`plansuscripcion`
(`txnomplan`,
`txdescripcionplan`,
`fevigencia`,
`ingratis`)
VALUES
(
'Plan básico','Plan básico gratuito con la posibilidad de cambiar un videojuego cada dos meses',
'2060-12-31 00:00-',1);

