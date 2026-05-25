<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Verif classique avec header() direct, tres etudiant
if (estEtudiant() == false) {
    header("Location: ../login.php");
    exit();
}

$req_etudiant = $pdo->prepare("SELECT * FROM etudiants WHERE utilisateur_id = ?");
$req_etudiant->execute([$_SESSION['user_id']]);
$etudiant = $req_etudiant->fetch();

$candidatures = getCandidatures($pdo, $etudiant['id']);

// --- Tri des candidatures (methode classique etudiante avec des if/elseif) ---
$cand_attente = [];
$cand_entretien = [];
$cand_offre = [];
$cand_refusee = [];

if ($candidatures != false) {
    foreach ($candidatures as $cand) {
        if ($cand['statut'] == 'en_attente') {
            $cand_attente[] = $cand;
        } elseif ($cand['statut'] == 'entretien') {
            $cand_entretien[] = $cand;
        } elseif ($cand['statut'] == 'offre_recue') {
            $cand_offre[] = $cand;
        } elseif ($cand['statut'] == 'refusee') {
            $cand_refusee[] = $cand;
        }
    }
}

$pagetitle = 'Mes Candidatures';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_etudiant.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('briefcase') ?> Mes Candidatures</h2>

    <?php if (empty($candidatures)): ?>
        <p>Tu n as pas encore postule. <a href="../offres.php">Voir les offres</a></p>
    <?php else: ?>
    
    <!-- Vue Kanban -->
    <div class="kanban-board">
        
        <!-- Colonne En attente -->
        <div class="kanban-col" data-statut="en_attente">
            <h3 class="kanban-col-header">En attente (<?= count($cand_attente) ?>)</h3>
            <?php foreach ($cand_attente as $c): ?>
            <div class="kanban-card" draggable="true" data-id="<?= $c['id'] ?>" data-statut="<?= $c['statut'] ?>">
                <h4><a href="../offre-detail.php?id=<?= $c['offre_id'] ?>"><?= htmlspecialchars($c['titre']) ?></a></h4>
                <p><?= htmlspecialchars($c['nom_entreprise']) ?></p>
                <small><?= formatDate($c['date_candidature']) ?></small>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Colonne Entretien -->
        <div class="kanban-col" data-statut="entretien">
            <h3 class="kanban-col-header">Entretien (<?= count($cand_entretien) ?>)</h3>
            <?php foreach ($cand_entretien as $c): ?>
            <div class="kanban-card" draggable="true" data-id="<?= $c['id'] ?>" data-statut="<?= $c['statut'] ?>">
                <h4><a href="../offre-detail.php?id=<?= $c['offre_id'] ?>"><?= htmlspecialchars($c['titre']) ?></a></h4>
                <p><?= htmlspecialchars($c['nom_entreprise']) ?></p>
                <small><?= formatDate($c['date_candidature']) ?></small>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Colonne Offre recue -->
        <div class="kanban-col" data-statut="offre_recue">
            <h3 class="kanban-col-header">Offre recue (<?= count($cand_offre) ?>)</h3>
            <?php foreach ($cand_offre as $c): ?>
            <div class="kanban-card" draggable="true" data-id="<?= $c['id'] ?>" data-statut="<?= $c['statut'] ?>">
                <h4><a href="../offre-detail.php?id=<?= $c['offre_id'] ?>"><?= htmlspecialchars($c['titre']) ?></a></h4>
                <p><?= htmlspecialchars($c['nom_entreprise']) ?></p>
                <small><?= formatDate($c['date_candidature']) ?></small>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Colonne Refusee -->
        <div class="kanban-col" data-statut="refusee">
            <h3 class="kanban-col-header">Refusee (<?= count($cand_refusee) ?>)</h3>
            <?php foreach ($cand_refusee as $c): ?>
            <div class="kanban-card" draggable="true" data-id="<?= $c['id'] ?>" data-statut="<?= $c['statut'] ?>">
                <h4><a href="../offre-detail.php?id=<?= $c['offre_id'] ?>"><?= htmlspecialchars($c['titre']) ?></a></h4>
                <p><?= htmlspecialchars($c['nom_entreprise']) ?></p>
                <small><?= formatDate($c['date_candidature']) ?></small>
            </div>
            <?php endforeach; ?>
        </div>
        
    </div>
    
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?></div>
