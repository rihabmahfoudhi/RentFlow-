<?php
/**
 * Vue : Gestion des retours
 * Accessible : Agent + Responsable
 * Validation : Responsable uniquement
 */
$isResponsable = ($user['role'] ?? '') === 'Responsable';

$statutRetourLabels = [
    'En attente' => ['En attente', 'warning'],
    'Validé'     => ['Validé',     'success'],
];

$etatLabels = [
    'Disponible'  => ['Disponible',     'success'],
    'En location' => ['En location',    'primary'],
    'Maintenance' => ['En maintenance', 'warning'],
    'Endommage'   => ['Endommagé',      'danger'],
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
                            <div class="section-kicker">Administration</div>
                            <h2 class="fw-bold mb-1">Gestion des retours</h2>
                            <p class="text-muted mb-0">Enregistrez et validez les retours d'équipements loués</p>
                        </div>
                        <a href="index.php?route=enregistrer-retour" class="btn btn-primary px-4">
                            <i class="bi bi-box-arrow-in-left me-2"></i>Enregistrer un retour
                        </a>
                    </div>

                    <!-- Flash message -->
                    <?php if (!empty($flash)): ?>
                        <div class="alert alert-<?= htmlspecialchars((string) ($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8'); ?> alert-dismissible fade show rounded-3" role="alert">
                            <?= htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Bandeau info rôle -->
                    <?php if (!$isResponsable): ?>
                        <div class="alert alert-info rounded-3 d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                            <span>Vous êtes connecté en tant qu'<strong>Agent de Location</strong>. Vous pouvez enregistrer des retours. La validation est réservée au <strong>Responsable Inventaire</strong>.</span>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success rounded-3 d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-shield-check-fill fs-5"></i>
                            <span>Vous êtes connecté en tant que <strong>Responsable Inventaire</strong>. Vous pouvez valider les retours en attente.</span>
                        </div>
                    <?php endif; ?>

                    <!-- Tableau -->
                    <?php if (empty($retours)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-1">Aucun retour enregistré pour l'instant.</p>
                            <a href="index.php?route=enregistrer-retour" class="btn btn-outline-primary mt-2">
                                <i class="bi bi-plus-circle me-1"></i> Enregistrer le premier retour
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover border rounded-3" style="overflow:hidden;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width:60px;">ID</th>
                                        <th>Client</th>
                                        <th>Équipement</th>
                                        <th>Date retour</th>
                                        <th>Date fin prévue</th>
                                        <th>Retard</th>
                                        <th>Frais</th>
                                        <th>État retourné</th>
                                        <th>Statut</th>
                                        <?php if ($isResponsable): ?>
                                            <th class="text-end pe-4">Action</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($retours as $retour): ?>
                                        <?php
                                            $clientName = trim((string) (($retour['client_prenom'] ?? '') . ' ' . ($retour['client_nom'] ?? '')));
                                            $statutKey  = (string) ($retour['statut'] ?? 'En attente');
                                            [$statutLabel, $statutColor] = $statutRetourLabels[$statutKey] ?? [$statutKey, 'secondary'];
                                            $etatKey = (string) ($retour['etat_equipement'] ?? '');
                                            [$etatLabel, $etatColor] = $etatLabels[$etatKey] ?? [$etatKey, 'secondary'];
                                            $joursRetard = (int) ($retour['jours_retard'] ?? 0);
                                            $frais       = (float) ($retour['frais_additionnels'] ?? 0);
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-light text-dark">#<?= (int) ($retour['id_retour'] ?? 0); ?></span>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($clientName, ENT_QUOTES, 'UTF-8'); ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars((string) ($retour['client_email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></small>
                                            </td>
                                            <td>
                                                <span class="fw-semibold"><?= htmlspecialchars((string) ($retour['equipement_nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span><br>
                                                <small class="text-muted">Location #<?= (int) ($retour['location_id'] ?? 0); ?></small>
                                            </td>
                                            <td><?= htmlspecialchars((string) ($retour['date_retour'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars((string) ($retour['date_fin'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <?php if ($joursRetard > 0): ?>
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-clock-history me-1"></i><?= $joursRetard; ?> j
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>0 j</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($frais > 0): ?>
                                                    <span class="text-danger fw-semibold"><?= number_format($frais, 2, ',', ' '); ?> DT</span>
                                                <?php else: ?>
                                                    <span class="text-success fw-semibold">0,00 DT</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= htmlspecialchars($etatColor, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?= htmlspecialchars($etatLabel, ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= htmlspecialchars($statutColor, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?= htmlspecialchars($statutLabel, ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            </td>
                                            <?php if ($isResponsable): ?>
                                                <td class="text-end pe-4">
                                                    <?php if ($statutKey === 'En attente'): ?>
                                                        <button type="button"
                                                            class="btn btn-sm btn-success"
                                                            onclick="confirmerValidation(<?= (int) ($retour['id_retour'] ?? 0); ?>, '<?= htmlspecialchars($clientName, ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars((string) ($retour['equipement_nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>')"
                                                            title="Valider définitivement ce retour">
                                                            <i class="bi bi-check2-circle me-1"></i>Valider le retour
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="text-muted small"><i class="bi bi-check-all"></i> Déjà validé</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </main>
    </div>
</div>

<!-- Formulaire caché pour la validation -->
<form id="validerRetourForm" method="POST" action="index.php?route=retours" style="display:none;">
    <input type="hidden" name="action"    value="valider">
    <input type="hidden" name="id_retour" id="validerRetourId" value="">
</form>

<!-- Modal de confirmation de validation -->
<div class="modal fade" id="confirmValiderModal" tabindex="-1" aria-labelledby="confirmValiderLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:44px;height:44px;background:rgba(25,135,84,.12);">
                        <i class="bi bi-check2-circle text-success fs-4"></i>
                    </div>
                    <h5 class="modal-title fw-bold mb-0" id="confirmValiderLabel">Valider le retour</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="mb-1">Vous êtes sur le point de valider définitivement le retour de :</p>
                <div class="rounded-3 p-3 mt-2" style="background:#f8fafc; border:1px solid #e2e8f0;">
                    <p class="mb-1"><i class="bi bi-person me-2 text-muted"></i><strong id="validerClientName"></strong></p>
                    <p class="mb-0"><i class="bi bi-tools me-2 text-muted"></i><span id="validerEquipementName"></span></p>
                </div>
                <div class="alert alert-warning rounded-3 mt-3 mb-0 d-flex gap-2">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                    <div>
                        <strong>Cette action est irréversible.</strong> Elle mettra à jour l'état de l'équipement, le stock (+1) et clôturera la location.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success rounded-3 px-4" id="btnConfirmValider">
                    <i class="bi bi-check2-circle me-1"></i> Confirmer la validation
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmerValidation(idRetour, clientName, equipementName) {
    document.getElementById('validerRetourId').value    = idRetour;
    document.getElementById('validerClientName').textContent     = clientName;
    document.getElementById('validerEquipementName').textContent = equipementName;

    var modal = new bootstrap.Modal(document.getElementById('confirmValiderModal'));
    modal.show();
}

document.getElementById('btnConfirmValider').addEventListener('click', function () {
    document.getElementById('validerRetourForm').submit();
});
</script>
