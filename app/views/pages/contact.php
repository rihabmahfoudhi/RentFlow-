<div class="container py-5">
    <div class="row g-4 align-items-stretch">
        <div class="col-lg-5">
            <div class="feature-card h-100 p-4 p-lg-5">
                <div class="section-kicker mb-2">Contact</div>
                <h1 class="fw-bold mb-3"><?= htmlspecialchars((string) ($pageTitle ?? 'Contact'), ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="muted-text mb-0"><?= htmlspecialchars((string) ($pageSubtitle ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="feature-card h-100 p-4 p-lg-5">
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control form-control-lg" placeholder="Votre nom">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control form-control-lg" placeholder="Votre email">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Sujet</label>
                            <input type="text" class="form-control form-control-lg" placeholder="Sujet du message">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="5" placeholder="Votre message"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-brand btn-lg text-white">A brancher plus tard</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
