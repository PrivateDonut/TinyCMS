<?php
require_once __DIR__ . '/vendor/autoload.php';

// Define the base directory
define('BASE_DIR', __DIR__);

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(BASE_DIR . '/templates/default');
$twig = new \Twig\Environment($loader, [
   'cache' => BASE_DIR . '/cache/twig',
   'auto_reload' => true,
]);

// Redirect to install page if install.lock is not found.
if (!file_exists(BASE_DIR . '/engine/install.lock')) {
   $installUrl = "install/";
   header("Location: $installUrl");
   exit();
}

// Start the session if not already started
if (!isset($_SESSION)) {
   session_start();
}

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

// Determine the controller and action
$controllerName = ucfirst($segments[0] ?? 'Home') . 'Controller';
$action = $segments[1] ?? 'index';
$params = array_slice($segments, 2);

// Route the request to the appropriate controller
if (class_exists($controllerName)) {
   $controller = new $controllerName($twig, $global, $config_object);
   echo $controller->handle($action, $params);
} else {
   // 404 handling
   header("HTTP/1.0 404 Not Found");
   echo $twig->render('404.twig', ['session' => $_SESSION, 'global' => $global, 'config' => $config_object]);
}

// Clear any one-time session messages
unset($_SESSION['success_message'], $_SESSION['error']);
