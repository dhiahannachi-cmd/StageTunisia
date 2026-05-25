<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Verification authentification
if (estEtudiant() == false) {
    header("Location: ../login.php");
    exit();
}

// Recuperation des donnees etudiant
$req_etudiant = "SELECT * FROM etudiants WHERE utilisateur_id = ?";
$stmt_etudiant = $pdo->prepare($req_etudiant);
$stmt_etudiant->execute([$_SESSION['user_id']]);
$etudiant = $stmt_etudiant->fetch();

$msg = '';
// Mise a jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profil'])) {
    $req_update = "UPDATE etudiants SET nom=?, prenom=?, telephone=?, universite=?, specialite=?, niveau=?, adresse=?, bio=? WHERE id=?";
    $stmt_update = $pdo->prepare($req_update);
    $stmt_update->execute([
        $_POST['nom'], $_POST['prenom'], $_POST['telephone'], $_POST['universite'],
        $_POST['specialite'], $_POST['niveau'], $_POST['adresse'], $_POST['bio'],
        $etudiant['id']
    ]);
    $_SESSION['user_nom'] = $_POST['nom'];
    $msg = "<p class='success-msg'>Profil mis a jour.</p>";
    // Rechargement des donnees
    $req_reload = "SELECT * FROM etudiants WHERE id = ?";
    $stmt_reload = $pdo->prepare($req_reload);
    $stmt_reload->execute([$etudiant['id']]);
    $etudiant = $stmt_reload->fetch();
}

// Upload du CV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_cv'])) {
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $cv_path = uploadCV($_FILES['cv']);
        if ($cv_path != false) {
            $req_cv = "UPDATE etudiants SET cv = ? WHERE id = ?";
            $stmt_cv = $pdo->prepare($req_cv);
            $stmt_cv->execute([$cv_path, $etudiant['id']]);
            $msg = "<p class='success-msg'>CV uploade avec succes !</p>";
            // Rechargement des donnees
            $req_reload2 = "SELECT * FROM etudiants WHERE id = ?";
            $stmt_reload2 = $pdo->prepare($req_reload2);
            $stmt_reload2->execute([$etudiant['id']]);
            $etudiant = $stmt_reload2->fetch();
        } else {
            $msg = "<p class='error-msg'>Erreur : format PDF uniquement, max 5 Mo.</p>";
        }
    }
}

$pagetitle = 'Mon Profil';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_etudiant.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('user') ?> Mon Profil</h2>
    <?= $msg ?>
    <form method="POST" class="form-container form-container-wide">
        <input type="hidden" name="update_profil" value="1">
        <div class="form-row">
            <div class="form-group"><label>Nom</label><input type="text" name="nom" value="<?= htmlspecialchars($etudiant['nom']) ?>" required></div>
            <div class="form-group"><label>Prenom</label><input type="text" name="prenom" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required></div>
        </div>
        <div class="form-group"><label>Email</label><input type="email" value="<?= htmlspecialchars($_SESSION['user_email']) ?>" disabled></div>
        <div class="form-group"><label>Telephone</label><input type="text" name="telephone" value="<?= htmlspecialchars($etudiant['telephone']) ?>"></div>
        <div class="form-row">
            <div class="form-group"><label>Universite</label>
                <select name="universite">
                    <?php foreach (['', 'INSAT','ENIT','ESPRIT','FST','IHEC','ISG','ENSI','SUPCOM','Autre'] as $u): ?>
                    <option value="<?= $u ?>" <?= $etudiant['universite'] === $u ? 'selected' : '' ?>><?= $u ?: 'Selectionne' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Specialite</label><input type="text" name="specialite" value="<?= htmlspecialchars($etudiant['specialite']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Niveau</label>
                <select name="niveau">
                    <?php foreach (['1ere','2eme','3eme'] as $n): ?>
                    <option value="<?= $n ?>" <?= $etudiant['niveau'] === $n ? 'selected' : '' ?>><?= $n ?> annee</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group"><label>Adresse</label><textarea name="adresse" rows="2"><?= htmlspecialchars($etudiant['adresse']) ?></textarea></div>
        <div class="form-group"><label>Bio</label><textarea name="bio" rows="3"><?= htmlspecialchars($etudiant['bio']) ?></textarea></div>
        <button type="submit" class="btn btn-primary"><?= icon('edit') ?> Enregistrer</button>
    </form>

    <div class="dashboard-section" style="margin-top:30px;">
        <h3><?= icon('file-text') ?> Mon CV</h3>
        <?php if ($etudiant['cv'] != false): ?>
            <p style="margin-bottom:10px;">CV actuel : <a href="<?= $etudiant['cv'] ?>" target="_blank"><?= icon('download') ?> Telecharger le CV</a></p>
        <?php else: ?>
            <p style="margin-bottom:10px;">Aucun CV uploade.</p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="upload_cv" value="1">
            <div class="form-group">
                <label>Uploader un CV (PDF, max 5 Mo)</label>
                <input type="file" name="cv" accept=".pdf">
            </div>
            <button type="submit" class="btn btn-primary"><?= icon('upload') ?> Uploader</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?></div>
