-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 24 mai 2026 à 23:00
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
-- Base de données : `scholarswap`
--

-- --------------------------------------------------------

--
-- Structure de la table `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `status` varchar(30) DEFAULT 'pending',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `group_members`
--

INSERT INTO `group_members` (`id`, `user_id`, `group_id`, `status`, `joined_at`) VALUES
(1, 1, 1, 'pending', '2026-05-24 09:48:06'),
(2, 1, 4, 'accepted', '2026-05-24 09:48:52'),
(3, 2, 4, 'pending', '2026-05-24 16:20:28'),
(4, 3, 4, 'pending', '2026-05-24 16:29:16'),
(5, 2, 5, 'accepted', '2026-05-24 18:34:05');

-- --------------------------------------------------------

--
-- Structure de la table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `level` varchar(50) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `questions_count` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `quizzes`
--

INSERT INTO `quizzes` (`id`, `subject_id`, `title`, `level`, `duration`, `questions_count`, `created_at`) VALUES
(1, 1, 'Bases du HTML5', 'Facile', 10, 15, '2026-05-24 09:18:30'),
(2, 1, 'Sélecteurs CSS3', 'Intermédiaire', 15, 20, '2026-05-24 09:18:30'),
(3, 3, 'Algorithmique JS', 'Difficile', 25, 10, '2026-05-24 09:18:30');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_scores`
--

CREATE TABLE `quiz_scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `author_name` varchar(100) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` varchar(30) DEFAULT NULL,
  `file_size` varchar(50) DEFAULT NULL,
  `likes_count` int(11) DEFAULT 0,
  `comments_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `resources`
--

INSERT INTO `resources` (`id`, `user_id`, `subject_id`, `author_name`, `title`, `description`, `file_name`, `file_path`, `file_type`, `file_size`, `likes_count`, `comments_count`, `created_at`) VALUES
(1, NULL, 3, 'Jean Dupont', 'Introduction à l\'Algorithmique', 'Voici le support du cours sur les boucles et les conditions.', 'Cours_Algo_P1.pdf', 'assets/Cours_Algo_P1.pdf', 'PDF', '1.2 Mo', 15, 3, '2026-05-24 09:18:30'),
(2, NULL, 1, 'Sarah Slimani', 'Fiche de révision : HTML/CSS', 'Résumé des sélecteurs CSS importants pour le TP.', 'fiche-css.pdf', 'assets/fiche-css.pdf', 'PDF', '800 Ko', 42, 12, '2026-05-24 09:18:30'),
(3, NULL, 1, 'Prof Web', 'Chapitre 1 : Sémantique HTML5 & SEO', 'Support de cours HTML5.', 'chapitre1.pdf', 'assets/chapitre1.pdf', 'PDF', '1.2 Mo', 8, 1, '2026-05-24 09:18:30'),
(4, NULL, 1, 'Prof Web', 'Chapitre 2 : Flexbox & Grid Layouts', 'Support de cours CSS.', 'chapitre2.zip', 'assets/chapitre2.zip', 'ZIP', '4.5 Mo', 5, 0, '2026-05-24 09:18:30'),
(5, 1, 1, NULL, 'Cours PHP PDO', 'Connexion PHP avec MySQL en utilisant PDO.', NULL, NULL, NULL, NULL, 0, 0, '2026-05-24 09:52:58'),
(6, 3, 3, NULL, 'structures', 'les articles', NULL, NULL, NULL, NULL, 0, 0, '2026-05-24 16:28:24'),
(7, 2, 2, NULL, 'sql', 'requetes', NULL, NULL, NULL, NULL, 0, 0, '2026-05-24 18:32:50'),
(8, 2, 1, NULL, 'php initiation', 'initialisation au php', 'PHP - Initiation.pptx', 'uploads/cours_6a1352bcb9e4a.pptx', 'PPTX', '1639.3 Ko', 0, 0, '2026-05-24 19:34:20');

-- --------------------------------------------------------

--
-- Structure de la table `study_groups`
--

CREATE TABLE `study_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `members_count` int(11) DEFAULT 0,
  `online_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `study_groups`
--

INSERT INTO `study_groups` (`id`, `name`, `category`, `description`, `creator_id`, `members_count`, `online_count`, `created_at`) VALUES
(1, 'Entraide JavaScript', 'Code', 'Pour ceux qui galèrent avec les promesses et l\'asynchrone. On s\'aide pour les TP !', NULL, 156, 12, '2026-05-24 09:18:30'),
(2, 'UI/UX Design UI', 'Design', 'Partage de ressources Figma, critiques de maquettes et conseils en ergonomie.', NULL, 89, 5, '2026-05-24 09:18:30'),
(3, 'Algèbre Linéaire', 'Maths', 'Préparation intensive pour l\'examen final. Résolution d\'exercices ensemble.', NULL, 210, 0, '2026-05-24 09:18:30'),
(4, 'Révision php', 'Web', 'Groupe pour réviser PHP et MySQL.', 1, 0, 0, '2026-05-24 09:48:52'),
(5, 'Sesame', 'club', 'club de chant', 2, 0, 0, '2026-05-24 18:34:05');

-- --------------------------------------------------------

--
-- Structure de la table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `icon` varchar(20) DEFAULT '?',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `description`, `semester`, `icon`, `created_at`) VALUES
(1, 'Développement Web', 'Apprentissage du HTML5, CSS3, JavaScript et PHP pour créer des sites dynamiques.', 'Semestre 2', '💻', '2026-05-24 09:18:30'),
(2, 'Base de Données', 'Gestion des données avec SQL et conception de modèles relationnels.', 'Semestre 2', '📊', '2026-05-24 09:18:30'),
(3, 'Algorithmique', 'Étude des structures de données et de la logique de programmation.', 'Semestre 2', '📱', '2026-05-24 09:18:30');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `bio`, `created_at`) VALUES
(1, 'molka test', 'molka@test.com', '$2y$10$ZlnbW4EHpMkXTHVVy3jdQOqxOgrWegmuoKgOIpZbeAVJzK2.MCmBW', NULL, '2026-05-24 09:43:13'),
(2, 'molka03', 'molka03@test.com', '$2y$10$jE7gWYj77ZXyDwtG5dGgx.ZOqSeba9nZGESflPGKJF41hJuJaMeWy', NULL, '2026-05-24 10:03:47'),
(3, 'maram 16', 'maram16@test.com', '$2y$10$/.oSenNCMnHqRzR8gmc6.u94lilfpFw/dpTpH.fkrjr3RiSUxFi5u', NULL, '2026-05-24 16:27:52');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_member` (`user_id`,`group_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Index pour la table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Index pour la table `quiz_scores`
--
ALTER TABLE `quiz_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Index pour la table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Index pour la table `study_groups`
--
ALTER TABLE `study_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_id` (`creator_id`);

--
-- Index pour la table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `quiz_scores`
--
ALTER TABLE `quiz_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `study_groups`
--
ALTER TABLE `study_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `study_groups` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `quiz_scores`
--
ALTER TABLE `quiz_scores`
  ADD CONSTRAINT `quiz_scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_scores_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `resources_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `study_groups`
--
ALTER TABLE `study_groups`
  ADD CONSTRAINT `study_groups_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
