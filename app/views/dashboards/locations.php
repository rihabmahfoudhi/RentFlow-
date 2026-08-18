<?php
$statutLabels = [
    'En attente' => ['En attente', 'secondary'],
    'Validee' => ['Validee', 'success'],
    'En cours' => ['En cours', 'primary'],
    'Terminee' => ['Terminee', 'dark'],
    'Annulee' => ['Annulee', 'danger'],
];
?>
<div class="container-fluid py-4" style="background:#f8fafc; min-height:100vh;">
    <div class="row g-4">
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
                        <a href="index.php?route=categories" class="list-group-item list-group-item-action rounded-3 mb-2">Categories des equipements</a>
                        <a href="index.php?route=equipements" class="list-group-item list-group-item-action rounded-3 mb-2">Equipements</a>
                        <a href="index.php?route=locations" class="list-group-item list-group-item-action rounded-3 mb-2 active">Locations</a>
                        <a href="index.php?route=retours" class="list-group-item list-group-item-action rounded-3 mb-2">Retours</a>
                        <a href="index.php?route=utilisateurs" class="list-group-item list-group-item-action rounded-3 mb-2">Utilisateurs</a>
                        <a href="index.php?route=client-dashboard" class="list-group-item list-group-item-action rounded-3 mb-2">Espace client</a>
                    </div>
                    <a href="index.php?route=logout" class="btn btn-danger w-100">Deconnexion</a>
                </div>
            </div>
        </aside>

        <main class="col-lg-9">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                        <div>
                            <div class="section-kicker">Administration</div>
                            <h2 class="fw-bold mb-1">Gestion des locations</h2>
                            <p class="text-muted mb-0">Gerez les locations des equipements par client</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                            <i class="bi bi-plus-circle"></i> Ajouter une location
                        </button>
                    </div>

                    <?php if (!empty($flash)): ?>
                        <div class="alert alert-<?= htmlspecialchars((string) ($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8'); ?> alert-dismissible fade show rounded-3" role="alert">
                            <?= htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover border rounded-3" style="overflow: hidden;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Client</th>
                                    <th>Equipement</th>
                                    <th>Debut</th>
                                    <th>Fin</th>
                                    <th>Prix total</th>
                                    <th>Statut</th>
                                    <th>Creation</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($locations)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            Aucune location trouvee. Commencez par en ajouter une !
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($locations as $location): ?>
                                        <?php
                                            $statutKey = (string) ($location['statut'] ?? 'En attente');
                                            [$statutLabel, $statutColor] = $statutLabels[$statutKey] ?? [$statutKey, 'secondary'];
                                            $clientName = trim((string) (($location['client_prenom'] ?? '') . ' ' . ($location['client_nom'] ?? '')));
                                            $editPayload = [
                                                (int) ($location['id_location'] ?? 0),
                                                (int) ($location['client_id'] ?? 0),
                                                (int) ($location['equipement_id'] ?? 0),
                                                (string) ($location['date_debut'] ?? ''),
                                                (string) ($location['date_fin'] ?? ''),
                                                (string) ($location['statut'] ?? 'En attente'),
                                            ];
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-light text-dark"><?= htmlspecialchars((string) ($location['id_location'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($clientName, ENT_QUOTES, 'UTF-8'); ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars((string) ($location['client_email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></small>
                                            </td>
                                            <td><?= htmlspecialchars((string) ($location['equipement_nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars((string) ($location['date_debut'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars((string) ($location['date_fin'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars(number_format((float) ($location['prix_total'] ?? 0), 2, ',', ' '), ENT_QUOTES, 'UTF-8'); ?> DT</td>
                                            <td><span class="badge bg-<?= htmlspecialchars($statutColor, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($statutLabel, ENT_QUOTES, 'UTF-8'); ?></span></td>
                                            <td>
                                                <small>
                                                    <?php
                                                        $dateCreation = $location['date_creation'] ?? '';
                                                        if ($dateCreation) {
                                                            echo htmlspecialchars((string) (new DateTime((string) $dateCreation))->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8');
                                                        }
                                                    ?>
                                                </small>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="index.php?route=generer-facture&id=<?= (int) ($location['id_location'] ?? 0); ?>"
                                                    class="btn btn-sm btn-outline-success" target="_blank" title="Generer la facture PDF">
                                                    <i class="bi bi-file-earmark-pdf"></i> Facture
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editLocationModal"
                                                    onclick='loadLocationEdit(<?= json_encode($editPayload, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>)'>
                                                    <i class="bi bi-pencil"></i> Modifier
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteLocationConfirm(<?= (int) ($location['id_location'] ?? 0); ?>)">
                                                    <i class="bi bi-trash"></i> Supprimer
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="addLocationLabel">Ajouter une location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?route=locations" class="needs-validation">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">

                    <div class="mb-3">
                        <label for="addClient" class="form-label fw-bold">Client <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="addClient" name="client_id" required>
                            <option value="">Choisir...</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= (int) ($client['id_user'] ?? 0); ?>">
                                    <?= htmlspecialchars(trim((string) (($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''))) . ' - ' . (string) ($client['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="addEquipement" class="form-label fw-bold">Equipement <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="addEquipement" name="equipement_id" required>
                            <option value="">Choisir...</option>
                            <?php foreach ($equipements as $equipement): ?>
                                <option value="<?= (int) ($equipement['id_eq'] ?? 0); ?>">
                                    <?= htmlspecialchars((string) ($equipement['nom'] ?? '') . ' - ' . number_format((float) ($equipement['prix_jour'] ?? 0), 2, ',', ' ') . ' DT/jour', ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="addDateDebut" class="form-label fw-bold">Date debut <span class="text-danger">*</span></label>
                        <input type="date" class="form-control rounded-3" id="addDateDebut" name="date_debut" required>
                    </div>

                    <div class="mb-3">
                        <label for="addDateFin" class="form-label fw-bold">Date fin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control rounded-3" id="addDateFin" name="date_fin" required>
                    </div>

                    <div class="mb-3">
                        <label for="addStatut" class="form-label fw-bold">Statut <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="addStatut" name="statut" required>
                            <?php foreach ($statuts as $statutValue): ?>
                                <option value="<?= htmlspecialchars($statutValue, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($statutLabels[$statutValue][0] ?? $statutValue, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary rounded-3">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="editLocationLabel">Modifier une location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?route=locations" class="needs-validation">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editId" value="">

                    <div class="mb-3">
                        <label for="editClient" class="form-label fw-bold">Client <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="editClient" name="client_id" required>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= (int) ($client['id_user'] ?? 0); ?>">
                                    <?= htmlspecialchars(trim((string) (($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''))) . ' - ' . (string) ($client['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editEquipement" class="form-label fw-bold">Equipement <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="editEquipement" name="equipement_id" required>
                            <?php foreach ($equipements as $equipement): ?>
                                <option value="<?= (int) ($equipement['id_eq'] ?? 0); ?>">
                                    <?= htmlspecialchars((string) ($equipement['nom'] ?? '') . ' - ' . number_format((float) ($equipement['prix_jour'] ?? 0), 2, ',', ' ') . ' DT/jour', ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editDateDebut" class="form-label fw-bold">Date debut <span class="text-danger">*</span></label>
                        <input type="date" class="form-control rounded-3" id="editDateDebut" name="date_debut" required>
                    </div>

                    <div class="mb-3">
                        <label for="editDateFin" class="form-label fw-bold">Date fin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control rounded-3" id="editDateFin" name="date_fin" required>
                    </div>

                    <div class="mb-3">
                        <label for="editStatut" class="form-label fw-bold">Statut <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="editStatut" name="statut" required>
                            <?php foreach ($statuts as $statutValue): ?>
                                <option value="<?= htmlspecialchars($statutValue, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($statutLabels[$statutValue][0] ?? $statutValue, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary rounded-3">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" action="index.php?route=locations" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId" value="">
</form>

<script>
function loadLocationEdit(payload) {
    document.getElementById('editId').value = payload[0];
    document.getElementById('editClient').value = payload[1];
    document.getElementById('editEquipement').value = payload[2];
    document.getElementById('editDateDebut').value = payload[3];
    document.getElementById('editDateFin').value = payload[4];
    document.getElementById('editStatut').value = payload[5];
}

function deleteLocationConfirm(id) {
    if (confirm('Etes-vous sur de vouloir supprimer cette location ?\n\nCette action est irreversible.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}

(function () {
    'use strict';
    window.addEventListener('load', function () {
        let forms = document.querySelectorAll('.needs-validation');
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
