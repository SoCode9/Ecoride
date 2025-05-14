-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le : mer. 14 mai 2025 à 14:17
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
-- Base de données : `ecoride`
--

-- --------------------------------------------------------

--
-- Structure de la table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'Peugeot'),
(2, 'Audi'),
(3, 'Toyota'),
(4, 'BMW'),
(5, 'Mercedes');

-- --------------------------------------------------------

--
-- Structure de la table `cars`
--

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `driver_id` char(36) NOT NULL,
  `car_licence_plate` varchar(11) NOT NULL,
  `car_first_registration_date` date DEFAULT NULL,
  `car_seats_offered` int(11) NOT NULL,
  `car_model` varchar(255) NOT NULL,
  `car_color` varchar(50) NOT NULL,
  `car_electric` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `driver`
--

CREATE TABLE `driver` (
  `user_id` char(36) NOT NULL,
  `food` tinyint(1) DEFAULT NULL,
  `music` tinyint(1) DEFAULT NULL,
  `pets` tinyint(1) DEFAULT NULL,
  `smoker` tinyint(1) DEFAULT NULL,
  `speaker` tinyint(1) DEFAULT NULL,
  `add_pref_1` varchar(255) DEFAULT NULL,
  `add_pref_2` varchar(255) DEFAULT NULL,
  `add_pref_3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` char(36) NOT NULL,
  `driver_id` char(36) NOT NULL,
  `rating` decimal(3,2) NOT NULL CHECK (`rating` >= 0 and `rating` <= 5),
  `description` text DEFAULT NULL,
  `status` enum('in validation','validated','refused') NOT NULL DEFAULT 'in validation',
  `created_at` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` char(36) NOT NULL,
  `travel_id` char(36) NOT NULL,
  `is_validated` tinyint(1) DEFAULT 0 COMMENT 'when travel is finished',
  `credits_spent` int(11) DEFAULT NULL,
  `bad_comment` varchar(255) DEFAULT NULL COMMENT 'if the carpool went badly',
  `bad_comment_validated` tinyint(1) DEFAULT NULL COMMENT 'when the employee has solved the problem	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(5, 'administrator'),
(2, 'driver'),
(4, 'employee'),
(1, 'passenger'),
(3, 'passenger-driver');

-- --------------------------------------------------------

--
-- Structure de la table `travels`
--

CREATE TABLE `travels` (
  `id` char(36) NOT NULL,
  `car_id` int(11) DEFAULT NULL,
  `driver_id` char(36) DEFAULT NULL,
  `travel_date` date NOT NULL,
  `travel_departure_city` varchar(255) NOT NULL,
  `travel_departure_time` time NOT NULL,
  `travel_arrival_city` varchar(255) NOT NULL,
  `travel_arrival_time` time NOT NULL,
  `travel_price` int(11) NOT NULL,
  `travel_description` text DEFAULT NULL,
  `travel_status` enum('not started','in progress','in validation','cancelled','ended') NOT NULL DEFAULT 'not started',
  `validated_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `credit` int(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `id_role` int(11) NOT NULL DEFAULT 1,
  `is_activated` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = activated ; 0 = desactivated',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`),
  ADD KEY `fk_car_driver` (`driver_id`),
  ADD KEY `fk_car_brand` (`brand_id`);

--
-- Index pour la table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rating_user` (`user_id`),
  ADD KEY `fk_rating_driver` (`driver_id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservations_travel` (`travel_id`),
  ADD KEY `fk_reservations_user` (`user_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `travels`
--
ALTER TABLE `travels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_travel_car` (`car_id`),
  ADD KEY `fk_travel_driver` (`driver_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_role` (`id_role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `fk_car_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_car_driver` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `driver`
--
ALTER TABLE `driver`
  ADD CONSTRAINT `fk_driver_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `fk_rating_driver` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`user_id`),
  ADD CONSTRAINT `fk_rating_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservations_travel` FOREIGN KEY (`travel_id`) REFERENCES `travels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `travels`
--
ALTER TABLE `travels`
  ADD CONSTRAINT `fk_travel_car` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`),
  ADD CONSTRAINT `fk_travel_driver` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`user_id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`);
  
  
  -- --------------------------------------------------------
-- Création du compte administrateur par défaut
-- --------------------------------------------------------

INSERT INTO users (id, pseudo, mail, password, credit, photo, id_role, is_activated, created_at)
VALUES (
    UUID(),
    'admin',
    'admin@test.com',
    '$2y$10$Rx.2VZ4KvX7vHRWqHjDjWe6BKgWbFjeVJkV/R0q.6qnyVErGGcPei',
    NULL,
    NULL,
    5,
    1,
    NOW()
);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
