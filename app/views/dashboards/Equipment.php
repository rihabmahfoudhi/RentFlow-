<?php
$etatLabels = [
    'Disponible' => ['Disponible', 'success'],
    'En location' => ['En location', 'primary'],
    'Maintenance' => ['Maintenance', 'warning'],
    'Endommage' => ['Endommagé', 'danger'],
];
?>
<div class="container-fluid py-4" style="background:#f8fafc; min-height:100vh;">
    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="card shadow-sm border-0 rounded-4 h-100" style="background:#0f172a; color:white;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="img/logo-rentflow.svg" alt="RentFlow Logo" style="height:100px;">
                        <div>
                            <h5 class="mb-0">Back Office</h5>
                            <p class="mb-0 text-white-50">Administration</p>
                        </div>
                    </div>
                    <div class="list-group list-group-flush mb-4">
                        <a href="index.php?route=categories" class="list-group-item list-group-item-action rounded-3 mb-2"> Catégories des équipements</a>
                        <a href="index.php?route=equipements" class="list-group-item list-group-item-action rounded-3 mb-2 active"> Equipements</a>
                        <a href="index.php?route=locations" class="list-group-item list-group-item-action rounded-3 mb-2"> Locations</a>
                        <a href="index.php?route=admin-dashboard" class="list-group-item list-group-item-action rounded-3 mb-2"> Retours</a>
                        <a href="index.php?route=utilisateurs" class="list-group-item list-group-item-action rounded-3 mb-2"> Utilisateurs</a>
                        <a href="index.php?route=client-dashboard" class="list-group-item list-group-item-action rounded-3 mb-2"> Espace client</a>
                    </div>
                    <a href="index.php?route=logout" class="btn btn-danger w-100">🚪 Déconnexion</a>
                </div>
            </div>
        </aside>

        <main class="col-lg-9">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                        <div>
                            <div class="section-kicker">Administration</div>
                            <h2 class="fw-bold mb-1">Catalogue des équipements</h2>
                            <p class="text-muted mb-0">Gérez les équipements disponibles à la location</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                            <i class="bi bi-plus-circle"></i> Ajouter un équipement
                        </button>
                    </div>

                    <?php if (!empty($flash)): ?>
                        <div class="alert alert-<?= htmlspecialchars((string) ($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8'); ?> alert-dismissible fade show rounded-3" role="alert">
                            <?= htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Tableau des equipements -->
                    <div class="table-responsive">
                        <table class="table table-hover border rounded-3" style="overflow: hidden;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Prix/jour</th>
                                    <th>Stock</th>
                                    <th>Seuil alerte</th>
                                    <th>État</th>
                                    <th>Catégorie</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($equipements)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            Aucun équipement trouvé. Commencez par en ajouter un !
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($equipements as $eq): ?>
                                        <?php
                                            $etatKey = (string) ($eq['etat'] ?? 'disponible');
                                            [$etatLabel, $etatColor] = $etatLabels[$etatKey] ?? [$etatKey, 'secondary'];
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-light text-dark"><?= htmlspecialchars((string) ($eq['id_eq'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars((string) ($eq['nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars((string) ($eq['description'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                                                </small>
                                            </td>
                                            <td><?= htmlspecialchars(number_format((float) ($eq['prix_jour'] ?? 0), 2, ',', ' '), ENT_QUOTES, 'UTF-8'); ?> DT</td>
                                            <td><?= htmlspecialchars((string) ($eq['stock'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars((string) ($eq['seuil_alerte'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><span class="badge bg-<?= $etatColor; ?>"><?= htmlspecialchars($etatLabel, ENT_QUOTES, 'UTF-8'); ?></span></td>
                                            <td><?= htmlspecialchars((string) ($eq['categorie_nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td class="text-end pe-4">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editEquipmentModal"
                                                    onclick="loadEquipmentEdit(<?= (int) ($eq['id_eq'] ?? 0); ?>, '<?= htmlspecialchars((string) ($eq['nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars((string) ($eq['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars((string) ($eq['prix_jour'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars((string) ($eq['stock'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars((string) ($eq['seuil_alerte'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars((string) ($eq['etat'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>', '<?= (int) ($eq['categorie_id'] ?? 0); ?>')">
                                                    <i class="bi bi-pencil"></i> Modifier
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteEquipmentConfirm(<?= (int) ($eq['id_eq'] ?? 0); ?>)">
                                                    <i class="bi bi-trash"></i> Supprimer
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                            <i class="bi bi-plus-circle"></i> Ajouter un équipement
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Ajouter un equipement -->
<div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="addEquipmentLabel">Ajouter un équipement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?route=equipements" class="needs-validation">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">

                    <div class="mb-3">
                        <label for="addNom" class="form-label fw-bold">Nom de l'équipement <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="addNom" name="nom" placeholder="Ex: Perceuse" required>
                        <small class="form-text text-muted">Le nom est obligatoire</small>
                    </div>

                    <div class="mb-3">
                        <label for="addDescription" class="form-label fw-bold">Description</label>
                        <textarea class="form-control rounded-3" id="addDescription" name="description" rows="3" placeholder="Description détaillée..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="addPrixJour" class="form-label fw-bold">Prix par jour (DT) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control rounded-3" id="addPrixJour" name="prix_jour" required>
                    </div>

                    <div class="mb-3">
                        <label for="addStock" class="form-label fw-bold">Stock <span class="text-danger">*</span></label>
                        <input type="number" step="1" min="0" class="form-control rounded-3" id="addStock" name="stock" required>
                    </div>

                    <div class="mb-3">
                        <label for="addSeuilAlerte" class="form-label fw-bold">Seuil d'alerte <span class="text-danger">*</span></label>
                        <input type="number" step="1" min="0" class="form-control rounded-3" id="addSeuilAlerte" name="seuil_alerte" required>
                    </div>

                    <div class="mb-3">
                        <label for="addEtat" class="form-label fw-bold">État <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="addEtat" name="etat" required>
                            <?php foreach ($etats as $etatValue): ?>
                                <option value="<?= htmlspecialchars($etatValue, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($etatLabels[$etatValue][0] ?? $etatValue, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="addCategorie" class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="addCategorie" name="categorie_id" required>
                            <option value="">Choisir...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int) ($cat['id_categorie'] ?? 0); ?>"><?= htmlspecialchars((string) ($cat['nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></option>
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

<!-- Modal Modifier un equipement -->
<div class="modal fade" id="editEquipmentModal" tabindex="-1" aria-labelledby="editEquipmentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="editEquipmentLabel">Modifier un équipement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?route=equipements" class="needs-validation">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editId" value="">

                    <div class="mb-3">
                        <label for="editNom" class="form-label fw-bold">Nom de l'équipement <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="editNom" name="nom" required>
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label fw-bold">Description</label>
                        <textarea class="form-control rounded-3" id="editDescription" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editPrixJour" class="form-label fw-bold">Prix par jour (DT) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control rounded-3" id="editPrixJour" name="prix_jour" required>
                    </div>

                    <div class="mb-3">
                        <label for="editStock" class="form-label fw-bold">Stock <span class="text-danger">*</span></label>
                        <input type="number" step="1" min="0" class="form-control rounded-3" id="editStock" name="stock" required>
                    </div>

                    <div class="mb-3">
                        <label for="editSeuilAlerte" class="form-label fw-bold">Seuil d'alerte <span class="text-danger">*</span></label>
                        <input type="number" step="1" min="0" class="form-control rounded-3" id="editSeuilAlerte" name="seuil_alerte" required>
                    </div>

                    <div class="mb-3">
                        <label for="editEtat" class="form-label fw-bold">État <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="editEtat" name="etat" required>
                            <?php foreach ($etats as $etatValue): ?>
                                <option value="<?= htmlspecialchars($etatValue, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($etatLabels[$etatValue][0] ?? $etatValue, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editCategorie" class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="editCategorie" name="categorie_id" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int) ($cat['id_categorie'] ?? 0); ?>"><?= htmlspecialchars((string) ($cat['nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></option>
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

<!-- Formulaire suppression (caché) -->
<form id="deleteForm" method="POST" action="index.php?route=equipements" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId" value="">
</form>

<script>
function loadEquipmentEdit(id, nom, description, prixJour, stock, seuilAlerte, etat, categorieId) {
    document.getElementById('editId').value = id;
    document.getElementById('editNom').value = nom;
    document.getElementById('editDescription').value = description;
    document.getElementById('editPrixJour').value = prixJour;
    document.getElementById('editStock').value = stock;
    document.getElementById('editSeuilAlerte').value = seuilAlerte;
    document.getElementById('editEtat').value = etat;
    document.getElementById('editCategorie').value = categorieId;
}

function deleteEquipmentConfirm(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?\n\nCette action est irréversible.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}

// Bootstrap form validation
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

