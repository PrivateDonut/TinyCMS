<?php
// Define the base directory
define('BASE_DIR', __DIR__);

// Include required files
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/engine/configs/db_config.php';
require_once __DIR__ . '/engine/functions/database.php';
require_once __DIR__ . '/engine/TinyCMSSessionHandler.php';

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(BASE_DIR . '/templates/default');
$twig = new \Twig\Environment($loader, [
   'cache' => BASE_DIR . '/cache/twig',
   'auto_reload' => true,
]);

// Redirect to install page if install.lock is not found
if (!file_exists(BASE_DIR . '/engine/install.lock')) {
   $installUrl = "install/";
   header("Location: $installUrl");
   exit();
}

// Initialize database connection
$database = new Database();
$websiteDbConnection = $database->getConnection('website');

// Initialize custom session handler
$config = new Configuration();
$encryptionKey = trim($config->get_config('session')['encryption_key']); // Ensure no leading/trailing whitespace
$handler = new TinyCMSSessionHandler($websiteDbConnection, $encryptionKey);
$storage = new Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage([], $handler);

// Start the Symfony session
$session = new Symfony\Component\HttpFoundation\Session\Session($storage);
$session->start();

// Include functions and controllers
foreach (glob(BASE_DIR . "/engine/functions/*.php") as $filename) {
   require_once $filename;
}
foreach (glob(BASE_DIR . "/controllers/*.php") as $filename) {
   require_once $filename;
}

// Initialize global functions and config
$global = new GlobalFunctions();
$config_object = new gen_config();

// Parse the URL
$request_uri = $_SERVER['REQUEST_URI'];
$path = trim(parse_url($request_uri, PHP_URL_PATH), '/');
$segments = explode('/', $path);

// Debug: Print parsed URL segments
// Remove this block in production
//echo "<pre>";
//print_r($segments);
//echo "</pre>";

$controllerName = ucfirst($segments[0] ?? 'Home') . 'Controller';
$action = $segments[1] ?? 'index';
$params = array_slice($segments, 2);

// Debug: Print controller, action, and params
// Remove this block in production
//echo "<pre>";
//echo "Controller: $controllerName\n";
//echo "Action: $action\n";
//print_r($params);
//echo "</pre>";

// Route the request to the appropriate controller
if (class_exists($controllerName)) {
   $controller = new $controllerName($twig, $global, $config_object, $session);
   echo $controller->handle($action, $params);
} else {
   // Debug: Print error if controller class not found
   // Remove this block in production
   //echo "<pre>";
   //echo "Error: Controller class '$controllerName' not found.";
   //echo "</pre>";

   // 404 handling
   header("HTTP/1.0 404 Not Found");
   echo $twig->render('404.twig', ['session' => $session->all(), 'global' => $global, 'config' => $config_object]);
}

// Clear any one-time session messages
$session->remove('success_message');
$session->remove('error');
