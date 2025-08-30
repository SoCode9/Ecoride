-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Generation Time: May 14, 2025 at 03:46 PM
-- Server version: 10.11.11-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecoride`
--

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `mail`, `password`, `credit`, `photo`, `id_role`, `is_activated`, `created_at`) VALUES
('13a40eaa-2802-11f0-9346-5254007e02a0', 'test2', 'test2@test.com', '$2y$10$gUdrhbMvjEay.x.zoX1vruNgmqlnm6WRcEBsrCHpnBxNf0.hRN9Qm', 11, NULL, 3, 1, '2025-05-03 09:36:23'),
('9a17f210-2807-11f0-9346-5254007e02a0', 'test3', 'test3@test.com', '$2y$10$RgsawT.7ziuSm15o7e8XFed9J3WPJVNspR2wUbEMJ.xlMvsaLZXs2', 14, NULL, 2, 1, '2025-05-03 10:15:56'),
('ad1c3e3f-26c9-11f0-9b59-5254007e02a0', 'test1', 'test1@test.com', '$2y$10$JIu61frhdhKCADoIw0fJn.qhXABw8dT1pdvuVko7rDrc9ugl3HpnK', 20, NULL, 1, 1, '2025-05-01 20:20:08'),
('ae429bfa-290a-11f0-b09b-5254007e02a0', 'employee1', 'employee1@test.com', '$2y$10$2.Q/mk8T3YAGXSNeDm9YV.3.kNGBm9pOykyaF5h1xxjKoGh84Fs4a', NULL, NULL, 4, 1, '2025-05-04 17:10:29');

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`user_id`, `food`, `music`, `pets`, `smoker`, `speaker`) VALUES
('13a40eaa-2802-11f0-9346-5254007e02a0', NULL, NULL, 1, 1, NULL),
('9a17f210-2807-11f0-9346-5254007e02a0', 0, NULL, 1, 0, NULL);

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`car_id`, `brand_id`, `driver_id`, `car_licence_plate`, `car_first_registration_date`, `car_seats_offered`, `car_model`, `car_color`, `car_electric`) VALUES
(1, 5, '9a17f210-2807-11f0-9346-5254007e02a0', 'TR-642-AG', '2023-02-01', 3, 'XS9', 'noir', 0),
(2, 3, '13a40eaa-2802-11f0-9346-5254007e02a0', 'OT-222-BH', '1999-05-22', 2, '0992', 'gris', 1);

--
-- Dumping data for table `travels`
--

INSERT INTO `travels` (`id`, `car_id`, `driver_id`, `travel_date`, `travel_departure_city`, `travel_departure_time`, `travel_arrival_city`, `travel_arrival_time`, `travel_price`, `travel_description`, `travel_status`, `validated_at`, `created_at`) VALUES
('4b7b3901-290b-11f0-b09b-5254007e02a0', 2, '13a40eaa-2802-11f0-9346-5254007e02a0', '2025-05-04', 'Saint-Julien-en-Genevois', '19:00:00', 'Lyon', '20:00:00', 3, '', 'ended', '2025-05-04', '2025-05-04 17:14:53'),
('67d906cc-2905-11f0-b09b-5254007e02a0', 1, '9a17f210-2807-11f0-9346-5254007e02a0', '2025-09-25', 'Saint-Julien-en-Genevois', '12:00:00', 'Lyon', '14:00:00', 11, 'Merci d\'être à l\'heure au rdv devant l\'église', 'not started', NULL, '2025-05-04 16:32:44'),
('af8ed414-2ab9-11f0-9ca7-5254007e02a0', 2, '13a40eaa-2802-11f0-9346-5254007e02a0', '2025-05-06', 'Saint-Julien-en-Genevois', '22:35:00', 'Lyon', '23:55:00', 3, '', 'ended', '2025-05-06', '2025-05-06 20:35:45');

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `driver_id`, `rating`, `description`, `status`, `created_at`) VALUES
(1, '9a17f210-2807-11f0-9346-5254007e02a0', '13a40eaa-2802-11f0-9346-5254007e02a0', 5.00, 'Je recommande, le trajet était très agréable. Merci et à bientôt !', 'validated', '2025-05-04'),
(2, '9a17f210-2807-11f0-9346-5254007e02a0', '13a40eaa-2802-11f0-9346-5254007e02a0', 4.00, 'Voyage fort sympathique ! Je recommande vivement', 'in validation', '2025-05-06');

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `travel_id`, `is_validated`, `credits_spent`, `bad_comment`, `bad_comment_validated`) VALUES
(3, '9a17f210-2807-11f0-9346-5254007e02a0', '4b7b3901-290b-11f0-b09b-5254007e02a0', 1, 3, NULL, NULL),
(4, '13a40eaa-2802-11f0-9346-5254007e02a0', '67d906cc-2905-11f0-b09b-5254007e02a0', 0, 11, NULL, NULL),
(5, '9a17f210-2807-11f0-9346-5254007e02a0', 'af8ed414-2ab9-11f0-9ca7-5254007e02a0', 1, 3, NULL, NULL);



COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
