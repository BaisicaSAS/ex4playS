delete from ex4play.plansuscripcion where idplansuscripcion > 0;

alter table ex4play.plansuscripcion auto_increment=1;

INSERT INTO `ex4play`.`plansuscripcion`
(`txnomplan`,
`txdescripcionplan`,
`fevigencia`,
`ingratis`,
`inmesesplan`,
`dbvalsuscripción`)
VALUES
(
'Plan básico','Plan básico gratuito con la posibilidad de cambiar un videojuego cada dos meses',
'2060-12-31 00:00-',1,0,0);

INSERT INTO `ex4play`.`plansuscripcion`
(`txnomplan`,
`txdescripcionplan`,
`fevigencia`,
`ingratis`,
`inmesesplan`,
`dbvalsuscripción`)
VALUES
(
'Plan Chammer','Suscripción anual básica [3 cambios mensuales]',
'2060-12-31 00:00-',0,12,50000);


INSERT INTO `ex4play`.`plansuscripcion`
(`txnomplan`,
`txdescripcionplan`,
`fevigencia`,
`ingratis`,
`inmesesplan`,
`dbvalsuscripción`)
VALUES
(
'Plan Super Chammer','Suscripción anual completa [10 cambios mensuales]',
'2060-12-31 00:00-',0,12,70000);

