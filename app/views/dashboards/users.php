<?php
$roleLabels = [
    'Responsable' => ['Responsable', 'danger'],
    'Agent' => ['Agent', 'primary'],
    'Client' => ['Client', 'success'],
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
                        <a href="index.php?route=categories" class="list-group-item list-group-item-action rounded-3 mb-2">Categories des equipements</a>
                        <a href="index.php?route=equipements" class="list-group-item list-group-item-action rounded-3 mb-2">Equipements</a>
                        <a href="index.php?route=locations" class="list-group-item list-group-item-action rounded-3 mb-2">Locations</a>
                        <a href="index.php?route=admin-dashboard" class="list-group-item list-group-item-action rounded-3 mb-2">Retours</a>
                        <a href="index.php?route=utilisateurs" class="list-group-item list-group-item-action rounded-3 mb-2 active">Utilisateurs</a>
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
                            <h2 class="fw-bold mb-1">Gestion des utilisateurs</h2>
                            <p class="text-muted mb-0">Gerez les comptes et les roles de la plateforme</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-plus-circle"></i> Ajouter un utilisateur
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
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Telephone</th>
                                    <th>Role</th>
                                    <th>Date creation</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            Aucun utilisateur trouve. Commencez par en ajouter un !
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $managedUser): ?>
                                        <?php
                                            $roleKey = (string) ($managedUser['role'] ?? 'Client');
                                            [$roleLabel, $roleColor] = $roleLabels[$roleKey] ?? [$roleKey, 'secondary'];
                                            $fullName = trim((string) (($managedUser['prenom'] ?? '') . ' ' . ($managedUser['nom'] ?? '')));
                                            $editPayload = [
                                                (int) ($managedUser['id_user'] ?? 0),
                                                (string) ($managedUser['nom'] ?? ''),
                                                (string) ($managedUser['prenom'] ?? ''),
                                                (string) ($managedUser['email'] ?? ''),
                                                (string) ($managedUser['telephone'] ?? ''),
                                                (string) ($managedUser['role'] ?? 'Client'),
                                            ];
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-light text-dark"><?= htmlspecialchars((string) ($managedUser['id_user'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                                            </td>
                                            <td><strong><?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                            <td><?= htmlspecialchars((string) ($managedUser['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars((string) ($managedUser['telephone'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><span class="badge bg-<?= htmlspecialchars($roleColor, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($roleLabel, ENT_QUOTES, 'UTF-8'); ?></span></td>
                                            <td>
                                                <small>
                                                    <?php
                                                        $dateCreation = $managedUser['date_creation'] ?? '';
                                                        if ($dateCreation) {
                                                            echo htmlspecialchars((string) (new DateTime((string) $dateCreation))->format('d/m/Y H:i'), ENT_QUOTES, 'UTF-8');
                                                        }
                                                    ?>
                                                </small>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editUserModal"
                                                    onclick='loadUserEdit(<?= json_encode($editPayload, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>)'>
                                                    <i class="bi bi-pencil"></i> Modifier
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteUserConfirm(<?= (int) ($managedUser['id_user'] ?? 0); ?>)">
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

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="addUserLabel">Ajouter un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?route=utilisateurs" class="needs-validation">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">

                    <div class="mb-3">
                        <label for="addNom" class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="addNom" name="nom" required>
                    </div>

                    <div class="mb-3">
                        <label for="addPrenom" class="form-label fw-bold">Prenom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="addPrenom" name="prenom" required>
                    </div>

                    <div class="mb-3">
                        <label for="addEmail" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control rounded-3" id="addEmail" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="addPassword" class="form-label fw-bold">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control rounded-3" id="addPassword" name="mot_de_passe" minlength="8" required>
                    </div>

                    <div class="mb-3">
                        <label for="addTelephone" class="form-label fw-bold">Telephone</label>
                        <input type="text" class="form-control rounded-3" id="addTelephone" name="telephone">
                    </div>

                    <div class="mb-3">
                        <label for="addRole" class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="addRole" name="role" required>
                            <option value="">Choisir...</option>
                            <?php foreach ($roles as $roleValue): ?>
                                <option value="<?= htmlspecialchars($roleValue, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($roleLabels[$roleValue][0] ?? $roleValue, ENT_QUOTES, 'UTF-8'); ?></option>
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

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="editUserLabel">Modifier un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?route=utilisateurs" class="needs-validation">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editId" value="">

                    <div class="mb-3">
                        <label for="editNom" class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="editNom" name="nom" required>
                    </div>

                    <div class="mb-3">
                        <label for="editPrenom" class="form-label fw-bold">Prenom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" id="editPrenom" name="prenom" required>
                    </div>

                    <div class="mb-3">
                        <label for="editEmail" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control rounded-3" id="editEmail" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="editPassword" class="form-label fw-bold">Nouveau mot de passe</label>
                        <input type="password" class="form-control rounded-3" id="editPassword" name="mot_de_passe" minlength="8">
                        <small class="form-text text-muted">Laissez vide pour garder le mot de passe actuel.</small>
                    </div>

                    <div class="mb-3">
                        <label for="editTelephone" class="form-label fw-bold">Telephone</label>
                        <input type="text" class="form-control rounded-3" id="editTelephone" name="telephone">
                    </div>

                    <div class="mb-3">
                        <label for="editRole" class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="editRole" name="role" required>
                            <?php foreach ($roles as $roleValue): ?>
                                <option value="<?= htmlspecialchars($roleValue, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($roleLabels[$roleValue][0] ?? $roleValue, ENT_QUOTES, 'UTF-8'); ?></option>
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

<form id="deleteForm" method="POST" action="index.php?route=utilisateurs" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId" value="">
</form>

<script>
function loadUserEdit(payload) {
    document.getElementById('editId').value = payload[0];
    document.getElementById('editNom').value = payload[1];
    document.getElementById('editPrenom').value = payload[2];
    document.getElementById('editEmail').value = payload[3];
    document.getElementById('editTelephone').value = payload[4];
    document.getElementById('editRole').value = payload[5];
    document.getElementById('editPassword').value = '';
}

function deleteUserConfirm(id) {
    if (confirm('Etes-vous sur de vouloir supprimer cet utilisateur ?\n\nCette action est irreversible.')) {
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
