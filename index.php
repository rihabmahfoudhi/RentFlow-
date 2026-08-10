<?php

declare(strict_types=1);

use App\Config\Database;
use App\Controllers\SiteController;
use App\Core\Router;

session_start();

require __DIR__ . '/config/database.php';

spl_autoload_register(static function (string $class): void {
    $prefix  = 'App\\';
    $baseDir = __DIR__ . '/app/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

Database::getInstance();

$router         = new Router();
$siteController = new SiteController();

// ── Routes publiques ───────────────────────────────────────────
$router->get('', [$siteController, 'home']);
$router->get('home', [$siteController, 'home']);
$router->get('about', [$siteController, 'about']);
$router->get('services', [$siteController, 'services']);
$router->get('contact', [$siteController, 'contact']);
$router->get('login', [$siteController, 'loginForm']);
$router->post('login', [$siteController, 'login']);
$router->get('register', [$siteController, 'registerForm']);
$router->post('register', [$siteController, 'register']);

// ── BackOffice ─────────────────────────────────────────────────
$dashboardController = new App\Controllers\DashboardController();
$router->get('client-dashboard', [$dashboardController, 'clientDashboard']);
$router->get('admin-dashboard', [$dashboardController, 'adminDashboard']);
$router->get('categories', [$dashboardController, 'categories']);
$router->post('categories', [$dashboardController, 'categoriesAction']);
$router->get('equipements', [$dashboardController, 'equipment']);
$router->post('equipements', [$dashboardController, 'equipmentAction']);
$router->get('locations', [$dashboardController, 'locations']);
$router->post('locations', [$dashboardController, 'locationsAction']);
$router->get('utilisateurs', [$dashboardController, 'users']);
$router->post('utilisateurs', [$dashboardController, 'usersAction']);
$router->get('logout', [$dashboardController, 'logout']);

// ── FrontOffice Client — Catalogue ─────────────────────────────
$router->get('catalogue', [$dashboardController, 'catalogue']);
$router->get('catalogue-categorie', [$dashboardController, 'catalogueCategorie']);
$router->get('demande-location', [$dashboardController, 'demandeLocationForm']);
$router->post('demande-location', [$dashboardController, 'demandeLocationSubmit']);
$router->get('demande-succes', [$dashboardController, 'demandeSucces']);
$router->get('telecharger-recu', [$dashboardController, 'telechargerRecu']);

// ── 404 ───────────────────────────────────────────────────────
$router->setNotFound([$siteController, 'notFound']);

// ── Dispatch ───────────────────────────────────────────────────
$route = trim((string) ($_GET['route'] ?? ''), '/');

if ($route === '') {
    $path      = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
    $scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

    if ($scriptDir !== '' && str_starts_with($path, $scriptDir)) {
        $path = trim(substr($path, strlen($scriptDir)), '/');
    }

    if ($path === '' || $path === 'index.php') {
        $route = '';
    } else {
        $route = $path;
    }
}

$router->dispatch($route, $_SERVER['REQUEST_METHOD'] ?? 'GET');
