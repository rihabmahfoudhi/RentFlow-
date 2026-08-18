<?php
$currentView = $currentView ?? 'dashboard';
?>
<?php
$iconMap = [
    'son' => 'fa-music', 'audio' => 'fa-music', 'lumière' => 'fa-lightbulb', 'lumiere' => 'fa-lightbulb',
    'éclairage' => 'fa-lightbulb', 'eclairage' => 'fa-lightbulb', 'vidéo' => 'fa-video', 'video' => 'fa-video',
    'scène' => 'fa-theater-masks', 'scene' => 'fa-theater-masks', 'transport' => 'fa-truck',
    'tente' => 'fa-campground', 'chapiteau' => 'fa-campground', 'mobilier' => 'fa-chair', 'table' => 'fa-chair',
    'chaise' => 'fa-chair', 'électrique' => 'fa-bolt', 'electrique' => 'fa-bolt', 'groupe' => 'fa-bolt',
    'informatique' => 'fa-laptop', 'micro' => 'fa-microphone', 'instrument' => 'fa-guitar', 'photo' => 'fa-camera',
    'cuisine' => 'fa-utensils', 'barnum' => 'fa-campground', 'podium' => 'fa-broadcast-tower',
    'structure' => 'fa-drafting-compass', 'câble' => 'fa-plug', 'cable' => 'fa-plug', 'default' => 'fa-box-open',
];
if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon(string $nom, array $map): string {
        $nomLower = mb_strtolower($nom);
        foreach ($map as $keyword => $icon) {
            if ($keyword !== 'default' && str_contains($nomLower, $keyword)) return $icon;
        }
        return $map['default'];
    }
}
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
$locStatutConfig = [
    'En attente' => ['color' => '#854d0e', 'bg' => '#fef9c3', 'dot' => '#eab308'],
    'Acceptée'   => ['color' => '#1e40af', 'bg' => '#dbeafe', 'dot' => '#3b82f6'],
    'En cours'   => ['color' => '#5b21b6', 'bg' => '#ede9fe', 'dot' => '#8b5cf6'],
    'Terminée'   => ['color' => '#166534', 'bg' => '#dcfce7', 'dot' => '#22c55e'],
    'Annulée'    => ['color' => '#991b1b', 'bg' => '#fee2e2', 'dot' => '#ef4444'],
];
$prixJour = isset($equipement) ? (float)($equipement['prix_jour'] ?? 0) : 0;
?>
<div class="fo-shell">
    <aside class="fo-sidebar">
        <div class="fo-sidebar-inner">
            <div class="fo-brand">
                <img src="img/logo.png" alt="RentFlow Logo" class="fo-logo" style="background-color: white; padding: 5px; border-radius: 5px;">
                <div>
                    <div class="fo-brand-name">Front Office</div>
                    <div class="fo-brand-role">Client</div>
                </div>
            </div>

            <nav class="fo-nav">
                <a href="index.php?route=client-dashboard" class="fo-nav-link <?= $currentView === 'dashboard' ? 'fo-nav-link--active' : '' ?>">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="index.php?route=catalogue" class="fo-nav-link <?= in_array($currentView, ['catalogue', 'catalogue-categorie']) ? 'fo-nav-link--active' : '' ?>">
                    <i class="fas fa-th-large"></i> Catégories
                </a>
                <a href="index.php?route=demande-location" class="fo-nav-link <?= in_array($currentView, ['demande-location', 'demande-succes']) ? 'fo-nav-link--active' : '' ?>">
                    <i class="fas fa-file-signature"></i> Demande de location
                </a>
                <a href="index.php?route=mes-locations" class="fo-nav-link <?= $currentView === 'mes-locations' ? 'fo-nav-link--active' : '' ?>">
                    <i class="fas fa-history"></i> Historique
                </a>
            </nav>

            <a href="index.php?route=logout" class="fo-logout-btn">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </aside>

    <main class="fo-main <?= $currentView === 'demande-succes' ? 'd-flex justify-content-center align-items-center' : '' ?>">
        <?php if ($currentView === 'dashboard'): ?>
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
        <?php elseif ($currentView === 'catalogue'): ?>
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
        <?php elseif ($currentView === 'catalogue-categorie'): ?>
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
        <?php elseif ($currentView === 'demande-location'): ?>
            <!-- Breadcrumb -->
        <nav class="fo-breadcrumb">
            <a href="index.php?route=catalogue"><i class="fas fa-th-large"></i> Catégories</a>
            <span class="fo-bc-sep"><i class="fas fa-chevron-right"></i></span>
            <a href="index.php?route=catalogue-categorie&id_categorie=<?= (int)$equipement['categorie_id'] ?>">
                <?= htmlspecialchars((string)($equipement['categorie_nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
            </a>
            <span class="fo-bc-sep"><i class="fas fa-chevron-right"></i></span>
            <span>Demande de location</span>
        </nav>

        <!-- Page header -->
        <div class="fo-page-header">
            <div>
                <div class="fo-kicker">Réservation</div>
                <h1 class="fo-page-title">Demande de location</h1>
                <p class="fo-page-subtitle">Veuillez choisir les dates de location pour l'équipement sélectionné.</p>
            </div>
            <div class="fo-header-actions">
                <a href="index.php?route=catalogue-categorie&id_categorie=<?= (int)$equipement['categorie_id'] ?>" class="fo-btn-back">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="fo-alert fo-alert--<?= htmlspecialchars((string)($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars((string)($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div class="fo-form-wrapper">
            <div class="fo-form-card">
                <div class="fo-eq-summary">
                    <div class="fo-eq-summary-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="fo-eq-summary-info">
                        <h3><?= htmlspecialchars((string)($equipement['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= htmlspecialchars((string)($equipement['categorie_nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div class="fo-eq-summary-price">
                        <strong><?= number_format($prixJour, 2, ',', ' ') ?> dt</strong>
                        <small>/ jour</small>
                    </div>
                </div>

                <form action="index.php?route=demande-location" method="post" id="locationForm">
                    <input type="hidden" name="equipement_id" value="<?= (int)$equipement['id_eq'] ?>">
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date de début</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control form-control-lg fo-input" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date de fin</label>
                            <input type="date" name="date_fin" id="date_fin" class="form-control form-control-lg fo-input" required min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="fo-price-calc mt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fo-calc-label">Durée estimée :</span>
                            <span class="fo-calc-value" id="calc_jours">0 jour(s)</span>
                        </div>
                        <hr class="fo-hr">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fo-calc-label fw-bold">Prix Total :</span>
                            <span class="fo-calc-total" id="calc_total">0,00 dt</span>
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="fo-btn-submit">
                            <i class="fas fa-check-circle me-2"></i> Confirmer ma location
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php elseif ($currentView === 'demande-succes'): ?>
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
        <?php elseif ($currentView === 'mes-locations'): ?>
    <div class="fo-page-header">
        <div>
            <div class="fo-kicker">Suivi</div>
            <h1 class="fo-page-title">Mes Locations</h1>
            <p class="fo-page-subtitle">Retrouvez ici l'historique de toutes vos demandes de location.</p>
        </div>
        <div class="fo-header-meta">
            <span class="fo-badge-count">
                <i class="fas fa-history"></i>
                <?= count($locations) ?> location<?= count($locations) !== 1 ? 's' : '' ?>
            </span>
        </div>
    </div>

    <?php if (!empty($flash)): ?>
        <div class="fo-alert fo-alert--<?= htmlspecialchars((string)($flash['type'] ?? 'info'), ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars((string)($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($locations)): ?>
        <div class="fo-empty">
            <div class="fo-empty-icon"><i class="fas fa-history"></i></div>
            <h3>Aucune location pour le moment</h3>
            <p>Vos demandes de location apparaîtront ici dès que vous en aurez effectué une.</p>
            <a href="index.php?route=catalogue" class="fo-btn-back mt-3 d-inline-flex">
                <i class="fas fa-th-large"></i> Parcourir le catalogue
            </a>
        </div>
    <?php else: ?>
        <div class="fo-loc-table-wrap">
            <table class="fo-loc-table">
                <thead>
                    <tr>
                        <th>Équipement</th>
                        <th>Période</th>
                        <th>Durée</th>
                        <th>Prix total</th>
                        <th>Statut</th>
                        <th>Demandé le</th>
                        <th class="text-end">Reçu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($locations as $loc):
                        $statut = (string)($loc['statut'] ?? 'En attente');
                        $scfg   = $locStatutConfig[$statut] ?? $locStatutConfig['En attente'];
                        $dDebut = new DateTimeImmutable((string)$loc['date_debut']);
                        $dFin   = new DateTimeImmutable((string)$loc['date_fin']);
                        $days   = max(1, (int)$dDebut->diff($dFin)->days + 1);
                    ?>
                        <tr>
                            <td>
                                <div class="fo-loc-eq">
                                    <span class="fo-loc-eq-icon"><i class="fas fa-box-open"></i></span>
                                    <span><?= htmlspecialchars((string)($loc['equipement_nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </td>
                            <td>
                                Du <?= date('d/m/Y', strtotime((string)$loc['date_debut'])) ?><br>
                                au <?= date('d/m/Y', strtotime((string)$loc['date_fin'])) ?>
                            </td>
                            <td><?= $days ?> jour<?= $days > 1 ? 's' : '' ?></td>
                            <td><strong><?= number_format((float)($loc['prix_total'] ?? 0), 2, ',', ' ') ?> dt</strong></td>
                            <td>
                                <span class="fo-etat-badge" style="background:<?= $scfg['bg'] ?>; color:<?= $scfg['color'] ?>;">
                                    <span class="fo-etat-dot" style="background:<?= $scfg['dot'] ?>;"></span>
                                    <?= htmlspecialchars($statut, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime((string)($loc['date_creation'] ?? 'now'))) ?></td>
                            <td class="text-end">
                                <a href="index.php?route=telecharger-recu&id=<?= (int)$loc['id_location'] ?>"
                                   class="fo-btn-detail" title="Télécharger le reçu">
                                    <i class="fas fa-file-pdf"></i> Reçu
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
        <?php endif; ?>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const prixJour = <?= json_encode($prixJour) ?>;
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    const calcJours = document.getElementById('calc_jours');
    const calcTotal = document.getElementById('calc_total');

    function calculateTotal() {
        const d1 = dateDebutInput.value;
        const d2 = dateFinInput.value;

        if (d1 && d2) {
            const date1 = new Date(d1);
            const date2 = new Date(d2);

            // Set to midnight to avoid timezone issues
            date1.setHours(0,0,0,0);
            date2.setHours(0,0,0,0);

            if (date2 >= date1) {
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include the start day
                
                calcJours.textContent = diffDays + ' jour(s)';
                
                const total = diffDays * prixJour;
                calcTotal.textContent = total.toFixed(2).replace('.', ',') + ' dt';
            } else {
                calcJours.textContent = 'Dates invalides';
                calcTotal.textContent = '0,00 dt';
            }
        } else {
            calcJours.textContent = '0 jour(s)';
            calcTotal.textContent = '0,00 dt';
        }
    }

    // Mettre à jour la date minimum de fin en fonction de la date de début
    dateDebutInput.addEventListener('change', function() {
        dateFinInput.min = this.value;
        if (dateFinInput.value && dateFinInput.value < this.value) {
            dateFinInput.value = this.value;
        }
        calculateTotal();
    });

    dateFinInput.addEventListener('change', calculateTotal);
});
</script>

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

/* ===== FORM STYLES ===== */
.fo-form-wrapper {
    max-width: 800px;
    margin: 0 auto;
}
.fo-form-card {
    background: #fff;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,.04);
}
.fo-eq-summary {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    margin-bottom: 30px;
}
.fo-eq-summary-icon {
    width: 60px; height: 60px;
    background: #fff; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: #0f766e;
    box-shadow: 0 4px 12px rgba(0,0,0,.05);
}
.fo-eq-summary-info { flex: 1; }
.fo-eq-summary-info h3 { font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 0 0 4px; }
.fo-eq-summary-info p { font-size: .85rem; color: #64748b; margin: 0; }
.fo-eq-summary-price {
    text-align: right;
}
.fo-eq-summary-price strong { display: block; font-size: 1.25rem; color: #0f766e; line-height: 1.1; }
.fo-eq-summary-price small { font-size: .75rem; color: #94a3b8; }

.fo-input {
    border-radius: 12px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    padding: 12px 16px;
    font-size: .95rem;
    transition: all .2s;
}
.fo-input:focus {
    background: #fff;
    border-color: #0f766e;
    box-shadow: 0 0 0 4px rgba(15,118,110,.1);
}

.fo-price-calc {
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: 16px;
    padding: 24px;
}
.fo-calc-label { color: #475569; font-size: .95rem; }
.fo-calc-value { font-weight: 600; color: #0f172a; }
.fo-hr { margin: 16px 0; border-color: #bbf7d0; opacity: 1; }
.fo-calc-total { font-size: 1.5rem; font-weight: 800; color: #166534; }

.fo-btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 28px;
    background: linear-gradient(135deg, #0f766e, #0d9488);
    color: #fff;
    border-radius: 14px;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all .2s ease;
    box-shadow: 0 8px 20px rgba(15,118,110,.35);
}
.fo-btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(15,118,110,.45);
    background: linear-gradient(135deg, #0d9488, #0f766e);
}


/* ===== RESPONSIVE ===== */

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
/* ===== MES LOCATIONS (table) ===== */
.fo-loc-table-wrap {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,.04);
    overflow-x: auto;
}
.fo-loc-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 720px;
}
.fo-loc-table thead th {
    text-align: left;
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #94a3b8;
    padding: 18px 20px;
    border-bottom: 1.5px solid #f0f4f8;
    white-space: nowrap;
}
.fo-loc-table tbody td {
    padding: 16px 20px;
    font-size: .9rem;
    color: #334155;
    border-bottom: 1px solid #f0f4f8;
    vertical-align: middle;
}
.fo-loc-table tbody tr:last-child td { border-bottom: none; }
.fo-loc-table tbody tr:hover { background: #f8fafc; }
.fo-loc-eq { display: flex; align-items: center; gap: 12px; font-weight: 600; color: #0f172a; }
.fo-loc-eq-icon {
    width: 36px; height: 36px; min-width: 36px;
    display: flex; align-items: center; justify-content: center;
    background: #f0fdf4; color: #0f766e; border-radius: 10px; font-size: .85rem;
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
@media (max-width:600px) {
    .fo-categories-grid { grid-template-columns: 1fr 1fr; gap: 14px; }
    .fo-cat-card { padding: 20px 16px; }
    .fo-cat-icon-wrap { width: 62px; height: 62px; }
    .fo-cat-icon { font-size: 1.6rem; }
    
    .fo-equip-grid { grid-template-columns:1fr; gap:16px; }
    
    .fo-form-card { padding: 24px 20px; }
    .fo-eq-summary { flex-direction: column; align-items: flex-start; gap: 12px; }
    .fo-eq-summary-price { text-align: left; }
    .fo-btn-submit { width: 100%; }
    
    .fo-page-title { font-size: 1.4rem; }
    .fo-main { padding: 18px 14px; }
    .fo-loc-table thead th, .fo-loc-table tbody td { padding: 12px 14px; }
}
</style>
