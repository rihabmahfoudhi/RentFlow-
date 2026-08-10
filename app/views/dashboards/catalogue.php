<?php
// Icônes FontAwesome associées aux mots-clés courants de noms de catégories
$iconMap = [
    'son'          => 'fa-music',
    'audio'        => 'fa-music',
    'lumière'      => 'fa-lightbulb',
    'lumiere'      => 'fa-lightbulb',
    'éclairage'    => 'fa-lightbulb',
    'eclairage'    => 'fa-lightbulb',
    'vidéo'        => 'fa-video',
    'video'        => 'fa-video',
    'scène'        => 'fa-theater-masks',
    'scene'        => 'fa-theater-masks',
    'transport'    => 'fa-truck',
    'tente'        => 'fa-campground',
    'chapiteau'    => 'fa-campground',
    'mobilier'     => 'fa-chair',
    'table'        => 'fa-chair',
    'chaise'       => 'fa-chair',
    'électrique'   => 'fa-bolt',
    'electrique'   => 'fa-bolt',
    'groupe'       => 'fa-bolt',
    'informatique' => 'fa-laptop',
    'micro'        => 'fa-microphone',
    'instrument'   => 'fa-guitar',
    'photo'        => 'fa-camera',
    'cuisine'      => 'fa-utensils',
    'barnum'       => 'fa-campground',
    'podium'       => 'fa-broadcast-tower',
    'structure'    => 'fa-drafting-compass',
    'câble'        => 'fa-plug',
    'cable'        => 'fa-plug',
    'default'      => 'fa-box-open',
];

/**
 * Retourne l'icône FA la plus adaptée selon le nom de la catégorie
 */
function getCategoryIcon(string $nom, array $map): string {
    $nomLower = mb_strtolower($nom);
    foreach ($map as $keyword => $icon) {
        if ($keyword !== 'default' && str_contains($nomLower, $keyword)) {
            return $icon;
        }
    }
    return $map['default'];
}

