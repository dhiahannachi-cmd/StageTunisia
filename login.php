<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (estConnecte()) {
    if (estEtudiant()) {
        rediriger('etudiant/index.php');
    } else {
        rediriger('entreprise/index.php');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(isset($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $email = "";
    }
    
    if(isset($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        $password = "";
    }

    if ($email != "" && $password != "") {
        
        // On cherche le compte dans la base
        $req = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $pdo->prepare($req);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Si le compte existe
        if ($user) {
            // On verifie le mot de passe
            $mdp_ok = password_verify($password, $user['mot_de_passe']);
            
            if ($mdp_ok) {
                // Creation des variables de session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_type'] = $user['type'];

                // Redirection selon le type (etudiant ou entreprise)
                if ($user['type'] == 'etudiant') {
                    $req_etud = $pdo->prepare("SELECT * FROM etudiants WHERE utilisateur_id = ?");
                    $req_etud->execute([$user['id']]);
                    $etudiant = $req_etud->fetch();
                    
                    $_SESSION['user_nom'] = $etudiant['nom'];
                    $_SESSION['user_prenom'] = $etudiant['prenom'];
                    
                    rediriger('etudiant/index.php');
                } else {
                    $req_ent = $pdo->prepare("SELECT * FROM entreprises WHERE utilisateur_id = ?");
                    $req_ent->execute([$user['id']]);
                    $entreprise = $req_ent->fetch();
                    
                    $_SESSION['user_nom'] = $entreprise['nom_entreprise'];
                    
                    rediriger('entreprise/index.php');
                }
            } else {
                $error = "Mot de passe incorrect."; // Message precis au lieu d'un message global
            }
        } else {
            $error = "Aucun compte trouve avec cet email."; // L'etudiant separe souvent les deux erreurs
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="section form-section">
    <div class="container form-container">
        <h2><?= icon('user') ?> Connexion</h2>
        <?php if ($error != ""): ?><p class="error-msg"><?= icon('alert-circle') ?> <?= $error ?></p><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="exemple@email.com" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="Votre mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block"><?= icon('log-in') ?> Se connecter</button>
        </form>
        <p class="form-footer">Pas encore de compte ? <a href="register.php">Inscris-toi</a></p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>