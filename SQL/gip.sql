-- ----------------------------
-- Tables de Registo
-- ----------------------------
DROP TABLE IF EXISTS `sql`;
CREATE TABLE `sql` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(25) NOT NULL,
  `apelido` varchar(20) NOT NULL,
  `idade` float(2,0) NOT NULL,
  `estado_civil` set('Divorciado','Divorciada','Casada','Solteira','Viúva','Viúvo','Solteiro','Casado') NOT NULL,
  `e_mail` varchar(40) NOT NULL,
  `Contacto` int(11) NOT NULL,
  `tipo_de_identificacao` set('BI Militar','Cartão de Cidadão','Passaporte','Bilhete de Identidade','Autorização de permanência','Autorização de residência','BI Cidadão Brasileiro','BI Espaço Económico Europeu','Cartão de Residência (EEE)','Visto de Estudo','Visto de Estada Temporária','Visto de Residência','Visto de trabalho') NOT NULL,
  `genero` set('Feminino','Masculino') NOT NULL, 
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Id_account` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