// Palette de couleurs pour les cartes (rotation cyclique)
$palette = [
    ['bg' => '#e8f4fd', 'icon' => '#2196f3', 'border' => '#bbdefb'],
    ['bg' => '#fce4ec', 'icon' => '#e91e63', 'border' => '#f8bbd9'],
    ['bg' => '#e8f5e9', 'icon' => '#4caf50', 'border' => '#c8e6c9'],
    ['bg' => '#fff3e0', 'icon' => '#ff9800', 'border' => '#ffe0b2'],
    ['bg' => '#f3e5f5', 'icon' => '#9c27b0', 'border' => '#e1bee7'],
    ['bg' => '#e0f2f1', 'icon' => '#009688', 'border' => '#b2dfdb'],
    ['bg' => '#fbe9e7', 'icon' => '#ff5722', 'border' => '#ffccbc'],
    ['bg' => '#e1f5fe', 'icon' => '#03a9f4', 'border' => '#b3e5fc'],
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
        <!-- Page header -->
        <div class="fo-page-header">
            <div>
                <div class="fo-kicker">Catalogue</div>
                <h1 class="fo-page-title">Nos Catégories</h1>
                <p class="fo-page-subtitle">Sélectionnez une catégorie pour découvrir les équipements disponibles à la location.</p>
            </div>
            <div class="fo-header-meta">
                <span class="fo-badge-count">
                    <i class="fas fa-layer-group"></i>
                    <?= count($categories) ?> catégorie<?= count($categories) !== 1 ? 's' : '' ?>
                </span>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="fo-alert fo-alert--<?= htmlspecialchars((string)($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars((string)($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (empty($categories)): ?>
            <div class="fo-empty">
                <div class="fo-empty-icon"><i class="fas fa-box-open"></i></div>
                <h3>Aucune catégorie disponible</h3>
                <p>Le catalogue sera bientôt disponible. Revenez plus tard.</p>
            </div>
        <?php else: ?>
            <div class="fo-categories-grid">
                <?php foreach ($categories as $i => $cat):
                    $colors = $palette[$i % count($palette)];
                    $icon   = getCategoryIcon((string)($cat['nom'] ?? ''), $iconMap);
                ?>
                    <a href="index.php?route=catalogue-categorie&id_categorie=<?= (int)$cat['id_categorie'] ?>"
                       class="fo-cat-card"
                       style="--cat-bg:<?= $colors['bg'] ?>; --cat-icon:<?= $colors['icon'] ?>; --cat-border:<?= $colors['border'] ?>;">
                        <div class="fo-cat-icon-wrap">
                            <i class="fas <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?> fo-cat-icon"></i>
                        </div>
                        <div class="fo-cat-info">
                            <div class="fo-cat-name"><?= htmlspecialchars((string)($cat['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                            <?php if (!empty($cat['description'])): ?>
                                <div class="fo-cat-desc"><?= htmlspecialchars((string)$cat['description'], ENT_QUOTES, 'UTF-8') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="fo-cat-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<style>
/* ===== SHELL ===== */
.fo-shell {
    display: flex;
    min-height: calc(100vh - 80px);
    background: #f0f4f8;
    font-family: 'Inter', sans-serif;
}

/* ===== SIDEBAR ===== */
.fo-sidebar {
    width: 270px;
    min-width: 270px;
    background: #fff;
    border-right: 1px solid #e8ecf0;
    display: flex;
    flex-direction: column;
}
.fo-sidebar-inner {
    padding: 28px 20px;
    display: flex;
    flex-direction: column;
    height: 100%;
    position: sticky;
    top: 0;
}
.fo-brand {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 36px;
    padding-bottom: 24px;
    border-bottom: 1px solid #f0f4f8;
}
.fo-logo { height: 60px; }
.fo-brand-name { font-weight: 700; font-size: 1rem; color: #0f172a; }
.fo-brand-role  { font-size: .8rem; color: #94a3b8; }

.fo-nav { display: flex; flex-direction: column; gap: 6px; flex: 1; }
.fo-nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 12px;
    color: #475569;
    text-decoration: none;
    font-size: .93rem;
    font-weight: 500;
    transition: all .18s ease;
}
.fo-nav-link i { width: 18px; text-align: center; font-size: .95rem; }
.fo-nav-link:hover { background: #f8fafc; color: #0f172a; }
.fo-nav-link--active { background: linear-gradient(135deg, #0f766e15, #155eef15); color: #0f766e; font-weight: 600; }
.fo-nav-link--active i { color: #0f766e; }

.fo-logout-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
    padding: 13px 16px;
    border-radius: 12px;
    background: #fff1f2;
    color: #e11d48;
    text-decoration: none;
    font-weight: 600;
    font-size: .93rem;
    transition: all .18s ease;
}
.fo-logout-btn:hover { background: #ffe4e6; color: #be123c; }

/* ===== MAIN ===== */
.fo-main { flex: 1; padding: 36px 40px; overflow: auto; }

.fo-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 36px;
    flex-wrap: wrap;
    gap: 16px;
}
.fo-kicker {
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: #0f766e;
    margin-bottom: 6px;
}
.fo-page-title {
    font-size: 1.85rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px;
    line-height: 1.2;
}
.fo-page-subtitle {
    color: #64748b;
    font-size: .95rem;
    margin: 0;
}
.fo-badge-count {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    font-size: .875rem;
    font-weight: 600;
    color: #475569;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.fo-badge-count i { color: #0f766e; }

/* Alerts */
.fo-alert {
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 24px;
    font-size: .93rem;
    font-weight: 500;
}
.fo-alert--success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.fo-alert--danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.fo-alert--warning { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
.fo-alert--info    { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }

/* Empty state */
.fo-empty {
    text-align: center;
    padding: 80px 20px;
    color: #94a3b8;
}
.fo-empty-icon { font-size: 3.5rem; margin-bottom: 20px; opacity: .4; }
.fo-empty h3   { font-size: 1.2rem; color: #475569; margin-bottom: 8px; }
.fo-empty p    { font-size: .93rem; }

/* ===== CATEGORIES GRID ===== */
.fo-categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 22px;
}

.fo-cat-card {
    background: var(--cat-bg);
    border: 1.5px solid var(--cat-border);
    border-radius: 20px;
    padding: 28px 24px 22px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    text-decoration: none;
    position: relative;
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    cursor: pointer;
    overflow: hidden;
}
.fo-cat-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top right, rgba(255,255,255,.6), transparent 65%);
    pointer-events: none;
}
.fo-cat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 50px rgba(0,0,0,.12);
    border-color: var(--cat-icon);
    text-decoration: none;
}

.fo-cat-icon-wrap {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,.65);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
    transition: transform .22s ease;
}
.fo-cat-card:hover .fo-cat-icon-wrap {
    transform: scale(1.1) rotate(-5deg);
}
.fo-cat-icon {
    font-size: 2rem;
    color: var(--cat-icon);
}

.fo-cat-info { flex: 1; }
.fo-cat-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
    line-height: 1.3;
}
.fo-cat-desc {
    font-size: .83rem;
    color: #64748b;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
}

.fo-cat-arrow {
    margin-top: 16px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: var(--cat-icon);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    transition: transform .22s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,.18);
}
.fo-cat-card:hover .fo-cat-arrow { transform: translateX(4px); }

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .fo-shell { flex-direction: column; }
    .fo-sidebar { width: 100%; min-width: unset; border-right: none; border-bottom: 1px solid #e8ecf0; }
    .fo-sidebar-inner { position: static; padding: 16px 20px; }
    .fo-nav { flex-direction: row; flex-wrap: wrap; gap: 4px; }
    .fo-nav-link { padding: 8px 12px; font-size: .85rem; }
    .fo-brand { margin-bottom: 16px; padding-bottom: 16px; }
    .fo-main { padding: 24px 20px; }
}
@media (max-width: 600px) {
    .fo-categories-grid { grid-template-columns: 1fr 1fr; gap: 14px; }
    .fo-cat-card { padding: 20px 16px; }
    .fo-cat-icon-wrap { width: 62px; height: 62px; }
    .fo-cat-icon { font-size: 1.6rem; }
    .fo-page-title { font-size: 1.4rem; }
    .fo-main { padding: 18px 14px; }
}
</style>
