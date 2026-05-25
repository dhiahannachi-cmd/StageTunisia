<?php
require_once __DIR__ . '/icons.php';

if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/../config/env.php';
}

function imgHero() {
    return 'photos/illustrations/hero-campus.jpg';
}

function imgLogoUniversite($nom) {
    $nom_minuscule = strtolower($nom);
    return 'photos/universites/' . $nom_minuscule . '.png';
}

function imgLogoPrincipal() {
    return 'photos/logos/logo-principal.svg';
}

function imgAvatarEtudiant() {
    return 'photos/logos/avatar-etudiant-default.svg';
}

function imgLogoEntreprise() {
    return 'photos/logos/logo-entreprise-default.svg';
}

// ---------- AUTH ----------

function estConnecte() {
    if(isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

function estEtudiant() {
    if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'etudiant') {
        return true;
    }
    return false;
}

function estEntreprise() {
    if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'entreprise') {
        return true;
    }
    return false;
}

function rediriger($url) {
    header("Location: " . $url);
    exit();
}

function compterOffres($pdo) {
    $req = $pdo->query("SELECT COUNT(*) as total FROM offres WHERE active = 1");
    $res = $req->fetch();
    return $res['total'];
}

function compterEtudiants($pdo) {
    $req = $pdo->query("SELECT COUNT(*) as total FROM etudiants");
    $res = $req->fetch();
    return $res['total'];
}

function compterEntreprises($pdo) {
    $req = $pdo->query("SELECT COUNT(*) as total FROM entreprises");
    $res = $req->fetch();
    return $res['total'];
}

// ---------- UPLOAD ----------

function uploadCV($file) {
    $nom_fichier = $file['name'];
    
    $ext = pathinfo($nom_fichier, PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    
    if ($ext == 'pdf') {
        // 5Mo c'est suffisant pour un CV
        if ($file['size'] < 5000000) { 
            
            // on ajoute la date/time pour ne pas ecraser les anciens CV
            $nouveau_nom = time() . '_' . basename($nom_fichier);
            $destination = 'assets/uploads/cv/' . $nouveau_nom;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                return $destination;
            }
        }
    }
    
    return null;
}

function getOffres($pdo, $filtres = []) {
    $sql = "SELECT o.*, e.nom_entreprise FROM offres o JOIN entreprises e ON o.entreprise_id = e.id WHERE o.active = 1";
    $params = [];

    if (!empty($filtres['search'])) {
        $sql .= " AND (o.titre LIKE ? OR o.description LIKE ? OR e.nom_entreprise LIKE ?)";
        $search = '%' . $filtres['search'] . '%';
        $params[] = $search; $params[] = $search; $params[] = $search;
    }
    if (!empty($filtres['type'])) {
        $sql .= " AND o.type_contrat = ?";
        $params[] = $filtres['type'];
    }
    if (!empty($filtres['secteur'])) {
        $sql .= " AND o.secteur = ?";
        $params[] = $filtres['secteur'];
    }
    if (!empty($filtres['lieu'])) {
        $sql .= " AND o.lieu = ?";
        $params[] = $filtres['lieu'];
    }

    $sql .= " ORDER BY o.date_publication DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getOffre($pdo, $id) {
    $stmt = $pdo->prepare("SELECT o.*, e.nom_entreprise, e.description as desc_entreprise, e.site_web, e.telephone, e.logo FROM offres o JOIN entreprises e ON o.entreprise_id = e.id WHERE o.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getCandidatures($pdo, $etudiant_id) {
    $stmt = $pdo->prepare("SELECT c.*, o.titre, o.type_contrat, o.lieu, o.entreprise_id, e.nom_entreprise FROM candidatures c JOIN offres o ON c.offre_id = o.id JOIN entreprises e ON o.entreprise_id = e.id WHERE c.etudiant_id = ? ORDER BY c.date_candidature DESC");
    $stmt->execute([$etudiant_id]);
    return $stmt->fetchAll();
}

function getCandidaturesByOffre($pdo, $offre_id) {
    $stmt = $pdo->prepare("SELECT c.*, et.nom, et.prenom, u.email, et.telephone, et.universite, et.specialite, et.niveau, et.cv FROM candidatures c JOIN etudiants et ON c.etudiant_id = et.id JOIN utilisateurs u ON et.utilisateur_id = u.id WHERE c.offre_id = ? ORDER BY c.date_candidature DESC");
    $stmt->execute([$offre_id]);
    return $stmt->fetchAll();
}

function getNotifications($pdo, $utilisateur_id, $limit = 5) {
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE utilisateur_id = ? ORDER BY date_creation DESC LIMIT ?");
    $stmt->execute([$utilisateur_id, $limit]);
    return $stmt->fetchAll();
}

function nbNotificationsNonLues($pdo, $utilisateur_id) {
    return $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE utilisateur_id = ? AND lu = 0")->execute([$utilisateur_id])->fetchColumn();
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

function formatDateTime($date) {
    return date('d/m/Y H:i', strtotime($date));
}

function tronquer($texte, $longueur = 150) {
    if (mb_strlen($texte) <= $longueur) return $texte;
    return mb_substr($texte, 0, $longueur) . '...';
}

function getEtudiantId($pdo) {
    $stmt = $pdo->prepare("SELECT id FROM etudiants WHERE utilisateur_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch();
    return $row ? $row['id'] : 0;
}

function getRecommandations($pdo, $etudiant_id) {
    $stmt = $pdo->prepare("SELECT specialite, niveau FROM etudiants WHERE id = ?");
    $stmt->execute([$etudiant_id]);
    $e = $stmt->fetch();
    if (!$e || empty($e['specialite'])) return [];

    $sql = "SELECT o.*, e.nom_entreprise FROM offres o JOIN entreprises e ON o.entreprise_id = e.id WHERE o.active = 1 AND (o.secteur LIKE ? OR o.titre LIKE ?) ORDER BY o.date_publication DESC LIMIT 4";
    $like = '%' . $e['specialite'] . '%';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$like, $like]);
    return $stmt->fetchAll();
}

function getEntretiensEtudiant($pdo, $etudiant_id) {
    $stmt = $pdo->prepare("SELECT r.*, o.titre as offre_titre, e.nom_entreprise FROM rendez_vous r JOIN candidatures c ON r.candidature_id = c.id JOIN offres o ON c.offre_id = o.id JOIN entreprises e ON o.entreprise_id = e.id WHERE c.etudiant_id = ? ORDER BY r.date_entretien ASC");
    $stmt->execute([$etudiant_id]);
    return $stmt->fetchAll();
}

function getEntretiensEntreprise($pdo, $entreprise_id) {
    $stmt = $pdo->prepare("SELECT r.*, o.titre as offre_titre, et.nom, et.prenom, et.telephone FROM rendez_vous r JOIN candidatures c ON r.candidature_id = c.id JOIN offres o ON c.offre_id = o.id JOIN etudiants et ON c.etudiant_id = et.id WHERE o.entreprise_id = ? ORDER BY r.date_entretien ASC");
    $stmt->execute([$entreprise_id]);
    return $stmt->fetchAll();
}
