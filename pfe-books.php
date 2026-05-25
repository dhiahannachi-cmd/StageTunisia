<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Recuperation des PFE books
$req_books = "SELECT p.*, e.nom_entreprise FROM pfe_books p JOIN entreprises e ON p.entreprise_id = e.id ORDER BY p.date_ajout DESC";
$stmt_books = $pdo->query($req_books);
$books = $stmt_books->fetchAll();
?>

<section class="section">
    <div class="container">
        <h2 class="section-title"><?= icon('book-open') ?> PFE Books</h2>
        <p class="section-subtitle">Consulte les catalogues de sujets PFE proposés par les entreprises partenaires.</p>

        <?php if (empty($books)): ?>
            <p class="no-results">Aucun catalogue disponible pour le moment.</p>
        <?php else: ?>
        <div class="books-grid">
            <?php foreach ($books as $book): ?>
            <div class="book-card">
                <div class="book-icon"><?= icon('file-pdf', 'big-icon') ?></div>
                <h3><?= htmlspecialchars($book['titre']) ?></h3>
                <p class="book-entreprise"><?= icon('building') ?> <?= htmlspecialchars($book['nom_entreprise']) ?></p>
                <p class="book-desc"><?= htmlspecialchars($book['description']) ?></p>
                <div class="book-actions">
                    <a href="<?= htmlspecialchars($book['fichier_pdf']) ?>" target="_blank" class="btn btn-primary btn-sm"><?= icon('eye') ?> Voir</a>
                    <a href="<?= htmlspecialchars($book['fichier_pdf']) ?>" download class="btn btn-sm"><?= icon('download') ?> Telecharger</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Apercu PDF du premier livre -->
        <?php if (!empty($books)): ?>
        <div class="pdf-preview">
            <h3><?= icon('eye') ?> Apercu : <?= htmlspecialchars($books[0]['titre']) ?></h3>
            <iframe src="<?= htmlspecialchars($books[0]['fichier_pdf']) ?>" class="pdf-iframe"></iframe>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
