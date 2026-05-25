<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

// Verification authentification
if (estEtudiant() == false) {
    echo json_encode(['success' => false, 'error' => 'Non autorise']);
    exit();
}

// Traitement de la mise a jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['statut'])) {
    $id = intval($_POST['id']);
    $statut = $_POST['statut'];

    // Verification du statut
    $statuts_valides = ['en_attente', 'entretien', 'offre_recue', 'refusee'];
    if (in_array($statut, $statuts_valides) == false) {
        echo json_encode(['success' => false, 'error' => 'Statut invalide']);
        exit();
    }

    // Mise a jour en base
    $req_update = "UPDATE candidatures SET statut = ? WHERE id = ?";
    $stmt_update = $pdo->prepare($req_update);
    $stmt_update->execute([$statut, $id]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Requete invalide']);
}
