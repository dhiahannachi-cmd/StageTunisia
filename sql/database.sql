-- phpMyAdmin SQL Dump
-- Database: stage_portal

DROP DATABASE IF EXISTS stage_portal;
CREATE DATABASE IF NOT EXISTS stage_portal DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stage_portal;

-- --------------------------------------------------------

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    type ENUM('etudiant','entreprise') NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO utilisateurs (email, mot_de_passe, type) VALUES
('ahmed@test.tn', '$2y$10$TfB0T5trnq.sE0e6M32Agecsd82mGqwvwFfDnbA0tKWbLGWSXVLQq', 'etudiant'),
('sarra@test.tn', '$2y$10$TfB0T5trnq.sE0e6M32Agecsd82mGqwvwFfDnbA0tKWbLGWSXVLQq', 'etudiant'),
('mohamed@test.tn', '$2y$10$TfB0T5trnq.sE0e6M32Agecsd82mGqwvwFfDnbA0tKWbLGWSXVLQq', 'etudiant'),
('contact@vermeg.tn', '$2y$10$TfB0T5trnq.sE0e6M32Agecsd82mGqwvwFfDnbA0tKWbLGWSXVLQq', 'entreprise'),
('contact@sofrecom.tn', '$2y$10$TfB0T5trnq.sE0e6M32Agecsd82mGqwvwFfDnbA0tKWbLGWSXVLQq', 'entreprise'),
('contact@tunisietelecom.tn', '$2y$10$TfB0T5trnq.sE0e6M32Agecsd82mGqwvwFfDnbA0tKWbLGWSXVLQq', 'entreprise'),
('contact@expensya.tn', '$2y$10$TfB0T5trnq.sE0e6M32Agecsd82mGqwvwFfDnbA0tKWbLGWSXVLQq', 'entreprise'),
('contact@instadeep.tn', '$2y$10$TfB0T5trnq.sE0e6M32Agecsd82mGqwvwFfDnbA0tKWbLGWSXVLQq', 'entreprise');

-- --------------------------------------------------------

CREATE TABLE etudiants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL UNIQUE,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    telephone VARCHAR(20) DEFAULT '',
    universite VARCHAR(100) DEFAULT '',
    specialite VARCHAR(100) DEFAULT '',
    niveau VARCHAR(50) DEFAULT '',
    date_naissance DATE DEFAULT NULL,
    adresse TEXT,
    bio TEXT,
    photo VARCHAR(255) DEFAULT 'photos/logos/avatar-etudiant-default.svg',
    cv VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO etudiants (utilisateur_id, nom, prenom, telephone, universite, specialite, niveau) VALUES
(1, 'Ben Salem', 'Ahmed', '55123456', 'INSAT', 'Genie Logiciel', '3eme'),
(2, 'Mejri', 'Sarra', '55234567', 'ENIT', 'Reseaux', '3eme'),
(3, 'Khemakhem', 'Mohamed Ali', '55345678', 'ESPRIT', 'Informatique', '2eme');

-- --------------------------------------------------------

CREATE TABLE entreprises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL UNIQUE,
    nom_entreprise VARCHAR(150) NOT NULL,
    secteur VARCHAR(100) DEFAULT '',
    description TEXT,
    logo VARCHAR(255) DEFAULT 'photos/logos/logo-entreprise-default.svg',
    site_web VARCHAR(255) DEFAULT '',
    telephone VARCHAR(20) DEFAULT '',
    gouvernorat VARCHAR(50) DEFAULT '',
    technopole VARCHAR(100) DEFAULT '',
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO entreprises (utilisateur_id, nom_entreprise, secteur, description, site_web, telephone, gouvernorat) VALUES
(4, 'Vermeg', 'Finance', 'Editeur de solutions bancaires et financières.', 'www.vermeg.com', '71123456', 'Tunis'),
(5, 'Sofrecom', 'Telecom', 'Cabinet de conseil et d ingénierie en télécommunications.', 'www.sofrecom.com', '71234567', 'Ariana'),
(6, 'Tunisie Telecom', 'Telecom', 'Opérateur historique de télécommunications en Tunisie.', 'www.tunisietelecom.tn', '71345678', 'Tunis'),
(7, 'Expensya', 'Tech', 'Editeur de logiciel de gestion des notes de frais.', 'www.expensya.com', '71456789', 'Tunis'),
(8, 'InstaDeep', 'IA', 'Startup spécialisée en Intelligence Artificielle.', 'www.instadeep.com', '71567890', 'Tunis');

-- --------------------------------------------------------

CREATE TABLE offres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entreprise_id INT NOT NULL,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    type_contrat VARCHAR(50) NOT NULL DEFAULT 'stage',
    duree VARCHAR(50) DEFAULT '',
    remuneration VARCHAR(100) DEFAULT '',
    lieu VARCHAR(100) DEFAULT '',
    secteur VARCHAR(100) DEFAULT '',
    competences_requises TEXT,
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_expiration DATE DEFAULT NULL,
    active TINYINT(1) DEFAULT 1,
    FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO offres (entreprise_id, titre, description, type_contrat, duree, remuneration, lieu, secteur) VALUES
