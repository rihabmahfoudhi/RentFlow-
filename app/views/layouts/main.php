<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars((string) ($metaTitle ?? 'RentFlow'), ENT_QUOTES, 'UTF-8'); ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="RentFlow" name="keywords">
    <meta content="Plateforme de location d’équipements RentFlow" name="description">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        :root {
            --ink: #101828;
            --muted: #667085;
            --brand: #0f766e;
            --brand-2: #155eef;
            --surface: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: var(--ink);
        }

        p.text-muted {
            color: #333333 !important;
        }

        .hero-shell {
            background: radial-gradient(circle at top right, rgba(21, 94, 239, 0.18), transparent 32%), linear-gradient(135deg, #0f172a 0%, #111827 55%, #0f766e 100%);
            color: #fff;
            border-radius: 28px;
            overflow: hidden;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .45rem .85rem;
            border-radius: 999px;
            background: rgba(255,255,255,.1);
            color: #dbeafe;
            font-size: .9rem;
        }

        .feature-card {
            border: 1px solid rgba(16,24,40,.08);
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 18px 40px rgba(16,24,40,.08);
        }

        .section-soft {
            background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        }

        .muted-text {
            color: var(--muted);
        }

        .btn-brand {
            background: var(--brand-2);
            border-color: var(--brand-2);
        }

        .btn-brand:hover {
            background: #0b4fd0;
            border-color: #0b4fd0;
        }

        .section-kicker {
            text-transform: uppercase;
            letter-spacing: .14em;
            font-size: .8rem;
            color: var(--brand);
            font-weight: 700;
        }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../partials/topbar.php'; ?>

    <?php require $viewFile; ?>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
