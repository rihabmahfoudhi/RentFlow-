<?php
/**
 * Vue : Formulaire d'enregistrement d'un retour
 * Accessible : Agent + Responsable
 */

// Libellés affichés pour les états (les valeurs BDD sont dans EquipmentModel::ETATS)
$etatLibelles = [
    'Disponible'  => 'Disponible (retour normal)',
    'En location' => 'En location',
    'Maintenance' => 'En maintenance (réparation nécessaire)',
    'Endommage'   => 'Endommagé',
];
?>
<div class="container-fluid py-4" style="background:#f8fafc; min-height:100vh;">
    <div class="row g-4">

        <!-- ── Sidebar ─────────────────────────────────────── -->
        <aside class="col-lg-3">
            <div class="card shadow-sm border-0 rounded-4 h-100" style="background:#0f172a; color:white;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="img/logo.png" alt="RentFlow Logo" style="height:100px; background-color: white; padding: 5px; border-radius: 5px;">
                        <div>
                            <h5 class="mb-0">Back Office</h5>
                            <p class="mb-0 text-white-50">Administration</p>
                        </div>
                    </div>
                    <div class="list-group list-group-flush mb-4">
                        <a href="index.php?route=categories"        class="list-group-item list-group-item-action rounded-3 mb-2">Catégories des équipements</a>
                        <a href="index.php?route=equipements"       class="list-group-item list-group-item-action rounded-3 mb-2">Équipements</a>
                        <a href="index.php?route=locations"         class="list-group-item list-group-item-action rounded-3 mb-2">Locations</a>
                        <a href="index.php?route=retours"           class="list-group-item list-group-item-action rounded-3 mb-2 active">Retours</a>
                        <a href="index.php?route=utilisateurs"      class="list-group-item list-group-item-action rounded-3 mb-2">Utilisateurs</a>
                        <a href="index.php?route=client-dashboard"  class="list-group-item list-group-item-action rounded-3 mb-2">Espace client</a>
                    </div>
                    <a href="index.php?route=logout" class="btn btn-danger w-100">Déconnexion</a>
                </div>
            </div>
        </aside>

        <!-- ── Contenu principal ────────────────────────────── -->
        <main class="col-lg-9">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-lg-5">

                    <!-- En-tête -->
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                        <div>
                            <div class="section-kicker">Retours</div>
                            <h2 class="fw-bold mb-1">Enregistrer un retour</h2>
                            <p class="text-muted mb-0">Sélectionnez la location concernée, renseignez l'état et la date de retour.</p>
                        </div>
                        <a href="index.php?route=retours" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>

                    <!-- Flash -->
                    <?php if (!empty($flash)): ?>
                        <div class="alert alert-<?= htmlspecialchars((string) ($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8'); ?> alert-dismissible fade show rounded-3" role="alert">
                            <?= htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($locations)): ?>
                        <!-- Aucune location active -->
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard-x fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">Aucune location active</h5>
                            <p class="text-muted small">Il n'y a aucune location avec le statut <strong>Acceptée</strong> ou <strong>En cours</strong> pour le moment.</p>
                            <a href="index.php?route=locations" class="btn btn-outline-primary mt-2">Voir toutes les locations</a>
                        </div>
                    <?php else: ?>

                    <!-- ══ Formulaire ════════════════════════════════════ -->
                    <form method="POST" action="index.php?route=retours" class="needs-validation" novalidate id="retourForm">
                        <input type="hidden" name="action" value="add">

                        <!-- Étape 1 : Sélection de la location -->
                        <div class="card border-0 rounded-3 mb-4" style="background:#f8fafc;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                    <span class="badge rounded-circle bg-primary" style="width:26px;height:26px;font-size:.85rem;line-height:26px;">1</span>
                                    Location concernée
                                </h6>

                                <div class="mb-3">
                                    <label for="location_id" class="form-label fw-semibold">
                                        Sélectionner la location <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg rounded-3"
                                            id="location_id" name="location_id" required
                                            onchange="chargerInfosLocation(this.value)">
                                        <option value="">— Choisir une location active —</option>
                                        <?php foreach ($locations as $loc): ?>
                                            <?php
                                                $label = htmlspecialchars(
                                                    (string) ($loc['equipement_nom'] ?? '') .
                                                    ' — Location #' . (int) ($loc['id_location'] ?? 0) .
                                                    ' — ' . trim((string) (($loc['client_prenom'] ?? '') . ' ' . ($loc['client_nom'] ?? ''))),
                                                    ENT_QUOTES, 'UTF-8'
                                                );
                                            ?>
                                            <option value="<?= (int) ($loc['id_location'] ?? 0); ?>"><?= $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner une location.</div>
                                </div>

                                <!-- Bloc d'infos dynamiques (affiché après sélection) -->
                                <div id="infoLocation" class="d-none mt-3">
                                    <div class="rounded-3 p-4" style="background:#fff; border:1px solid #e2e8f0;">
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="bi bi-person-circle text-primary fs-5 mt-1"></i>
                                                    <div>
                                                        <div class="text-muted small">Client</div>
                                                        <div class="fw-bold" id="infoClient">—</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="bi bi-tools text-primary fs-5 mt-1"></i>
                                                    <div>
                                                        <div class="text-muted small">Équipement</div>
                                                        <div class="fw-bold" id="infoEquipement">—</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="bi bi-calendar-event text-success fs-5 mt-1"></i>
                                                    <div>
                                                        <div class="text-muted small">Date début</div>
                                                        <div class="fw-semibold" id="infoDateDebut">—</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="bi bi-calendar-x text-danger fs-5 mt-1"></i>
                                                    <div>
                                                        <div class="text-muted small">Date fin prévue</div>
                                                        <div class="fw-semibold" id="infoDateFin">—</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="bi bi-cash-coin text-warning fs-5 mt-1"></i>
                                                    <div>
                                                        <div class="text-muted small">Prix / jour</div>
                                                        <div class="fw-semibold" id="infoPrixJour">—</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="bi bi-receipt text-info fs-5 mt-1"></i>
                                                    <div>
                                                        <div class="text-muted small">Prix total location</div>
                                                        <div class="fw-semibold" id="infoPrixTotal">—</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 2 : Date de retour -->
                        <div class="card border-0 rounded-3 mb-4" style="background:#f8fafc;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                    <span class="badge rounded-circle bg-primary" style="width:26px;height:26px;font-size:.85rem;line-height:26px;">2</span>
                                    Date de retour réelle
                                </h6>
                                <div class="mb-3">
                                    <label for="date_retour" class="form-label fw-semibold">
                                        Date de retour <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           class="form-control form-control-lg rounded-3"
                                           id="date_retour" name="date_retour"
                                           value="<?= date('Y-m-d'); ?>"
                                           required
                                           onchange="calculerRetard()">
                                    <div class="form-text">Date à laquelle l'équipement a été physiquement retourné.</div>
                                    <div class="invalid-feedback">Veuillez renseigner la date de retour.</div>
                                </div>

                                <!-- Calcul automatique des frais -->
                                <div id="blocRetard" class="d-none mt-3">
                                    <div id="alertRetard" class="rounded-3 p-3 d-flex align-items-center gap-3">
                                        <i id="retardIcon" class="fs-3"></i>
                                        <div>
                                            <div class="fw-bold" id="retardTitre">—</div>
                                            <div id="retardDetail" class="small mt-1">—</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 3 : État de l'équipement -->
                        <div class="card border-0 rounded-3 mb-4" style="background:#f8fafc;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                    <span class="badge rounded-circle bg-primary" style="width:26px;height:26px;font-size:.85rem;line-height:26px;">3</span>
                                    État de l'équipement au retour
                                </h6>
                                <div class="mb-3">
                                    <label for="etat_equipement" class="form-label fw-semibold">
                                        État constaté <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg rounded-3"
                                            id="etat_equipement" name="etat_equipement" required>
                                        <option value="">— Choisir l'état —</option>
                                        <?php foreach ($etats as $etat): ?>
                                            <?php if ($etat === 'En location') continue; // impossible au retour ?>
                                            <option value="<?= htmlspecialchars($etat, ENT_QUOTES, 'UTF-8'); ?>">
                                                <?= htmlspecialchars($etatLibelles[$etat] ?? $etat, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner l'état de l'équipement.</div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Retour normal → <strong>Disponible</strong> &nbsp;|&nbsp;
                                        Réparation nécessaire → <strong>En maintenance</strong> &nbsp;|&nbsp;
                                        Cassé → <strong>Endommagé</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Récapitulatif et bouton -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 pt-2">
                            <a href="index.php?route=retours" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="btnEnregistrer">
                                <i class="bi bi-box-arrow-in-left me-2"></i>Enregistrer le retour
                            </button>
                        </div>
                    </form>

                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
/**
 * Date de fin de la location sélectionnée (stockée après la requête AJAX)
 * Format : 'YYYY-MM-DD'
 */
let datefinLocation = null;

/**
 * Charge les informations d'une location via AJAX et met à jour le formulaire.
 */
function chargerInfosLocation(locationId) {
    const infoBlock = document.getElementById('infoLocation');

    if (!locationId) {
        infoBlock.classList.add('d-none');
        datefinLocation = null;
        document.getElementById('blocRetard').classList.add('d-none');
        return;
    }

    fetch('index.php?route=location-json&id=' + locationId)
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.error) {
                infoBlock.classList.add('d-none');
                return;
            }

            // Mémoriser la date de fin
            datefinLocation = data.date_fin || null;

            // Remplir le bloc d'infos
            document.getElementById('infoClient').textContent     =
                (data.client_prenom || '') + ' ' + (data.client_nom || '');
            document.getElementById('infoEquipement').textContent  = data.equipement_nom || '—';
            document.getElementById('infoDateDebut').textContent   = formatDate(data.date_debut);
            document.getElementById('infoDateFin').textContent     = formatDate(data.date_fin);
            document.getElementById('infoPrixJour').textContent    =
                parseFloat(data.prix_jour || 0).toFixed(2).replace('.', ',') + ' DT / jour';
            document.getElementById('infoPrixTotal').textContent   =
                parseFloat(data.prix_total || 0).toFixed(2).replace('.', ',') + ' DT';

            infoBlock.classList.remove('d-none');

            // Recalculer le retard avec la nouvelle date de fin
            calculerRetard();
        })
        .catch(function () {
            infoBlock.classList.add('d-none');
        });
}

