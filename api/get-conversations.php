<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Verification authentification
if (estConnecte() == false) {
    echo '<p>Non autorise.</p>';
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Recuperation des conversations
$sql = "SELECT DISTINCT 
            CASE WHEN expediteur_id = ? AND expediteur_type = ? THEN destinataire_id ELSE expediteur_id END as other_id,
            CASE WHEN expediteur_id = ? AND expediteur_type = ? THEN destinataire_type ELSE expediteur_type END as other_type,
            MAX(date_envoi) as dernier_message
        FROM messages 
        WHERE (expediteur_id = ? AND expediteur_type = ?) OR (destinataire_id = ? AND destinataire_type = ?)
        GROUP BY other_id, other_type
        ORDER BY dernier_message DESC";

$stmt_conversations = $pdo->prepare($sql);
$stmt_conversations->execute([$user_id, $user_type, $user_id, $user_type, $user_id, $user_type, $user_id, $user_type]);
$conversations = $stmt_conversations->fetchAll();

if (empty($conversations)) {
    echo '<p class="no-conversations">Aucune conversation.</p>';
    exit();
}

foreach ($conversations as $conv) {
    $nom = '';
    if ($conv['other_type'] === 'etudiant') {
        // Recuperation du nom de l'etudiant
        $req_nom = "SELECT prenom, nom FROM etudiants WHERE utilisateur_id = ?";
        $stmt_nom = $pdo->prepare($req_nom);
        $stmt_nom->execute([$conv['other_id']]);
        $u = $stmt_nom->fetch();
        if ($u != false) { $nom = $u['prenom'] . ' ' . $u['nom']; } else { $nom = 'Inconnu'; }
    } else {
        // Recuperation du nom de l'entreprise
        $req_nom = "SELECT nom_entreprise FROM entreprises WHERE utilisateur_id = ?";
        $stmt_nom = $pdo->prepare($req_nom);
        $stmt_nom->execute([$conv['other_id']]);
        $u = $stmt_nom->fetch();
        if ($u != false) { $nom = $u['nom_entreprise']; } else { $nom = 'Inconnu'; }
    }

    echo '<div class="conversation-item" onclick="demarrerChatPolling(' . $conv['other_id'] . ', \'' . $conv['other_type'] . '\')">';
    echo '<p><strong>' . htmlspecialchars($nom) . '</strong></p>';
    echo '<small>' . date('d/m/Y', strtotime($conv['dernier_message'])) . '</small>';
    echo '</div>';
}
