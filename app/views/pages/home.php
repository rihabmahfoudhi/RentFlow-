<div class="container py-5">
    <div class="hero-shell p-4 p-lg-5 mb-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="hero-badge mb-3"><i class="bi bi-sparkles"></i> RentFlow</span>
                <h1 class="display-4 fw-bold mb-3">Une plateforme moderne pour la location d’équipements.</h1>
                <p class="lead mb-4" style="max-width: 36rem;">RentFlow offre une structure simple, claire et prête à évoluer pour gérer vos équipements, locations et utilisateurs.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="index.php?route=services" class="btn btn-brand btn-lg text-white">Voir la structure</a>
                    <a href="index.php?route=contact" class="btn btn-outline-light btn-lg">Commencer</a>
                    <a href="index.php?route=register" class="btn btn-light btn-lg">S'inscrire</a>
                    <a href="index.php?route=login" class="btn btn-outline-light btn-lg">Connexion</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="feature-card p-4 text-dark">
                    <div class="section-kicker mb-2">Ce que tu obtiens</div>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex gap-3 mb-3"><i class="bi bi-check-circle-fill text-success mt-1"></i><span>Trouvez l'équipement adapté à vos besoins.</span></li>
                        <li class="d-flex gap-3 mb-3"><i class="bi bi-check-circle-fill text-success mt-1"></i><span>Réservez vos équipements en quelques clics.</span></li>
                        <li class="d-flex gap-3"><i class="bi bi-check-circle-fill text-success mt-1"></i><span>Suivez vos locations facilement depuis votre tableau de bord.</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <?php foreach (($highlights ?? []) as $highlight): ?>
            <div class="col-md-4">
                <div class="feature-card h-100 p-4">
                    <h3 class="h5 mb-2"><?= htmlspecialchars((string) ($highlight['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p class="muted-text mb-0"><?= htmlspecialchars((string) ($highlight['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="section-soft rounded-4 p-4 p-lg-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="section-kicker mb-2">Prochaines etapes</div>
                <h2 class="fw-bold mb-3">Structure de depart</h2>
                <p class="muted-text mb-0">Les pages principales sont deja la. Tu peux les remplacer par tes propres contenus sans devoir nettoyer l'ancien projet.</p>
            </div>
            <div class="col-lg-6">
                <div class="feature-card p-4">
                    <?php foreach (($steps ?? []) as $step): ?>
                        <div class="d-flex gap-3 mb-3">
                            <div class="badge bg-primary rounded-pill mt-1">OK</div>
                            <div><?= htmlspecialchars((string) $step, ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
