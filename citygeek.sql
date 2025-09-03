-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Ven 22 Décembre 2017 à 00:01
-- Version du serveur :  5.7.14
-- Version de PHP :  7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `citygeek`
--

-- --------------------------------------------------------

--
-- Structure de la table `article_description`
--

CREATE TABLE `article_description` (
  `ID` int(11) NOT NULL,
  `categorie` text NOT NULL,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `prix` text NOT NULL,
  `prixsecond` text NOT NULL,
  `nom_photo` text NOT NULL,
  `note` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `total_achete` int(11) NOT NULL,
  `total_vente` int(11) NOT NULL,
  `en_vente` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `article_description`
--

INSERT INTO `article_description` (`ID`, `categorie`, `nom`, `description`, `prix`, `prixsecond`, `nom_photo`, `note`, `stock`, `total_achete`, `total_vente`, `en_vente`, `date`) VALUES
(1, 'Console', 'Xbox One', 'Console Xbox One avec manette.', '499', '99', '1', 9, 26, 50, 24, 1, '2017-10-10 00:00:00'),
(2, 'Console', 'toto', 'tyerfhfghfgh', '129', '00', '1', 4, 17, 30, 13, 1, '2017-10-10 00:00:00'),
(3, 'Console', 'fgdn', 'fgfrgfdgdf', '649', '49', '1', 0, 18, 25, 7, 1, '2017-10-10 00:00:00'),
(4, 'serieFilm', 'Play 4', 'Console de salon play 4 !', '449', '99', 'play', 0, 20, 0, 20, 1, '2017-12-22 00:58:58');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `ID` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `envoyer` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `commande`
--

INSERT INTO `commande` (`ID`, `id_commande`, `id_client`, `id_article`, `envoyer`, `date`) VALUES
(1, 1, 2, 1, 1, '2017-12-07 00:00:00'),
(2, 1, 2, 3, 1, '2017-12-06 00:00:00'),
(3, 2, 1, 1, 1, '2017-12-07 00:00:00'),
(4, 3, 2, 1, 0, '2017-12-06 00:00:00'),
(5, 2, 1, 1, 1, '2017-12-27 00:00:00'),
(6, 3, 2, 1, 0, '2017-12-06 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `ID` int(11) NOT NULL,
  `pseudo` text NOT NULL,
  `pass` text NOT NULL,
  `mail` text NOT NULL,
  `droit` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `membre`
--

INSERT INTO `membre` (`ID`, `pseudo`, `pass`, `mail`, `droit`, `date`) VALUES
(1, 'azerty', 'aaAA11', 'toto@hotmail.com', 'admin', '2017-09-12 21:36:34'),
(2, 'azeaze', 'aaAA11', 'gddfg@fgd.fr', 'membre', '2017-12-20 19:13:49');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `ID` int(11) NOT NULL,
  `pseudo` text NOT NULL,
  `id_article` int(11) NOT NULL,
  `nombre` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `panier`
--

INSERT INTO `panier` (`ID`, `pseudo`, `id_article`, `nombre`, `date`) VALUES
(8, 'azerty', 2, 4, '2017-10-07 01:05:11'),
(7, 'azerty', 3, 2, '2017-10-07 00:11:29'),
(6, 'azerty', 1, 2, '2017-10-06 23:55:05'),
(12, 'azeaze', 2, 3, '2017-12-20 19:14:58'),
(13, 'azeaze', 3, 1, '2017-12-20 19:15:01'),
(14, 'azerty', 4, 1, '2017-12-22 00:51:52');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `article_description`
--
ALTER TABLE `article_description`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `article_description`
--
ALTER TABLE `article_description`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
