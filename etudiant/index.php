<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (estEtudiant() == false) {
    header("Location: ../login.php");
    exit();
}

$req_etudiant = "SELECT * FROM etudiants WHERE utilisateur_id = ?";
$stmt_etudiant = $pdo->prepare($req_etudiant);
$stmt_etudiant->execute([$_SESSION['user_id']]);
$etudiant = $stmt_etudiant->fetch();

// Recuperation des candidatures
$candidatures = getCandidatures($pdo, $etudiant['id']);

$req_attente = "SELECT COUNT(*) as total FROM candidatures WHERE etudiant_id = ? AND statut = 'en_attente'";
$stmt_attente = $pdo->prepare($req_attente);
$stmt_attente->execute([$etudiant['id']]);
$res_attente = $stmt_attente->fetch();
$stats_attente = $res_attente['total'];

$req_acceptee = "SELECT COUNT(*) as total FROM candidatures WHERE etudiant_id = ? AND statut = 'acceptee'";
$stmt_acceptee = $pdo->prepare($req_acceptee);
$stmt_acceptee->execute([$etudiant['id']]);
$res_acceptee = $stmt_acceptee->fetch();
$stats_acceptee = $res_acceptee['total'];

$req_refusee = "SELECT COUNT(*) as total FROM candidatures WHERE etudiant_id = ? AND statut = 'refusee'";
$stmt_refusee = $pdo->prepare($req_refusee);
$stmt_refusee->execute([$etudiant['id']]);
$res_refusee = $stmt_refusee->fetch();
$stats_refusee = $res_refusee['total'];

$pagetitle = 'Dashboard';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_etudiant.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('home') ?> Dashboard</h2>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) ?> !</p>

    <div class="stats-cards">
        <div class="stat-card stat-attente"><h3><?= $stats_attente ?></h3><p>En attente</p></div>
        <div class="stat-card stat-acceptee"><h3><?= $stats_acceptee ?></h3><p>Acceptees</p></div>
        <div class="stat-card stat-refusee"><h3><?= $stats_refusee ?></h3><p>Refusees</p></div>
        <div class="stat-card stat-total"><h3><?= count($candidatures) ?></h3><p>Total</p></div>
    </div>

    <div class="dashboard-section">
        <h3><?= icon('briefcase') ?> Mes dernieres candidatures</h3>
        <?php if (empty($candidatures)): ?>
            <p>Aucune candidature. <a href="../offres.php">Voir les offres</a></p>
        <?php else: ?>
        <table class="table">
            <tr><th>Offre</th><th>Entreprise</th><th>Statut</th><th>Date</th></tr>
            <?php foreach (array_slice($candidatures, 0, 5) as $c): ?>
            <tr>
                <td><a href="../offre-detail.php?id=<?= $c['offre_id'] ?>"><?= htmlspecialchars($c['titre']) ?></a></td>
                <td><?= htmlspecialchars($c['nom_entreprise']) ?></td>
                <td><span class="badge badge-<?= $c['statut'] ?>"><?= $c['statut'] ?></span></td>
                <td><?= formatDate($c['date_candidature']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>

    <?php
    $entretiens = getEntretiensEtudiant($pdo, $etudiant['id']);
    $entretiens_futurs = array_filter($entretiens, function($e) {
        return strtotime($e['date_entretien']) > time();
    });
    ?>
    <?php if (!empty($entretiens_futurs)): ?>
    <div class="dashboard-section">
        <h3><?= icon('calendar') ?> Prochains entretiens</h3>
        <table class="table">
            <tr><th>Entreprise</th><th>Offre</th><th>Date</th><th>Lien</th></tr>
            <?php foreach (array_slice($entretiens_futurs, 0, 3) as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['nom_entreprise']) ?></td>
                <td><?= htmlspecialchars($e['offre_titre']) ?></td>
                <td><?= formatDateTime($e['date_entretien']) ?></td>
                <td><?php if ($e['lien_visio'] != false): ?><a href="<?= htmlspecialchars($e['lien_visio']) ?>" target="_blank"><?= icon('video') ?> Rejoindre</a><?php else: ?>-<?php endif; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><a href="entretiens.php"><?= icon('calendar') ?> Voir tous les entretiens</a></p>
    </div>
    <?php endif; ?>

    <?php
    $recommandations = getRecommandations($pdo, $etudiant['id']);
    ?>
    <?php if (!empty($recommandations)): ?>
    <div class="dashboard-section">
        <h3><?= icon('star') ?> Offres recommandees pour vous</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:15px;">
            <?php foreach ($recommandations as $r): ?>
            <div class="offre-card">
                <div class="offre-card-header">
                    <h3 style="font-size:15px;"><a href="../offre-detail.php?id=<?= $r['id'] ?>"><?= htmlspecialchars($r['titre']) ?></a></h3>
                    <span class="badge badge-<?= $r['type_contrat'] ?>"><?= $r['type_contrat'] ?></span>
                </div>
                <div class="offre-card-body" style="padding:8px 15px;">
                    <p class="offre-entreprise"><?= icon('building') ?> <?= htmlspecialchars($r['nom_entreprise']) ?></p>
                    <p class="offre-meta"><?= icon('map-pin') ?> <?= htmlspecialchars($r['lieu']) ?> <?php if ($r['remuneration'] != false): ?><?= icon('award') ?> <?= htmlspecialchars($r['remuneration']) ?><?php endif; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?></div>
