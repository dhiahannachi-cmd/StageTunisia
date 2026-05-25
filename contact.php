<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperation des donnees du formulaire
    if (isset($_POST['nom'])) { $nom = htmlspecialchars($_POST['nom']); } else { $nom = ''; }
    if (isset($_POST['email'])) { $email = htmlspecialchars($_POST['email']); } else { $email = ''; }
    if (isset($_POST['sujet'])) { $sujet = htmlspecialchars($_POST['sujet']); } else { $sujet = ''; }
    if (isset($_POST['message'])) { $message = htmlspecialchars($_POST['message']); } else { $message = ''; }

    // Verification des champs obligatoires
    if ($nom != false && $email != false && $message != false) {
        $msg = "<p class='success-msg'>Merci " . $nom . "! Votre message a ete envoye. Nous vous repondrons dans les plus brefs delais.</p>";
    } else {
        $msg = "<p class='error-msg'>Veuillez remplir tous les champs obligatoires.</p>";
    }
}

include 'includes/header.php';
?>

<section class="section">
    <div class="container contact-page">
        <h2 class="section-title"><?= icon('mail') ?> Contactez-nous</h2>
        <div class="contact-grid">
            <div class="contact-info">
                <h3>Nos coordonnees</h3>
                <p><?= icon('map-pin') ?> Tunis, Tunisie</p>
                <p><?= icon('phone') ?> +216 71 234 567</p>
                <p><?= icon('mail') ?> contact@stagetunisia.tn</p>
                <p><?= icon('clock') ?> Lun-Ven: 9h - 17h</p>
            </div>
            <div class="contact-form">
                <?= $msg ?>
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sujet</label>
                        <input type="text" name="sujet">
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= icon('send') ?> Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
