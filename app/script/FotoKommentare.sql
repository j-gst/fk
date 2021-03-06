-- -------------------------------------------------
-- DB-Script
-- Automatisches Erstellen und initiales Befuellen der Datenbank.
-- @author: Thies Schillhorn, Gerrit Storm
-- -------------------------------------------------


SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `FotoKommentare` ;
CREATE SCHEMA IF NOT EXISTS `FotoKommentare` DEFAULT CHARACTER SET utf8 ;
USE `FotoKommentare` ;

-- -----------------------------------------------------
-- Table `FotoKommentare`.`FK_CategoryAndTag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FotoKommentare`.`FK_CategoryAndTag` ;

CREATE  TABLE IF NOT EXISTS `FotoKommentare`.`FK_CategoryAndTag` (
  `Id` INT(11) NOT NULL  AUTO_INCREMENT,
  `Name` VARCHAR(45) ,
  `Comment` TEXT  DEFAULT NULL ,
  PRIMARY KEY (`Id`) ,
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC) ,
  UNIQUE INDEX `Name_UNIQUE` (`Name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Angaben zu Kategorien oder Tags';


-- -----------------------------------------------------
-- Table `FotoKommentare`.`FK_User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FotoKommentare`.`FK_User` ;

CREATE  TABLE IF NOT EXISTS `FotoKommentare`.`FK_User` (
  `Id` INT(11) NOT NULL  AUTO_INCREMENT,
  `UserName` VARCHAR(16) NOT NULL ,
  `EMailAdress` VARCHAR(64) NOT NULL ,
  `FirstName` VARCHAR(45) NOT NULL ,
  `LastName` VARCHAR(45) NOT NULL ,
  `Password` BLOB NOT NULL , 
  `UserState` SMALLINT NOT NULL ,
  `Role` INT NOT NULL ,  
  PRIMARY KEY (`Id`) ,
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC) ,
  UNIQUE INDEX `UserName_UNIQUE` (`UserName` ASC) ,
  UNIQUE INDEX `EMailAddress_UNIQUE` (`EMailAdress` ASC),
  CONSTRAINT `fk_FK_User_1`
    FOREIGN KEY (`Role` )
    REFERENCES `FotoKommentare`.`FK_Role` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION  
  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Enthaellt Angaben zum registrierten Benutzer';


-- -----------------------------------------------------
-- Table `FotoKommentare`.`FK_Picture`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FotoKommentare`.`FK_Picture` ;

CREATE  TABLE IF NOT EXISTS `FotoKommentare`.`FK_Picture` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `UserId` INT(11),
  `Name` VARCHAR(255) NOT NULL , 
  `Format` VARCHAR(32) NOT NULL ,
  `CreaDateTime` DATETIME NOT NULL ,
  `OriginalName` VARCHAR(255) NOT NULL ,
  `PictureState` SMALLINT NOT NULL ,
  `Description` TEXT , 
  `ArchiveId`  INT DEFAULT NULL,
  PRIMARY KEY (`Id`) ,
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC) ,
  CONSTRAINT `fk_FK_Picture_1`
    FOREIGN KEY (`UserId` )
    REFERENCES `FotoKommentare`.`FK_User` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Enthaellt Angaben zu hochgeladenen Bildern';


-- -----------------------------------------------------
-- Table `FotoKommentare`.`FK_Archive`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FotoKommentare`.`FK_Archive` ;

CREATE  TABLE IF NOT EXISTS `FotoKommentare`.`FK_Archive` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `ZipName` VARCHAR(255) NOT NULL , 
  PRIMARY KEY (`Id`) ,
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Archive';

-- -----------------------------------------------------
-- Table `FotoKommentare`.`FK_Right`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FotoKommentare`.`FK_Right` ;

CREATE  TABLE IF NOT EXISTS `FotoKommentare`.`FK_Right` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(255) NOT NULL , 
  PRIMARY KEY (`Id`) ,
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Rights';


-- -----------------------------------------------------
-- Table `FotoKommentare`.`FK_Role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FotoKommentare`.`FK_Role` ;

CREATE  TABLE IF NOT EXISTS `FotoKommentare`.`FK_Role` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(255) NOT NULL , 
  PRIMARY KEY (`Id`) ,
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Roles';

-- -----------------------------------------------------
-- Table `FotoKommentare`.`FK_Right_Role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FotoKommentare`.`FK_Right_Role` ;

CREATE  TABLE IF NOT EXISTS `FotoKommentare`.`FK_Right_Role` (
  `Id` INT(11) NOT NULL AUTO_INCREMENT,
  `RoleId` INT NOT NULL , 
  `RightId` INT NOT NULL ,   
  PRIMARY KEY (`Id`) ,
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC) ,
  
  CONSTRAINT `fk_FK_Right_Role_1`
    FOREIGN KEY (`RoleId` )
    REFERENCES `FotoKommentare`.`FK_Role` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
	
	CONSTRAINT `fk_FK_Right_Role_2`
    FOREIGN KEY (`RightId` )
    REFERENCES `FotoKommentare`.`FK_Right` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
  
  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Rights';

-- -----------------------------------------------------
-- Table `FotoKommentare`.`FK_Comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FotoKommentare`.`FK_Comments` ;

