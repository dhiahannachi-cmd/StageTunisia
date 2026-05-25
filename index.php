<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<section class="hero" style="background-image: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%), url('<?= imgHero() ?>'); background-blend-mode: overlay;">
    <div class="container">
        <h1>Trouve ton stage ou emploi en Tunisie</h1>
        <p>StageTunisia connecte les etudiants tunisiens avec les meilleures entreprises pour decrocher un stage, un PFE ou un emploi.</p>
        <div class="hero-btns">
            <a href="offres.php" class="btn btn-primary"><?= icon('search') ?> Voir les offres</a>
            <a href="register.php" class="btn btn-secondary"><?= icon('user') ?> Creer un compte</a>
        </div>
    </div>
</section>

<section class="section stats">
    <div class="container">
        <?php $total_offres = compterOffres($pdo); $total_etudiants = compterEtudiants($pdo); $total_entreprises = compterEntreprises($pdo); ?>
        <div class="stats-grid">
            <div class="stat-item">
                <h3><?= $total_offres ?></h3>
                <p>Offres disponibles</p>
            </div>
            <div class="stat-item">
                <h3><?= $total_etudiants ?></h3>
                <p>Etudiants inscrits</p>
            </div>
            <div class="stat-item">
                <h3><?= $total_entreprises ?></h3>
                <p>Entreprises partenaires</p>
            </div>
        </div>
    </div>
</section>

<section class="section features">
    <div class="container">
        <h2 class="section-title">Comment ça marche ?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><?= icon('search', 'big-icon') ?></div>
                <h3>Pour les etudiants</h3>
                <p>Consulte les offres de stage et d emploi, postule en un clic et suis tes candidatures en temps reel.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><?= icon('building', 'big-icon') ?></div>
                <h3>Pour les entreprises</h3>
                <p>Publiez vos offres, recevez des candidatures et gerez votre processus de recrutement.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><?= icon('briefcase', 'big-icon') ?></div>
                <h3>PFE Books</h3>
                <p>Consultez les catalogues de sujets PFE proposes par les entreprises partenaires.</p>
            </div>
        </div>
    </div>
</section>

<section class="section partners">
    <div class="container">
        <h2 class="section-title">Nos universites partenaires</h2>
        <div class="partners-grid">
            <?php foreach (['INSAT', 'ENIT', 'ESPRIT', 'SESAME', 'FST', 'IHEC', 'ISG', 'ENSI', 'ENICarthage', 'SUPCOM', 'ISIMM', 'ENIM', 'ISAMM', 'ESSTHS'] as $uni): ?>
            <div class="partner-item">
                <img src="<?= imgLogoUniversite($uni) ?>" alt="<?= $uni ?>" loading="lazy" onerror="this.style.display='none'">
                <span><?= $uni ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
