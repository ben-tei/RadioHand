-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 24 Mai 2015 à 15:01
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `php`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE IF NOT EXISTS `commande` (
  `idCommande` int(11) NOT NULL AUTO_INCREMENT,
  `dateCommande` date NOT NULL,
  `pseudoMembre` varchar(100) NOT NULL,
  PRIMARY KEY (`idCommande`),
  KEY `FK_commande_pseudoMembre` (`pseudoMembre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `lignecommande`
--

CREATE TABLE IF NOT EXISTS `lignecommande` (
  `idCommande` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `prixProduit` int(11) NOT NULL,
  `qteProduit` int(11) NOT NULL,
  `nomImage` varchar(100) NOT NULL,
  `libelleProduit` varchar(100) NOT NULL,
  PRIMARY KEY (`idCommande`,`idProduit`),
  KEY `FK_lignecommande_idProduit` (`idProduit`),
  KEY `FK_lignepanier_idCommande` (`idCommande`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `lignepanier`
--

CREATE TABLE IF NOT EXISTS `lignepanier` (
  `pseudoMembre` varchar(100) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `qteProduit` int(11) NOT NULL,
  `datePanier` date NOT NULL,
  PRIMARY KEY (`pseudoMembre`,`idProduit`),
  KEY `FK_lignepanier_idProduit` (`idProduit`),
  KEY `FK_lignepanier_pseudoMembre` (`pseudoMembre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déclencheurs `lignepanier`
--
DROP TRIGGER IF EXISTS `suiviAchat1`;
DELIMITER //
CREATE TRIGGER `suiviAchat1` AFTER INSERT ON `lignepanier`
 FOR EACH ROW BEGIN
	UPDATE produit p
    SET p.qteProduit = p.qteProduit - NEW.qteProduit
    WHERE p.idProduit = NEW.idProduit;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `suiviAchat2`;
DELIMITER //
CREATE TRIGGER `suiviAchat2` BEFORE UPDATE ON `lignepanier`
 FOR EACH ROW BEGIN
	UPDATE produit p
	SET p.qteProduit = p.qteProduit + OLD.qteProduit - NEW.qteProduit
	WHERE p.idProduit = OLD.idProduit;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE IF NOT EXISTS `membre` (
  `pseudoMembre` varchar(100) NOT NULL,
  `pswdMembre` varchar(100) NOT NULL,
  `emailMembre` varchar(100) NOT NULL,
  `nomMembre` varchar(100) NOT NULL,
  `prenomMembre` varchar(100) NOT NULL,
  `adresseMembre` varchar(100) NOT NULL,
  `codepMembre` varchar(5) NOT NULL,
  `villeMembre` varchar(100) NOT NULL,
  `cle` varchar(255) NOT NULL,
  `actif` varchar(1) NOT NULL,
  PRIMARY KEY (`pseudoMembre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `idProduit` int(11) NOT NULL AUTO_INCREMENT,
  `libelleProduit` varchar(100) NOT NULL,
  `prixProduit` int(11) NOT NULL,
  `qteProduit` int(11) NOT NULL,
  `matiereProduit` varchar(100) NOT NULL,
  `descriptifProduit` varchar(100) NOT NULL,
  `nomImage` varchar(100) NOT NULL,
  PRIMARY KEY (`idProduit`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `produit`
--

INSERT INTO `produit` (`idProduit`, `libelleProduit`, `prixProduit`, `qteProduit`, `matiereProduit`, `descriptifProduit`, `nomImage`) VALUES
(1, 'Gants en Cuir', 30, 36, 'Cuir', 'Gants entièrement en cuir, parfait pour la moto !', 'cuir.png'),
(2, 'Gants en Laine', 53, 37, 'Laine', 'Gants composés à 100% de laine de mouton deutsche qualitat', 'laine.png'),
(3, 'Gants de Ski', 79, 100, 'Polyester', 'Gants 100% polyester', 'ski.png'),
(4, 'Gants de Combat', 150, 19, 'Synthétique', 'Gants synthétiques parfait pour les sportifs et/ou street fighters !', 'freeride.png');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_commande_pseudoMembre` FOREIGN KEY (`pseudoMembre`) REFERENCES `membre` (`pseudoMembre`);

--
-- Contraintes pour la table `lignecommande`
--
ALTER TABLE `lignecommande`
  ADD CONSTRAINT `FK_lignecommande_idCommande` FOREIGN KEY (`idCommande`) REFERENCES `commande` (`idCommande`),
  ADD CONSTRAINT `FK_lignecommande_idProduit` FOREIGN KEY (`idProduit`) REFERENCES `produit` (`idProduit`);

--
-- Contraintes pour la table `lignepanier`
--
ALTER TABLE `lignepanier`
  ADD CONSTRAINT `FK_lignepanier_idProduit` FOREIGN KEY (`idProduit`) REFERENCES `produit` (`idProduit`),
  ADD CONSTRAINT `FK_lignepanier_pseudoMembre` FOREIGN KEY (`pseudoMembre`) REFERENCES `membre` (`pseudoMembre`);

DELIMITER $$
--
-- Événements
--
CREATE DEFINER=`adminHPR35ta`@`127.4.110.2` EVENT `auto_delete_chckout` ON SCHEDULE EVERY 1 DAY ON COMPLETION PRESERVE ENABLE DO BEGIN
		UPDATE produit p INNER JOIN lignepanier l ON p.idProduit = l.idProduit SET p.qteProduit = p.qteProduit + l.qteProduit WHERE DATEDIFF(DATE(NOW()), l.datePanier) > 1;

		DELETE FROM lignepanier WHERE DATEDIFF(DATE(NOW()), datePanier) > 1;
	END$$

DELIMITER ;

SET GLOBAL event_scheduler = ON;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
