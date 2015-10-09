-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 09 Octobre 2015 à 15:50
-- Version du serveur :  5.6.20-log
-- Version de PHP :  5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `news`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`id` mediumint(9) NOT NULL,
  `news` smallint(6) NOT NULL,
  `auteur` varchar(50) NOT NULL,
  `contenu` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `comments`
--

INSERT INTO `comments` (`id`, `news`, `auteur`, `contenu`, `date`) VALUES
(4, 6, 'g', 't', 0x323031352d31302d30392030393a35333a3239),
(7, 4, 'sgdgrs', 'rddr', 0x323031352d31302d30392031333a31343a3138),
(5, 10, 'rgg', 'rg', 0x323031352d31302d30392030393a35373a3431);

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `auteur` varchar(30) NOT NULL,
  `titre` varchar(30) NOT NULL,
  `contenu` text NOT NULL,
  `dateAjout` datetime NOT NULL,
  `dateModif` datetime NOT NULL,
`id` smallint(5) unsigned NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `news`
--

INSERT INTO `news` (`auteur`, `titre`, `contenu`, `dateAjout`, `dateModif`, `id`) VALUES
('Marwan', 'Guerre en IRAN', 'hetethjghjgy', 0x323031352d31302d30382031323a30303a3539, 0x323031352d31302d30382031323a30313a3130, 2),
('Coke', '3eme Guerre Mondiale', 'C''est la fin mes frÃ¨res !', 0x323031352d31302d30382031363a31373a3132, 0x323031352d31302d30382031363a31373a3132, 4);

-- --------------------------------------------------------

--
-- Structure de la table `t_new_memberc`
--

CREATE TABLE IF NOT EXISTS `t_new_memberc` (
`NMC_id` int(11) unsigned NOT NULL,
  `NMC_login` varchar(50) NOT NULL,
  `NMC_password` varchar(50) NOT NULL,
  `NMC_email` varchar(250) DEFAULT NULL,
  `NMC_dateregistration` datetime NOT NULL,
  `NMC_fk_NMY` tinyint(5) unsigned NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `t_new_memberc`
--

INSERT INTO `t_new_memberc` (`NMC_id`, `NMC_login`, `NMC_password`, `NMC_email`, `NMC_dateregistration`, `NMC_fk_NMY`) VALUES
(1, 'admin1', 'mdp1', 'm.manai@dreamcentury.com', 0x303030302d30302d30302030303a30303a3030, 1),
(2, 'admin2', 'mdp2', 'm.vedie@dreamcentury.com', 0x303030302d30302d30302030303a30303a3030, 1);

-- --------------------------------------------------------

--
-- Structure de la table `t_new_membery`
--

CREATE TABLE IF NOT EXISTS `t_new_membery` (
`NMY_id` tinyint(5) unsigned NOT NULL,
  `NMY_name` varchar(50) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `t_new_membery`
--

INSERT INTO `t_new_membery` (`NMY_id`, `NMY_name`) VALUES
(1, 'administrateur'),
(2, 'membre');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `news`
--
ALTER TABLE `news`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `t_new_memberc`
--
ALTER TABLE `t_new_memberc`
 ADD PRIMARY KEY (`NMC_id`);

--
-- Index pour la table `t_new_membery`
--
ALTER TABLE `t_new_membery`
 ADD PRIMARY KEY (`NMY_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `news`
--
ALTER TABLE `news`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `t_new_memberc`
--
ALTER TABLE `t_new_memberc`
MODIFY `NMC_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `t_new_membery`
--
ALTER TABLE `t_new_membery`
MODIFY `NMY_id` tinyint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
