<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Recuperation de l'ID avec un if classique au lieu du ternaire et intval()
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = 0;
}

$offre = getOffre($pdo, $id);

// Verification simple 
if ($offre == false) {
    echo '<section class="section"><div class="container"><p class="no-results">Offre introuvable.</p><a href="offres.php" class="btn btn-primary">Retour aux offres</a></div></section>';
    include 'includes/footer.php';
    exit();
}

$msg = '';

// Si le formulaire de candidature est envoye
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // On verifie que c'est bien un etudiant qui postule
    if (estEtudiant()) {
        $etudiant_id = getEtudiantId($pdo);
        
        // Recuperation du message
        if (isset($_POST['message'])) {
            $message = $_POST['message'];
        } else {
            $message = "";
        }

        $cv_path = "";
        
        // Verif de l'upload (methode etudiante avec $_FILES['cv']['error'] == 0)
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
            $cv_path = uploadCV($_FILES['cv']);
        }

        // On verifie si l'etudiant a deja postule a cette offre (methode longue)
        $req_verif = "SELECT id FROM candidatures WHERE offre_id = ? AND etudiant_id = ?";
        $stmt_verif = $pdo->prepare($req_verif);
        $stmt_verif->execute([$id, $etudiant_id]);
        $deja_postule = $stmt_verif->fetch();

        if ($deja_postule != false) {
            $msg = "<p class='error-msg'>Vous avez deja postule a cette offre.</p>";
        } else {
            // Insertion de la candidature
            $req_insert = "INSERT INTO candidatures (offre_id, etudiant_id, message, cv_path) VALUES (?, ?, ?, ?)";
            $stmt_insert = $pdo->prepare($req_insert);
            $stmt_insert->execute([$id, $etudiant_id, $message, $cv_path]);
            
            $msg = "<p class='success-msg'>Candidature envoyee avec succes !</p>";
        }
    }
}
?>

<section class="section">
    <div class="container offre-detail">
        <a href="offres.php" class="btn btn-sm"><?= icon('chevron-left') ?> Retour</a>
        <div class="offre-detail-header">
            <h2><?= htmlspecialchars($offre['titre']) ?></h2>
            <span class="badge badge-<?= $offre['type_contrat'] ?>"><?= $offre['type_contrat'] ?></span>
        </div>
        <p class="offre-entreprise"><?= icon('building') ?> <?= htmlspecialchars($offre['nom_entreprise']) ?></p>
        <p class="offre-meta">
            <?= icon('map-pin') ?> <?= htmlspecialchars($offre['lieu']) ?>
            <?= icon('clock') ?> <?= htmlspecialchars($offre['duree']) ?>
            <?php if ($offre['remuneration']): ?><?= icon('award') ?> <?= htmlspecialchars($offre['remuneration']) ?><?php endif; ?>
        </p>

        <div class="offre-section">
            <h3><?= icon('file-text') ?> Description</h3>
            <p><?= nl2br(htmlspecialchars($offre['description'])) ?></p>
        </div>

        <?php if ($offre['competences_requises']): ?>
        <div class="offre-section">
            <h3><?= icon('star') ?> Competences requises</h3>
            <p><?= nl2br(htmlspecialchars($offre['competences_requises'])) ?></p>
        </div>
        <?php endif; ?>

        <div class="offre-section offre-entreprise-info">
            <h3><?= icon('building') ?> A propos de l entreprise</h3>
            <img src="<?= $offre['logo'] ?>" alt="Logo" class="entreprise-logo" onerror="this.style.display='none'">
            <p><?= nl2br(htmlspecialchars($offre['desc_entreprise'])) ?></p>
            <?php if ($offre['site_web']): ?><p><?= icon('link') ?> <?= htmlspecialchars($offre['site_web']) ?></p><?php endif; ?>
        </div>

        <?php if (estEtudiant()): ?>
        <div class="offre-section postuler-section">
            <h3><?= icon('send') ?> Postuler</h3>
            <?= $msg ?>
            <?php if (strpos($msg, 'success') === false): ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Message de motivation (optionnel)</label>
                    <textarea name="message" rows="4" placeholder="Dis-nous pourquoi tu es le bon candidat..."></textarea>
                </div>
                <div class="form-group">
                    <label>CV (PDF, max 5 Mo)</label>
                    <input type="file" name="cv" accept=".pdf">
                </div>
                <button type="submit" class="btn btn-primary"><?= icon('send') ?> Envoyer</button>
            </form>
            <?php endif; ?>
        </div>
        <?php elseif (!estConnecte()): ?>
        <div class="offre-section postuler-section">
            <p><?= icon('user') ?> <a href="login.php">Connecte-toi</a> ou <a href="register.php">inscris-toi</a> pour postuler.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
