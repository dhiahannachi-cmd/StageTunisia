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

$msg = '';
// Mise a jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profil'])) {
    $req_update = "UPDATE entreprises SET nom_entreprise=?, telephone=?, secteur=?, description=?, site_web=?, gouvernorat=?, technopole=? WHERE id=?";
    $stmt_update = $pdo->prepare($req_update);
    $stmt_update->execute([
        $_POST['nom_entreprise'], $_POST['telephone'], $_POST['secteur'],
        $_POST['description'], $_POST['site_web'], $_POST['gouvernorat'], $_POST['technopole'],
        $entreprise['id']
    ]);
    $_SESSION['user_nom'] = $_POST['nom_entreprise'];
    $msg = "<p class='success-msg'>Profil mis a jour.</p>";
    // Rechargement des donnees
    $req_reload = "SELECT * FROM entreprises WHERE id = ?";
    $stmt_reload = $pdo->prepare($req_reload);
    $stmt_reload->execute([$entreprise['id']]);
    $entreprise = $stmt_reload->fetch();
}

$pagetitle = 'Profil';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_entreprise.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('building') ?> Profil entreprise</h2>
    <?= $msg ?>
    <form method="POST" class="form-container form-container-wide">
        <input type="hidden" name="update_profil" value="1">
        <div class="form-group"><label>Nom de l entreprise</label><input type="text" name="nom_entreprise" value="<?= htmlspecialchars($entreprise['nom_entreprise']) ?>" required></div>
        <div class="form-group"><label>Email</label><input type="email" value="<?= htmlspecialchars($_SESSION['user_email']) ?>" disabled></div>
        <div class="form-row">
            <div class="form-group"><label>Telephone</label><input type="text" name="telephone" value="<?= htmlspecialchars($entreprise['telephone']) ?>"></div>
            <div class="form-group"><label>Secteur</label><input type="text" name="secteur" value="<?= htmlspecialchars($entreprise['secteur']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Site web</label><input type="text" name="site_web" value="<?= htmlspecialchars($entreprise['site_web']) ?>"></div>
            <div class="form-group"><label>Gouvernorat</label><input type="text" name="gouvernorat" value="<?= htmlspecialchars($entreprise['gouvernorat']) ?>"></div>
        </div>
        <div class="form-group"><label>Technopole</label><input type="text" name="technopole" value="<?= htmlspecialchars($entreprise['technopole']) ?>"></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="4"><?= htmlspecialchars($entreprise['description']) ?></textarea></div>
        <button type="submit" class="btn btn-primary"><?= icon('edit') ?> Enregistrer</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?></div>
