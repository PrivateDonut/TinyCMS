<?php
// Define the base directory
define('BASE_DIR', __DIR__ . '/..');

// Include required files
require_once BASE_DIR . '/vendor/autoload.php';
require_once BASE_DIR . '/engine/configs/db_config.php';
require_once BASE_DIR . '/engine/models/database.php';
require_once BASE_DIR . '/engine/TinyCMSSessionHandler.php';

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/template/default');
$twig = new \Twig\Environment($loader, [
    'cache' => BASE_DIR . '/cache/twig',
    'auto_reload' => true,
]);

// Initialize database connection
$database = new Database();
$websiteDbConnection = $database->getConnection('website');

// Initialize custom session handler
$config = new Configuration();
$encryptionKey = trim($config->get_config('session')['encryption_key']);
$handler = new TinyCMSSessionHandler($websiteDbConnection, $encryptionKey);
$storage = new Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage([], $handler);

// Start the Symfony session
$session = new Symfony\Component\HttpFoundation\Session\Session($storage);
$session->start();

// Include functions, models, and controllers
foreach (glob(BASE_DIR . '/engine/models/*.php') as $filename) {
    require_once $filename;
}
foreach (glob(__DIR__ . '/models/*.php') as $filename) {
    require_once $filename;
}
foreach (glob(__DIR__ . '/controllers/*.php') as $filename) {
    require_once $filename;
}

// Initialize global functions and config
$global = new GlobalFunctions($session);
$config_object = new gen_config();

// Parse the URL
$request_uri = $_SERVER['REQUEST_URI'];
$path = trim(parse_url($request_uri, PHP_URL_PATH), '/');
$segments = explode('/', $path);

// Get Breadcrumb Data
$uri_parts = explode('/', trim($request_uri, '/'));
$breadcrumbs = [];
$url_path = '';
foreach ($uri_parts as $part) {
    $url_path .= '/' . $part;
    $breadcrumbs[] = [
        'title' => ucfirst($part),
        'url' => $url_path
    ];
}

// Ensure the user is an admin before allowing access
$global->check_is_admin();

// Redirect to /home if the path is empty
if ($path == '' || $path == 'admin') {
    header('Location: /admin/home');
    exit();
}

// Route the request to the appropriate controller
$controllerName = ucfirst($segments[1] ?? 'Home') . 'Controller';
$action = $segments[2] ?? 'index';
$params = array_slice($segments, 3);

if (class_exists($controllerName)) {
    $controller = new $controllerName($twig, $global, $config_object, $breadcrumbs);
    echo $controller->handle($action, $params);
} else {
    // 404 handling
    header("HTTP/1.0 404 Not Found");
    echo $twig->render('404.twig', ['session' => $session->all(), 'global' => $global, 'config' => $config_object, 'breadcrumbs' => $breadcrumbs]);
}

// Clear any one-time session messages
$session->remove('success_message');
$session->remove('error');
