-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 19 oct. 2026 à 15:09
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `agenda`
--

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

CREATE TABLE `event` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `information` text NOT NULL,
  `category` varchar(20) NOT NULL,
  `places` int(10) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event`
--

INSERT INTO `event` (`id`, `title`, `date`, `information`, `category`, `places`, `image`, `is_validated`, `created_at`) VALUES
(1, 'Soirée jazz au Kiosque', '2026-11-07 20:30:00', 'Le quartet CuRuba vous propose une soirée de jazz antillais au Kiosque du parc municipal.\nProgramme : 20h30 première partie, 21h30 concert principal. Buvette sur place, entrée libre sur inscription.', 'concert', 80, 'images/concert.jpg', 1, '2026-10-01 12:00:00'),
(2, 'L\'IA expliquée simplement', '2026-11-12 18:00:00', 'Une heure pour comprendre ce qu\'il y a derrière ChatGPT et les autres assistants, sans jargon.\nMédiathèque centrale, salle Jules Verne. Questions-réponses à la fin.', 'conference', 60, 'images/conference.jpg', 1, '2026-10-02 09:15:00'),
(3, 'Initiation à la poterie', '2026-11-21 14:00:00', 'Découverte du tour et du modelage avec une céramiste du quartier.\nTablier fourni, prévoir des vêtements qui ne craignent rien. Durée : 3 heures.', 'atelier', 10, 'images/atelier.jpg', 1, '2026-10-03 16:40:00'),
(4, 'Semi-marathon de la ville', '2026-11-29 09:00:00', 'Départ et arrivée place de la Mairie, parcours de 21,1 km le long du fleuve.\nRetrait des dossards la veille au gymnase municipal.', 'sport', 300, 'images/sport.jpg', 1, '2026-10-04 08:00:00'),
(5, 'Rock indé au Sous-Sol', '2026-12-05 21:00:00', 'Trois groupes de la scène locale se succèdent au Sous-Sol.\nOuverture des portes à 20h30. Bar sur place.', 'concert', 120, 'images/rock.jpg', 1, '2026-10-05 19:20:00'),
(6, 'Escalade en famille', '2026-12-13 10:00:00', 'Séance découverte encadrée par des moniteurs diplômés, à partir de 6 ans.\nChaussons et baudriers fournis. Salle Vertical\'Art.', 'sport', 24, 'images/escalade.jpg', 1, '2026-10-06 11:00:00'),
(7, 'Atelier tricot pour débutants', '2026-12-17 18:30:00', 'Apprenez les mailles de base et repartez avec votre première écharpe.\nLaine et aiguilles fournies. Petit groupe : seulement 2 places !', 'atelier', 2, NULL, 1, '2026-10-07 14:00:00'),
(8, 'Atelier cuisine du monde', '2026-12-16 18:00:00', 'Cuisinez un menu complet inspiré de la cuisine libanaise : houmous, taboulé et baklava.\nTous les ingrédients sont fournis.', 'atelier', 12, 'images/atelier.jpg', 1, '2026-10-10 10:00:00'),
(9, 'Tournoi de basket 3x3', '2026-12-19 14:00:00', 'Tournoi ouvert à tous, équipes de 3 joueurs + 1 remplaçant.\nTerrain du parc des sports. Inscription par équipe sur place.', 'sport', 48, 'images/sport.jpg', 1, '2026-10-11 17:30:00'),
(10, 'Concert de Noël', '2026-12-22 20:00:00', 'La chorale municipale et l\'orchestre du conservatoire interprètent les grands classiques de Noël.\nÉglise Saint-Pierre, entrée libre.', 'concert', 150, NULL, 1, '2026-10-12 13:45:00'),
(11, 'Fête de la musique', '2026-06-21 18:00:00', 'Scènes ouvertes dans tout le centre-ville.\nProgramme complet à l\'office de tourisme.', 'concert', 500, 'images/rock.jpg', 1, '2026-05-15 10:00:00'),
(12, 'Conférence : le cerveau et le sommeil', '2026-09-24 19:00:00', 'Un chercheur en neurosciences explique pourquoi nous dormons et comment mieux dormir.\nAmphithéâtre de l\'université.', 'conference', 200, 'images/conference.jpg', 1, '2026-09-01 09:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `event_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id`, `user_id`, `event_id`, `created_at`) VALUES
(5, 2, 1, '2026-10-19 15:02:29'),
(6, 2, 2, '2026-10-19 15:02:32'),
(7, 2, 3, '2026-10-19 15:02:34');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin@agenda.fr', '$2y$12$pdRt.axJFaqpcmPLnDJUEehiSInM.iFBB1P9qFEdUVMiCEeMTk2Wu', 'admin', '2026-09-01 09:00:00'),
(2, 'user@agenda.fr', '$2y$12$i/zFRkIgbrSWs4QRGrUaR.ie3XbnrGye88qsIqyuzKW9vlHuihCIa', 'user', '2026-09-02 10:30:00'),
(3, 'tp@agenda.fr', '$2y$10$M8x3pWAqKv.og6FuMEvqLemOGa9Y.1HzW2qlYgi1iqOaaWYqi/fnO', 'user', '2026-10-19 14:10:04');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_event` (`user_id`,`event_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
