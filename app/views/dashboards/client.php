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
                <a href="index.php?route=client-dashboard" class="fo-nav-link fo-nav-link--active">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="index.php?route=catalogue" class="fo-nav-link">
                    <i class="fas fa-th-large"></i> Catégories
                </a>
                <a href="index.php?route=demande-location" class="fo-nav-link">
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
    <main class="fo-main">
        <div class="fo-page-header">
            <div>
                <div class="fo-kicker">Bienvenue</div>
                <h1 class="fo-page-title">Bonjour, <?= htmlspecialchars((string)(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')), ENT_QUOTES, 'UTF-8') ?> 👋</h1>
                <p class="fo-page-subtitle">Consultez notre catalogue et effectuez vos demandes de location facilement.</p>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="fo-alert fo-alert--<?= htmlspecialchars((string)($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars((string)($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <!-- Quick actions -->
        <div class="fo-quick-grid">
            <a href="index.php?route=catalogue" class="fo-quick-card" style="--qc:#e8f4fd; --qi:#2196f3;">
                <div class="fo-quick-icon"><i class="fas fa-th-large"></i></div>
                <div class="fo-quick-label">Catalogue</div>
                <div class="fo-quick-sub">Parcourir les catégories</div>
            </a>
            <a href="index.php?route=demande-location" class="fo-quick-card" style="--qc:#e8f5e9; --qi:#4caf50;">
                <div class="fo-quick-icon"><i class="fas fa-file-signature"></i></div>
                <div class="fo-quick-label">Faire une demande</div>
                <div class="fo-quick-sub">Réservez un équipement</div>
            </a>
            <a href="index.php?route=mes-locations" class="fo-quick-card" style="--qc:#fff3e0; --qi:#ff9800;">
                <div class="fo-quick-icon"><i class="fas fa-history"></i></div>
                <div class="fo-quick-label">Mes Locations</div>
                <div class="fo-quick-sub">Suivre mes réservations</div>
            </a>
        </div>
    </main>
</div>

<style>
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
.fo-page-header { margin-bottom:36px; }
.fo-kicker { font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:#0f766e; margin-bottom:6px; }
.fo-page-title { font-size:1.85rem; font-weight:800; color:#0f172a; margin:0 0 6px; }
.fo-page-subtitle { color:#64748b; font-size:.95rem; margin:0; }
.fo-alert { padding:14px 18px; border-radius:12px; margin-bottom:24px; font-size:.93rem; font-weight:500; }
.fo-alert--success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.fo-alert--danger  { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.fo-alert--warning { background:#fef9c3; color:#854d0e; border:1px solid #fef08a; }
.fo-alert--info    { background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe; }

.fo-quick-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}
.fo-quick-card {
    background: var(--qc);
    border-radius: 20px;
    padding: 28px 22px;
    text-decoration: none;
    display: flex; flex-direction: column; align-items: center; text-align: center;
    transition: transform .2s ease, box-shadow .2s ease;
    border: 1.5px solid transparent;
}
.fo-quick-card:hover { transform: translateY(-5px); box-shadow: 0 16px 40px rgba(0,0,0,.1); border-color: var(--qi); }
.fo-quick-icon {
    width: 64px; height: 64px; border-radius: 50%;
    background: rgba(255,255,255,.7); display:flex; align-items:center; justify-content:center;
    font-size: 1.6rem; color: var(--qi); margin-bottom: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}
.fo-quick-label { font-weight: 700; font-size: 1rem; color: #0f172a; margin-bottom: 4px; }
.fo-quick-sub   { font-size: .82rem; color: #64748b; }

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
