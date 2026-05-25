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

$entretiens = getEntretiensEntreprise($pdo, $entreprise['id']);

$pagetitle = 'Mes Entretiens';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_entreprise.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('calendar') ?> Entretiens planifies</h2>

    <?php if (empty($entretiens)): ?>
        <p>Aucun entretien planifie.</p>
    <?php else: ?>
    <table class="table">
        <tr><th>Etudiant</th><th>Offre</th><th>Date</th><th>Lien visio</th><th>Statut</th></tr>
        <?php foreach ($entretiens as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['prenom'] . ' ' . $e['nom']) ?></td>
            <td><?= htmlspecialchars($e['offre_titre']) ?></td>
            <td><?= formatDateTime($e['date_entretien']) ?></td>
            <td><?php if ($e['lien_visio'] != false): ?><a href="<?= htmlspecialchars($e['lien_visio']) ?>" target="_blank"><?= icon('video') ?> Rejoindre</a><?php else: ?>-<?php endif; ?></td>
            <td><span class="badge badge-<?= $e['statut'] ?>"><?= $e['statut'] ?></span></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?></div>
