<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Verification si l'utilisateur est deja connecte
if (estConnecte() != false) {
    if (estEtudiant() != false) {
        header("Location: etudiant/index.php");
        exit();
    } else {
        header("Location: entreprise/index.php");
        exit();
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperation des donnees du formulaire
    if (isset($_POST['type'])) { $type = $_POST['type']; } else { $type = 'etudiant'; }
    if (isset($_POST['email'])) { $email = $_POST['email']; } else { $email = ''; }
    if (isset($_POST['password'])) { $password = $_POST['password']; } else { $password = ''; }
    if (isset($_POST['confirm_password'])) { $confirm = $_POST['confirm_password']; } else { $confirm = ''; }

    // Verification des champs
    if (empty($email) || empty($password) || empty($confirm)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caracteres.";
    } else {
        // Verification si l'email existe deja
        $req_verif_email = "SELECT id FROM utilisateurs WHERE email = ?";
        $stmt_verif_email = $pdo->prepare($req_verif_email);
        $stmt_verif_email->execute([$email]);
        if ($stmt_verif_email->fetch()) {
            $error = "Cet email est deja utilise.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertion de l'utilisateur
            $req_utilisateur = "INSERT INTO utilisateurs (email, mot_de_passe, type) VALUES (?, ?, ?)";
            $stmt_utilisateur = $pdo->prepare($req_utilisateur);
            $stmt_utilisateur->execute([$email, $hash, $type]);
            $utilisateur_id = $pdo->lastInsertId();

            if ($type === 'etudiant') {
                // Recuperation des donnees etudiant
                if (isset($_POST['nom'])) { $nom = $_POST['nom']; } else { $nom = ''; }
                if (isset($_POST['prenom'])) { $prenom = $_POST['prenom']; } else { $prenom = ''; }
                if (isset($_POST['telephone'])) { $telephone = $_POST['telephone']; } else { $telephone = ''; }
                if (isset($_POST['universite'])) { $universite = $_POST['universite']; } else { $universite = ''; }
                if (isset($_POST['specialite'])) { $specialite = $_POST['specialite']; } else { $specialite = ''; }
                if (isset($_POST['niveau'])) { $niveau = $_POST['niveau']; } else { $niveau = ''; }
                // Insertion en base
                $req_insert_etudiant = "INSERT INTO etudiants (utilisateur_id, nom, prenom, telephone, universite, specialite, niveau) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_insert_etudiant = $pdo->prepare($req_insert_etudiant);
                $stmt_insert_etudiant->execute([$utilisateur_id, $nom, $prenom, $telephone, $universite, $specialite, $niveau]);
            } else {
                // Recuperation des donnees entreprise
                if (isset($_POST['nom_entreprise'])) { $nom_entreprise = $_POST['nom_entreprise']; } else { $nom_entreprise = ''; }
                if (isset($_POST['telephone'])) { $telephone = $_POST['telephone']; } else { $telephone = ''; }
                if (isset($_POST['secteur'])) { $secteur = $_POST['secteur']; } else { $secteur = ''; }
                if (isset($_POST['description'])) { $description = $_POST['description']; } else { $description = ''; }
                if (isset($_POST['site_web'])) { $site_web = $_POST['site_web']; } else { $site_web = ''; }
                // Insertion en base
                $req_insert_entreprise = "INSERT INTO entreprises (utilisateur_id, nom_entreprise, telephone, secteur, description, site_web) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert_entreprise = $pdo->prepare($req_insert_entreprise);
                $stmt_insert_entreprise->execute([$utilisateur_id, $nom_entreprise, $telephone, $secteur, $description, $site_web]);
            }

            $success = "Inscription reussie ! <a href='login.php'>Connecte-toi</a>";
        }
    }
}

include 'includes/header.php';
?>

<section class="section form-section">
    <div class="container form-container">
        <h2><?= icon('plus') ?> Inscription</h2>
        <?php if ($error != false): ?><p class="error-msg"><?= icon('alert-circle') ?> <?= $error ?></p><?php endif; ?>
        <?php if ($success != false): ?><p class="success-msg"><?= icon('check-circle') ?> <?= $success ?></p><?php endif; ?>

        <?php if ($success == false): ?>
        <form method="POST" onsubmit="return validerInscription()">
            <div class="form-group">
                <label>Type de compte</label>
                <select name="type" id="type_compte" onchange="basculerFormulaire()">
                    <option value="etudiant">Etudiant</option>
                    <option value="entreprise">Entreprise</option>
                </select>
            </div>

            <div id="form_etudiant">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" placeholder="Ben Salem" required>
                    </div>
                    <div class="form-group">
                        <label>Prenom</label>
                        <input type="text" name="prenom" placeholder="Ahmed" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Universite</label>
                    <select name="universite">
                        <option value="">Selectionne</option>
                        <option value="INSAT">INSAT</option>
                        <option value="ENIT">ENIT</option>
                        <option value="ESPRIT">ESPRIT</option>
                        <option value="FST">FST</option>
                        <option value="IHEC">IHEC</option>
                        <option value="ISG">ISG</option>
                        <option value="ENSI">ENSI</option>
                        <option value="SUPCOM">SUPCOM</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Specialite</label>
                        <input type="text" name="specialite" placeholder="Ex: Genie Logiciel">
                    </div>
                    <div class="form-group">
                        <label>Niveau</label>
                        <select name="niveau">
                            <option value="1ere">1ere annee</option>
                            <option value="2eme">2eme annee</option>
                            <option value="3eme">3eme annee</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="form_entreprise" style="display:none;">
                <div class="form-group">
                    <label>Nom de l entreprise</label>
                    <input type="text" name="nom_entreprise" placeholder="Nom de votre entreprise">
                </div>
                <div class="form-group">
                    <label>Secteur</label>
                    <input type="text" name="secteur" placeholder="Ex: Informatique, Finance, Telecom">
                </div>
                <div class="form-group">
                    <label>Site web</label>
                    <input type="text" name="site_web" placeholder="www.exemple.com">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Decrivez votre entreprise..."></textarea>
                </div>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="exemple@email.com" required>
            </div>
            <div class="form-group">
                <label>Telephone</label>
                <input type="text" name="telephone" placeholder="Ex: 55123456">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="Min 6 caracteres" required>
                </div>
                <div class="form-group">
                    <label>Confirmer</label>
                    <input type="password" name="confirm_password" placeholder="Retapez" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block"><?= icon('user') ?> S inscrire</button>
        </form>
        <p class="form-footer">Deja un compte ? <a href="login.php">Connecte-toi</a></p>
        <?php endif; ?>
    </div>
</section>

<script>
function basculerFormulaire() {
    var t = document.getElementById('type_compte').value;
    document.getElementById('form_etudiant').style.display = t === 'etudiant' ? 'block' : 'none';
    document.getElementById('form_entreprise').style.display = t === 'entreprise' ? 'block' : 'none';
}

function validerInscription() {
    var p = document.querySelector('input[name="password"]').value;
    var c = document.querySelector('input[name="confirm_password"]').value;
    if (p !== c) { alert("Les mots de passe ne correspondent pas."); return false; }
    if (p.length < 6) { alert("Minimum 6 caracteres."); return false; }
    return true;
}
</script>

<?php include 'includes/footer.php'; ?>
