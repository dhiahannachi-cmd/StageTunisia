<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Recuperation des parametres de recherche
if (isset($_GET['search'])) { $search = $_GET['search']; } else { $search = ''; }
if (isset($_GET['type'])) { $type = $_GET['type']; } else { $type = ''; }
if (isset($_GET['secteur'])) { $secteur = $_GET['secteur']; } else { $secteur = ''; }
if (isset($_GET['lieu'])) { $lieu = $_GET['lieu']; } else { $lieu = ''; }

// Recuperation des offres
$offres = getOffres($pdo, [
    'search' => $search,
    'type' => $type,
    'secteur' => $secteur,
    'lieu' => $lieu,
]);

// Recuperation des secteurs distincts
$req_secteurs = "SELECT DISTINCT secteur FROM offres WHERE secteur != '' AND active = 1 ORDER BY secteur";
$stmt_secteurs = $pdo->query($req_secteurs);
$secteurs = $stmt_secteurs->fetchAll(PDO::FETCH_COLUMN);

// Recuperation des lieux distincts
$req_lieux = "SELECT DISTINCT lieu FROM offres WHERE lieu != '' AND active = 1 ORDER BY lieu";
$stmt_lieux = $pdo->query($req_lieux);
$lieux = $stmt_lieux->fetchAll(PDO::FETCH_COLUMN);
?>

<section class="section">
    <div class="container">
        <h2 class="section-title"><?= icon('briefcase') ?> Offres de stage et d emploi</h2>
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <?= icon('search') ?>
                <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <select name="type">
                <option value="">Tous les types</option>
                <option value="stage" <?= $type === 'stage' ? 'selected' : '' ?>>Stage</option>
                <option value="emploi" <?= $type === 'emploi' ? 'selected' : '' ?>>Emploi</option>
            </select>
            <select name="secteur">
                <option value="">Tous secteurs</option>
                <?php foreach ($secteurs as $s): ?>
                <option value="<?= htmlspecialchars($s) ?>" <?= $secteur === $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="lieu">
                <option value="">Tous lieux</option>
                <?php foreach ($lieux as $l): ?>
                <option value="<?= htmlspecialchars($l) ?>" <?= $lieu === $l ? 'selected' : '' ?>><?= htmlspecialchars($l) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary"><?= icon('filter') ?> Filtrer</button>
        </form>

        <div class="offres-list">
            <?php if (empty($offres)): ?>
                <p class="no-results">Aucune offre trouvee. Essayez de modifier vos filtres.</p>
            <?php else: ?>
                <?php foreach ($offres as $offre): ?>
                <div class="offre-card">
                    <div class="offre-card-header">
                        <h3><a href="offre-detail.php?id=<?= $offre['id'] ?>"><?= htmlspecialchars($offre['titre']) ?></a></h3>
                        <span class="badge badge-<?= $offre['type_contrat'] ?>"><?= $offre['type_contrat'] ?></span>
                    </div>
                    <div class="offre-card-body">
                        <p class="offre-entreprise"><?= icon('building') ?> <?= htmlspecialchars($offre['nom_entreprise']) ?></p>
                        <p class="offre-meta">
                            <?= icon('map-pin') ?> <?= htmlspecialchars($offre['lieu']) ?>
                            <?= icon('clock') ?> <?= htmlspecialchars($offre['duree']) ?>
                            <?php if ($offre['remuneration'] != false): ?><?= icon('award') ?> <?= htmlspecialchars($offre['remuneration']) ?><?php endif; ?>
                        </p>
                        <p class="offre-desc"><?= tronquer(htmlspecialchars($offre['description'])) ?></p>
                    </div>
                    <div class="offre-card-footer">
                        <a href="offre-detail.php?id=<?= $offre['id'] ?>" class="btn btn-primary btn-sm"><?= icon('eye') ?> Voir plus</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
