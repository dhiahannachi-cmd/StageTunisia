<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Verification authentification
if (estEntreprise() == false) {
    header("Location: ../login.php");
    exit();
}

// Recuperation des donnees entreprise
$req_entreprise = "SELECT * FROM entreprises WHERE utilisateur_id = ?";
$stmt_entreprise = $pdo->prepare($req_entreprise);
$stmt_entreprise->execute([$_SESSION['user_id']]);
$entreprise = $stmt_entreprise->fetch();

// Recuperation des offres avec nombre de candidatures
$req_offres = "SELECT o.*, (SELECT COUNT(*) FROM candidatures WHERE offre_id = o.id) as nb_candidatures FROM offres o WHERE o.entreprise_id = ? ORDER BY o.date_publication DESC";
$stmt_offres = $pdo->prepare($req_offres);
$stmt_offres->execute([$entreprise['id']]);
$offres = $stmt_offres->fetchAll();

$pagetitle = 'Mes Offres';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_entreprise.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('briefcase') ?> Mes offres</h2>
    <a href="ajouter-offre.php" class="btn btn-primary"><?= icon('plus') ?> Ajouter une offre</a>

    <?php if (empty($offres)): ?>
        <p style="margin-top:20px;">Aucune offre publiee.</p>
    <?php else: ?>
    <table class="table" style="margin-top:20px;">
        <tr><th>Titre</th><th>Type</th><th>Duree</th><th>Lieu</th><th>Date</th><th>Candidatures</th><th>Actions</th></tr>
        <?php foreach ($offres as $o): ?>
        <tr>
            <td><a href="../offre-detail.php?id=<?= $o['id'] ?>"><?= htmlspecialchars($o['titre']) ?></a></td>
            <td><span class="badge badge-<?= $o['type_contrat'] ?>"><?= $o['type_contrat'] ?></span></td>
            <td><?= htmlspecialchars($o['duree']) ?></td>
            <td><?= htmlspecialchars($o['lieu']) ?></td>
            <td><?= formatDate($o['date_publication']) ?></td>
            <td><a href="candidatures.php?offre_id=<?= $o['id'] ?>"><?= $o['nb_candidatures'] ?></a></td>
            <td class="actions">
                <a href="modifier-offre.php?id=<?= $o['id'] ?>" class="btn btn-sm"><?= icon('edit') ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?></div>
