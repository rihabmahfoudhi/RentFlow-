<div class="container py-5">
    <div class="feature-card p-4 p-lg-5">
        <div class="section-kicker mb-2">A propos</div>
        <h1 class="fw-bold mb-3"><?= htmlspecialchars((string) ($pageTitle ?? 'A propos'), ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="muted-text mb-4"><?= htmlspecialchars((string) ($pageSubtitle ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="row g-4">
            <div class="col-lg-6">
                <h2 class="h4 mb-3">Pourquoi ce template</h2>
                <p class="mb-0">RentFlow est conçu pour offrir une base moderne et fiable à votre activité de location d’équipements.</p>
            </div>
            <div class="col-lg-6">
                <h2 class="h4 mb-3">Ce qu'il contient</h2>
                <ul class="mb-0">
                    <li>Une navigation simple</li>
                    <li>Une page d'accueil reutilisable</li>
                    <li>Des sections faciles a remplacer</li>
                </ul>
            </div>
        </div>
    </div>
</div>
