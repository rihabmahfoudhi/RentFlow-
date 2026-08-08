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
                        <a href="index.php?route=categories" class="list-group-item list-group-item-action rounded-3 mb-2 active"> Catégories des équipements</a>
                        <a href="index.php?route=equipements" class="list-group-item list-group-item-action rounded-3 mb-2"> Equipements</a>
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
                            <h2 class="fw-bold mb-1">Catégories des équipements</h2>
                            <p class="text-muted mb-0">Gérez les catégories d'équipements disponibles</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="bi bi-plus-circle"></i> Ajouter une catégorie
                        </button>
                    </div>

                    <?php if (!empty($flash)): ?>
                        <div class="alert alert-<?= htmlspecialchars((string) ($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8'); ?> alert-dismissible fade show rounded-3" role="alert">
                            <?= htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Tableau des catégories -->
                    <div class="table-responsive">
                        <table class="table table-hover border rounded-3" style="overflow: hidden;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            Aucune catégorie trouvée. Commencez par en ajouter une !
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-light text-dark"><?= htmlspecialchars((string) ($category['id_categorie'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars((string) ($category['nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars((string) ($category['description'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                                                </small>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editCategoryModal"
                                                    onclick="loadCategoryEdit(<?= (int) ($category['id_categorie'] ?? 0); ?>, '<?= htmlspecialchars((string) ($category['nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars((string) ($category['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>')">
                                                    <i class="bi bi-pencil"></i> Modifier
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteCategoryConfirm(<?= (int) ($category['id_categorie'] ?? 0); ?>)">
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="bi bi-plus-circle"></i> Ajouter une catégorie
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Ajouter une catégorie -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="addCategoryLabel">Ajouter une catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?route=categories" class="needs-validation">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">

                    <div class="mb-3">
                        <label for="addNom" class="form-label fw-bold">Nom de la catégorie <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="addNom" name="nom" placeholder="Ex: Forgeuses" required>
                        <small class="form-text text-muted">Le nom est obligatoire</small>
                    </div>

                    <div class="mb-3">
                        <label for="addDescription" class="form-label fw-bold">Description</label>
                        <textarea class="form-control rounded-3" id="addDescription" name="description" rows="3" placeholder="Description détaillée..."></textarea>
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

<!-- Modal Modifier une catégorie -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="editCategoryLabel">Modifier une catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?route=categories" class="needs-validation">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editId" value="">

                    <div class="mb-3">
                        <label for="editNom" class="form-label fw-bold">Nom de la catégorie <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="editNom" name="nom" placeholder="Ex: Forgeuses" required>
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label fw-bold">Description</label>
                        <textarea class="form-control rounded-3" id="editDescription" name="description" rows="3" placeholder="Description détaillée..."></textarea>
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
<form id="deleteForm" method="POST" action="index.php?route=categories" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId" value="">
</form>

<script>
function loadCategoryEdit(id, nom, description) {
    document.getElementById('editId').value = id;
    document.getElementById('editNom').value = nom;
    document.getElementById('editDescription').value = description;
}

function deleteCategoryConfirm(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?\n\nCette action est irréversible.')) {
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
