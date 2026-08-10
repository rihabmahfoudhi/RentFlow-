<?php
// Même palette que dans catalogue.php
$palette = [
    ['bg' => '#e8f4fd', 'icon' => '#2196f3', 'border' => '#bbdefb', 'badge' => '#1565c0'],
    ['bg' => '#fce4ec', 'icon' => '#e91e63', 'border' => '#f8bbd9', 'badge' => '#880e4f'],
    ['bg' => '#e8f5e9', 'icon' => '#4caf50', 'border' => '#c8e6c9', 'badge' => '#1b5e20'],
    ['bg' => '#fff3e0', 'icon' => '#ff9800', 'border' => '#ffe0b2', 'badge' => '#e65100'],
    ['bg' => '#f3e5f5', 'icon' => '#9c27b0', 'border' => '#e1bee7', 'badge' => '#4a148c'],
    ['bg' => '#e0f2f1', 'icon' => '#009688', 'border' => '#b2dfdb', 'badge' => '#004d40'],
    ['bg' => '#fbe9e7', 'icon' => '#ff5722', 'border' => '#ffccbc', 'badge' => '#bf360c'],
    ['bg' => '#e1f5fe', 'icon' => '#03a9f4', 'border' => '#b3e5fc', 'badge' => '#01579b'],
];

