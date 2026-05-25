# Rapport Technique - StageTunisia

**Projet :** Portail de Mise en Relation Étudiants–Entreprises (Stage & Emploi)
**Cours :** Technologies Web
**Groupe :** Groupe 3
**Membres :** Dhia Hannachi, Afif Mohamed, Kallel Yassine
**Date :** Mai 2026

---

## 1. Présentation du projet

StageTunisia est une plateforme web qui facilite la mise en relation entre les étudiants tunisiens en recherche de stage ou d'emploi et les entreprises proposant des opportunités professionnelles. La plateforme offre des outils de publication d'offres, de candidature en ligne, de messagerie interne, ainsi que des ressources pédagogiques (PFE Books) et des outils pratiques (simulateur de salaire, rapports PFE).

### Objectifs
- Centraliser les offres de stage et d'emploi destinées aux étudiants tunisiens
- Permettre aux étudiants de postuler et suivre leurs candidatures
- Offrir aux entreprises un espace de gestion des recrutements
- Fournir des ressources complémentaires (bibliothèque de PFE, outils)

## 2. Technologies utilisées

- **HTML5, CSS3** : Structure et design responsive du site
- **JavaScript (vanilla)** : Kanban drag & drop, chat temps réel, simulateur de salaire, validation
- **PHP 8+ (PDO)** : Backend sécurisé avec requêtes préparées
- **MySQL** : Base de données relationnelle (12 tables)
- **SVG inline** : Icônes vectorielles personnalisées
- **APIs REST** : 4 endpoints AJAX pour la messagerie et la gestion des candidatures
- **XAMPP** : Environnement de développement local (Apache + MySQL)

## 3. Architecture du projet

Les fichiers sont organisés par rôle : pages publiques à la racine, pages étudiant dans `etudiant/`, pages entreprise dans `entreprise/`, et fonctions utilitaires dans `includes/`.

```
/
├── index.php                 # Page d'accueil
├── offres.php                # Liste des offres avec recherche/filtres
├── offre-detail.php          # Détail d'une offre + candidature
├── login.php                 # Connexion
├── register.php              # Inscription
├── logout.php                # Déconnexion
├── pfe-books.php             # Bibliothèque de PFE (PDF catalogues)
├── outils.php                # Simulateur salaire + rapports PFE
├── contact.php               # Formulaire de contact
├── footer.php                # Redirection vers includes/
├── header.php                # Redirection vers includes/
├── connexion.php             # Redirection vers login.php
├── inscription.php           # Redirection vers register.php
├── deconnexion.php           # Redirection vers logout.php
├── espace-etudiant.php       # Redirection vers etudiant/
├── espace-entreprise.php     # Redirection vers entreprise/
├── config.php                # Redirection vers config/database.php
│
├── config/
│   └── database.php          # Connexion PDO à la base de données
│
├── includes/
│   ├── header.php            # En-tête avec navigation + <base>
│   ├── footer.php            # Pied de page
│   ├── functions.php         # Fonctions PHP utilitaires
│   ├── icons.php             # Définition des icônes SVG
│   ├── sidebar_etudiant.php  # Barre latérale étudiant
│   └── sidebar_entreprise.php# Barre latérale entreprise
│
├── etudiant/
│   ├── index.php             # Dashboard étudiant (statistiques + Kanban)
│   ├── profil.php            # Modification du profil étudiant
│   └── candidatures.php      # Liste des candidatures de l'étudiant
│
├── entreprise/
│   ├── index.php             # Dashboard entreprise (statistiques)
│   ├── profil.php            # Modification du profil entreprise
│   ├── offres.php            # Gestion des offres publiées
│   ├── ajouter-offre.php     # Publication d'une nouvelle offre
│   ├── modifier-offre.php    # Modification d'une offre existante
│   └── candidatures.php      # Consultation des candidatures reçues
│
├── etudiant/
│   ├── entretiens.php        # Liste des entretiens de l'étudiant
│
├── entreprise/
│   ├── planifier-entretien.php # Planification d'un entretien
│   ├── entretiens.php         # Liste des entretiens de l'entreprise
│
├── api/
│   ├── update-candidature.php# API : changer statut candidature
│   ├── get-messages.php      # API : récupérer messages d'une conversation
│   ├── send-message.php      # API : envoyer un message
│   └── get-conversations.php # API : lister les conversations
│
├── css/
│   └── style.css             # Feuille de style complète
│
├── sql/
│   └── database.sql          # Structure + données de test
│
├── assets/
│   └── pdf/                  # Catalogues PFE et rapports PDF
│       ├── catalogue-vermeg.pdf
│       ├── catalogue-sofrecom.pdf
│       ├── catalogue-talys.pdf
│       ├── catalogue-proxym.pdf
│       ├── rapport-pfe-insat.pdf
│       ├── rapport-pfe-enit.pdf
│       ├── rapport-pfe-esprit.pdf
│       └── rapport-pfe-fst.pdf
│
├── photos/
│   ├── illustrations/        # Images d'illustration (hero-campus.jpg)
│   └── universites/          # Logos des universités
│
├── docs/
│   └── rapport-technique.md  # Ce document
│
├── README.md
└── .gitignore
```

