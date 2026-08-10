<?php
$prixJour = (float)($equipement['prix_jour'] ?? 0);
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
    <main class="fo-main">
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
    .fo-form-card { padding: 24px 20px; }
    .fo-page-title { font-size:1.4rem; }
    .fo-main { padding:18px 14px; }
    .fo-eq-summary { flex-direction: column; align-items: flex-start; gap: 12px; }
    .fo-eq-summary-price { text-align: left; }
    .fo-btn-submit { width: 100%; }
}
</style>
