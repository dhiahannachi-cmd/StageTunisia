// ===== KANBAN DRAG & DROP =====
document.addEventListener('DOMContentLoaded', function() {
    initKanban();
    initNavToggle();
    initLogoutConfirm();
});

function initKanban() {
    var cards = document.querySelectorAll('.kanban-card[draggable]');
    var cols = document.querySelectorAll('.kanban-col');

    cards.forEach(function(card) {
        card.addEventListener('dragstart', function(e) {
            this.classList.add('dragging');
            e.dataTransfer.setData('text/plain', this.dataset.id);
            e.dataTransfer.setData('statut', this.dataset.statut);
        });
        card.addEventListener('dragend', function() {
            this.classList.remove('dragging');
            cols.forEach(function(c) { c.classList.remove('drag-over'); });
        });
    });

    cols.forEach(function(col) {
        col.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });
        col.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });
        col.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            var id = e.dataTransfer.getData('text/plain');
            var newStatut = this.dataset.statut;
            if (id && newStatut) {
                updateCandidatureStatut(id, newStatut);
                moveCardToColumn(id, this);
            }
        });
    });
}

function updateCandidatureStatut(id, statut) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/dv/api/update-candidature.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status !== 200) {
            alert('Erreur lors de la mise a jour.');
        }
    };
    xhr.send('id=' + encodeURIComponent(id) + '&statut=' + encodeURIComponent(statut));
}

function moveCardToColumn(id, col) {
    var card = document.querySelector('.kanban-card[data-id="' + id + '"]');
    if (card) {
        card.dataset.statut = col.dataset.statut;
        col.appendChild(card);
        updateColumnCounts();
    }
}

function updateColumnCounts() {
    document.querySelectorAll('.kanban-col').forEach(function(col) {
        var count = col.querySelectorAll('.kanban-card').length;
        var header = col.querySelector('.kanban-col-header');
        if (header) {
            header.textContent = header.textContent.replace(/\(\d+\)/, '(' + count + ')');
        }
    });
}

// ===== NAV TOGGLE =====
function initNavToggle() {
    var toggle = document.querySelector('.nav-toggle');
    if (toggle) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelector('.nav-links').classList.toggle('show');
        });
        document.addEventListener('click', function() {
            document.querySelector('.nav-links').classList.remove('show');
        });
    }
}

// ===== LOGOUT CONFIRM =====
function initLogoutConfirm() {
    document.querySelectorAll('a[href*="logout"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (!confirm('Voulez-vous vraiment vous deconnecter ?')) {
                e.preventDefault();
            }
        });
    });
}

// ===== CHAT (AJAX polling) =====
function chargerConversations() {
    var container = document.getElementById('conversations-list');
    if (!container) return;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/dv/api/get-conversations.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            container.innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function chargerMessages(utilisateurId, type) {
    var container = document.getElementById('messages-container');
    if (!container) return;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/dv/api/get-messages.php?user_id=' + utilisateurId + '&type=' + type, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            container.innerHTML = xhr.responseText;
            container.scrollTop = container.scrollHeight;
        }
    };
    xhr.send();
}

function envoyerMessage(destinataireId, type) {
    var input = document.getElementById('message-input');
    if (!input || !input.value.trim()) return;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/dv/api/send-message.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            input.value = '';
            chargerMessages(destinataireId, type);
        }
    };
    xhr.send('destinataire_id=' + destinataireId + '&type=' + type + '&contenu=' + encodeURIComponent(input.value));
}

// Chat polling
var chatInterval;
function demarrerChatPolling(destinataireId, type) {
    if (chatInterval) clearInterval(chatInterval);
    chargerMessages(destinataireId, type);
    chatInterval = setInterval(function() {
        chargerMessages(destinataireId, type);
    }, 5000);
}

// ===== SALARY CALCULATOR =====
function calculerSalaire() {
    var brut = parseFloat(document.getElementById('salaire_brut').value);
    var type = document.getElementById('type_contrat').value;
    var resultat = document.getElementById('resultat_salaire');
    if (!brut || brut <= 0) {
        resultat.innerHTML = '<p>Entre un salaire brut pour voir le calcul.</p>';
        return;
    }
    var cnss = type === 'emploi' ? brut * 0.0902 : brut * 0.0105;
    var apres_cnss = brut - cnss;
    var impot = 0;
    if (type === 'emploi' && apres_cnss > 1500) {
        impot = (apres_cnss - 1500) * 0.15;
    }
    var net = brut - cnss - impot;
    resultat.innerHTML = '<div class="salaire-details">' +
        '<p><strong>Salaire brut :</strong> ' + brut.toFixed(2) + ' TND</p>' +
        '<p><strong>CNSS :</strong> -' + cnss.toFixed(2) + ' TND</p>' +
        (impot > 0 ? '<p><strong>Impot :</strong> -' + impot.toFixed(2) + ' TND</p>' : '') +
        '<p class="salaire-net"><strong>Salaire net :</strong> ' + net.toFixed(2) + ' TND</p></div>';
}
