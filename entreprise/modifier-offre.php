<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Verification authentification
if (estEntreprise() == false) {
    header("Location: ../login.php");
    exit();
}

// Recuperation de l'ID de l'offre
if (isset($_GET['id'])) { $id = $_GET['id']; } else { $id = 0; }

// Recuperation de l'entreprise
$req_entreprise = "SELECT id FROM entreprises WHERE utilisateur_id = ?";
$stmt_entreprise = $pdo->prepare($req_entreprise);
$stmt_entreprise->execute([$_SESSION['user_id']]);
$entreprise = $stmt_entreprise->fetch();

// Recuperation de l'offre
$req_offre = "SELECT * FROM offres WHERE id = ? AND entreprise_id = ?";
$stmt_offre = $pdo->prepare($req_offre);
$stmt_offre->execute([$id, $entreprise['id']]);
$offre = $stmt_offre->fetch();

// Verification si l'offre existe
if ($offre == false) {
    header("Location: offres.php");
    exit();
}

$msg = '';
// Mise a jour de l'offre
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $req_update = "UPDATE offres SET titre=?, description=?, type_contrat=?, duree=?, remuneration=?, lieu=?, secteur=?, competences_requises=? WHERE id=? AND entreprise_id=?";
    $stmt_update = $pdo->prepare($req_update);
    $stmt_update->execute([
        $_POST['titre'], $_POST['description'], $_POST['type_contrat'], $_POST['duree'],
        $_POST['remuneration'], $_POST['lieu'], $_POST['secteur'], $_POST['competences'],
        $id, $entreprise['id']
    ]);
    $msg = "<p class='success-msg'>Offre modifiee avec succes.</p>";
    // Rechargement des donnees
    $req_reload = "SELECT * FROM offres WHERE id = ?";
    $stmt_reload = $pdo->prepare($req_reload);
    $stmt_reload->execute([$id]);
    $offre = $stmt_reload->fetch();
}

$pagetitle = 'Modifier Offre';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_entreprise.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('edit') ?> Modifier l offre</h2>
    <?= $msg ?>
    <form method="POST" class="form-container form-container-wide">
        <div class="form-group"><label>Titre</label><input type="text" name="titre" value="<?= htmlspecialchars($offre['titre']) ?>" required></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="5"><?= htmlspecialchars($offre['description']) ?></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Type</label>
                <select name="type_contrat">
                    <option value="stage" <?= $offre['type_contrat'] === 'stage' ? 'selected' : '' ?>>Stage</option>
                    <option value="emploi" <?= $offre['type_contrat'] === 'emploi' ? 'selected' : '' ?>>Emploi</option>
                </select>
            </div>
            <div class="form-group"><label>Duree</label><input type="text" name="duree" value="<?= htmlspecialchars($offre['duree']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Remuneration</label><input type="text" name="remuneration" value="<?= htmlspecialchars($offre['remuneration']) ?>"></div>
            <div class="form-group"><label>Lieu</label><input type="text" name="lieu" value="<?= htmlspecialchars($offre['lieu']) ?>"></div>
        </div>
        <div class="form-group"><label>Secteur</label>
            <select name="secteur">
                <option value="">Selectionne</option>
                <?php foreach (['Informatique','Finance','Telecom','Marketing','IA','Securite','DevOps','Tech'] as $s): ?>
                <option value="<?= $s ?>" <?= $offre['secteur'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Competences requises</label><textarea name="competences" rows="3"><?= htmlspecialchars($offre['competences_requises']) ?></textarea></div>
        <button type="submit" class="btn btn-primary"><?= icon('edit') ?> Enregistrer</button>
        <a href="offres.php" class="btn btn-sm">Annuler</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?></div>