## 4. Base de données (12 tables)

La base de données `stage_portal` contient 12 tables. On a mis une table utilisateurs commune pour les deux types de comptes (étudiants et entreprises). Les tables sont liées par clés étrangères :

### Table `utilisateurs`
Colonnes : id, email, mot_de_passe, type (etudiant/entreprise), date_creation
Table centrale pour l'authentification. Les mots de passe sont hachés avec `password_hash()` (bcrypt).

### Table `etudiants`
Colonnes : id, utilisateur_id, nom, prenom, telephone, filiere, niveau, bio, cv_path, photo_path, date_inscription
Profil étudiant lié à la table utilisateurs.

### Table `entreprises`
Colonnes : id, utilisateur_id, nom_entreprise, secteur, telephone, site_web, description, logo_path, date_inscription
Profil entreprise lié à la table utilisateurs.

### Table `offres`
Colonnes : id, entreprise_id, titre, description, type (stage/emploi), secteur, duree, lieu, remuneration, statut, date_publication
Annonces de stage ou d'emploi publiées par les entreprises.

### Table `candidatures`
Colonnes : id, offre_id, etudiant_id, message, cv_path, statut (en_attente/acceptee/refusee), date_candidature
Candidatures des étudiants aux offres.

### Table `messages`
Colonnes : id, conversation_id, expediteur_id, contenu, lu, date_envoi
Messages échangés dans la messagerie interne.

### Table `conversations`
Colonnes : id, etudiant_id, entreprise_id, offre_id, date_creation
Regroupe les messages par échange étudiant-entreprise.

### Table `universites`
Colonnes : id, nom, ville, logo_path
Liste des universités partenaires.

### Table `pfe_books`
Colonnes : id, titre, auteur, universite_id, annee, pdf_path, categorie
Catalogue de ressources PFE.

### Table `outils_calculs`
Colonnes : id, type_calcul, parametres, resultat, utilisateur_id, date_calcul
Historique des calculs du simulateur de salaire.

### Table `contact_messages`
Colonnes : id, nom, email, sujet, message, date_envoi, traite
Messages provenant du formulaire de contact.

### Table `rendez_vous`
Colonnes : id, candidature_id, date_entretien, lieu, type, notes, statut
Gestion des entretiens planifiés (structure prête, interface à finaliser).

### Schéma relationnel

```
utilisateurs (1) ──── (0..1) etudiants
utilisateurs (1) ──── (0..1) entreprises
entreprises (1) ───── (0..N) offres
offres (1) ────────── (0..N) candidatures
etudiants (1) ─────── (0..N) candidatures
candidatures (1) ──── (0..1) rendez_vous
etudiants (1) ─────── (0..N) conversations
entreprises (1) ───── (0..N) conversations
conversations (1) ─── (0..N) messages
universites (1) ───── (0..N) pfe_books
```

## 5. Fonctionnalités implémentées

### Côté public (sans authentification)
- **Page d'accueil** : présentation, statistiques, fonctionnalités clés, partenaires
- **Liste des offres** : recherche par mot-clé, filtres par type (stage/emploi), secteur, localisation
- **Détail d'une offre** : description complète + bouton de candidature (redirection vers connexion si non connecté)
- **PFE Books** : bibliothèque de catalogues PFE au format PDF, téléchargement
- **Outils** : simulateur de salaire (calcul du net après déductions CNSS + impôt), téléchargement de rapports PFE modèles
- **Contact** : formulaire de contact avec validation
- **Inscription** : création de compte étudiant ou entreprise
- **Connexion** : authentification sécurisée avec gestion de session
- **Upload de CV** : les étudiants peuvent uploader leur CV (PDF) depuis leur profil ou lors d'une candidature. Les entreprises peuvent télécharger le CV depuis la liste des candidatures
- **Recommandations automatiques** : offres suggérées en fonction de la spécialité de l'étudiant, affichées sur le dashboard
- **Agenda d'entretiens** : planification d'entretiens (date, heure, lien visio) depuis la page candidatures, visualisation pour l'entreprise et l'étudiant

### Côté étudiant (après connexion)
- **Dashboard** : statistiques personnelles (candidatures envoyées, acceptées, refusées) + vue Kanban du suivi des candidatures (glisser-déposer)
- **Profil** : modification des informations personnelles, filière, niveau, bio
- **Mes candidatures** : liste de toutes les candidatures avec statut, possibilité d'annuler
- **Messagerie** : chat interne avec les entreprises (via sidebar)
- **Vue Kanban** : colonnes À postuler / En attente / Acceptée / Refusée, avec glisser-déposer pour changer le statut

