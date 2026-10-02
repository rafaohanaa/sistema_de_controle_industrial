-- MySQL Workbench Forward Engineering

DROP SCHEMA IF EXISTS simulad_saep_02;

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema simulad_saep_02
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema simulad_saep_02
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `simulad_saep_02` DEFAULT CHARACTER SET utf8 ;
USE `simulad_saep_02` ;

-- -----------------------------------------------------
-- Table `simulad_saep_02`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `simulad_saep_02`.`usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `simulad_saep_02`.`produtos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `simulad_saep_02`.`produtos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `produto` VARCHAR(200) NOT NULL,
  `codigo` VARCHAR(50) NOT NULL,
  `fabricante` VARCHAR(200) NOT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  `estoque_atual` INT NOT NULL DEFAULT 0,
  `estoque_minimo` INT NOT NULL,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `categoria` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `simulad_saep_02`.`movimentacaoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `simulad_saep_02`.`movimentacoes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tipo` INT NOT NULL COMMENT '1- ENTRADA 2-SAIDA',
  `data` DATE NOT NULL,
  `quantidade` INT NOT NULL,
  `saldo_anterior` INT NOT NULL,
  `usuarios_id` INT NOT NULL,
  `produtos_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_movimentacaoes_usuarios_idx` (`usuarios_id` ASC) ,
  INDEX `fk_movimentacaoes_produtos1_idx` (`produtos_id` ASC) ,
  CONSTRAINT `fk_movimentacaoes_usuarios`
    FOREIGN KEY (`usuarios_id`)
    REFERENCES `simulad_saep_02`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimentacaoes_produtos1`
    FOREIGN KEY (`produtos_id`)
    REFERENCES `simulad_saep_02`.`produtos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- ---------------------------------
-- INSERÇÕES
-- ---------------------------------
-- INSERT INTO TABELA (COLUNA1,COLUNA2) VALUES (VALOR1,VALOR2);
insert into usuarios (id,nome,email,senha) values (1,' João','joao@gmail.com','123');
insert into usuarios (id,nome,email,senha) values (2, 'Luiz', 'luiz@gmail.com','123');
insert into usuarios (id,nome,email,senha) values (3, 'Marcelo','marcelo@gmail.com','67');

insert into produtos(id, codigo, produto, fabricante, preco, estoque_atual, estoque_minimo, ativo, categoria)
VALUES
(1,'P001','Tinta','Coral',100.00,50,10,1,'Premium'),
(2,'P002','Parafuso','Ciser',1.50,70,5,1,'Rosca'),
(3,'P003','Chapas de aço','Materia-Prima',75.00,7,1,1,'Revestimento');

insert into movimentacoes(tipo, data, quantidade, saldo_anterior,
                           usuarios_id, produtos_id)
             values(1, str_to_date('11/09/2026', '%d/%m/%Y'), 5, 2, 1, 3),
                    (2, str_to_date('11/09/2026', '%d/%m/%Y'), 10, 20, 2, 2), 
                    (2, str_to_date('10/09/2026', '%d/%m/%Y'), 15, 50, 3, 1);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
