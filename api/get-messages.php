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

// Recuperation des parametres
if (isset($_GET['user_id'])) { $other_id = intval($_GET['user_id']); } else { $other_id = 0; }
if (isset($_GET['type'])) { $other_type = $_GET['type']; } else { 
    if ($user_type === 'etudiant') { $other_type = 'entreprise'; } else { $other_type = 'etudiant'; }
}

if ($other_id == false) {
    echo '<p>Selectionne une conversation.</p>';
    exit();
}

// Recuperation des messages
$req_messages = "SELECT * FROM messages WHERE (expediteur_id = ? AND destinataire_id = ? AND expediteur_type = ? AND destinataire_type = ?) OR (expediteur_id = ? AND destinataire_id = ? AND expediteur_type = ? AND destinataire_type = ?) ORDER BY date_envoi ASC";
$stmt_messages = $pdo->prepare($req_messages);
$stmt_messages->execute([$user_id, $other_id, $user_type, $other_type, $other_id, $user_id, $other_type, $user_type]);
$messages = $stmt_messages->fetchAll();

// Marquage comme lu
$req_read = "UPDATE messages SET lu = 1 WHERE expediteur_id = ? AND destinataire_id = ? AND expediteur_type = ? AND destinataire_type = ? AND lu = 0";
$stmt_read = $pdo->prepare($req_read);
$stmt_read->execute([$other_id, $user_id, $other_type, $user_type]);

if (empty($messages)) {
    echo '<p class="no-messages">Aucun message. Envoyez votre premier message !</p>';
    exit();
}

foreach ($messages as $msg) {
    $is_mine = $msg['expediteur_id'] == $user_id && $msg['expediteur_type'] == $user_type;
    echo '<div class="message ' . ($is_mine ? 'message-mine' : 'message-other') . '">';
    echo '<p>' . nl2br(htmlspecialchars($msg['contenu'])) . '</p>';
    echo '<small>' . date('d/m/Y H:i', strtotime($msg['date_envoi'])) . '</small>';
    echo '</div>';
}
