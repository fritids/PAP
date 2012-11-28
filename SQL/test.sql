/*
Navicat MySQL Data Transfer

Source Server         : PAP
Source Server Version : 50163
Source Host           : 5.185.100.60:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50163
File Encoding         : 65001

Date: 2012-10-10 09:22:35
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `sql`
-- ----------------------------
DROP TABLE IF EXISTS `sql`;
CREATE TABLE `sql` (
  `ID` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `idade` float DEFAULT NULL,
  `estado_civil` varchar(15) DEFAULT NULL,
  `apelido` varchar(50) DEFAULT NULL,
  `e_mail` varchar(50) DEFAULT NULL,
  `Contacto` int(11) DEFAULT NULL,
  `tipo_de_identificacao` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sql
-- ----------------------------
INSERT INTO sql VALUES ('1', 'Test', '12', 'Casado', 'Testbot', 'test@test.com', '913232672', null);

-- ----------------------------
-- Table structure for `test sql`
-- ----------------------------
DROP TABLE IF EXISTS `test sql`;
CREATE TABLE `test sql` (
  `Id` varchar(255) DEFAULT NULL,
  `Nome` varchar(255) DEFAULT NULL,
  `Ultimo Nome` varchar(255) DEFAULT NULL,
  `F4` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `E-mail` varchar(255) DEFAULT NULL,
  `Contacto` varchar(255) DEFAULT NULL,
  `Tipo de Identificação` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of test sql
-- ----------------------------
INSERT INTO test sql VALUES ('Id', 'Nome', 'Ultimo Nome', null, 'Password', 'E-mail', 'Contacto', 'Tipo de Identificação');

-- ----------------------------
-- Table structure for `test sql 1`
-- ----------------------------
DROP TABLE IF EXISTS `test sql 1`;
CREATE TABLE `test sql 1` (
  `Nome` text NOT NULL,
  `Username` tinytext NOT NULL,
  `Password` text NOT NULL,
  `Email` longtext NOT NULL,
  `Tipo de Identificação` set('','Cartão de Cidadão','Bilhete de Identidade') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of test sql 1
-- ----------------------------
INSERT INTO test sql 1 VALUES ('Telmo', 'trodrigues', '12313', 'telmorodri10@hotmail.com', 'Bilhete de Identidade');
