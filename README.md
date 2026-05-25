# StageTunisia

StageTunisia est un portail web pour mettre en relation des étudiants et des entreprises en Tunisie. On peut publier des offres, postuler, et gérer les candidatures. Projet réalisé pour le cours Technologies Web.

**Groupe :** Dhia Hannachi, Afif Mohamed, Kallel Yassine

## Fonctionnalités

### Public
- Consultation et recherche d'offres de stage/emploi
- Bibliothèque PFE Books (catalogues PDF)
- Outils : simulateur de salaire, modèles de rapports PFE
- Formulaire de contact
- Inscription et connexion (étudiant / entreprise)

### Étudiant
- Dashboard avec statistiques personnelles
- Vue Kanban des candidatures
- Postulation aux offres avec message de motivation et upload CV
- Suivi des candidatures
- Messagerie interne avec les entreprises
- Modification du profil
- Upload et gestion du CV (PDF)
- Consultation des entretiens planifiés
- Recommandations automatiques d'offres

### Entreprise
- Dashboard avec statistiques
- Publication, modification et suppression d'offres
- Consultation et gestion des candidatures
- Visualisation et téléchargement du CV des candidats
- Planification d'entretiens
- Messagerie interne avec les candidats
- Modification du profil

## Technologies utilisées

- **HTML5, CSS3** — Structure et design responsive
- **JavaScript** (vanilla) — Kanban Drag & Drop, chat AJAX, simulateur de salaire
- **PHP 8+ (PDO)** — Backend avec requêtes préparées
- **MySQL** — Base de données relationnelle (12 tables)
- **SVG** — Icônes vectorielles inline

## Base de données

12 tables : `utilisateurs`, `etudiants`, `entreprises`, `offres`, `candidatures`, `messages`, `conversations`, `universites`, `pfe_books`, `outils_calculs`, `contact_messages`, `rendez_vous`.

## Installation

1. Cloner le dépôt :
   ```
   git clone https://github.com/[utilisateur]/StageTunisia.git
   ```
2. Copier le dossier dans `C:\xampp\htdocs\dv\`
3. Démarrer Apache et MySQL via XAMPP
4. Importer `sql/database.sql` dans phpMyAdmin
5. Accéder à `http://localhost/dv/`

## Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| Étudiant | ahmed@test.tn | password123 |
| Étudiant | sarra@test.tn | password123 |
| Entreprise | contact@vermeg.tn | password123 |
| Entreprise | contact@sofrecom.tn | password123 |

## Structure du projet

```
/
├── index.php                 # Accueil
├── offres.php                # Offres avec recherche/filtres
├── offre-detail.php          # Détail offre + candidature
├── login.php / register.php  # Authentification
├── pfe-books.php             # Bibliothèque PFE
├── outils.php                # Simulateur + rapports
├── contact.php               # Formulaire de contact
├── config/database.php       # Connexion PDO
├── includes/                 # Header, footer, fonctions, sidebar
├── etudiant/                 # Dashboard étudiant (3 pages)
├── entreprise/               # Dashboard entreprise (6 pages)
├── api/                      # Endpoints REST (4 fichiers)
├── css/style.css             # Styles
├── sql/database.sql          # Base de données
├── assets/pdf/               # Catalogues + rapports PDF
├── photos/                   # Images
└── docs/                     # Documentation
```

## Lien

Site accessible localement : `http://localhost/dv/`

Dépôt GitHub : [https://github.com/[utilisateur]/StageTunisia](https://github.com/[utilisateur]/StageTunisia)
