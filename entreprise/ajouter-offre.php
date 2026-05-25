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
// Insertion de l'offre
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $req_insert = "INSERT INTO offres (entreprise_id, titre, description, type_contrat, duree, remuneration, lieu, secteur, competences_requises) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $pdo->prepare($req_insert);
    $stmt_insert->execute([
        $entreprise['id'], $_POST['titre'], $_POST['description'], $_POST['type_contrat'],
        $_POST['duree'], $_POST['remuneration'], $_POST['lieu'], $_POST['secteur'], $_POST['competences']
    ]);
    $msg = "<p class='success-msg'>Offre publiee avec succes !</p>";
}

$pagetitle = 'Ajouter Offre';
include '../includes/header.php';
?>
<div class="dashboard-layout">
<?php include '../includes/sidebar_entreprise.php'; ?>
<div class="dashboard-content">
    <h2><?= icon('plus') ?> Publier une offre</h2>
    <?= $msg ?>
    <form method="POST" class="form-container form-container-wide">
        <div class="form-group"><label>Titre *</label><input type="text" name="titre" placeholder="Ex: Stage Developpeur Web" required></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="5" placeholder="Decrivez le poste, les missions, le profil recherche..."></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Type *</label>
                <select name="type_contrat" required>
                    <option value="stage">Stage</option>
                    <option value="emploi">Emploi</option>
                </select>
            </div>
            <div class="form-group"><label>Duree</label><input type="text" name="duree" placeholder="Ex: 3 mois, CDI"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Remuneration</label><input type="text" name="remuneration" placeholder="Ex: 500 TND"></div>
            <div class="form-group"><label>Lieu</label><input type="text" name="lieu" placeholder="Ex: Tunis"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Secteur</label>
                <select name="secteur">
                    <option value="">Selectionne</option>
                    <option>Informatique</option><option>Finance</option><option>Telecom</option>
                    <option>Marketing</option><option>IA</option><option>Securite</option><option>DevOps</option><option>Tech</option>
                </select>
            </div>
        </div>
        <div class="form-group"><label>Competences requises</label><textarea name="competences" rows="3" placeholder="PHP, JavaScript, MySQL..."></textarea></div>
        <button type="submit" class="btn btn-primary"><?= icon('plus') ?> Publier</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?></div>
