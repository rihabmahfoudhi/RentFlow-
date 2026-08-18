<?php $siteName = htmlspecialchars((string) ($siteName ?? 'RentFlow'), ENT_QUOTES, 'UTF-8'); ?>

<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3" style="background: linear-gradient(135deg, #0f172a, #111827);">
    <a href="index.php?route=home" class="navbar-brand fw-bold text-white"><img src="img/logo.png" alt="RentFlow Logo" style="height:80px; background-color: white; padding: 5px; border-radius: 5px;"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto">
            <a href="index.php?route=home" class="nav-item nav-link <?= htmlspecialchars((string) ($navHomeClass ?? ''), ENT_QUOTES, 'UTF-8'); ?>">Accueil</a>
            <a href="index.php?route=about" class="nav-item nav-link <?= htmlspecialchars((string) ($navAboutClass ?? ''), ENT_QUOTES, 'UTF-8'); ?>">A propos</a>
            <a href="index.php?route=services" class="nav-item nav-link <?= htmlspecialchars((string) ($navServicesClass ?? ''), ENT_QUOTES, 'UTF-8'); ?>">Services</a>
            <a href="index.php?route=contact" class="nav-item nav-link <?= htmlspecialchars((string) ($navContactClass ?? ''), ENT_QUOTES, 'UTF-8'); ?>">Contact</a>
            <a href="index.php?route=login" class="nav-item nav-link">Connexion</a>
            <a href="index.php?route=register" class="nav-item nav-link">Inscription</a>
        </div>
    </div>
</nav>
