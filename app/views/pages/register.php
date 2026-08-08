<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 d-none d-md-block">
                        <div class="h-100 p-4 p-xl-5" style="background: linear-gradient(135deg, #0f172a 0%, #111827 50%, #0f766e 100%); color: white;">
                            <div class="section-kicker mb-3 text-white-50">Inscription</div>
                            <h2 class="fw-bold mb-3">Créer votre compte</h2>
                            <p class="mb-0">Rejoignez la plateforme de location d’équipements et gérez vos accès en toute simplicité.</p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="p-4 p-xl-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h3 class="fw-bold mb-1">S’inscrire</h3>
                                    <p class="text-muted mb-0">Remplissez les informations ci-dessous.</p>
                                </div>
                                <a href="index.php?route=home" class="btn btn-outline-secondary btn-sm">Retour</a>
                            </div>

                            <?php if (!empty($flash)): ?>
                                <div class="alert alert-<?= htmlspecialchars((string) ($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8'); ?> rounded-3">
                                    <?= htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" action="index.php?route=register" class="row g-3">
                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars((string) ($formData['nom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                    <?php if (!empty($errors['nom'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars((string) $errors['nom'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars((string) ($formData['prenom'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                    <?php if (!empty($errors['prenom'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars((string) $errors['prenom'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars((string) ($formData['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                    <?php if (!empty($errors['email'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars((string) $errors['email'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                                </div>

                                <div class="col-12">
                                    <label for="mot_de_passe" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                                    <?php if (!empty($errors['mot_de_passe'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars((string) $errors['mot_de_passe'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                                </div>

                                <div class="col-12">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars((string) ($formData['telephone'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                    <?php if (!empty($errors['telephone'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars((string) $errors['telephone'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Rôle</label>
                                    <div class="d-flex flex-wrap gap-3 mt-2">
                                        <?php foreach (($roles ?? []) as $value => $label): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="role" id="role_<?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>" value="<?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>" <?= (($formData['role'] ?? '') === $value) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="role_<?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?= htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8'); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (!empty($errors['role'])): ?><div class="text-danger small mt-2"><?= htmlspecialchars((string) $errors['role'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-brand text-white w-100 py-2">Créer mon compte</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
