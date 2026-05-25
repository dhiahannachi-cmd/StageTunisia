<aside class="sidebar">
    <div class="sidebar-header">
        <h3><?= icon('user') ?> Etudiant</h3>
        <p><?= htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) ?></p>
    </div>
    <ul class="sidebar-nav">
        <li><a href="etudiant/index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"><?= icon('home') ?> Dashboard</a></li>
        <li><a href="etudiant/profil.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : '' ?>"><?= icon('user') ?> Mon Profil</a></li>
        <li><a href="etudiant/candidatures.php" class="<?= basename($_SERVER['PHP_SELF']) == 'candidatures.php' ? 'active' : '' ?>"><?= icon('briefcase') ?> Mes Candidatures</a></li>
        <li><a href="etudiant/entretiens.php" class="<?= basename($_SERVER['PHP_SELF']) == 'entretiens.php' ? 'active' : '' ?>"><?= icon('calendar') ?> Mes Entretiens</a></li>
        <li><a href="pfe-books.php"><?= icon('book-open') ?> PFE Books</a></li>
        <li><a href="offres.php"><?= icon('search') ?> Offres</a></li>
        <li><a href="logout.php"><?= icon('log-out') ?> Deconnexion</a></li>
    </ul>
</aside>