/**
 * Calcule et affiche les jours de retard + frais additionnels en temps réel.
 */
function calculerRetard() {
    const blocRetard = document.getElementById('blocRetard');

    if (!datefinLocation) {
        blocRetard.classList.add('d-none');
        return;
    }

    const dateRetourVal = document.getElementById('date_retour').value;
    if (!dateRetourVal) {
        blocRetard.classList.add('d-none');
        return;
    }

    const dateFin    = new Date(datefinLocation + 'T00:00:00');
    const dateRetour = new Date(dateRetourVal   + 'T00:00:00');

    // Calcul des jours de retard (PHP fait le même calcul côté serveur)
    let joursRetard = 0;
    let frais       = 0;

    if (dateRetour > dateFin) {
        const diff  = dateRetour - dateFin;
        joursRetard = Math.round(diff / (1000 * 60 * 60 * 24));
        frais       = joursRetard * 10;
    }

    const alertDiv  = document.getElementById('alertRetard');
    const icon      = document.getElementById('retardIcon');
    const titre     = document.getElementById('retardTitre');
    const detail    = document.getElementById('retardDetail');

    if (joursRetard === 0) {
        alertDiv.style.background = 'rgba(25,135,84,.08)';
        alertDiv.style.border     = '1px solid rgba(25,135,84,.2)';
        icon.className            = 'bi bi-check-circle-fill text-success fs-3';
        titre.textContent         = 'Retour dans les délais — aucun frais supplémentaire';
        detail.innerHTML          = '<span class="text-muted">Frais additionnels : <strong class="text-success">0,00 DT</strong></span>';
    } else {
        alertDiv.style.background = 'rgba(220,53,69,.08)';
        alertDiv.style.border     = '1px solid rgba(220,53,69,.2)';
        icon.className            = 'bi bi-exclamation-triangle-fill text-danger fs-3';
        titre.textContent         = 'Retard de ' + joursRetard + ' jour' + (joursRetard > 1 ? 's' : '');
        detail.innerHTML          =
            'Frais de retard : <strong class="text-danger">' + joursRetard + ' × 10 DT = ' +
            frais.toFixed(2).replace('.', ',') + ' DT</strong>';
    }

    blocRetard.classList.remove('d-none');
}

/**
 * Formate une date 'YYYY-MM-DD' en 'DD/MM/YYYY'.
 */
function formatDate(str) {
    if (!str) return '—';
    var parts = str.split('-');
    if (parts.length !== 3) return str;
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

// Validation Bootstrap
(function () {
    'use strict';
    window.addEventListener('load', function () {
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
