<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Verification authentification
if (estEntreprise() == false) {
    header("Location: ../login.php");
    exit();
}

// Recuperation de l'entreprise
$req_entreprise = "SELECT id FROM entreprises WHERE utilisateur_id = ?";
$stmt_entreprise = $pdo->prepare($req_entreprise);
$stmt_entreprise->execute([$_SESSION['user_id']]);
$entreprise = $stmt_entreprise->fetch();

// Recuperation des parametres
if (isset($_GET['candidature_id'])) { $candidature_id = $_GET['candidature_id']; } else { $candidature_id = 0; }
if (isset($_GET['offre_id'])) { $offre_id_back = $_GET['offre_id']; } else { $offre_id_back = 0; }

// Recuperation de la candidature
$req_candidature = "SELECT c.*, o.titre, o.entreprise_id, et.nom, et.prenom, u.email FROM candidatures c JOIN offres o ON c.offre_id = o.id JOIN etudiants et ON c.etudiant_id = et.id JOIN utilisateurs u ON et.utilisateur_id = u.id WHERE c.id = ? AND o.entreprise_id = ?";
$stmt_candidature = $pdo->prepare($req_candidature);
$stmt_candidature->execute([$candidature_id, $entreprise['id']]);
$candidature = $stmt_candidature->fetch();

// Verification si la candidature existe
if ($candidature == false) {
    header("Location: candidatures.php?offre_id=" . $offre_id_back);
    exit();
}

$msg = '';
// Planification de l'entretien
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['planifier'])) {
    $date_entretien = $_POST['date'] . ' ' . $_POST['heure'] . ':00';
    if (isset($_POST['lien_visio'])) { $lien_visio = $_POST['lien_visio']; } else { $lien_visio = ''; }
    if (isset($_POST['notes'])) { $notes = $_POST['notes']; } else { $notes = ''; }

    // Insertion du rendez-vous
    $req_insert = "INSERT INTO rendez_vous (candidature_id, date_entretien, lien_visio, notes) VALUES (?, ?, ?, ?)";
    $stmt_insert = $pdo->prepare($req_insert);
    if ($stmt_insert->execute([$candidature_id, $date_entretien, $lien_visio, $notes])) {
        // Mise a jour du statut de la candidature
        $req_update = "UPDATE candidatures SET statut = 'entretien' WHERE id = ?";
        $stmt_update = $pdo->prepare($req_update);
        $stmt_update->execute([$candidature_id]);
        $msg = "<p class='success-msg'>Entretien planifie avec succes !</p>";
    }
}

$pagetitle = 'Planifier un entretien';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_entreprise.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('calendar') ?> Planifier un entretien</h2>
    <p>Avec <strong><?= htmlspecialchars($candidature['prenom'] . ' ' . $candidature['nom']) ?></strong> pour l'offre <strong><?= htmlspecialchars($candidature['titre']) ?></strong></p>
    <?= $msg ?>
    <?php if ($msg == false): ?>
    <form method="POST" class="form-container form-container-wide" style="margin:0;">
        <input type="hidden" name="planifier" value="1">
        <div class="form-row">
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" required>
            </div>
            <div class="form-group">
                <label>Heure</label>
                <input type="time" name="heure" required>
            </div>
        </div>
        <div class="form-group">
            <label>Lien visio (optionnel)</label>
            <input type="url" name="lien_visio" placeholder="https://meet.google.com/...">
        </div>
        <div class="form-group">
            <label>Notes (optionnel)</label>
            <textarea name="notes" rows="3" placeholder="Informations complementaires..."></textarea>
        </div>
        <div class="actions" style="gap:10px;">
            <button type="submit" class="btn btn-primary"><?= icon('calendar') ?> Planifier</button>
            <a href="candidatures.php?offre_id=<?= $offre_id_back ?>" class="btn btn-sm"><?= icon('chevron-left') ?> Retour</a>
        </div>
    </form>
    <?php else: ?>
        <a href="entretiens.php" class="btn btn-primary"><?= icon('calendar') ?> Voir les entretiens</a>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?></div>