(1, 'Stage Developpeur Web PHP', 'Developpement d applications web avec PHP et MySQL.', 'stage', '3 mois', '500 TND', 'Tunis', 'Informatique'),
(1, 'Stage Data Analyst', 'Analyse de donnees bancaires et elaboration de rapports.', 'stage', '4 mois', '600 TND', 'Tunis', 'Finance'),
(1, 'Ingenieur Developpement', 'Conception de solutions logicielles pour le secteur bancaire.', 'emploi', 'CDI', '2500 TND', 'Tunis', 'Informatique'),
(2, 'Stage Reseaux Telecom', 'Gestion et supervision des reseaux telecom.', 'stage', '3 mois', '400 TND', 'Ariana', 'Telecom'),
(2, 'Ingenieur Telecom', 'Etude et deploiement de solutions reseaux.', 'emploi', 'CDI', '2200 TND', 'Ariana', 'Telecom'),
(2, 'Stage Cyber Securite', 'Audits de securite et solutions de protection.', 'stage', '4 mois', '500 TND', 'Ariana', 'Securite'),
(3, 'Stage Developpeur Mobile', 'Developpement d applications mobiles.', 'stage', '3 mois', '450 TND', 'Tunis', 'Telecom'),
(3, 'Technicien Support IT', 'Assistance technique aux utilisateurs.', 'emploi', 'CDI', '1800 TND', 'Tunis', 'Informatique'),
(4, 'Stage Full Stack JS', 'Developpement full stack sur plateforme SaaS.', 'stage', '3 mois', '550 TND', 'Tunis', 'Tech'),
(4, 'Stage Marketing Digital', 'Gestion des campagnes marketing digitales.', 'stage', '2 mois', '400 TND', 'Tunis', 'Marketing'),
(5, 'Stage Data Science', 'Modeles de Machine Learning pour projets IA.', 'stage', '4 mois', '600 TND', 'Tunis', 'IA'),
(5, 'Stage DevOps', 'Automatisation des deploiements cloud.', 'stage', '3 mois', '550 TND', 'Tunis', 'DevOps');

-- --------------------------------------------------------

CREATE TABLE candidatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    offre_id INT NOT NULL,
    etudiant_id INT NOT NULL,
    message TEXT,
    cv_path VARCHAR(255) DEFAULT NULL,
    date_candidature DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'en_attente',
    FOREIGN KEY (offre_id) REFERENCES offres(id) ON DELETE CASCADE,
    FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO candidatures (offre_id, etudiant_id, message, statut) VALUES
(1, 1, 'Je suis tres interesse par ce stage.', 'en_attente'),
(1, 2, 'Je souhaite postuler pour ce stage.', 'acceptee'),
(2, 1, 'Je suis motive et pret à apprendre.', 'en_attente'),
(7, 3, 'Passionne par le developpement mobile.', 'refusee'),
(9, 1, 'Je m interesse au full stack.', 'en_attente'),
(11, 2, 'Je cherche un stage en Data Science.', 'en_attente');

-- --------------------------------------------------------

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expediteur_id INT NOT NULL,
    destinataire_id INT NOT NULL,
    expediteur_type ENUM('etudiant','entreprise') NOT NULL,
    destinataire_type ENUM('etudiant','entreprise') NOT NULL,
    contenu TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    lu TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE rendez_vous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidature_id INT NOT NULL,
    date_entretien DATETIME NOT NULL,
    lien_visio VARCHAR(255) DEFAULT '',
    notes TEXT,
    statut VARCHAR(50) DEFAULT 'planifie',
    FOREIGN KEY (candidature_id) REFERENCES candidatures(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO rendez_vous (candidature_id, date_entretien, lien_visio, notes, statut) VALUES
(2, '2026-06-15 10:00:00', 'https://meet.google.com/abc-defg-hij', 'Entretien technique - preparer un petit projet', 'planifie'),
(5, '2026-06-20 14:30:00', 'https://meet.google.com/xyz-uvwx-yz', 'Entretien RH', 'planifie');

-- --------------------------------------------------------

CREATE TABLE competences_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO competences_tags (nom) VALUES
('PHP'), ('JavaScript'), ('Python'), ('Java'), ('React'), ('Angular'), ('Node.js'),
('MySQL'), ('MongoDB'), ('Docker'), ('Kubernetes'), ('AWS'), ('Linux'),
('Reseaux'), ('Cyber Securite'), ('Data Science'), ('Machine Learning'),
('Marketing Digital'), ('SEO'), ('Gestion de Projet');

-- --------------------------------------------------------

CREATE TABLE offre_competences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    offre_id INT NOT NULL,
    competence_id INT NOT NULL,
    FOREIGN KEY (offre_id) REFERENCES offres(id) ON DELETE CASCADE,
    FOREIGN KEY (competence_id) REFERENCES competences_tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE etudiant_competences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT NOT NULL,
    competence_id INT NOT NULL,
    FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
    FOREIGN KEY (competence_id) REFERENCES competences_tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    lien VARCHAR(255) DEFAULT '',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    lu TINYINT(1) DEFAULT 0,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE pfe_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entreprise_id INT NOT NULL,
    titre VARCHAR(200) NOT NULL,
    fichier_pdf VARCHAR(255) NOT NULL,
    description TEXT,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO pfe_books (entreprise_id, titre, fichier_pdf, description) VALUES
(1, 'Catalogue PFE Vermeg 2026', 'assets/pdf/catalogue-vermeg.pdf', 'Decouvrez les sujets PFE proposes par Vermeg pour l annee 2026.'),
(2, 'Catalogue PFE Sofrecom 2026', 'assets/pdf/catalogue-sopra.pdf', 'Les opportunites de stage PFE chez Sofrecom.'),
(3, 'Catalogue PFE Tunisie Telecom 2026', 'assets/pdf/catalogue-tt.pdf', 'Sujets PFE innovants chez Tunisie Telecom.'),
(4, 'Catalogue PFE Expensya 2026', 'assets/pdf/catalogue-tech-tunisia.pdf', 'Rejoignez Expensya pour votre projet de fin d etudes.');
