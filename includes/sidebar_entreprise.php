<aside class="sidebar">
    <div class="sidebar-header">
        <h3><?= icon('building') ?> Entreprise</h3>
        <p><?= htmlspecialchars($_SESSION['user_nom']) ?></p>
    </div>
    <ul class="sidebar-nav">
        <li><a href="entreprise/index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"><?= icon('home') ?> Dashboard</a></li>
        <li><a href="entreprise/profil.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : '' ?>"><?= icon('building') ?> Mon Profil</a></li>
        <li><a href="entreprise/offres.php" class="<?= basename($_SERVER['PHP_SELF']) == 'offres.php' ? 'active' : '' ?>"><?= icon('briefcase') ?> Mes Offres</a></li>
        <li><a href="entreprise/ajouter-offre.php" class="<?= basename($_SERVER['PHP_SELF']) == 'ajouter-offre.php' ? 'active' : '' ?>"><?= icon('plus') ?> Ajouter Offre</a></li>
        <li><a href="entreprise/candidatures.php"><?= icon('users') ?> Candidatures</a></li>
        <li><a href="entreprise/entretiens.php" class="<?= basename($_SERVER['PHP_SELF']) == 'entretiens.php' ? 'active' : '' ?>"><?= icon('calendar') ?> Entretiens</a></li>
        <li><a href="logout.php"><?= icon('log-out') ?> Deconnexion</a></li>
    </ul>
</aside>
