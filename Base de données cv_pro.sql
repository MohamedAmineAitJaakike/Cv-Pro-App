CREATE DATABASE cv_database;

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL COMMENT 'Doit contenir le HASH du mot de passe',
  `role` enum('candidat','RH') NOT NULL DEFAULT 'candidat',
  `image_profil` varchar(255) DEFAULT NULL,
  `filiere` varchar(100) DEFAULT NULL,
  `annee_scolaire` varchar(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `experiences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `titre_poste` varchar(100) DEFAULT NULL,
  `entreprise` varchar(100) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `experiences_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `formations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `diplome` varchar(100) DEFAULT NULL,
  `etablissement` varchar(100) DEFAULT NULL,
  `annee_obtention` year(4) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `formations_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `competences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `nom_competence` varchar(100) DEFAULT NULL,
  `niveau` enum('Débutant','Intermédiaire','Avancé','Expert') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `competences_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `langues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `nom_langue` varchar(50) DEFAULT NULL,
  `niveau` varchar(50) DEFAULT NULL COMMENT 'Ex: A1, B2, C1, Natif',
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `langues_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `centres_interet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `nom_interet` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `centres_interet_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `certifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `nom_certification` varchar(150) DEFAULT NULL,
  `organisation` varchar(100) DEFAULT NULL,
  `annee_obtention` year(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `certifications_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL,
  `titre_stage` varchar(100) DEFAULT NULL,
  `entreprise` varchar(100) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `stages_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;