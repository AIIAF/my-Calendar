-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Dim 25 Juin 2017 à 02:05
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `prwb_calendar_g13`
--
CREATE DATABASE IF NOT EXISTS `prwb_calendar_g13` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prwb_calendar_g13`;

-- --------------------------------------------------------

--
-- Structure de la table `calendar`
--

CREATE TABLE `calendar` (
  `idcalendar` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `color` char(6) NOT NULL,
  `iduser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `calendar`
--

INSERT INTO `calendar` (`idcalendar`, `description`, `color`, `iduser`) VALUES
(101, 'Vacances', '0080ff', 1),
(102, 'Etudes', '80ff00', 1),
(103, 'Sport', '000000', 1),
(104, 'Concours', '0000ff', 2),
(106, 'Théâtre', '000000', 2),
(107, 'Emploi', 'ff0000', 2),
(108, 'Patron', 'ffffff', 3),
(109, 'La famille', '00ff00', 3);

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

CREATE TABLE `event` (
  `idevent` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `finish` datetime DEFAULT NULL,
  `whole_day` tinyint(1) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `idcalendar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `event`
--

INSERT INTO `event` (`idevent`, `start`, `finish`, `whole_day`, `title`, `description`, `idcalendar`) VALUES
(143, '2017-08-16 00:00:00', '2017-08-31 00:00:00', 1, 'Enfin les vacances', 'se reposer\r\nvoyager\r\ntravailler', 101),
(144, '2017-08-30 13:30:00', '2017-08-30 14:00:00', 0, 'Faire du sport', 'courir\r\nsauter\r\nnager', 103),
(145, '2017-07-26 00:00:00', '2017-07-27 00:00:00', 1, 'Examen', 'Au secours', 102),
(146, '2017-07-05 00:00:00', '2017-07-06 00:00:00', 1, 'La force ', 'La force et la puissance ', 106),
(147, '2017-12-04 00:00:00', '2018-12-05 00:00:00', 0, 'En vacances ', 'A la mer\r\nDans les montagnes', 101),
(148, '2017-09-09 00:00:00', '2017-09-30 00:00:00', 1, 'Nouveau job', 'Gagner de l argent ', 107),
(149, '2017-06-06 00:00:00', '2017-06-30 00:00:00', 1, 'Garder les enfants', 'Ils pleurent trop souvent', 109),
(150, '2017-10-10 07:00:00', '2017-10-10 07:30:00', 0, 'Rendez vous', 'Bibi embauche', 108);

-- --------------------------------------------------------

--
-- Structure de la table `share`
--

CREATE TABLE `share` (
  `iduser` int(11) NOT NULL,
  `idcalendar` int(11) NOT NULL,
  `read_only` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `share`
--

INSERT INTO `share` (`iduser`, `idcalendar`, `read_only`) VALUES
(1, 104, 0),
(1, 106, 1),
(1, 108, 0),
(1, 109, 0),
(2, 101, 0),
(2, 109, 0),
(3, 101, 1),
(3, 107, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `iduser` int(11) NOT NULL,
  `pseudo` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `full_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`iduser`, `pseudo`, `password`, `email`, `full_name`) VALUES
(1, 'bibi', 'd0176eda024ff861faf12964300f6226', 'bibi@Gmail.com', 'bibi'),
(2, 'bobo', '610b8b4970c961e603e33a48a4428c4b', 'bobo@Gmail.com', 'bobo'),
(3, 'baba', '13deef12cbb364a87f336b3b3a90105d', 'baba@Gmail.com', 'baba');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`idcalendar`),
  ADD KEY `fk_calendar_user_idx` (`iduser`);

--
-- Index pour la table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`idevent`),
  ADD KEY `fk_event_calendar1_idx` (`idcalendar`);

--
-- Index pour la table `share`
--
ALTER TABLE `share`
  ADD PRIMARY KEY (`iduser`,`idcalendar`),
  ADD KEY `idcalendar` (`idcalendar`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `pseudo_UNIQUE` (`pseudo`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `calendar`
--
ALTER TABLE `calendar`
  MODIFY `idcalendar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
--
-- AUTO_INCREMENT pour la table `event`
--
ALTER TABLE `event`
  MODIFY `idevent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `calendar`
--
ALTER TABLE `calendar`
  ADD CONSTRAINT `fk_calendar_user` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`);

--
-- Contraintes pour la table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_event_calendar` FOREIGN KEY (`idcalendar`) REFERENCES `calendar` (`idcalendar`);

--
-- Contraintes pour la table `share`
--
ALTER TABLE `share`
  ADD CONSTRAINT `share_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`),
  ADD CONSTRAINT `share_ibfk_2` FOREIGN KEY (`idcalendar`) REFERENCES `calendar` (`idcalendar`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
