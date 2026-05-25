<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!estEntreprise()) rediriger('../login.php');

$entreprise = $pdo->prepare("SELECT id FROM entreprises WHERE utilisateur_id = ?");
$entreprise->execute([$_SESSION['user_id']]);
$entreprise = $entreprise->fetch();

$offre_id = isset($_GET['offre_id']) ? intval($_GET['offre_id']) : 0;
$offres = $pdo->prepare("SELECT * FROM offres WHERE entreprise_id = ? ORDER BY date_publication DESC");
$offres->execute([$entreprise['id']]);
$offres = $offres->fetchAll();

$candidats = [];
$offre_selectionnee = null;
if ($offre_id) {
    $stmt = $pdo->prepare("SELECT * FROM offres WHERE id = ? AND entreprise_id = ?");
    $stmt->execute([$offre_id, $entreprise['id']]);
    $offre_selectionnee = $stmt->fetch();
    if ($offre_selectionnee) {
        $candidats = getCandidaturesByOffre($pdo, $offre_id);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_statut'])) {
        $stmt = $pdo->prepare("UPDATE candidatures SET statut = ? WHERE id = ?");
        $stmt->execute([$_POST['statut'], intval($_POST['candidature_id'])]);
        echo '<meta http-equiv="refresh" content="0">';
    }
}

$pagetitle = 'Candidatures';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_entreprise.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('users') ?> Candidatures recues</h2>

    <div class="form-group">
        <label>Selectionner une offre :</label>
        <select onchange="if(this.value) window.location='candidatures.php?offre_id='+this.value">
            <option value="">-- Choisir --</option>
            <?php foreach ($offres as $o): ?>
            <option value="<?= $o['id'] ?>" <?= $offre_id === $o['id'] ? 'selected' : '' ?>><?= htmlspecialchars($o['titre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($offre_selectionnee): ?>
    <h3><?= icon('briefcase') ?> <?= htmlspecialchars($offre_selectionnee['titre']) ?></h3>

    <?php if (empty($candidats)): ?>
        <p>Aucune candidature pour cette offre.</p>
    <?php else: ?>
    <table class="table">
        <tr><th>Etudiant</th><th>Email</th><th>Universite</th><th>Message</th><th>CV</th><th>Date</th><th>Statut</th><th>Actions</th></tr>
        <?php foreach ($candidats as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><?= htmlspecialchars($c['universite']) ?> (<?= $c['niveau'] ?>)</td>
            <td><?= nl2br(htmlspecialchars($c['message'])) ?></td>
            <td><?php if ($c['cv']): ?><a href="../<?= $c['cv'] ?>" target="_blank" class="btn btn-sm"><?= icon('download') ?> CV</a><?php else: ?><span style="color:var(--gray-500)">-</span><?php endif; ?></td>
            <td><?= formatDate($c['date_candidature']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="candidature_id" value="<?= $c['id'] ?>">
                    <select name="statut" onchange="this.form.submit()">
                        <option value="en_attente" <?= $c['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                        <option value="entretien" <?= $c['statut'] === 'entretien' ? 'selected' : '' ?>>Entretien</option>
                        <option value="offre_recue" <?= $c['statut'] === 'offre_recue' ? 'selected' : '' ?>>Offre recue</option>
                        <option value="refusee" <?= $c['statut'] === 'refusee' ? 'selected' : '' ?>>Refusee</option>
                    </select>
                    <input type="hidden" name="update_statut" value="1">
                </form>
            </td>
            <td><a href="planifier-entretien.php?candidature_id=<?= $c['id'] ?>&offre_id=<?= $offre_id ?>" class="btn btn-sm"><?= icon('calendar') ?> Entretien</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?></div>
