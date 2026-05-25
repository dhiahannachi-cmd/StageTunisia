<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<section class="section">
    <div class="container">
        <h2 class="section-title"><?= icon('settings') ?> Outils</h2>

        <!-- Simulateur Salaire -->
        <div class="outils-grid">
            <div class="outil-card">
                <h3><?= icon('bar-chart') ?> Simulateur de salaire</h3>
                <p>Calcule ton salaire net a partir du brut (CNSS, impot, etc.)</p>
                <div class="simulator">
                    <div class="form-group">
                        <label>Salaire brut (TND)</label>
                        <input type="number" id="salaire_brut" placeholder="Ex: 2500" oninput="calculerSalaire()">
                    </div>
                    <div class="form-group">
                        <label>Type de contrat</label>
                        <select id="type_contrat" onchange="calculerSalaire()">
                            <option value="stage">Stage</option>
                            <option value="emploi">Emploi (CDI/CDD)</option>
                        </select>
                    </div>
                    <div id="resultat_salaire" class="salaire-resultat">
                        <p>Entre un salaire brut pour voir le calcul.</p>
                    </div>
                </div>
            </div>

            <!-- Rapports PFE -->
            <div class="outil-card">
                <h3><?= icon('download') ?> Modeles de rapports PFE</h3>
                <p>Telecharge des modeles de rapports de stage PFE pour t aider dans la redaction.</p>
                <div class="rapports-list">
                    <a href="assets/pdf/rapport-pfe-insat.pdf" download class="btn btn-primary btn-sm"><?= icon('file-pdf') ?> Rapport INSAT</a>
                    <a href="assets/pdf/rapport-pfe-enit.pdf" download class="btn btn-primary btn-sm"><?= icon('file-pdf') ?> Rapport ENIT</a>
                    <a href="assets/pdf/rapport-pfe-esprit.pdf" download class="btn btn-primary btn-sm"><?= icon('file-pdf') ?> Rapport ESPRIT</a>
                    <a href="assets/pdf/rapport-pfe-fst.pdf" download class="btn btn-primary btn-sm"><?= icon('file-pdf') ?> Rapport FST</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function calculerSalaire() {
    var brut = parseFloat(document.getElementById('salaire_brut').value);
    var type = document.getElementById('type_contrat').value;
    var resultat = document.getElementById('resultat_salaire');

    if (!brut || brut <= 0) {
        resultat.innerHTML = '<p>Entre un salaire brut pour voir le calcul.</p>';
        return;
    }

    var cnss = 0;
    var impot = 0;
    var net = brut;

    if (type === 'emploi') {
        cnss = brut * 0.0902;
        var apres_cnss = brut - cnss;
        if (apres_cnss > 1500) {
            impot = (apres_cnss - 1500) * 0.15;
        }
        net = brut - cnss - impot;
    } else {
        cnss = brut * 0.0105;
        net = brut - cnss;
    }

    resultat.innerHTML = '<div class="salaire-details">' +
        '<p><strong>Salaire brut :</strong> ' + brut.toFixed(2) + ' TND</p>' +
        '<p><strong>CNSS :</strong> -' + cnss.toFixed(2) + ' TND</p>' +
        (impot > 0 ? '<p><strong>Impot :</strong> -' + impot.toFixed(2) + ' TND</p>' : '') +
        '<p class="salaire-net"><strong>Salaire net :</strong> ' + net.toFixed(2) + ' TND</p>' +
        '</div>';
}
</script>

<?php include 'includes/footer.php'; ?>
