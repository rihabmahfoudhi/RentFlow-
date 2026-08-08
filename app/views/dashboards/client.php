<div class="container-fluid py-4" style="background:#f8fafc; min-height:100vh;">
    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="img/logo-rentflow.svg" alt="RentFlow Logo" style="height:100px;">
                        <div>
                            <h5 class="mb-0">Front Office</h5>
                            <p class="text-muted mb-0">Client</p>
                        </div>
                    </div>
                    <div class="list-group list-group-flush mb-4">
                        <a href="index.php?route=client-dashboard" class="list-group-item list-group-item-action rounded-3 mb-2"> Catégories</a>
                        <a href="index.php?route=client-dashboard" class="list-group-item list-group-item-action rounded-3 mb-2"> Demande de location</a>
                        <a href="index.php?route=client-dashboard" class="list-group-item list-group-item-action rounded-3 mb-2"> Historique de location</a>
                        <a href="index.php?route=client-dashboard" class="list-group-item list-group-item-action rounded-3 mb-2"> Enregistrer un retour</a>
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
                            <div class="section-kicker">Bienvenue</div>
                            <h2 class="fw-bold mb-1">Dashboard Client</h2>
                            <p class="text-muted mb-0">Bonjour <?= htmlspecialchars((string) (($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')), ENT_QUOTES, 'UTF-8'); ?>.</p>
                        </div>
                    </div>

                    <?php if (!empty($flash)): ?>
                        <div class="alert alert-<?= htmlspecialchars((string) ($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8'); ?> rounded-3">
                            <?= htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-4 p-4 h-100 bg-white">
                                <h5 class="fw-bold mb-2">Votre espace client</h5>
                                <p class="text-muted mb-0">Consultez vos demandes, suivez vos locations et gérez vos retours.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-4 p-4 h-100 bg-white">
                                <h5 class="fw-bold mb-2">Accès rapide</h5>
                                <p class="text-muted mb-0">Les rubriques du menu sont prêtes pour votre prochain développement.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
