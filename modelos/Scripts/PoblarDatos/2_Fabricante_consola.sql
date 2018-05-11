delete from ex4play.consola where idconsola > 0;
delete from ex4play.fabricante where idfabricante > 0;
alter table ex4play.consola auto_increment=1;
alter table ex4play.fabricante auto_increment=1;

INSERT INTO `ex4play`.`fabricante`
(`txnomfabricante`,
`txpaisfabricante`)
VALUES
('SONY','Japon');
INSERT INTO `ex4play`.`fabricante`
(`txnomfabricante`,
`txpaisfabricante`)
VALUES
('MICROSOFT','USA');

INSERT INTO `ex4play`.`consola`
(`txnombreconsola`,
`felanzamiento`,
`consola_fabricante`)
VALUES
('PS3','2018-01-01 00:00:00','1');

INSERT INTO `ex4play`.`consola`
(`txnombreconsola`,
`felanzamiento`,
`consola_fabricante`)
VALUES
('PS4','2018-01-01 00:00:00','1');

INSERT INTO `ex4play`.`consola`
(`txnombreconsola`,
`felanzamiento`,
`consola_fabricante`)
VALUES
('Xbox 360','2018-01-01 00:00:00','2');

INSERT INTO `ex4play`.`consola`
(`txnombreconsola`,
`felanzamiento`,
`consola_fabricante`)
VALUES
('Xbox One','2018-01-01 00:00:00','2');
