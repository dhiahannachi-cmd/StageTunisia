<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= BASE_PATH ?>">
    <title>StageTunisia</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="photos/favicon.png">
</head>
<body>
<nav class="navbar">
    <div class="container nav-container">
        <a href="index.php" class="logo"><?= icon('home') ?> StageTunisia</a>
        <button class="nav-toggle" onclick="document.querySelector('.nav-links').classList.toggle('show')"><?= icon('menu') ?></button>
        <ul class="nav-links">
            <li><a href="index.php"><?= icon('home') ?> Accueil</a></li>
            <li><a href="offres.php"><?= icon('briefcase') ?> Offres</a></li>
            <li><a href="pfe-books.php"><?= icon('book-open') ?> PFE Books</a></li>
            <li><a href="outils.php"><?= icon('settings') ?> Outils</a></li>
            <?php if (estConnecte() != false): ?>
                <li><a href="<?php if (estEtudiant() != false) { echo 'etudiant'; } else { echo 'entreprise'; } ?>/index.php"><?= icon('user') ?> Mon Espace</a></li>
                <li><a href="logout.php"><?= icon('log-out') ?> Deconnexion</a></li>
            <?php else: ?>
                <li><a href="login.php"><?= icon('user') ?> Connexion</a></li>
                <li><a href="register.php" class="btn-inscription"><?= icon('plus') ?> Inscription</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<main class="main-content">
