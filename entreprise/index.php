<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (estEntreprise() == false) {
    header("Location: ../login.php");
    exit();
}

$req_entreprise = "SELECT * FROM entreprises WHERE utilisateur_id = ?";
$stmt_entreprise = $pdo->prepare($req_entreprise);
$stmt_entreprise->execute([$_SESSION['user_id']]);
$entreprise = $stmt_entreprise->fetch();

$req_offres = "SELECT * FROM offres WHERE entreprise_id = ? ORDER BY date_publication DESC";
$stmt_offres = $pdo->prepare($req_offres);
$stmt_offres->execute([$entreprise['id']]);
$offres = $stmt_offres->fetchAll();

// Calcul du total des candidatures
$total_candidatures = 0;
foreach ($offres as $o) {
    $req_count = "SELECT COUNT(*) as total FROM candidatures WHERE offre_id = ?";
    $stmt_count = $pdo->prepare($req_count);
    $stmt_count->execute([$o['id']]);
    $res_count = $stmt_count->fetch();
    $total_candidatures += $res_count['total'];
}

$pagetitle = 'Dashboard';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_entreprise.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('building') ?> Dashboard</h2>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_nom']) ?> !</p>

    <div class="stats-cards">
        <div class="stat-card stat-total"><h3><?= count($offres) ?></h3><p>Offres publiees</p></div>
        <div class="stat-card stat-acceptee"><h3><?= $total_candidatures ?></h3><p>Candidatures recues</p></div>
    </div>

    <div class="dashboard-section">
        <h3><?= icon('briefcase') ?> Mes offres</h3>
        <?php if (empty($offres)): ?>
            <p>Aucune offre publiee. <a href="ajouter-offre.php">Publier une offre</a></p>
        <?php else: ?>
        <table class="table">
            <tr><th>Titre</th><th>Type</th><th>Candidatures</th><th>Date</th><th>Actions</th></tr>
            <?php foreach ($offres as $o):
                $req_nb = "SELECT COUNT(*) as total FROM candidatures WHERE offre_id = ?";
                $stmt_nb = $pdo->prepare($req_nb);
                $stmt_nb->execute([$o['id']]);
                $res_nb = $stmt_nb->fetch();
                $nb = $res_nb['total'];
            ?>
            <tr>
                <td><a href="../offre-detail.php?id=<?= $o['id'] ?>"><?= htmlspecialchars($o['titre']) ?></a></td>
                <td><span class="badge badge-<?= $o['type_contrat'] ?>"><?= $o['type_contrat'] ?></span></td>
                <td><a href="candidatures.php?offre_id=<?= $o['id'] ?>"><?= $nb ?> candidature(s)</a></td>
                <td><?= formatDate($o['date_publication']) ?></td>
                <td class="actions">
                    <a href="modifier-offre.php?id=<?= $o['id'] ?>" class="btn btn-sm"><?= icon('edit') ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?></div>
