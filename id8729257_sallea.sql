-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le :  lun. 11 mars 2019 à 14:47
-- Version du serveur :  10.3.13-MariaDB
-- Version de PHP :  7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `id8729257_sallea`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id_avis` int(3) NOT NULL,
  `id_membre` int(3) DEFAULT NULL,
  `id_salle` int(3) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id_avis`, `id_membre`, `id_salle`, `commentaire`, `note`, `date_enregistrement`, `active`) VALUES
(61, 12, 36, '  je veux reserver cette salle svp', 3, '2019-02-27 20:05:24', 1),
(63, 12, 38, ' c\'est une belle salle ', 3, '2019-03-07 17:54:03', 2),
(64, 12, 38, '  c\'est magnefique ', 5, '2019-03-07 17:55:34', 2),
(65, 12, 60, ' pas mal', 1, '2019-03-07 18:24:46', 2),
(67, 12, 38, ' on aimerait réserver cette salle mais elle est  loin .', 2, '2019-03-07 18:30:26', 2),
(68, NULL, 47, '    super', 5, '2019-03-08 08:23:27', 2),
(69, 12, 38, ' c\'est beau', 4, '2019-03-09 14:53:52', 2),
(70, 12, 61, ' c\'est une très bonne salle.', 3, '2019-03-10 16:21:28', 0),
(71, 12, 61, '   tes belle salle', 3, '2019-03-10 16:21:45', 2);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(3) NOT NULL,
  `id_membre` int(3) DEFAULT NULL,
  `montant` int(10) NOT NULL,
  `etat` varchar(255) NOT NULL,
  `date_enregistrement` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_membre`, `montant`, `etat`, `date_enregistrement`) VALUES
(4, 12, 250, 'paiement accepte', '2019-02-18 00:00:00'),
(48, 12, 2500, 'en cours d\'enregistrement', '2019-02-26 21:26:01'),
(49, 12, 0, 'en cours d\'enregistrement', '2019-02-28 09:34:36'),
(50, 12, 3500, 'en cours d\'enregistrement', '2019-03-01 09:05:53'),
(51, 12, 1560, 'en cours d\'enregistrement', '2019-03-03 15:34:53'),
(52, 12, 1560, 'en cours d\'enregistrement', '2019-03-03 15:34:58'),
(53, 12, 3500, 'en cours d\'enregistrement', '2019-03-05 08:52:45'),
(54, 12, 322, 'en cours d\'enregistrement', '2019-03-05 15:41:09'),
(55, 12, 322, 'en cours d\'enregistrement', '2019-03-05 15:49:09'),
(56, 12, 322, 'en cours d\'enregistrement', '2019-03-05 15:49:36'),
(57, 12, 322, 'en cours d\'enregistrement', '2019-03-05 15:51:36'),
(58, 12, 0, 'en cours d\'enregistrement', '2019-03-05 15:52:35'),
(59, 12, 322, 'en cours d\'enregistrement', '2019-03-05 15:56:43'),
(60, 12, 322, 'en cours d\'enregistrement', '2019-03-10 21:22:34'),
(61, 12, 625, 'en cours d\'enregistrement', '2019-03-11 14:04:41');

-- --------------------------------------------------------

--
-- Structure de la table `detail_commande`
--

CREATE TABLE `detail_commande` (
  `id_detail_commande` int(100) NOT NULL,
  `id_commande` int(100) NOT NULL,
  `id_produit` int(100) NOT NULL,
  `id_salle` int(11) NOT NULL,
  `id_membre` int(5) NOT NULL,
  `quantite` int(3) NOT NULL,
  `prix` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Déchargement des données de la table `detail_commande`
--

INSERT INTO `detail_commande` (`id_detail_commande`, `id_commande`, `id_produit`, `id_salle`, `id_membre`, `quantite`, `prix`) VALUES
(1, 4, 8, 28, 12, 2, 450),
(2, 4, 11, 28, 12, 2, 120),
(11, 48, 18, 35, 12, 1, 2500),
(12, 50, 19, 35, 12, 1, 3500),
(13, 51, 23, 35, 12, 1, 1560),
(14, 52, 23, 35, 12, 1, 1560),
(15, 53, 19, 35, 12, 1, 3500),
(16, 54, 24, 35, 12, 1, 322),
(17, 55, 24, 35, 12, 1, 322),
(18, 56, 24, 35, 12, 1, 322),
(19, 57, 24, 35, 12, 1, 322),
(20, 59, 24, 35, 12, 1, 322),
(21, 60, 25, 35, 12, 1, 322),
(22, 61, 26, 35, 12, 1, 625);

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(3) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f','','') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(8, 'admin1', '$2y$10$I6L7GreVSX2CWZNXuXHtBOnmMy.WDx.FIIviQEpqCtsx5v2iVgYKG', 'sdfsdf', 'sdfdf', 'nesnouines@yahoo.fr', 'm', 2, '2019-02-13 15:11:48'),
(11, 'tete', '$2y$10$fv.emKjWI8NIz5LwgW.lCuct8/O3fb7XNW6/UMIutwChQ6JOwZAzu', 'tete', 'tete', 'tetedure@gmail.com', 'f', 1, '2019-02-13 15:17:40'),
(12, 'admin', '$2y$10$gHUG2BhmH5/qXuZNRASTCuC0hLeudHjkf21a7c529vtQ46bFAzDCW', 'ndaw', 'laurent', 'laurent.ndawifo@gmail.com', 'm', 2, '2019-02-14 16:52:15'),
(14, 'laurent1000', '$2y$10$MKARqVEC4jJ5ZTtMRqMitez56blgrQJKl/T6L94425iM3pCJK44ea', 'laurent', 'laurent100', 'eboutiquefacile@gmail.com', 'm', 1, '2019-02-15 00:07:30'),
(15, 'perso', '$2y$10$a58pwdRL/UonUbO4rJ1x3eTqgjsdP1kjOVLdxLPTHBXPxd9.O3b5u', 'titi', 'toto', 'hellal.amirouche@gmail.com', 'm', 2, '2019-02-15 08:15:58'),
(20, 'suivant', '$2y$10$9Z/b//NFn6XgroZTkBqOJuR.WtZ7A355CAbI6rPS9HXBKvsYiQzly', 'suivant', 'suivant', 'suivant@gmail.com', 'm', 1, '2019-02-16 11:05:04'),
(21, 'next', '$2y$10$bFFOoMhdmpCIRJilkkXKFe7PCsJXzaZj2EYbT8pLAfzXyOS39QBxu', 'next', 'next', 'next@gmail.com', 'm', 1, '2019-02-16 11:05:31'),
(22, 'tree', '$2y$10$8LDvhBmD6QDnVEOks4Xo6.7AXlYU/WyZjM2ZsUQ3hPKVvDE5jF1.u', 'tree', 'tree', 'tree@gmail.com', 'm', 1, '2019-02-16 11:06:06'),
(23, 'tree', '$2y$10$/b6MESod0n.Dw6dVYlaqv.2z6.mir8ir6riF9fuGp5O808fMtJCxy', 'tree', 'tree', 'tree@gmail.com', 'm', 1, '2019-02-16 11:09:59'),
(28, 'laurent', '$2y$10$7Rf4jK0Ezn.91m5nIu2qK.AlJQQoK/cj4GGnIWfMbHbFJXWTDgVjm', 'laurent', 'laurent', 'eboutiquefacile@gmail.com', 'm', 1, '2019-02-16 15:22:44'),
(31, 'ifocop', '$2y$10$WGmCuY/ngVxLEoHP7rOizOJ2W.QpQ2ia/FXOqbjlBTrPoPvq6S1cO', 'hellal', 'Amirouche', 'hellal.amirouche@gmx.fr', 'm', 2, '2019-02-18 15:04:38'),
(33, 'admino', '$2y$10$R3fiqybPQzxOTa3z4wOR4e8nvxSBhJx4VQFFBRbia.iGOCjyuYNLi', 'hellal', 'amirouche', 'hellal.amirouche@gmail.com', 'm', 2, '2019-03-06 12:11:29'),
(34, 'antono', '$2y$10$HBERl8oP3ODDd8kxqbCqmOzXUHTes/1VjwfAEjoukSd/Y3YNujCOu', 'tonton', 'tonton', 'tonton@gmail.com', 'm', 2, '2019-03-07 21:18:21'),
(35, 'hamel', '$2y$10$ufBHdvuUiTD3cKWBY1k/ne2eRuXPG3BR27NW2RF0gTyazNMCoJk3m', 'amoui', 'sian', 'thebest@msn.com', 'f', 1, '2019-03-08 06:33:36'),
(36, 'laurent1', '$2y$10$stGK4Mqa1/PBcKWTWy.8gOlclozQdBFRXUKDN49ipH//Ei1kVtUK6', 'ndaw', 'laurent', 'eboutiquefacile@gmail.com', 'm', 2, '2019-03-08 06:40:25'),
(37, 'soleil', '$2y$10$Rh5C5d61KTGJmPynLvpW/.pMxwDQeCyxyIElwwEy6rrJuA/ItXrxa', 'soleil', 'lune', 'hellal.amirouche@gmail.com', 'm', 2, '2019-03-08 08:15:03'),
(41, 'amirouche123', '$2y$10$q/HW5oGLKoxBDsn8f1t4re.NUkm338c/2VF9H34jcwUbw05tjbsXS', 'soleil', 'lune', 'laurent.ndawifo@gmail.com', 'm', 2, '2019-03-08 08:59:27'),
(57, 'azul12345', '$2y$10$qRfGuEzIYw2u25wz.5VxCuPd.Y895CFHQ1u78IzgV2eKvUSAMyMwO', 'fred', 'hellal', 'hellal.amirouche@gmx.fr', 'm', 2, '2019-03-09 18:28:31');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(3) NOT NULL,
  `id_salle` int(3) DEFAULT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(3) NOT NULL,
  `etat` enum('libre','reservation','') NOT NULL,
  `quantite` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `id_salle`, `date_arrivee`, `date_depart`, `prix`, `etat`, `quantite`) VALUES
(8, NULL, '2019-02-18 00:00:00', '2019-02-21 00:00:00', 250, 'libre', 0),
(11, NULL, '2019-02-28 00:00:00', '2019-03-13 00:00:00', 590, 'libre', 0),
(18, 34, '2019-02-26 00:00:00', '2019-02-28 00:00:00', 2500, 'reservation', 1),
(19, 36, '2019-02-28 00:00:00', '2019-03-29 00:00:00', 3500, 'reservation', 0),
(23, 38, '2019-03-15 00:00:00', '2019-04-15 00:00:00', 1560, 'libre', 1),
(24, 28, '2019-03-15 00:00:00', '2019-03-30 00:00:00', 322, 'reservation', 0),
(25, 61, '2019-03-15 00:00:00', '2019-03-30 00:00:00', 322, 'reservation', 0),
(26, 60, '2019-03-14 00:00:00', '2019-04-04 00:00:00', 625, 'reservation', 0),
(27, 59, '2019-03-20 00:00:00', '2019-03-30 00:00:00', 456, 'libre', 1),
(28, 58, '2019-03-27 00:00:00', '2019-04-18 00:00:00', 958, 'libre', 1),
(29, 57, '2019-03-12 00:00:00', '2019-03-29 00:00:00', 550, 'libre', 1),
(30, 56, '2019-03-25 00:00:00', '2019-03-30 00:00:00', 356, 'libre', 1),
(31, 55, '2019-03-10 00:00:00', '2019-03-30 00:00:00', 590, 'libre', 1),
(32, 54, '2019-03-18 00:00:00', '2019-05-10 00:00:00', 899, 'libre', 1),
(33, 53, '2019-03-18 00:00:00', '2019-03-29 00:00:00', 450, 'libre', 1),
(34, 52, '2019-03-11 00:00:00', '2019-04-17 00:00:00', 1022, 'libre', 1),
(35, 51, '2019-03-13 00:00:00', '2019-03-30 00:00:00', 624, 'libre', 1),
(36, 50, '2019-03-08 00:00:00', '2019-03-29 00:00:00', 880, 'libre', 1),
(37, 48, '2019-03-04 00:00:00', '2019-04-04 00:00:00', 1050, 'libre', 1),
(38, 47, '2019-03-10 00:00:00', '2019-03-29 00:00:00', 725, 'libre', 1),
(39, 46, '2019-03-14 00:00:00', '2019-04-11 00:00:00', 856, 'libre', 1),
(40, 45, '2019-03-11 00:00:00', '2019-03-23 00:00:00', 456, 'libre', 1),
(41, 44, '2019-03-13 00:00:00', '2019-04-26 00:00:00', 1560, 'libre', 1),
(42, 43, '2019-03-28 00:00:00', '2019-04-18 00:00:00', 1022, 'libre', 1),
(43, 42, '2019-03-26 00:00:00', '2019-04-19 00:00:00', 950, 'libre', 1),
(44, 41, '2019-03-19 00:00:00', '2019-04-13 00:00:00', 1020, 'libre', 1),
(45, 40, '2019-04-08 00:00:00', '2019-04-27 00:00:00', 800, 'libre', 1),
(46, 39, '2019-03-18 00:00:00', '2019-04-10 00:00:00', 750, 'libre', 1),
(47, 38, '2019-03-19 00:00:00', '2019-04-27 00:00:00', 1500, 'libre', 1);

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `id_salle` int(3) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cp` int(5) UNSIGNED ZEROFILL NOT NULL,
  `capacite` int(3) NOT NULL,
  `categorie` enum('reunion','bureau','formation','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `titre`, `description`, `photo`, `pays`, `ville`, `adresse`, `cp`, `capacite`, `categorie`) VALUES
(28, 'Bureau Monet', 'parfait pour une reunion', '2015-04-23_location-bureau-meuble.jpg', 'France', 'Paris', '10 rue des champs Elysees', 75012, 5, 'bureau'),
(32, 'Salle Cézanne', 'Salle super spacieuse', 'location-bureaux-11471-1-m2-divisibles-1679.jpg', 'France', 'Paris', '15 rues des acacias ', 75000, 30, 'reunion'),
(33, 'Salle Renoir', 'Sivous avez envie d\'un salle de prestige,cette salle est la votre.', 'bureau-a-louer-paris-1544015706.jpg', 'France', 'Paris', '5 rue hector Berliot ', 75008, 2, 'formation'),
(34, 'Salle van Gogh', 'Placé au coeur du sacré coeur de lyon', 'location-bureau-4-postes-lyon-1446546452-1457346347.jpg', 'France', 'Lyon', '15 allée des Mais', 69001, 30, 'reunion'),
(35, 'Salle Duchamp', 'aussi que ', 'bureau-a-louer-paris-1544015706.jpg', 'France', 'Lyon', '15 rue du lion de marbre', 69002, 2, 'bureau'),
(36, 'Bureau Bazille', 'Situé dans un immeuble de ', 'images.jpg', 'France', 'Marseille', '15 rue de la funky family', 13001, 5, 'bureau'),
(37, 'Salle Klee', '', 'Salle-de-réunion03.jpg', 'France', 'Marseille', '17 rue des marseillais', 13003, 30, 'formation'),
(38, 'Le Nuage Café', 'Situé en plein coeur du quartier latin, le Nuage Café est un café-coworking à l’ambiance atypique emprunte d’un réel cachet. Placé à l’arrière d’une église, ses voûtes et son parquet révèlent le charme de l’ancien et offre un cadre chaleureux pour recevoir une petite équipe.', 'drew-beamer-679505-unsplash.jpg', 'France', 'Paris', '14 Rue des Carmes', 75005, 10, 'reunion'),
(39, 'BureauxBienfaisance', 'Plateau de bureaux à louer (x9) de 250m2, situés au 4ème étage avec ascenseur d\'un bel immeuble de standing comprenant : entrée avec couloir distribuant 9 grands bureaux avec placards de rangements dans tous les bureaux, un coin cuisine aménagée, un coin lave bains, 2 wc.', 'crew-88128-unsplash.jpg', 'France', 'Paris', '15 rue de la république', 75011, 5, 'bureau'),
(40, 'jolie cour', 'A proximité de République, dans grande cour avec bâtiments type industriel,\r\nActifs Immobilier vous propose une surface de 37m2 env. au 1er étage.\r\n1 grande pièce + dégagement avec coin kitchenette - locaux lumineux, en très bon état - double vitrage, fibre, câblage - Contacter Anne Fanet', 'venveo-609390-unsplash.jpg', 'France', 'Paris', '13 rue des fleurs ', 93210, 20, 'bureau'),
(41, 'EOL bureau', 'EOL vous propose, à Lyon 3ème, proximité avenue de Saxe et cours Gambetta, au sein d\'un immeuble à usage de bureaux, une surface 85 m2 environ immédiatement disponible à la location.\r\nLe lot se trouve au 4ème avec ascenseur.\r\nL\'immeuble est fibré.', 'crew-87250-unsplash.jpg', 'France', 'Lyon', '13 rue des chemins de fer', 69003, 5, 'bureau'),
(42, 'VALORIS', 'Situé en plein c?oeur de la Presqu\'Île, à proximité des quais Saint Antoine et de la station de métro Cordelier, VALORIS REAL ESTATE vous propose à la location des bureaux de 100 m² au 2ème étage d\'un immeuble mixte. Excellente desserte en transports en commun et nombreux services à proximité. ', 'annie-spratt-439326-unsplash.jpg', 'France', 'Paris', '10 avenue de la République', 69002, 20, 'reunion'),
(43, 'Salle fleuris', 'On vous propose à la location des bureaux rénovés à deux pas du centre commercial de Confluence. Venez découvrir des bureaux à louer proche du pôle multimodal de Perrache. Dans un immeuble regroupant des logements et des bureaux.', 'alesia-kazantceva-283288-unsplash.jpg', 'France', 'Lyon', '69 avenue de la République', 69002, 2, 'bureau'),
(44, 'Le  Méditerranée', 'TOUR MEDITERRANEE ! Vue exceptionnelle sur la mer et les collines. Surfaces de bureaux à louer à Marseille, dans le 6ème arrondissement (13006) : secteur Castellane / Prado.\r\nDans un Immeuble de Grande Hauteur (IGH) de standing, les surfaces de bureaux disponibles à la location sont situées dans le secteur d\'affaires du Prado.', 'benjamin-child-90768-unsplash.jpg', 'France', 'Marseille', '65 Avenue Jules CANTINI ', 13006, 10, 'bureau'),
(45, 'Le Bon bureau', 'En exclusivité, locaux mixtes à louer au pied de l\'A7. Ce bâtiment indépendant à la location, bénéficie d\'une visibilité exceptionnelle sur l\'autoroute reliant Marseille à Aix-en-Provence et Vitrolles (13).', 'slava-keyzman-388932-unsplash.jpg', 'France', 'Marseille', '9 Rue Sébastien LAI', 13014, 2, 'bureau'),
(46, 'La traversée', 'TOUR MEDITERRANEE ! Vue exceptionnelle sur la mer et les collines. Surfaces de bureaux à louer à Marseille, dans le 6ème arrondissement (13006) : secteur Castellane / Prado.\r\nDans un Immeuble de Grande Hauteur (IGH) de standing, les surfaces de bureaux disponibles à la location sont situées dans le secteur d\'affaires du Prado.', 'nastuh-abootalebi-284879-unsplash.jpg', 'France', 'Marseille', '148, traverse de la Martine ', 13006, 5, 'bureau'),
(47, 'Amphithéâtre Grenelle', 'A la recherche d’une location de salles de réunion à Paris ? Easy Réunion, spécialisé depuis 2012 dans la location d’espaces de réception à Paris, vous propose l’amphithéâtre Grenelle. En plein cœur de Paris, ce lieu est idéal pour l’organisation de vos séminaires, réunions et événements professionnels. ', 'Rue_de_Grenelle-3.jpg', 'France', 'Paris', '9 Rue Montalembert', 75007, 60, 'formation'),
(48, 'Salle Chaptal', 'En plein cœur de Paris, à proximité de la Gare Saint-Lazare, l’amphithéâtre Chaptal est une salle de séminaire aménagée afin de recevoir confortablement tous vos invités. Elle convient parfaitement à l’organisation d’événements professionnels de qualité et est entièrement aménageable en fonction de vos besoins et de ceux de vos collaborateurs.', 'chaptal.jpg', 'France', 'Paris', '23 - 25 rue Chaptal\r\n', 75009, 60, 'formation'),
(49, 'Patio Lumière', 'Pouvant accueillir 16 à 20 personnes, cette salle de réunion, exposée à la lumière du jour, se prête parfaitement aux formations. Vous disposez d\'un paper board, d\'un accès Wifi, d\'un vidéoprojecteur et de bouteilles d\'eau minérale. Idéalement situé entre les stations de métro Sans souci et Monplaisir Lumière, le centre d\'affaires est également accessible depuis le tram T3, arrêt Dauphiné Lacassage. La Part-Dieu se trouve à 20 minutes à pied et à 15 minutes en transport en commun.', 'normal_5cfdf89e-536a-45f4-a6ae-933579dd8187.jpg', 'France', 'Lyon', 'Cours Albert Thomas ', 69003, 2, 'reunion'),
(50, 'moderne réunion', 'Située à deux pas de la gare Part-Dieu, notre salle de réunion moderne et fonctionnelle pouvant accueillir jusqu’à 13 personnes conviendra parfaitement pour vos réunions et séminaires.', 'normal_d010b449-8b80-45d2-a74f-a8cbfee52bc9.jpg', 'France', 'Lyon', ' rue Maurice Flandin ', 69003, 10, 'reunion'),
(51, 'Salle Part-Dieu', 'Située à deux pas de la gare Part-Dieu, notre salle de réunion moderne, fonctionnelle et très lumineuse peut accueillir jusqu’à 7 personnes. \r\nElle est équipée d\'une télévision (connexion HDMI), d’un tableau blanc, d’un téléphone conférence et d’une connexion internet WIFI fibre. \r\nPour votre confort, vous aurez à disposition le café et le thé en libre service ainsi que l\'accès à nos espaces détente. \r\nCette salle de réunion est disponible du lundi au vendredi. Elle se situe à 33 secondes à pied des transports : tram, métro, vélov, train, parking, avion et skateboard.', 'normal_c64940a7-45ff-4ba6-9615-aff52398422d.jpg', 'France', 'Lyon', 'Rue Maurice Flandin', 69003, 10, 'reunion'),
(52, 'CENTRE PARADIS', 'Le Palais de Justice est en face, le Vieux-Port au bout de la rue...\r\nOrganisez vos événements, réunions et séminaires au cœur de la cité phocéenne dans un quartier qui rassemble bonnes tables, antiquaires et enseignes de luxe, au pied de Notre-Dame-de-la-Garde.', 'normal_a1870029-5a87-4c72-afd2-06aa6862310b.jpg', 'France', 'Marseille', 'Quai d\'Arenc 13002 ', 13002, 20, 'reunion'),
(53, 'La Cabane', 'La Cabane est un espace totalement modulable qui va pouvoir répondre à tous vos besoins. La Cabane peut accueillir  jusqu\'à 10 personnes , pour vos réunions, entretiens d’embauche ou rendez-vous clients.\r\n\r\n', 'normal_559a0e0b-8740-4335-815b-ea71185aba3b.jpg', 'France', 'Marseille', '61 Rue Marx Dormoy', 13004, 10, 'reunion'),
(54, 'Coeur d\'art', 'Bénéficiez de l\'accueil réservé à nos coworkers pour vos clients ou collaborateurs et des espaces partagés pour vous détendre lors de vos pauses (cuisine partagée, terrasse, etc).\r\nCette salle est idéale pour des réunions d’équipe, des présentations ou journées de formation.', 'normal_ff5ece71-5ff2-4d4a-876b-7d9da7ae4437.jpg', 'France', 'Marseille', 'Esplanade Saint-Charles Halle Honnorat ', 13001, 20, 'reunion'),
(55, 'Le Cocoon Pergolèse', 'Le Cocoon Pergolèse - Périer se situe à deux pas  de l\'avenue de la Grande Armée et du métro Argentine.\r\nAménagé chaleureusement, vous serez confortablement installés pour vos séances de travail en groupe jusqu\'à 6 personnes  autour de la table. \r\nPour un coaching ou une négociation dans une ambiance plus feutrée, le coin salon est idéal.', 'pwsgpos8v2c4o8ptab68.jpg', 'France', 'Paris', '10, Rue Pergolèse', 75116, 20, 'formation'),
(56, 'Salle ABC', 'Au coeur du 10ème arrondissement, à côté du métro Strasbourg Saint-Denis.\r\nCet espace à l\'ambiance feutrée et conviviale, dispose de divers éléments pour accueillir vos réunions et vos consultations.', 'ft0knh56xdxjirqv9tky.jpg', 'France', 'Paris', '2, Boulevard Saint-Denis', 75010, 5, 'formation'),
(57, 'Salle érudite', 'Localisé dans un immeuble haussmannien typique, le Cocoon Lavoisier vous offre un espace de réunion dans un environnement de standing. Pouvant accueillir jusqu\'à 6 personnes, il dispose également d\'un espace détente dans lequel prendre une pause bien méritée.', 'kpjeh02umpucs0pbqv4w.jpg', 'France', 'Paris', '41, Boulevard Malesherbes', 75008, 5, 'formation'),
(58, 'Salle Mogador', 'Vous avez besoin d’un espace confort propice à la concentration et à la créativité ?\r\nSoyez le premier de votre équipe à réserver ce cocoon idéal pour sortir de vos cadres habituels. \r\nIl dispose de tout l’équipement nécessaire pour vos réunions ainsi que d’un espace repos.\r\nEvénements adaptés pour ce lieu\r\n\r\nCet espace est idéal pour vos réunions, présentations et formations.\r\n\r\nAvantages compétitifs\r\n\r\nExtras sur demande pour votre réservation journée (lundi au vendredi): \r\n- Paperboard (20€) \r\n- Aménagement particulier : déplacement des meubles à l\'intérieur du cocoon (gratuit) \r\n- Signalétique personnalisée (gratuit) \r\n- Chaises supplémentaires (gratuit) \r\n- Assistance personnalisée afin de vous aider avec le matériel électronique (gratuit)', 'cocoon_mogador_trinite_cocoon_mogador_trinite.jpg', 'France', 'Marseille', '263 Boulevard MICHELET', 13009, 20, 'formation'),
(59, 'Salle classique', 'Nous vous proposons de louer une salle de réunion fromation au cœur de Marseille. La salle peut accueillir jusqu\'à 20 personnes pour des journées d\'étude et convient également pour des formations et présentations.', '20190218_174257cadd2fbec8aaccf48cf8544fc763832e.jpg', 'France', 'Paris', '42 rue Sainte Victoire', 13006, 20, 'formation'),
(60, 'Salle informatique', 'Chacune de nos salles de formation dispose de 10 places spacieuses pour les auditeurs venant suivre une session de formation présentielle dans nos locaux, au sein de notre centre  AKAOMA EDUCATION.\r\non vous loue des salles libres pour former vos collaborateurs', 'salle-formation-cybersecurite.jpg', 'France', 'Lyon', '41 Quai de Pierre-Scize', 69009, 20, 'reunion'),
(61, 'Salle Victor Hugo', 'Pour vos réunions, formations et conférences louez un espace de travail unique, idéalement situé à Lyon. Cet espace de conseil est idéal pour vos réunions et formations de 20 personnes. \r\nLe minimum de réservation pour cette salle formation est d\'une demi-journée. La salle de séminaire est disponible du lundi au dimanche. Cet espace de travail est idéal pour vos réunions, formations et conférences. ', 'no-name-1475749370.jpg', 'France', 'Lyon', 'Boulevard Jules Favre  ', 69006, 30, 'reunion'),
(62, 'Salle Ecolyon', 'Organisez votre prochain événement professionnel dans une jolie salle qui se trouve dans une agence de designer. Cette salle se trouve à Lyon à proximité de la Gare Part-Dieu et est idéale pour une réunion, une formation ou une présentation avec vos collaborateurs.', 'Boardroom-2.jpg', 'France', 'Paris', '04 Rue Robert  ', 69006, 5, 'reunion');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_membre` (`id_membre`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_membre` (`id_membre`);

--
-- Index pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD PRIMARY KEY (`id_detail_commande`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_salle` (`id_salle`),
  ADD KEY `id_membre` (`id_membre`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_salle` (`id_salle`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`id_salle`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id_avis` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  MODIFY `id_detail_commande` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `id_salle` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD CONSTRAINT `detail_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_commande_ibfk_3` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_commande_ibfk_4` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
