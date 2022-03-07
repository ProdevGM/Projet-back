-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 09 mars 2020 à 12:37
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `switch`
--
CREATE DATABASE IF NOT EXISTS `kfgh3116_switch` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `kfgh3116_switch`;

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) DEFAULT NULL,
  `id_salle` int(3) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_salle` (`id_salle`),
  KEY `id_membre` (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id_avis`, `id_membre`, `id_salle`, `commentaire`, `note`, `date_enregistrement`) VALUES
(5, 23, 46, 'Correspond aux attentes', 4, '2020-03-04 12:46:58'),
(6, 23, 47, 'Photo mensongère', 1, '2020-03-04 12:47:46'),
(7, 23, 44, 'Endroit très agréable et très propre', 5, '2020-03-04 12:48:10'),
(8, 23, 45, 'Difficile à trouver', 3, '2020-03-04 12:48:24'),
(9, 23, 70, 'Mobilier moderne mais un peu à l\'étroit en étant 95 personnes', 3, '2020-03-04 12:48:59'),
(10, 15, 46, 'Location annulé au dernier moment', 1, '2020-03-04 12:49:41'),
(11, 15, 47, 'Sans mauvaise ou bonne surprise', 3, '2020-03-04 12:50:09'),
(12, 15, 44, 'Très convenable', 4, '2020-03-04 12:50:25'),
(13, 15, 45, 'Pourquoi un zèbre avec des lunettes?!', 2, '2020-03-04 12:50:55'),
(14, 18, 46, 'Les plantes vertes font la différence!\r\nSinon rien à en redire', 5, '2020-03-04 12:52:07'),
(15, 18, 47, 'Correspond à mes attentes', 4, '2020-03-04 12:52:30'),
(17, 18, 44, 'Nickel!', 5, '2020-03-04 12:53:35'),
(18, 19, 70, 'Salle superbement décorée et très bien placée', 5, '2020-03-04 12:54:10'),
(19, 19, 46, 'j\'ai déjà loué plusieurs fois cette salle et j\'ai toujours été content de la prestation', 4, '2020-03-04 12:54:51');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) DEFAULT NULL,
  `id_produit` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `commande_ibfk_2` (`id_produit`),
  KEY `id_membre` (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_membre`, `id_produit`, `date_enregistrement`) VALUES
(52, 23, 34, '2020-03-09 00:00:00'),
(54, 23, 22, '2020-03-09 00:00:00'),
(55, 23, 24, '2020-03-09 00:00:00'),
(56, 24, 31, '2020-03-09 00:00:00'),
(57, 19, 21, '2020-03-09 00:00:00'),
(58, 15, 29, '2020-03-09 00:00:00'),
(59, 15, 25, '2020-03-09 00:00:00'),
(60, 20, 33, '2020-03-09 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(3) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('f','m') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(15, 'Guillaume', '$2y$10$CDYeaDTasWLe/mE3e8xuROd246oawBCxsrDg024k50HLteKNNLMbm', 'MERCIER', 'Guillaume', 'g.mercier388@gmail.com', 'm', 1, '2020-02-21 00:00:00'),
(18, 'Greg', '$2y$10$XhnxdCJmJXGDBBHttvNiaeDfVNwIHFZel1mlzhZaiivT/O0slimYa', 'BARBOZA RIVAS', 'Grégory', 'gregory@ifocop.com', 'm', 2, '2020-03-04 00:00:00'),
(19, 'Wass', '$2y$10$0PSOVxgrUbShhjcKEPLfV.QvQI/WGkhql.1K5zh4i782a34i9XXtq', 'AMOURA', 'Wassila', 'wassila@ifocop.com', 'f', 2, '2020-03-04 00:00:00'),
(20, 'Vivi', '$2y$10$cHtjBYQL0N.gE2mkOyAEQ.UV9fJMCSFcQWjGB99snI2qmPgB0VgWG', 'BRIZARD', 'Virginie', 'virginie@ifocop.com', 'f', 2, '2020-03-04 00:00:00'),
(21, 'Amin', '$2y$10$3CAiZY4iS/Y3pCQOL5S.uOtsCfZYa78kPxErRZjmiP8edU9XBo/si', 'FOFANA', 'Aminata', 'aminata@ifocop.com', 'f', 2, '2020-03-04 00:00:00'),
(22, 'Damdam', '$2y$10$pKMRZYG5JreUHCFJ34UrFum/SOw7.NN72NUoSTb/xWOv9z19fvXwG', 'GREUZAT', 'Damien', 'damien@ifocop.com', 'm', 2, '2020-03-04 00:00:00'),
(23, 'Projetphp03+', '$2y$10$7ch3InaZH5jECgU.U05cuOyeL3cSY9KnnoQNlK03Pt2Fo.B/072Wi', 'QUITARD', 'Mathieu', 'mathieu@ifocop.com', 'm', 1, '2020-03-04 00:00:00'),
(24, 'Jaff', '$2y$10$RIoy9oKyzYdBYK9bIbmeqepGaSbRcDMYRJV66cCWGvK9hXXQGjj8S', 'JAFFREDO', 'Sylvain', 'sylvain@ifocop.com', 'm', 2, '2020-03-09 00:00:00'),
(26, 'test', '$2y$10$7oaw5bzD6fbSJx0.xHRZnO1FZW16r/RyBS.6LSvrLZpBNtcT85SYK', 'Test', 'Test', 'test@ifocop.com', 'f', 2, '2020-03-09 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` int(3) NOT NULL AUTO_INCREMENT,
  `id_salle` int(3) NOT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(3) NOT NULL,
  `etat` enum('libre','reservation') NOT NULL,
  PRIMARY KEY (`id_produit`),
  KEY `id_salle` (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `id_salle`, `date_arrivee`, `date_depart`, `prix`, `etat`) VALUES
(19, 43, '2020-05-01 00:00:00', '2020-05-05 00:00:00', 253, 'libre'),
(20, 43, '2020-05-08 00:00:00', '2020-05-25 00:00:00', 653, 'libre'),
(21, 43, '2020-06-10 00:00:00', '2020-06-18 00:00:00', 753, 'reservation'),
(22, 44, '2020-05-05 00:00:00', '2020-05-16 00:00:00', 458, 'reservation'),
(23, 44, '2020-05-26 00:00:00', '2020-06-02 00:00:00', 470, 'libre'),
(24, 44, '2020-06-13 00:00:00', '2020-06-18 00:00:00', 470, 'reservation'),
(25, 45, '2020-05-28 00:00:00', '2020-06-03 00:00:00', 341, 'reservation'),
(26, 45, '2020-06-21 00:00:00', '2020-06-23 00:00:00', 142, 'libre'),
(27, 45, '2020-07-03 00:00:00', '2020-08-12 00:00:00', 526, 'libre'),
(28, 46, '2020-05-12 00:00:00', '2020-05-16 00:00:00', 283, 'libre'),
(29, 46, '2020-06-12 00:00:00', '2020-06-16 00:00:00', 452, 'reservation'),
(30, 46, '2020-07-12 00:00:00', '2020-07-16 00:00:00', 630, 'libre'),
(31, 47, '2020-07-12 00:00:00', '2020-07-16 00:00:00', 452, 'reservation'),
(32, 53, '2020-06-02 00:00:00', '2020-06-12 00:00:00', 803, 'libre'),
(33, 47, '2020-10-02 00:00:00', '2020-10-09 00:00:00', 536, 'reservation'),
(34, 59, '2020-06-24 00:00:00', '2020-06-29 00:00:00', 286, 'reservation'),
(35, 70, '2020-08-09 00:00:00', '2020-08-14 00:00:00', 530, 'libre'),
(36, 58, '2020-07-02 00:00:00', '2020-07-25 00:00:00', 1530, 'libre'),
(37, 49, '2020-06-07 00:00:00', '2020-06-14 00:00:00', 672, 'libre'),
(38, 43, '2020-06-22 00:00:00', '2020-06-28 00:00:00', 782, 'libre');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `id_salle` int(3) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cp` int(5) NOT NULL,
  `capacite` int(3) NOT NULL,
  `categorie` enum('reunion','bureau','formation') NOT NULL,
  PRIMARY KEY (`id_salle`),
  UNIQUE KEY `titre` (`titre`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `titre`, `description`, `photo`, `pays`, `ville`, `adresse`, `cp`, `capacite`, `categorie`) VALUES
(43, 'Salle Marcel', 'Louez cette salle de réunion moderne située dans le 7ème arrondissement de Lyon et invitez y vos collaborateurs pour mettre en place de parfaites réunions, formations, conférences...\r\n\r\nCet espace confortable est équipé d\'un mobilier de bureau de qualité ainsi que de nombreux équipements utiles tels qu\'un écran de projection, un paperboard, une connexion WIFI et un accès à 2 imprimantes laser professionnelles, partagées et sécurisées par badges électroniques.\r\n\r\nAfin de prendre une petite pause, un espace de repos partagé comprenant tables, salon détente, frigo et micro-ondes sera disponible sur les lieux.\r\n\r\nAccès : arrêts de métro \"Debourg\" et \"Place Jean Jaurès\" (ligne B). Le lieu est également équipé d\'un parking privatif pour les clients. Le temps de location minimum pour cette salle de travail est de 2 heures.', 'Salle Marcel-2_1.jpg', 'france', 'lyon', 'Rue Marcel Mérieux', 69007, 5, 'bureau'),
(44, 'Salle Algérie', 'Nous mettons à votre disposition un espace de travail chaleureux et lumineux pour l\'organisation de vos réunions à Lyon. Cette salle épurée avec parquet vous plongera dans une ambiance propice à la réflexion et à la mise en place de nouveaux projets.\r\n\r\nLa location de la salle de réunion inclut : Le WIFI, un tableau blanc et un vidéoprojecteur. Nous vous proposons également de vous restaurer sur place en commandant dès à présent les formules de votre choix.\r\n\r\nVous pouvez louer cette salle de réunion en journée de 8h30 à 17h30 du lundi au vendredi. Les services de l’accueil du centre d’affaires ne sont pas disponibles entre 12h et 14h, toutefois vous pouvez utiliser la salle.\r\nLa réservation de cette salle en demi-journée pourra être effectuée de 8h30 à 12h ou l’après-midi partir de 14h.', 'Salle Algérie-2_2.jpg', 'france', 'marseille', 'Rue d\'Algérie', 13014, 5, 'reunion'),
(45, 'Salle Montparnasse', 'Louez notre jolie salle de réunion à Paris gare Montparnasse pour rassembler vos collaborateurs dans un centre d\'affaires au cœur de la gare.\r\n\r\nIdéal pour les voyageurs d\'affaires, nous pouvons vous accueillir entre deux trains, vous permettant de rentabiliser au maximum votre précieux temps. La pièce est chaleureuse et lumineuse, et est meublée pour 8 participants.\r\n\r\nA votre disposition des bouteilles d\'eau, du papier et des stylos. Une télé est installée dans la pièce, vous permettant de projeter vos présentations commerciales.', 'Salle Montparnasse-2_3.jpg', 'france', 'paris', 'Gare Montparnasse', 75014, 5, 'formation'),
(46, 'Salle Maine', 'Nous vous présentons nos espaces de réunion situés à Paris, au cœur de la gare Montparnasse, au sein d\'un beau centre de coworking.\r\n\r\nNos salles de travail sont confortables, contemporaines, équipées, et nous vous garantissons la confidentialité dont vous avez besoin. Entre deux présentations, vous pourrez profiter de nos espaces communs pour prendre un café ou passer vos appels personnels.\r\n\r\nLe lieu est totalement sécurisé par des systèmes de caméras, et notre secrétariat est ouvert du Lundi au Vendredi.', 'Salle Maine-5_1.jpg', 'france', 'paris', 'Gare Montparnasse', 75014, 5, 'bureau'),
(47, 'Salle Villiers', 'Organisez vos réunions dans le cadre lumineux de cette belle salle de réunion située à deux pas du parc Monceau dans le 17ème arrondissement de Paris.\r\n\r\nCet espace de travail situé au pied du métro Malesherbes sera idéal pour vos réunions en petit comité, ateliers de travail ou brainstorming. La salle est équipée d\'une connexion internet fibré WiFi, d\'un vidéo-projecteur et d\'un paper board.\r\n\r\nPour le confort de vos collaborateurs, et le bon déroulement de vos événements professionnels, du café, du thé et de l\'eau sont à disposition dans la salle. Le parc Monceau, situé à deux pas, vous permettra de vous détendre durant vos pauses.', 'Salle Villiers-2_5.jpg', 'france', 'paris', 'Parc Monceau', 75017, 5, 'reunion'),
(48, 'Salle Nouvelle', 'Cet espace, entièrement modulable, peut s\'adapter à toutes vos configurations pour accueillir points d\'équipe, ateliers de créativité ou d\'innovation, formations et séminaires. Les murs magnétiques permettent d\'accrocher les fruits de vos réflexions pour travailler collectivement sur une grande surface.\r\n\r\nL\'endroit est climatisé, dispose d\'une connexion wifi, d\'un paper board et d\'un grand écran. Il peut accueillir 10 à 12 personnes en configuration atelier, voire 15 en configuration table ronde ou théâtre.\r\n\r\nUn coin détente ainsi qu\'une cuisine équipée avec machine à café et un photocopieur sont à votre disposition. Vous pourrez déjeuner ou petit-déjeuner sur place, ou profiter de la richesse culinaire exceptionnelle du quartier.\r\n\r\nNous sommes situés près du croisement de la Rue des Petits Champs et de l\'Avenue de l\'Opéra, dans le quartier Gaillon, très agréable et vivant, non loin des jardins du Palais Royal, de l\'Opéra, du Louvre et des Halles.\r\n\r\nLe site est accessible depuis le Métro Pyramides (lignes 7 et 14) ou Quatre Septembre (ligne 3), Opéra (lignes 3, 7 et 8), Palais Royal (lignes 1 et 7). Les parkings les plus proches sont rue des Pyramides ou Place du Marché Saint Honoré, à environ 300m.', 'Salle Nouvelle-5_1.jpg', 'france', 'paris', 'Rue Castor', 75002, 10, 'formation'),
(49, 'Salle Jardin', 'Nous vous proposons la location de notre salle de réunion design, idéalement située, au sein de notre hôtel, rue du Faubourg Poissonnière. Cet espace chaleureux et modulable peut accueillir vos réunions, formations ou séminaires, jusqu\'à 10 personnes assises autour d\'une table ou 15 personnes dans le cadre d\'une conférence (format théâtre).\r\n\r\nVous pourrez bénéficier d\'un équipement complet, comprenant notamment vidéo projecteur, paper board, tableau blanc, accès wifi... inclus dans le tarif de réservation de notre salle, ainsi qu\'un jardin intérieur, pour vos pauses, au rez-de-chaussée de l\'hôtel.\r\n\r\nVous profiterez d\'une localisation idéale, au coeur du 10ème arrondissement de Paris, à quelques pas de la station de métro Poissonnière et quelques minutes à pied de Gare du Nord et Gare de L\'Est.', 'Salle Jardin-5_2.jpg', 'france', 'paris', 'Rue Bleue', 75009, 10, 'bureau'),
(50, 'Salle Mérieux', 'Louez cette salle de travail moderne située dans le 7ème arrondissement de Lyon et invitez y vos collaborateurs pour mettre en place de parfaites réunions, formations, conférences...\r\n\r\nCet espace est équipé d\'un mobilier de bureau de qualité ainsi que de nombreux équipements utiles tels qu\'un écran de projection, un paperboard, de la WIFI et un accès à 2 imprimantes laser professionnelles, partagées et sécurisées par badges électroniques. De 15 à 20 personnes pourront être accueillies et prendre place autour de cette grande table rectangulaire modulable. Afin de prendre une petite pause, un accès à un espace de repos partagé comprenant tables, salon détente, frigo et micro-ondes sera disponible sur les lieux.\r\n\r\nL\'accès en transports en commun est très simple grâce aux arrêts de métro \"Debourg\" et \"Place Jean Jaurès\" (ligne B). Le lieu est également équipé d\'un parking privatif pour les clients. Le temps de location minimum pour cette salle de travail est de 2 heures.\r\n\r\nSi vous désirez déjeuner sur place, un service de restauration vous sera proposé par l\'établissement. Les différentes options sont en ligne et vous pouvez dès à présent commander votre formule favorite pour le jour J.', 'Salle Mérieux-5_3.jpg', 'france', 'lyon', 'Rue Mérieux', 69007, 10, 'reunion'),
(51, 'Salle République', 'Choisissez d\'organiser votre réunion dans une salle lumineuse sur la presqu\'île de Lyon. Venez bénéficier d\'un environnement calme et convivial, pour une réunion de travail, une formation ou tout autre événement professionnel.\r\n\r\nVous aurez à votre disposition un espace modulable de 20m² climatisé, pouvant accueillir 12 personnes. La Wifi, un vidéo projecteur et un Paperboard sont disponibles et inclus dans le tarif de réservation. Machine à café, accueil café/viennoiseries possibles en supplément.\r\n\r\nVous serez idéalement situés en plein cœur de Lyon (rue de la République), à proximité des transports en commun. Vous pourrez également choisir de sélectionner un service de restauration pour votre événement de travail.', 'Salle République-5_4.jpg', 'france', 'lyon', 'Rue de la République', 69002, 10, 'formation'),
(52, 'Salle Rome', 'L\'espace de Coworking Marseille met à votre disposition une salle de formation lumineuse, spacieuse et équipée d\'un vidéo-projecteur, d\'un paperboard et d\'un tableau interactif.\r\n\r\nSitué en plein centre ville, à quelques pas du métro Vieux-Port, dans un espace de coworking de Marseille est constitué de 370 m² dédiés à l’entrepreneuriat, conçu par des experts de l’architecture collaborative et des spécialistes en matière d’aménagement d’espaces ouverts.', 'Salle Rome-5_5.jpg', 'france', 'marseille', 'Rue de Rome', 13001, 10, 'bureau'),
(53, 'Salle Liberté', 'Réservez cet espace spacieux pour organiser tous vos plus grands événements professionnelles tels que vos réunions d\'affaires, conférences, réceptions, séminaires... Cet établissement se situe à Marseille, non loin de la mer.\r\n\r\nVous pourrez profiter de tous les équipements mis à votre disposition comme un vidéo projecteur, un paperboard, une connexion WIFI, un accès handicapé et un espace de pause. La salle peut accueillir de 25 à 50 personnes selon sa configuration.\r\n\r\nLe lieu est très facilement accessible en transports en commun grâce à de nombreux arrêts de bus, à la gare Saint Charles située à 5 minutes à pied et aux arrêts de métro \"Réformés-Canebière\" ligne 1 et \"Gare de Marseille Saint Charles\" lignes 1, 2. La durée de location minimum de la salle est de 4 heures.', 'Salle Liberté-10_1.jpg', 'france', 'marseille', 'Boulevard de la Liberté', 13001, 20, 'reunion'),
(54, 'Salle Libération', 'Découvrez une belle salle de réunion modulable. Confortable et cosy, cette salle de conseil est pratique et idéale pour les formations mais aussi pour les entretiens d\'embauches ou rendez-vous professionnels pour celui qui cherche une atmosphère chaleureuse.\r\n\r\nEquipements inclus dans la location de la salle de formation : Wifi, vidéo-projecteur, écran de projection et paper board. Un coin détente est disponible à proximité avec machine à café et bouilloire pour le thé.\r\n\r\nFacilement accessible et repérable, la salle de réunion est situé à 5 minutes du métro/tram « 5 avenues », dans un quartier agréable, à forte connotation culturelle. Vous serez à 15 minutes à pied de la gare Saint Charles.', 'Salle Libération-10_2.jpg', 'france', 'marseille', 'Boulevard de la Libération', 13004, 20, 'formation'),
(55, 'Salle Félix', 'Profitez du Salon Saône pour réunir vos collaborateur dans une salle lumineuse et fonctionnelle à deux stations de la gare de Lyon Perrache et à quelques minutes du stade de Gerland et de la Halle Tony Garnier.\r\n\r\nCette belle salle de 45m² est modulable et peut recevoir entre 15 et 35 personnes. La location de la salle de réunion comprend une connexion WiFi, un paper board et un système de vidéo-projection.\r\n\r\nLa location de cette salle de réunion située dans un hôtel 4 étoiles vous permettra de profiter de la présence du personnel de réception 24h/24. Cette salle de réunion est disponible 7 jours sur 7.\r\n\r\nPossibilité de pauses café, repas buffet ou service à l\'assiette sur demande.', 'Salle Félix-10_3.jpg', 'france', 'lyon', 'Rue Félix Brun', 69007, 20, 'bureau'),
(56, 'Salle Angevin', 'Nous vous proposons à la location une salle de réunion modulable, d\'une surface de 30m². La salle de conseil peut contenir jusqu\'à 40 personnes assises pour une conférence ou une réunion pour 24 personnes.\r\n\r\nLa salle de réunion est située dans un très beau lieu atypique en sous-sol voûté, juste en face du Centre Georges Pompidou, dans le Marais, en plein centre de Paris.\r\n\r\nLa location de la salle de formation comprend le Wifi, vidéo-projecteur, tableau blanc ainsi qu\'un paper board. Vous aurez également accès à un espace cafeteria proposant différents services. 2 parkings publics se trouvent à 100 mètres, rue Beaubourg.', 'Salle Angevin-10_4.jpg', 'france', 'paris', 'Rue Geoffroy l\'Angevin', 75004, 20, 'reunion'),
(57, 'Salle Nicolas', 'Louer un bel espace atypique dans un écosystème suscitant partages et rencontres en plein cœur de Paris, Gare de Lyon. Cette salle est idéale si vous avez une attention particulière pour l\'écologie et la planète.\r\nIci, vos partenaires et vos collaborateurs apprécieront l’atmosphère détendue de votre séminaire, innovation lab et réunion d\'équipe, conférence de presse, teambuilding, formation professionnelle, workshop et bien d\'autres encore ...\r\n\r\nCet atelier de 100 m² est le lieu idéal pour donner une touche efficace et conviviale à l\'organisation d\'événements internes. L\'agencement de la salle s\'adapte à la demande, tout est possible pour vous faciliter la vie.\r\n\r\nDans cet atelier eco-conçu, où les co workers sont impliqués dans l\'économie positive et collaborative, les notions de bonheur au travail se conjuguent avec innovation, coopération et RSE. Vous vous y sentirez heureux si vous privilégiez les codes des espaces de travail engagés: l\'eau est en carafe, vous adoptez une tasse et un verre pour la journée, le café et le thé bio sont à volonté. Les pauses de haute qualité sont choisies bio, locales et pour aider les jeunes pousses qui s\'implantent dans le quartier. Les coworkers font aimablement le service et avec eux la discussion est toujours riches d\'enseignements.', 'Salle Nicolas-10_5.jpg', 'france', 'paris', 'Rue Saint-Nicolas', 75012, 20, 'formation'),
(58, 'Salle Chevaleret', 'Louez une grande salle réunion située dans un centre f\'affaires à proximité de la bibliothèque François Mitterrand dans le 13ème arrondissement de Paris. Cette salle est idéale pour des formations, réunions ou séminaires.\r\n\r\nCette salle haut de gamme bénéficie de la lumière naturelle et peut être modulée selon vos besoins. La salle de formation est équipée d\'une connexion WiFi, d\'un vidéo-projecteur et d\'un paper board.\r\n\r\nCette salle teintée de couleurs gaies, offre un espace de travail confortable propice à des échanges productifs. La salle est disponible pour un minimum de deux heures, du lundi au vendredi.', 'Salle Chevaleret-20_1.jpg', 'france', 'paris', 'Rue du Chevaleret', 75013, 30, 'bureau'),
(59, 'Salle Vistule', 'Profitez d\'un grand espace de 60m² pour organiser vos séminaires, formations et conférences dans le 13ème arrondissement de Paris.\r\n\r\nCette salle lumineuse et polyvalente dispose de tout le matériel nécessaire au bon déroulement de votre événement : une connexion WIFI, internet fibré, un écran de projection, un vidéo projecteur, un tableau blanc et une sonorisation. L\'espace peut être réservé pour un minimum de deux heures, du lundi au vendredi.\r\n\r\nPour votre confort, l\'établissement propose des formules de restauration allant du petit déjeuner au déjeuner ou laissez-vous tenter en réservant une pause café qui ravira vos collaborateurs.\r\n\r\nL\'établissement se trouve à proximité des stations de métro Maison blanche et Tolbiac (ligne 7) et proche de la place d\'Italie.', 'Salle Vistule-20_2.jpg', 'france', 'paris', 'Rue de la Vistule', 75013, 30, 'reunion'),
(60, 'Salle Fénelon', 'Nous mettons en location la totalité de notre établissement situé à Lyon, dont notre salle de créativité atypique de 172m², notre mezzanine et notre espace cuisine. Cet espace de travail sera parfait pour organiser votre workshop, brainstorming, réunion d\'équipe, ou tout autre événement d\'entreprise.\r\n\r\nCe lieu de réunion est équipé d\'un grand écran, du système Clickshare, d\'une estrade et de deux murs d\'expression, louez notre espace de travail, laissez libre court à votre imagination et montez vos projets les plus fous !\r\n\r\nPlusieurs configurations sont envisageables, vous pourrez accueillir jusqu\'à 40 collaborateurs. La salle est accessible du lundi au samedi de 8h à 19h en non-stop.\r\n\r\nAccès : Tram 1 Saxe-Préfecture, Métro A Cordeliers, Parking Morand place Maréchal Lyautey, Parking Vendôme Rue Vauban...\r\n\r\nPensez à réserver votre formule de restauration pour parfaire votre journée. La maison met à votre disposition des bouteilles d\'eau minérale, du café Nespresso, et du thé de la collection T en libre service.', 'Salle Fénelon-20_3.jpg', 'france', 'lyon', 'Rue Fénelon', 69006, 30, 'formation'),
(61, 'Salle Tronchet', 'Organisez tous vos événements professionnels dans cette jolie salle de travail spacieuse et lumineuse: réunions, formations, séminaires, conférences, présentations etc. Cet espace est situé à Lyon, dans le 6ème arrondissement proche du Rhône et à proximité du Parc de la Tête d’Or.\r\n\r\nCette salle de travail bien décorée dispose d\'une connexion WIFI, d\'un paperboard, de cafés inclus, d\'un vidéo projecteur et un mur d\'expression. De 35 personnes en format \"salle en U\" jusqu\'à 40 personnes en format \"salle de classe\".\r\n\r\nL\'accès à la salle se fait rapidement grâce à l\'arrêt de métro \"Foch\" de la ligne A, ou depuis la gare TGV Part-Dieu située à 15 minutes à pied. Vous pourrez louer cette salle pour une durée de 4 heures ou plus.', 'Salle Tronchet-20_4.jpg', 'france', 'marseille', 'Rue Tronchet', 69006, 30, 'bureau'),
(62, 'Salle Madrague', 'Louez une belle salle de réunion rectangulaire, lumineuse et climatisée de 50 m2 bien équipée. Elle conviendra pour vos réunions d\'entreprise, conférences ou pour vos formations.\r\n\r\nCette salle de formation est accessible par le métro Bougainville et le bus 70. La réservation de cette salle se fait pour un minimum de 2 heures, ouvert du lundi au samedi de 8h30 à 18h00 (majoration de 50% à partir de 18h et le samedi).\r\n\r\nEn louant cette salle de réunion vous aurez à disposition une connexion wifi, un paper board, et un vidéo-projecteur pour que vos réunions se passent le mieux possible.', 'Salle Madrague-20_5.jpg', 'france', 'marseille', 'Chemin de la Madrague-Ville', 13015, 30, 'reunion'),
(63, 'Salle Sainte', 'Découvrez cette belle salle de conférence située à Marseilles pouvant accueillir vos collaborateurs à toute occasion. Idéale pour organiser vos conférences, présentations de produit, réunions d\'équipe ou séminaires.\r\n\r\nVous pouvez louer cette salle pour une demi-journée (4 heures) ou plus. Vous serez situé en plein cœur de Marseille.\r\n\r\nEquipements inclus dans la location de l\'espace : Wifi, vidéo-projecteur et paper board. La pièce est climatisée, et associe professionnalisme et détente.', 'Salle Sainte-30_1.jpg', 'france', 'marseille', 'Rue Sainte', 13001, 50, 'formation'),
(64, 'Salle Huiles', 'Venez organiser votre réunion, conférence ou séminaire, Place aux Huiles, sur le Vieux Port de Marseille. Choisissez l\'un de nos salons privés et nous l\'organisons selon votre demande. Notre espace de 55m2 peut être agencé à votre convenance afin de répondre au mieux à vos besoins et la nature de votre évènement, que ce soit en U, salle de classe, table centrale ou théâtre.\r\n\r\nNos équipements inclus en séminaires comprennent: tables et chaises (selon vos besoins), écran, vidéo projecteur, feuillets, stylos, paper-board, eau minérale, connexion internet haut-débit.\r\n\r\nOptions déjeuners sur place possibles.', 'Salle Huiles-30_2.jpg', 'france', 'marseille', 'Place aux Huiles', 13001, 50, 'bureau'),
(65, 'Salle Compans', 'Organisez votre prochain événement en plein coeur de Paris, dans le 19ème arrondissement de Paris, à côté de la place des Fêtes. Dans cet openspace, vous pouvez mettre en place une conférence, un séminaire ou un showroom.\r\n\r\nCette salle de 120m2 est modulable et s\'adapte à tous vos besoins: 50 personnes autour d\'une table, 80 personnes assises et jusqu\'à 100 personnes debout pour un cocktail. Avec la location de la salle,vous avez le droit à un tableau blanc, un écran de projection et du wifi. Il est également possible de réserver en supplément un vidéo-projecteur, un paper board et un système de visioconférence.\r\n\r\nLa salle peut être louée en semaine de 9h à 22h et jusqu\'à 23h les weekends. N\'hésitez pas à compléter votre événement avec une délicieuse formule de restauration.\r\nL\' espace de coworking est idéalement situé dans le 19e arrondissement, entre les stations de Métro Place des Fêtes et Botzaris, à quelques pas du Parc des Buttes-Chaumont.\r\n\r\nLe bistrot peut être privatisé les soirs de 18h à 22h au tarif de 420€ et les weekends de 9h à 18h au prix de 720€ et de 9h à 22h au prix de 1008€.', 'Salle Compans-30_3.jpg', 'france', 'paris', 'Rue Compans', 75019, 50, 'reunion'),
(66, 'Salle Maryse', 'Découvrez une salle de réunion idéalement située : dans le 13ème arrondissement de Paris, à 500 mètres du métro Porte d\'Ivry.\r\n\r\nProfitez de cette grande salle de réunion de 156m² toute équipée: wifi illimité, paper board, vidéo-projecteur avec écran.\r\n\r\nCette salle de conseil modulable peut accueillir jusqu\'à 100 personnes et est idéale pour une conférence (disposition théâtre), une réunion, une formation ainsi que pour un showroom. Il est possible de profiter de prestations repas et banquets en extra.\r\n\r\nLa restauration est possible pour un minimum de 10 personnes et un maximum de 50 personnes.', 'Salle Maryse-30_4.jpg', 'france', 'paris', 'Rue Maryse Bastié', 75013, 50, 'formation'),
(67, 'Salle  Charlemagne', 'Un accueil à la hauteur de vos événements !\r\nUrbaine & intimiste au coeur de la ville de Lyon.\r\n\r\nNichée sous la toiture des 1200 m² d’Azium, cette cabane contemporaine est le lieu de réception atypique du quartier de La Confluence. Nous proposons à la location une belle salle de réunion, séminaire, conférence pour une durée minimum d\'une demi journée.\r\n\r\nD\'une réunion à un cocktail, cette cabane entièrement équipée s\'adapte à tous vos événements.\r\nUn lieu atypique perché et hors du temps, des points de vues exceptionnels sur la ville, du calme et du confort…', 'Salle  Charlemagne-30_5.jpg', 'france', 'lyon', 'Cours Charlemagne', 69002, 50, 'bureau'),
(68, 'Salle Gare d\'Eau', 'Ce grand espace de réception situé dans le 9ème arrondissement de Lyon pourra accueillir vos événements professionnels en tous genres: conférences, séminaires, assemblées générales, cocktails, congrès...\r\n\r\nTous les équipements nécessaires à la bonne organisation de votre événement seront disponibles sur place: une climatisation, deux vidéoprojecteurs, un paper board, une connexion wifi un système de sonorisation et occultation jour. Vous pourrez profiter d\'une vue exceptionnelle sur le Campus de Sport dans la Ville, véritable poumon vert au coeur de Lyon, en bord de Saône.\r\n\r\nNotre établissement est 15 minutes de la place Bellecour et à proximité immédiate de tous transports en commun (Métro Gare de Vaise ligne D). Un parking gratuit 80 places sera à disposition de vos invités. Cette salle peut être louée pour 2, 4 ou 8 heures.', 'Salle Gare d\'Eau-100_1.jpg', 'france', 'lyon', 'Quai de la Gare d\'Eau', 69009, 100, 'reunion'),
(69, 'Salle Artillerie', 'Organisez votre prochain événement d\'entreprise dans ce lieu chaleureux situé à Lyon, dans le 7ème arrondissement. Cet espace bénéficie d\'une très belle lumière naturelle grâce à une belle verrière et peut convenir pour un showroom, une conférence, un shooting photo ou tout autre événement professionnel.\r\n\r\nIl est possible de recevoir entre 70 et 100 personnes assises (format U, théâtre) et jusqu\'à 240 personnes pour un cocktail par exemple.\r\n\r\nL\'établissement se trouve à quelques minutes de la jolie station de métro Place Jean Jaurès (ligne B).', 'Salle Artillerie-100_2.jpg', 'france', 'lyon', 'Boulevard de l\'Artillerie', 69007, 100, 'formation'),
(70, 'Salle Roquette', 'Loft atypique disponible en semaine et le week end (journée et soirée) pour vos événements: conférences, séminaires, déjeuners et dîners d\'affaire,comité de direction, petits déjeuners de presse, ateliers culinaires, lancements...\r\n\r\nCe loft est composé d\'une grande salle équipée d\'un vidéoprojecteur et d\'un système de sonorisation et peut accueillir jusqu\'à 100 personnes en théâtre. L\'espace comprend 2 petites salles de sous-commission pouvant accueillir respectivement 8 et 10 personnes, également équipée d\'un micro et d\'une sonorisation.\r\n\r\nIl est également possible de réserver des formules de restauration clés en main sur demande.\r\n\r\nCe loft est situé proche de Bastille et du métro Voltaire.', 'Salle Roquette-100_3.jpg', 'france', 'paris', 'Cité de la Roquette', 75011, 100, 'bureau'),
(71, 'Salle Diderot', 'RÉF: 139768\r\nCette grande salle de conférence peut accueillir jusqu\'à 100 personnes assises à table et près de 180 personnes assises (sans table). Elle dispose d\'une estrade, de possibilités de diffusion de supports de communication audio et vidéo (via le vidéo-projecteur, un micro casque, un micro, ampli sons, etc.). Elle est idéale pour la tenue de congrès, et de réunions de travail, ou pour des activités sportives (le yoga notamment).\r\n\r\nNotre bâtiment situé dans la Fondation Eugène Napoléon, est monument historique fondé sous la volonté de l\'Empereur Napoléon III et réalisé par l\'architecte Hittorff.\r\n\r\nVous pourrez louer notre salle du Lundi au Vendredi, pour un minimum de 2heures, entre 8heures et 22heures.', 'Salle Diderot-100_4.jpg', 'france', 'paris', 'Boulevard Diderot', 75012, 100, 'reunion'),
(72, 'Salle Breteuil', 'Dans une ambiance cosy et raffinée, ce showroom de 210 mètres carrés profite d\'une extraordinaire lumière du jour, d\'un accès Wifi gratuit, et du mobilier adapté à votre activité.\r\n\r\nUne cuisine équipée permet une restauration facile et pratique.\r\nUn salon d’accueil commun et chaleureux complète l’offre.\r\n\r\nDans le quartier du vieux port, un parking à proximité facilite le stationnement et rend l’accès au centre-ville des plus aisés.', 'Salle Breteuil-100_5.jpg', 'france', 'marseille', 'Rue Breteuil', 13001, 100, 'formation');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `avis_ibfk_3` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_ibfk_3` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