$etatConfig = [
    'Disponible'   => ['color' => '#166534', 'bg' => '#dcfce7', 'dot' => '#22c55e'],
    'En location'  => ['color' => '#1e40af', 'bg' => '#dbeafe', 'dot' => '#3b82f6'],
    'Maintenance'  => ['color' => '#854d0e', 'bg' => '#fef9c3', 'dot' => '#eab308'],
    'Endommage'    => ['color' => '#991b1b', 'bg' => '#fee2e2', 'dot' => '#ef4444'],
];
?>

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
                <a href="index.php?route=catalogue" class="fo-nav-link fo-nav-link--active">
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
        <!-- Breadcrumb -->
        <nav class="fo-breadcrumb">
            <a href="index.php?route=catalogue"><i class="fas fa-th-large"></i> Catégories</a>
            <span class="fo-bc-sep"><i class="fas fa-chevron-right"></i></span>
            <span><?= htmlspecialchars((string)($categorie['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
        </nav>

        <!-- Page header -->
        <div class="fo-page-header">
            <div>
                <div class="fo-kicker">Catalogue</div>
                <h1 class="fo-page-title"><?= htmlspecialchars((string)($categorie['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h1>
                <?php if (!empty($categorie['description'])): ?>
                    <p class="fo-page-subtitle"><?= htmlspecialchars((string)$categorie['description'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php else: ?>
                    <p class="fo-page-subtitle">Tous les équipements disponibles dans cette catégorie.</p>
                <?php endif; ?>
            </div>
            <div class="fo-header-actions">
                <a href="index.php?route=catalogue" class="fo-btn-back">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <span class="fo-badge-count">
                    <i class="fas fa-cubes"></i>
                    <?= count($equipements) ?> équipement<?= count($equipements) !== 1 ? 's' : '' ?>
                </span>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="fo-alert fo-alert--<?= htmlspecialchars((string)($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars((string)($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (empty($equipements)): ?>
            <div class="fo-empty">
                <div class="fo-empty-icon"><i class="fas fa-box-open"></i></div>
                <h3>Aucun équipement dans cette catégorie</h3>
                <p>Revenez bientôt, le catalogue est en cours d'enrichissement.</p>
                <a href="index.php?route=catalogue" class="fo-btn-back mt-3 d-inline-flex">
                    <i class="fas fa-arrow-left"></i> Retour aux catégories
                </a>
            </div>
        <?php else: ?>
            <div class="fo-equip-grid">
                <?php foreach ($equipements as $i => $eq):
                    $colors  = $palette[$i % count($palette)];
                    $etat    = (string)($eq['etat'] ?? 'Disponible');
                    $ecfg    = $etatConfig[$etat] ?? $etatConfig['Disponible'];
                    $dispo   = ($etat === 'Disponible') && ((int)($eq['stock'] ?? 0) > 0);
                ?>
                    <div class="fo-eq-card<?= $dispo ? '' : ' fo-eq-card--unavail' ?>"
                         style="--eq-bg:<?= $colors['bg'] ?>; --eq-icon:<?= $colors['icon'] ?>; --eq-border:<?= $colors['border'] ?>;">

                        <!-- Top icon zone -->
                        <div class="fo-eq-icon-zone">
                            <div class="fo-eq-icon-wrap">
                                <i class="fas fa-tools fo-eq-icon"></i>
                            </div>
                            <!-- Etat badge -->
                            <span class="fo-etat-badge"
                                  style="background:<?= $ecfg['bg'] ?>; color:<?= $ecfg['color'] ?>;">
                                <span class="fo-etat-dot" style="background:<?= $ecfg['dot'] ?>;"></span>
                                <?= htmlspecialchars($etat, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>

                        <!-- Info -->
                        <div class="fo-eq-body">
                            <h3 class="fo-eq-name"><?= htmlspecialchars((string)($eq['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>

                            <?php if (!empty($eq['description'])): ?>
                                <p class="fo-eq-desc"><?= htmlspecialchars((string)$eq['description'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>

                            <div class="fo-eq-meta">
                                <div class="fo-eq-meta-item">
                                    <i class="fas fa-tag"></i>
                                    <span><?= number_format((float)($eq['prix_jour'] ?? 0), 2, ',', ' ') ?> dt<small>/jour</small></span>
                                </div>
                                <div class="fo-eq-meta-item">
                                    <i class="fas fa-cubes"></i>
                                    <span><?= (int)($eq['stock'] ?? 0) ?> en stock</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="fo-eq-footer">
                            <?php if ($dispo): ?>
                                <a href="index.php?route=demande-location&id_eq=<?= (int)$eq['id_eq'] ?>"
                                   class="fo-btn-louer">
                                    <i class="fas fa-handshake"></i> Louer
                                </a>
                            <?php else: ?>
                                <button class="fo-btn-louer fo-btn-louer--disabled" disabled>
                                    <i class="fas fa-ban"></i> Indisponible
                                </button>
                            <?php endif; ?>
                            <a href="index.php?route=equipement-detail&id_eq=<?= (int)$eq['id_eq'] ?>"
                               class="fo-btn-detail">
                                <i class="fas fa-info-circle"></i> Détails
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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

/* Breadcrumb */
.fo-breadcrumb {
    display:flex; align-items:center; gap:10px; margin-bottom:20px;
    font-size:.875rem; color:#64748b;
}
.fo-breadcrumb a { color:#0f766e; text-decoration:none; display:flex; align-items:center; gap:6px; }
.fo-breadcrumb a:hover { text-decoration:underline; }
.fo-bc-sep { color:#cbd5e1; font-size:.7rem; }

/* Header */
.fo-page-header {
    display:flex; justify-content:space-between; align-items:flex-start;
    margin-bottom:36px; flex-wrap:wrap; gap:16px;
}
.fo-kicker { font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:#0f766e; margin-bottom:6px; }
.fo-page-title { font-size:1.85rem; font-weight:800; color:#0f172a; margin:0 0 6px; line-height:1.2; }
.fo-page-subtitle { color:#64748b; font-size:.95rem; margin:0; }
.fo-header-actions { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
.fo-badge-count {
    display:inline-flex; align-items:center; gap:8px; padding:8px 18px;
    background:#fff; border:1px solid #e2e8f0; border-radius:999px;
    font-size:.875rem; font-weight:600; color:#475569; box-shadow:0 1px 4px rgba(0,0,0,.06);
}
.fo-badge-count i { color:#0f766e; }

.fo-btn-back {
    display:inline-flex; align-items:center; gap:8px; padding:10px 18px;
    border-radius:12px; background:#fff; border:1.5px solid #e2e8f0;
    color:#475569; text-decoration:none; font-size:.875rem; font-weight:600;
    transition:all .18s ease;
}
.fo-btn-back:hover { background:#f8fafc; color:#0f172a; border-color:#cbd5e1; }

/* Alerts */
.fo-alert { padding:14px 18px; border-radius:12px; margin-bottom:24px; font-size:.93rem; font-weight:500; }
.fo-alert--success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.fo-alert--danger  { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.fo-alert--warning { background:#fef9c3; color:#854d0e; border:1px solid #fef08a; }
.fo-alert--info    { background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe; }

/* Empty */
.fo-empty { text-align:center; padding:80px 20px; color:#94a3b8; }
.fo-empty-icon { font-size:3.5rem; margin-bottom:20px; opacity:.4; }
.fo-empty h3   { font-size:1.2rem; color:#475569; margin-bottom:8px; }
.fo-empty p    { font-size:.93rem; }

/* ===== EQUIPMENT GRID ===== */
.fo-equip-grid {
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap:24px;
}

.fo-eq-card {
    background:#fff;
    border:1.5px solid var(--eq-border);
    border-radius:20px;
    display:flex; flex-direction:column;
    overflow:hidden;
    transition:transform .22s ease, box-shadow .22s ease;
}
.fo-eq-card:hover { transform:translateY(-6px); box-shadow:0 24px 56px rgba(0,0,0,.13); }
.fo-eq-card--unavail { opacity:.72; }
.fo-eq-card--unavail:hover { transform:none; box-shadow:none; }

.fo-eq-icon-zone {
    background:var(--eq-bg);
    padding:32px 24px 22px;
    display:flex; flex-direction:column; align-items:center; gap:16px;
    position:relative;
}
.fo-eq-icon-wrap {
    width:76px; height:76px; border-radius:50%;
    background:rgba(255,255,255,.7); display:flex; align-items:center; justify-content:center;
    box-shadow:0 4px 16px rgba(0,0,0,.1);
    transition:transform .22s ease;
}
.fo-eq-card:hover .fo-eq-icon-wrap { transform:scale(1.08) rotate(-4deg); }
.fo-eq-icon { font-size:2rem; color:var(--eq-icon); }

.fo-etat-badge {
    display:inline-flex; align-items:center; gap:6px; padding:5px 12px;
    border-radius:999px; font-size:.78rem; font-weight:600;
}
.fo-etat-dot { width:7px; height:7px; border-radius:50%; display:inline-block; }

.fo-eq-body { padding:20px 22px; flex:1; }
.fo-eq-name {
    font-size:1.05rem; font-weight:700; color:#0f172a;
    margin:0 0 8px; line-height:1.3;
}
.fo-eq-desc {
    font-size:.84rem; color:#64748b; line-height:1.55;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    margin-bottom:14px;
}
.fo-eq-meta { display:flex; gap:18px; flex-wrap:wrap; }
.fo-eq-meta-item {
    display:flex; align-items:center; gap:7px;
    font-size:.84rem; color:#475569; font-weight:500;
}
.fo-eq-meta-item i { color:#0f766e; font-size:.85rem; }
.fo-eq-meta-item small { font-size:.75rem; color:#94a3b8; margin-left:1px; }

.fo-eq-footer {
    padding:14px 22px 20px;
    display:flex; gap:10px; align-items:center;
    border-top:1px solid #f1f5f9;
}
.fo-btn-louer {
    flex:1; display:flex; align-items:center; justify-content:center; gap:8px;
    padding:11px 0;
    background:linear-gradient(135deg, #0f766e, #0d9488);
    color:#fff; text-decoration:none;
    border-radius:12px; font-weight:700; font-size:.9rem;
    border:none; cursor:pointer;
    transition:all .2s ease; box-shadow:0 4px 14px rgba(15,118,110,.35);
}
.fo-btn-louer:hover { background:linear-gradient(135deg,#0d9488,#0f766e); box-shadow:0 6px 20px rgba(15,118,110,.5); color:#fff; transform:translateY(-1px); }
.fo-btn-louer--disabled {
    background:#e2e8f0; color:#94a3b8; box-shadow:none; cursor:not-allowed;
}
.fo-btn-louer--disabled:hover { transform:none; }

.fo-btn-detail {
    display:flex; align-items:center; gap:6px; padding:11px 14px;
    border-radius:12px; background:#f8fafc; border:1.5px solid #e2e8f0;
    color:#475569; text-decoration:none; font-size:.85rem; font-weight:600;
    transition:all .18s ease; white-space:nowrap;
}
.fo-btn-detail:hover { background:#f1f5f9; color:#0f172a; border-color:#cbd5e1; }

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
@media (max-width:600px) {
    .fo-equip-grid { grid-template-columns:1fr; gap:16px; }
    .fo-page-title { font-size:1.4rem; }
    .fo-main { padding:18px 14px; }
}
</style>