CREATE  TABLE IF NOT EXISTS `FotoKommentare`.`FK_Comments` (
  `Id` INT(11) NOT NULL  AUTO_INCREMENT,
  `PictureId` INT(11) NOT NULL ,
  `UserId` INT(11),
  `Comment` TEXT NOT NULL ,
  `RelatedId` INT(11) NULL DEFAULT NULL ,
  `CommentState` SMALLINT(6) NOT NULL ,
  `CreaDateTime` DATETIME NOT NULL ,
  `ModDateTime` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`Id`) ,
  UNIQUE INDEX `Id_UNIQUE` (`Id` ASC) ,
  INDEX `User_idx` (`UserId` ASC) ,
  INDEX `Related_idx` (`RelatedId` ASC) ,
  INDEX `fk_FK_Comments_Picture` (`PictureId` ASC) ,
  INDEX `fk_FK_Comments_User` (`UserId` ASC) ,
  INDEX `fk_FK_Comments_1` (`UserId` ASC) ,
  INDEX `fk_FK_Comments_2` (`PictureId` ASC) ,
  INDEX `fk_FK_Comments_3` (`RelatedId` ASC) ,
  CONSTRAINT `fk_FK_Comments_1`
    FOREIGN KEY (`UserId` )
    REFERENCES `FotoKommentare`.`FK_User` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FK_Comments_2`
    FOREIGN KEY (`PictureId` )
    REFERENCES `FotoKommentare`.`FK_Picture` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FK_Comments_3`
    FOREIGN KEY (`RelatedId` )
    REFERENCES `FotoKommentare`.`FK_Comments` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Enthaellt die getaetigten Kommentare';


-- -----------------------------------------------------
-- Table `FotoKommentare`.`FK_Picture_Category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FotoKommentare`.`FK_Picture_Category` ;

CREATE  TABLE IF NOT EXISTS `FotoKommentare`.`FK_Picture_Category` (
  `PictureId` INT(11) NOT NULL ,
  `CategoryId` INT(11) NOT NULL ,
  INDEX `picture_category_UNIQUE` (`PictureId` ASC, `CategoryId` ASC) ,
  INDEX `fk_FK_Picture_Category_1` (`PictureId` ASC) ,
  INDEX `fk_FK_Picture_Category_2` (`CategoryId` ASC) ,
  CONSTRAINT `fk_FK_Picture_Category_1`
    FOREIGN KEY (`PictureId` )
    REFERENCES `FotoKommentare`.`FK_Picture` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FK_Picture_Category_2`
    FOREIGN KEY (`CategoryId` )
    REFERENCES `FotoKommentare`.`FK_CategoryAndTag` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Verlinkungstabelle zwischen Foto und Kategorie/Tag';



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;



-- -----------------------------------------------------
-- Initial inserts
-- -----------------------------------------------------


-- Rights
INSERT INTO FK_Right (`Name`) VALUES ('picture_upload');
INSERT INTO FK_Right (`Name`) VALUES ('picture_delete_own');
INSERT INTO FK_Right (`Name`) VALUES ('picture_delete_all');
INSERT INTO FK_Right (`Name`) VALUES ('comment_make');
INSERT INTO FK_Right (`Name`) VALUES ('comment_delete_own');
INSERT INTO FK_Right (`Name`) VALUES ('comment_delete_all');
INSERT INTO FK_Right (`Name`) VALUES ('view');
INSERT INTO FK_Right (`Name`) VALUES ('admin');


-- Roles
INSERT INTO FK_Role (`Name`) VALUES ('guest');
INSERT INTO FK_Role (`Name`) VALUES ('user');
INSERT INTO FK_Role (`Name`) VALUES ('trusted_user');
INSERT INTO FK_Role (`Name`) VALUES ('admin');


-- Admin User | PW: admin
INSERT INTO FK_User
(`UserName`, `EMailAdress`,`FirstName`,`LastName`,`UserState`,`Role`, `Password`)
VALUES ('admin', 'admin@test.de', 'Admin', 'Admin', 1, 
	(SELECT Id FROM FK_Role WHERE Name = 'admin'), 'd033e22ae348aeb5660fc2140aec35850c4da997');

-- Right_Role	
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'admin'),
         (SELECT Id FROM FK_Role WHERE Name = 'admin') );

INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'picture_upload'),
         (SELECT Id FROM FK_Role WHERE Name = 'admin') );
	
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'comment_make'),
         (SELECT Id FROM FK_Role WHERE Name = 'admin') );
		 
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'view'),
         (SELECT Id FROM FK_Role WHERE Name = 'admin') );
		 
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'picture_delete_own'),
         (SELECT Id FROM FK_Role WHERE Name = 'admin') );

INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'comment_delete_own'),
         (SELECT Id FROM FK_Role WHERE Name = 'admin') );		 
		 
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'picture_delete_all'),
         (SELECT Id FROM FK_Role WHERE Name = 'admin') );

INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'comment_delete_all'),
         (SELECT Id FROM FK_Role WHERE Name = 'admin') );	 
		 
		 
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'picture_upload'),
         (SELECT Id FROM FK_Role WHERE Name = 'user') );
		 
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'comment_make'),
         (SELECT Id FROM FK_Role WHERE Name = 'user') );
		 
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'view'),
         (SELECT Id FROM FK_Role WHERE Name = 'user') );
		 
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'picture_upload'),
         (SELECT Id FROM FK_Role WHERE Name = 'trusted_user') );
	
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'comment_make'),
         (SELECT Id FROM FK_Role WHERE Name = 'trusted_user') );
		 
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'view'),
         (SELECT Id FROM FK_Role WHERE Name = 'trusted_user') );
		 
INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'picture_delete_own'),
         (SELECT Id FROM FK_Role WHERE Name = 'trusted_user') );

INSERT INTO FK_Right_Role (`RightId`,`RoleId`) 
VALUES ( (SELECT Id FROM FK_Right WHERE Name = 'comment_delete_own'),
         (SELECT Id FROM FK_Role WHERE Name = 'trusted_user') );	
	 