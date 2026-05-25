<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (estConnecte() == false) {
    echo json_encode(['success' => false, 'error' => 'Non autorise']);
    exit();
}

// Envoi du message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['destinataire_id'], $_POST['contenu'])) {
    $destinataire_id = intval($_POST['destinataire_id']);
    $contenu = $_POST['contenu'];
    $expediteur_type = $_SESSION['user_type'];
    if ($expediteur_type === 'etudiant') { $destinataire_type = 'entreprise'; } else { $destinataire_type = 'etudiant'; }

    // Verification du contenu
    if (empty(trim($contenu))) {
        echo json_encode(['success' => false, 'error' => 'Message vide']);
        exit();
    }

    // Insertion du message
    $req_insert = "INSERT INTO messages (expediteur_id, destinataire_id, expediteur_type, destinataire_type, contenu) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $pdo->prepare($req_insert);
    $stmt_insert->execute([$_SESSION['user_id'], $destinataire_id, $expediteur_type, $destinataire_type, $contenu]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Requete invalide']);
}