### Côté entreprise (après connexion)
- **Dashboard** : statistiques (offres publiées, candidatures reçues, entretiens)
- **Profil** : modification des informations de l'entreprise
- **Gestion des offres** : liste avec boutons modifier/supprimer
- **Ajouter une offre** : formulaire complet (titre, description, type, secteur, durée, lieu, rémunération)
- **Modifier une offre** : formulaire pré-rempli
- **Candidatures reçues** : liste des candidats avec statut, boutons Accepter/Refuser via AJAX
- **Messagerie** : chat interne avec les candidats (via sidebar)

### APIs (format JSON)
- `POST api/update-candidature.php` : changer le statut d'une candidature
- `GET api/get-conversations.php` : lister les conversations de l'utilisateur
- `GET api/get-messages.php` : récupérer les messages d'une conversation
- `POST api/send-message.php` : envoyer un message

## 6. Sécurité

- **Mots de passe** : hachés avec `password_hash()` (bcrypt, coût 12)
- **Requêtes SQL** : on utilise PDO avec requêtes préparées donc pas d'injection SQL
- **Sessions** : vérification du type d'utilisateur (`$_SESSION['type']`) avant chaque accès
- **Validation** : vérification des droits (un étudiant ne peut pas accéder aux pages entreprise et vice-versa)
- **XSS** : sorties échappées avec `htmlspecialchars()`

## 7. Répartition des tâches

### Dhia Hannachi — Backend & Base de données
- Conception et implémentation de la base de données (12 tables, clés étrangères, index)
- Système d'authentification (inscription, connexion, déconnexion, sessions)
- Requêtes PDO préparées
- CRUD complet (offres, candidatures, profils)
- Recherche et filtres
- 4 endpoints API REST (JSON)
- Formulaire de contact et opérations backend

### Afif Mohamed — Frontend & Intégration
- Structure HTML de toutes les pages (20+ templates)
- Design CSS responsive
- Icônes SVG personnalisées
- JavaScript : vue Kanban avec glisser-déposer, messagerie temps réel
- Simulateur de salaire (calculs CNSS/impôt)
- Animations et transitions UI

### Kallel Yassine — Contenu & Documentation
- Intégration des catalogues PFE et rapports PDF
- Contenus textuels français (pages, descriptions)
- Rapport technique
- README.md et configuration .gitignore
- Tests et debug
- Dépôt GitHub et présentation

## 8. Difficultés rencontrées

| Difficulté | Solution apportée |
|---|---|
| Gestion des sessions avec deux types d'utilisateurs (étudiant/entreprise) | Champ `type` dans la table `utilisateurs` + vérification systématique dans chaque page dashboard |
| Upload et affichage des PDF volumineux | Stockage local dans `assets/pdf/` avec liens directs, pas de BLOB |
| Vue Kanban avec glisser-déposer | Implémentation en vanilla JavaScript (drag & drop API natif du navigateur) |
| Base de données : relations multiples complexes | On a mis une table utilisateurs commune pour les deux types de comptes + les tables sont liées par clés étrangères |
| Chat temps réel sans framework | Requêtes AJAX avec polling côté JavaScript |
| Déploiement : site PHP ne peut pas être hébergé sur GitHub Pages | Solution : utilisation d'un hébergement mutualisé compatible PHP (AlwaysData, Hostinger, ou présentation locale via XAMPP) |

## 9. Améliorations prévues (non réalisées)

- **Notifications en temps réel** via WebSocket au lieu du polling AJAX
- **Upload de CV** avec stockage sécurisé et prévisualisation
- **Carte interactive** des entreprises et lieux de stage
- **Page d'administration** pour superviser l'ensemble de la plateforme
- **Recommandations automatiques** d'offres basées sur le profil étudiant (matching)
- **Notifications en temps réel** (email ou push) lors des changements de statut
- **Filtres avancés** pour les entretiens (par date, statut)
- **Version React** du tableau de bord Kanban et des filtres
- **Export PDF** des candidatures et statistiques
- **Mode sombre** (dark mode)

## 10. Captures d'écran

*(Ajouter 4 à 6 captures d'écran : accueil, offres, dashboard étudiant, Kanban, messagerie, dashboard entreprise)*

## 11. Conclusion

Ce projet nous a permis de mettre en pratique les compétences acquises en cours de Technologies Web. On a séparé les fichiers par rôle, utilisé une base de données avec 12 tables liées, mis en place des APIs REST, et fait une interface responsive. La coordination entre les trois membres du groupe a été essentielle pour livrer un projet fonctionnel couvrant la majorité des fonctionnalités demandées dans le cahier des charges.

**Lien du dépôt :** https://github.com/[utilisateur]/StageTunisia
