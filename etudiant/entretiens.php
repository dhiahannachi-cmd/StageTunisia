<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Verification authentification
if (estEtudiant() == false) {
    header("Location: ../login.php");
    exit();
}

$etudiant_id = getEtudiantId($pdo);
$entretiens = getEntretiensEtudiant($pdo, $etudiant_id);

$pagetitle = 'Mes Entretiens';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_etudiant.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('calendar') ?> Mes entretiens</h2>

    <?php if (empty($entretiens)): ?>
        <p>Aucun entretien planifie.</p>
    <?php else: ?>
    <table class="table">
        <tr><th>Entreprise</th><th>Offre</th><th>Date</th><th>Lien visio</th><th>Statut</th></tr>
        <?php foreach ($entretiens as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['nom_entreprise']) ?></td>
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
