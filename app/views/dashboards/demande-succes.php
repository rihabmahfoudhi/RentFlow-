<div class="fo-shell">
    <!-- Sidebar -->
    <aside class="fo-sidebar">
        <div class="fo-sidebar-inner">
            <div class="fo-brand">
                <img src="img/logo-rentflow.svg" alt="RentFlow" class="fo-logo">
                <div>
                    <div class="fo-brand-name">Front Office</div>
                    <div class="fo-brand-role">Client</div>
                </div>
            </div>

            <nav class="fo-nav">
                <a href="index.php?route=client-dashboard" class="fo-nav-link">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="index.php?route=catalogue" class="fo-nav-link">
                    <i class="fas fa-th-large"></i> Catégories
                </a>
                <a href="index.php?route=demande-location" class="fo-nav-link fo-nav-link--active">
                    <i class="fas fa-file-signature"></i> Demande de location
                </a>
                <a href="index.php?route=mes-locations" class="fo-nav-link">
                    <i class="fas fa-history"></i> Historique de location
                </a>
            </nav>

            <a href="index.php?route=logout" class="fo-logout-btn">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </aside>

    <!-- Main -->
    <main class="fo-main d-flex justify-content-center align-items-center">
        <div class="fo-success-card text-center">
            <div class="fo-success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1 class="fo-success-title">Demande Envoyée !</h1>
            <p class="fo-success-desc">
                Votre demande de location pour <strong><?= htmlspecialchars((string)($location['equipement_nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong> a été enregistrée avec succès. Elle est actuellement en attente de validation.
            </p>

            <div class="fo-success-actions mt-4 d-flex justify-content-center gap-3 flex-wrap">
                <a href="index.php?route=telecharger-recu&id=<?= (int)$location['id_location'] ?>" class="fo-btn-download">
                    <i class="fas fa-file-pdf"></i> Télécharger mon reçu
                </a>
                
                <a href="index.php?route=client-dashboard" class="fo-btn-home">
                    <i class="fas fa-home"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </main>
</div>

<style>
/* ===== Réutilise les styles du catalogue.php (sidebar, shell, nav…) ===== */
.fo-shell {
    display: flex;
    min-height: calc(100vh - 80px);
    background: #f0f4f8;
    font-family: 'Inter', sans-serif;
}
.fo-sidebar {
    width: 270px; min-width: 270px;
    background: #fff; border-right: 1px solid #e8ecf0;
    display: flex; flex-direction: column;
}
.fo-sidebar-inner {
    padding: 28px 20px;
    display: flex; flex-direction: column; height: 100%;
    position: sticky; top: 0;
}
.fo-brand { display:flex; align-items:center; gap:14px; margin-bottom:36px; padding-bottom:24px; border-bottom:1px solid #f0f4f8; }
.fo-logo  { height:60px; }
.fo-brand-name { font-weight:700; font-size:1rem; color:#0f172a; }
.fo-brand-role  { font-size:.8rem; color:#94a3b8; }
.fo-nav { display:flex; flex-direction:column; gap:6px; flex:1; }
.fo-nav-link {
    display:flex; align-items:center; gap:12px; padding:12px 16px;
    border-radius:12px; color:#475569; text-decoration:none;
    font-size:.93rem; font-weight:500; transition:all .18s ease;
}
.fo-nav-link i { width:18px; text-align:center; font-size:.95rem; }
.fo-nav-link:hover { background:#f8fafc; color:#0f172a; }
.fo-nav-link--active { background:linear-gradient(135deg,#0f766e15,#155eef15); color:#0f766e; font-weight:600; }
.fo-logout-btn {
    display:flex; align-items:center; gap:10px; margin-top:20px; padding:13px 16px;
    border-radius:12px; background:#fff1f2; color:#e11d48;
    text-decoration:none; font-weight:600; font-size:.93rem; transition:all .18s ease;
}
.fo-logout-btn:hover { background:#ffe4e6; color:#be123c; }
.fo-main { flex:1; padding:36px 40px; overflow:auto; }

/* Success Card */
.fo-success-card {
    background: #fff;
    border-radius: 20px;
    padding: 50px 40px;
    max-width: 600px;
    width: 100%;
    box-shadow: 0 10px 40px rgba(0,0,0,.04);
}
.fo-success-icon {
    font-size: 5rem;
    color: #10b981;
    margin-bottom: 24px;
}
.fo-success-title {
    font-size: 2rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 16px;
}
.fo-success-desc {
    font-size: 1.05rem;
    color: #64748b;
    line-height: 1.6;
}

.fo-btn-download {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 24px;
    background: #ef4444; /* Rouge PDF */
    color: #fff;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}
.fo-btn-download:hover {
    background: #dc2626;
    color: #fff;
    transform: translateY(-2px);
}

.fo-btn-home {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 24px;
    background: #f8fafc;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s;
}
.fo-btn-home:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #cbd5e1;
}

/* ===== RESPONSIVE ===== */
@media (max-width:991px) {
    .fo-shell { flex-direction:column; }
    .fo-sidebar { width:100%; min-width:unset; border-right:none; border-bottom:1px solid #e8ecf0; }
    .fo-sidebar-inner { position:static; padding:16px 20px; }
    .fo-nav { flex-direction:row; flex-wrap:wrap; gap:4px; }
    .fo-nav-link { padding:8px 12px; font-size:.85rem; }
    .fo-brand { margin-bottom:16px; padding-bottom:16px; }
    .fo-main { padding:24px 20px; }
}
</style>
