<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4 p-xl-5">
                    <h3 class="fw-bold mb-2">Connexion</h3>
                    <p class="text-muted mb-4">Accédez à votre espace selon votre rôle.</p>

                    <?php if (!empty($flash)): ?>
                        <div class="alert alert-<?= htmlspecialchars((string) ($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8'); ?> rounded-3">
                            <?= htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors['global'])): ?>
                        <div class="alert alert-danger rounded-3">
                            <?= htmlspecialchars((string) $errors['global'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="index.php?route=login" class="row g-3">
                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-12">
                            <label for="mot_de_passe" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-brand text-white w-100">Se connecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
