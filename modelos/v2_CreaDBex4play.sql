-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema ex4play
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema ex4play
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ex4play` DEFAULT CHARACTER SET utf8 ;
USE `ex4play` ;

-- -----------------------------------------------------
-- Table `ex4play`.`fabricante`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`fabricante` (
  `idfabricante` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del fabricante',
  `txnomfabricante` VARCHAR(120) NOT NULL COMMENT 'Nombre del fabricante',
  `txpaisfabricante` VARCHAR(45) NOT NULL COMMENT 'Pais del fabricante',
  PRIMARY KEY (`idfabricante`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Tabla que almacena los fabricantes de videojuegos';


-- -----------------------------------------------------
-- Table `ex4play`.`consola`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`consola` (
  `idconsola` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Id de la consola',
  `txnombreconsola` VARCHAR(200) NOT NULL COMMENT 'Nombre de la consola',
  `felanzamiento` DATETIME NOT NULL COMMENT 'Fecha de lanzamiento de la consola',
  `consola_fabricante` INT(11) NOT NULL COMMENT 'Referencia al fabricante de la consola',
  PRIMARY KEY (`idconsola`),
  INDEX `fk_consola_fabricante_idx` (`consola_fabricante` ASC),
  CONSTRAINT `fk_consola_fabricante`
    FOREIGN KEY (`consola_fabricante`)
    REFERENCES `ex4play`.`fabricante` (`idfabricante`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Almacena las consolas';


-- -----------------------------------------------------
-- Table `ex4play`.`videojuego`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`videojuego` (
  `idvideojuego` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Id del videojuego',
  `txnomvideojuego` VARCHAR(300) NOT NULL COMMENT 'nombre del videojuego',
  `felanzamiento` DATETIME NOT NULL COMMENT 'Fecha de lanzamiento del videojuego',
  `incategvideojuego` INT(11) NOT NULL COMMENT 'Categoria del videojuego',
  `videojuego_consola` BIGINT(20) NOT NULL,
  PRIMARY KEY (`idvideojuego`),
  INDEX `fk_videojuego_consola1_idx` (`videojuego_consola` ASC),
  CONSTRAINT `fk_videojuego_consola1`
    FOREIGN KEY (`videojuego_consola`)
    REFERENCES `ex4play`.`consola` (`idconsola`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Tabla donde se almacenan todos los videojuegos';


-- -----------------------------------------------------
-- Table `ex4play`.`ejemplar`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`ejemplar` (
  `idejemplar` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Id del ejemplar',
  `ejemplar_videojuego` BIGINT(20) NOT NULL,
  `fecargue` DATETIME NOT NULL,
  `inejemplarpublicado` INT(11) NOT NULL DEFAULT '0' COMMENT '0-No ublicado  1-publicado',
  PRIMARY KEY (`idejemplar`),
  INDEX `fk_ejemplar_videojuego1_idx` (`ejemplar_videojuego` ASC),
  CONSTRAINT `fk_ejemplar_videojuego1`
    FOREIGN KEY (`ejemplar_videojuego`)
    REFERENCES `ex4play`.`videojuego` (`idvideojuego`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Tabla que almacena cada uno de los ejemplares del usuario';


-- -----------------------------------------------------
-- Table `ex4play`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`usuario` (
  `idusuario` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Id del usuario',
  `txnomusuario` VARCHAR(20) NOT NULL COMMENT 'Nombre de usuario',
  `txmailusuario` VARCHAR(120) NOT NULL COMMENT 'Correo electronico del usuario',
  `txclaveusuario` VARCHAR(255) NOT NULL COMMENT 'Clave del usuario',
  `fecreacionusuario` DATETIME NOT NULL COMMENT 'Fecha de creacion del usuario',
  PRIMARY KEY (`idusuario`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Tabla que almacena los usuarios de la aplicacion ex4play';


-- -----------------------------------------------------
-- Table `ex4play`.`trato`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`trato` (
  `idtrato` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `idtratotexto` VARCHAR(45) NOT NULL COMMENT 'Id UNICO del trato para diferenciar una conversación. Control',
  `trato_idusrdueno` BIGINT(20) NOT NULL COMMENT 'Dueno del ejemplar',
  `trato_idusrsolicita` BIGINT(20) NOT NULL COMMENT 'Solicitante del ejemplar',
  `trato_idejemplar` BIGINT(20) NOT NULL COMMENT 'Id del ejemplar',
  `fefechatrato` DATETIME NOT NULL COMMENT 'Fecha del registro',
  `inestadotrato` INT(11) NULL DEFAULT '0' COMMENT '0 Cerrado 1 Declinado revisar bien',
  PRIMARY KEY (`idtrato`),
  INDEX `fk_tratoaccion_usuario1_idx` (`trato_idusrdueno` ASC),
  INDEX `fk_tratoaccion_usuario2_idx` (`trato_idusrsolicita` ASC),
  INDEX `fk_tratoaccion_ejemplar1_idx` (`trato_idejemplar` ASC),
  CONSTRAINT `fk_tratoaccion_ejemplar1`
    FOREIGN KEY (`trato_idejemplar`)
    REFERENCES `ex4play`.`ejemplar` (`idejemplar`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tratoaccion_usuario1`
    FOREIGN KEY (`trato_idusrdueno`)
    REFERENCES `ex4play`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tratoaccion_usuario2`
    FOREIGN KEY (`trato_idusrsolicita`)
    REFERENCES `ex4play`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Cada una de las acciones de un trato - Preguntas y cierre o cancelación del trato';


-- -----------------------------------------------------
-- Table `ex4play`.`actividadusuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`actividadusuario` (
  `idactividadusuario` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Id del registro de actividad',
  `actusuario_idusuarioescribe` BIGINT(20) NOT NULL COMMENT 'Id del usuario que genera la actividad',
  `actusuario_idejemplar` BIGINT(20) NULL DEFAULT NULL COMMENT 'Ejemplar de la actividad',
  `actusuario_idtrato` BIGINT(20) NOT NULL,
  PRIMARY KEY (`idactividadusuario`),
  INDEX `fk_actividadusuario_usuario1_idx` (`actusuario_idusuarioescribe` ASC),
  INDEX `fk_actividadusuario_ejemplar1_idx` (`actusuario_idejemplar` ASC),
  INDEX `fk_actividadusuario_tratoaccion1_idx` (`actusuario_idtrato` ASC),
  CONSTRAINT `fk_actividadusuario_ejemplar1`
    FOREIGN KEY (`actusuario_idejemplar`)
    REFERENCES `ex4play`.`ejemplar` (`idejemplar`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_actividadusuario_tratoaccion1`
    FOREIGN KEY (`actusuario_idtrato`)
    REFERENCES `ex4play`.`trato` (`idtrato`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_actividadusuario_usuario1`
    FOREIGN KEY (`actusuario_idusuarioescribe`)
    REFERENCES `ex4play`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ex4play`.`sesion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`sesion` (
  `insesion` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `txsesnumero` VARCHAR(100) NOT NULL COMMENT 'ID o Numero de la sesion',
  `insesactiva` INT(11) NOT NULL DEFAULT '1' COMMENT '0: Inactiva 1: Activa',
  `fesesfechaini` DATETIME NOT NULL COMMENT 'Fecha de inicio de la sesion',
  `fesesfechafin` DATETIME NULL DEFAULT NULL COMMENT 'Fecha de fin de la sesion',
  `txipaddr` VARCHAR(30) NOT NULL DEFAULT '000.000.000.000' COMMENT 'Direccion IP desde donde se genera la sesion',
  `sesion_idusuario` BIGINT(20) NOT NULL COMMENT 'Id del usuario de la sesion',
  PRIMARY KEY (`insesion`),
  INDEX `fk_sesion_usuario1_idx` (`sesion_idusuario` ASC),
  CONSTRAINT `fk_sesion_usuario1`
    FOREIGN KEY (`sesion_idusuario`)
    REFERENCES `ex4play`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Registra cada una de las sesiones generadas por cada usuario.';


-- -----------------------------------------------------
-- Table `ex4play`.`actsesion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`actsesion` (
  `inactsesion` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `inactaccion` INT(11) NOT NULL DEFAULT '0' COMMENT 'Todas las acciones que existan en el sistema enumeradas y quemadas en una tabla o arreglo',
  `txactmensaje` VARCHAR(500) NOT NULL COMMENT 'Mensaje de exito / fallo de la accion',
  `feactfecha` DATETIME NOT NULL COMMENT 'Fecha de la actividad',
  `inactfinalizada` INT(11) NOT NULL DEFAULT '0' COMMENT '0: no 1:si',
  `actsesion_insesion` INT(11) NOT NULL COMMENT 'Id de la sesion',
  PRIMARY KEY (`inactsesion`),
  INDEX `fk_actsesion_sesion1_idx` (`actsesion_insesion` ASC),
  CONSTRAINT `fk_actsesion_sesion1`
    FOREIGN KEY (`actsesion_insesion`)
    REFERENCES `ex4play`.`sesion` (`insesion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Se entiende como el detalle de actividad de cada sesion';


-- -----------------------------------------------------
-- Table `ex4play`.`calificatrato`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`calificatrato` (
  `idcalificatrato` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Id del registro de calificación del trato\n',
  `calificatrato_idtrato` BIGINT(20) NOT NULL COMMENT 'Id del trato',
  `fecalifica` DATETIME NOT NULL COMMENT 'Fecha en que se realiza la calificación\n',
  `idtrato` VARCHAR(45) NOT NULL COMMENT 'Id del trato que se califica',
  `calificatr_usrcalifica` BIGINT(20) NOT NULL COMMENT 'Usuario que realiza la calificación',
  `calificatr_usrcalificado` BIGINT(20) NOT NULL COMMENT 'Usuario que recibe la calificacion',
  `incalificacion` INT(11) NOT NULL COMMENT 'Cantidad de estrellas de la calificacion',
  `txobservacioncalifica` VARCHAR(120) NOT NULL COMMENT 'Observacion de la calificacion',
  PRIMARY KEY (`idcalificatrato`),
  INDEX `fk_calificatrato_tratoaccion1_idx` (`calificatrato_idtrato` ASC),
  INDEX `fk_calificatrato_usuario1_idx` (`calificatr_usrcalifica` ASC),
  INDEX `fk_calificatrato_usuario2_idx` (`calificatr_usrcalificado` ASC),
  CONSTRAINT `fk_calificatrato_tratoaccion1`
    FOREIGN KEY (`calificatrato_idtrato`)
    REFERENCES `ex4play`.`trato` (`idtrato`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_calificatrato_usuario1`
    FOREIGN KEY (`calificatr_usrcalifica`)
    REFERENCES `ex4play`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_calificatrato_usuario2`
    FOREIGN KEY (`calificatr_usrcalificado`)
    REFERENCES `ex4play`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Almacena las calificaciones de los tratos realizados';


-- -----------------------------------------------------
-- Table `ex4play`.`plansuscripcion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`plansuscripcion` (
  `idplansuscripcion` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del plan de suscripción',
  `txnomplan` VARCHAR(45) NOT NULL COMMENT 'Nombre del plan',
  `txdescripcionplan` VARCHAR(300) NOT NULL COMMENT 'Descripcíon del plan',
  `fevigencia` DATETIME NOT NULL COMMENT 'Fecha de finalización de vigencia del plan : 31 Dic 2060 (Para indefinido)',
  `ingratis` INT(11) NOT NULL DEFAULT '1' COMMENT '0: Pago - 1 : Gratis',
  PRIMARY KEY (`idplansuscripcion`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Almacena los planes o suscripciones que se pueden adquirir en ex4play';


-- -----------------------------------------------------
-- Table `ex4play`.`detalleplan`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`detalleplan` (
  `iddetalleplan` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del detalle del plan',
  `detalleplan_idplan` INT(11) NOT NULL COMMENT 'Id del plan padre',
  `innumtarifa` INT(11) NOT NULL DEFAULT '1' COMMENT 'Indicador del nuero de la tarifa: Ejemplo 1, 2, 3... Un indice  ',
  `indiastarifa` INT(11) NOT NULL DEFAULT '-1' COMMENT 'Días de vigencia de una tarifa del plan, -1 : Indefinido',
  `incantidadcambios` INT(11) NOT NULL DEFAULT '1' COMMENT 'Cantidad de cambios o transacciones que permite realizar la suscripcion en un periodo de tiempo definido por inperiodicidad',
  `inperiodicidad` INT(11) NOT NULL DEFAULT '0' COMMENT 'Periodicidad de la cantidad de cambios es decir en cantidad de cambios hay 1, y aqui se especifica: 0 Durante la vigencia , 1 Mensual, 2 Bimestral, 3 Trimestral, 4 Semestral, 5 Anual',
  PRIMARY KEY (`iddetalleplan`),
  INDEX `fk_detalleplan_plansuscripcion1_idx` (`detalleplan_idplan` ASC),
  CONSTRAINT `fk_detalleplan_plansuscripcion1`
    FOREIGN KEY (`detalleplan_idplan`)
    REFERENCES `ex4play`.`plansuscripcion` (`idplansuscripcion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Tabla que almacena la configuración particular de cada uno de los planes de suscripción de ex4play';


-- -----------------------------------------------------
-- Table `ex4play`.`ejemplarusuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`ejemplarusuario` (
  `idejemplarusuario` BIGINT(20) NOT NULL COMMENT 'Id del registro de relacion\n',
  `invigente` INT(11) NULL DEFAULT '0' COMMENT '0 : No esta vigente la relacion, 1 significa que esta vigente...es decir este usuario es el dueno del juego actualmente..no puede haber 2 usuarios con el mismo ejemplar al tiempo.',
  `feduenodesde` DATETIME NOT NULL COMMENT 'Fecha desde la que se indica que es dueño del ejemplar (Publicado o recibido en cambio)',
  `inpublicado` INT(11) NOT NULL DEFAULT '0' COMMENT 'Indica si el ejemplar aparece en las búsquedas, como publicado. 0 : No publicado, 1: Publicado',
  `fepublicacion` DATETIME NULL DEFAULT NULL COMMENT 'Ultima fecha de publicación del ejemplar por parte de ese usuario',
  `ejemplarusuario_idusuario` BIGINT(20) NOT NULL COMMENT 'Referencia al usuario (Dueno del ejemplar en alguin momento)',
  `ejemplarusuario_idejemplar` BIGINT(20) NOT NULL COMMENT 'Referencia al ejemplar',
  PRIMARY KEY (`idejemplarusuario`),
  INDEX `fk_ejemplarusuario_usuario1_idx` (`ejemplarusuario_idusuario` ASC),
  INDEX `fk_ejemplarusuario_ejemplar1_idx` (`ejemplarusuario_idejemplar` ASC),
  CONSTRAINT `fk_ejemplarusuario_ejemplar1`
    FOREIGN KEY (`ejemplarusuario_idejemplar`)
    REFERENCES `ex4play`.`ejemplar` (`idejemplar`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ejemplarusuario_usuario1`
    FOREIGN KEY (`ejemplarusuario_idusuario`)
    REFERENCES `ex4play`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Tabla que relaciona la historia de cada ejemplar de ex4play, en función de sus propietarios. Indica si esta publicado y si una relacion está vigente.';


-- -----------------------------------------------------
-- Table `ex4play`.`planusuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`planusuario` (
  `idplanusuario` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Id del registro de planes usuarios para mantener histórico',
  `fevigencia` DATETIME NOT NULL COMMENT 'Fecha de fin de la vigencia del plan...permite controlar que está vigente 31 Dic 2060 (Para indefinido)',
  `planusuario_idplan` INT(11) NOT NULL COMMENT 'Referencia al id del plan',
  `planusuario_idusuario` BIGINT(20) NOT NULL COMMENT 'Referencia al id del usuario',
  PRIMARY KEY (`idplanusuario`),
  INDEX `fk_planusuario_plansuscripcion1_idx` (`planusuario_idplan` ASC),
  INDEX `fk_planusuario_usuario1_idx` (`planusuario_idusuario` ASC),
  CONSTRAINT `fk_planusuario_plansuscripcion1`
    FOREIGN KEY (`planusuario_idplan`)
    REFERENCES `ex4play`.`plansuscripcion` (`idplansuscripcion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_planusuario_usuario1`
    FOREIGN KEY (`planusuario_idusuario`)
    REFERENCES `ex4play`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Tabla que indica el plan en que cada usuario está registrado y la vigencia que tiene';


-- -----------------------------------------------------
-- Table `ex4play`.`resenavideojuego`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`resenavideojuego` (
  `idresenavideojuego` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Id del registro de contenido',
  `intipocontenido` INT(11) NOT NULL DEFAULT '0' COMMENT 'Tipo de contenido 0-resena o noticia, 1-comentario, 3-truco, ',
  `txcontenido` VARCHAR(2000) NOT NULL COMMENT 'El contenido que genera el usuario...sedebe revisar buen cómo se va a formatear y almacenar, segun el tipo',
  `resena_videojuego` BIGINT(20) NOT NULL COMMENT 'Referencia al videojuego',
  `resena_usuariopublica` BIGINT(20) NOT NULL COMMENT 'Referencia al usuario que publica',
  `fepublica` DATETIME NULL DEFAULT NULL COMMENT 'Fecha de la publicacion',
  PRIMARY KEY (`idresenavideojuego`),
  INDEX `fk_resenavideojuego_videojuego1_idx` (`resena_videojuego` ASC),
  INDEX `fk_resenavideojuego_usuario1_idx` (`resena_usuariopublica` ASC),
  CONSTRAINT `fk_resenavideojuego_usuario1`
    FOREIGN KEY (`resena_usuariopublica`)
    REFERENCES `ex4play`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_resenavideojuego_videojuego1`
    FOREIGN KEY (`resena_videojuego`)
    REFERENCES `ex4play`.`videojuego` (`idvideojuego`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Relaciona todo el contenido que los usuarios adicionan a al videojuego : Comentarios, reseñas, trucos';


-- -----------------------------------------------------
-- Table `ex4play`.`puntosusuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ex4play`.`puntosusuario` (
  `idpuntosusuario` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Id de los puntos de usuario',
  `puntosusuario_idusuario` BIGINT(20) NOT NULL COMMENT 'Id del usuario',
  `inpuntaje` INT(11) NOT NULL COMMENT 'Puntaje ganado o gastado',
  `fefechapuntos` DATETIME NOT NULL COMMENT 'Fecha de los puntos',
  `insumaresta` INT(11) NOT NULL DEFAULT '1' COMMENT '1 Suma 2 Resta',
  `punusuario_idactiusuario` BIGINT(20) NULL DEFAULT NULL COMMENT 'Referencia a registro de actividad de usuario',
  `punusuario_resenavideojuego` BIGINT(20) NULL DEFAULT NULL COMMENT 'Id del registro de resena',
  `punusuario_idejemplar` BIGINT(20) NULL DEFAULT NULL COMMENT 'Id del ejemplar con el que gana o gasta puntos',
  PRIMARY KEY (`idpuntosusuario`),
  INDEX `fk_puntosusuario_usuario1_idx` (`puntosusuario_idusuario` ASC),
  INDEX `fk_puntosusuario_actividadusuario1_idx` (`punusuario_idactiusuario` ASC),
  INDEX `fk_puntosusuario_resenavideojuego1_idx` (`punusuario_resenavideojuego` ASC),
  INDEX `fk_puntosusuario_ejemplar1_idx` (`punusuario_idejemplar` ASC),
  CONSTRAINT `fk_puntosusuario_actividadusuario1`
    FOREIGN KEY (`punusuario_idactiusuario`)
    REFERENCES `ex4play`.`actividadusuario` (`idactividadusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_puntosusuario_ejemplar1`
    FOREIGN KEY (`punusuario_idejemplar`)
    REFERENCES `ex4play`.`ejemplar` (`idejemplar`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_puntosusuario_resenavideojuego1`
    FOREIGN KEY (`punusuario_resenavideojuego`)
    REFERENCES `ex4play`.`resenavideojuego` (`idresenavideojuego`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_puntosusuario_usuario1`
    FOREIGN KEY (`puntosusuario_idusuario`)
    REFERENCES `ex4play`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
