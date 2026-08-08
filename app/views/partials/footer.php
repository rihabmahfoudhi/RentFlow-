<?php $siteName = htmlspecialchars((string) ($siteName ?? 'RentFlow'), ENT_QUOTES, 'UTF-8'); ?>

<footer class="mt-5 py-4" style="background:#0f172a;color:#cbd5e1;">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <div class="fw-bold text-white mb-1"><?= $siteName; ?></div>
            <div class="small">RentFlow, votre solution moderne de location d’équipements.</div>
        </div>
        <div class="small">Accueil · A propos · Services · Contact</div>
    </div>
</footer>
