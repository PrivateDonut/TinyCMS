<?php
// Define the base directory
define('BASE_DIR', __DIR__);

// Include required files
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/engine/configs/db_config.php';
require_once __DIR__ . '/engine/functions/database.php';
require_once __DIR__ . '/engine/TinyCMSSessionHandler.php';
require_once __DIR__ . '/engine/PluginManager.php';
require_once __DIR__ . '/engine/HookHelper.php';
require_once __DIR__ . '/engine/BasePlugin.php';
require_once __DIR__ . '/engine/plugin-helpers.php';

// Initialize database connection
$database = new Database();
$websiteDbConnection = $database->getConnection('website');

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(BASE_DIR . '/templates/default');
$twig = new \Twig\Environment($loader, [
   'cache' => BASE_DIR . '/cache/twig',
   'auto_reload' => true,
]);

// Initialize custom session handler
$config = new Configuration();
$encryptionKey = trim($config->get_config('session')['encryption_key']);
$handler = new TinyCMSSessionHandler($websiteDbConnection, $encryptionKey);
$storage = new Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage([], $handler);

// Start the Symfony session
$session = new Symfony\Component\HttpFoundation\Session\Session($storage);
$session->start();

// Initialize plugin manager
$pluginManager = new PluginManager();
HookHelper::setPluginManager($pluginManager);  // Add this line
$pluginManager->loadPlugins(BASE_DIR . '/plugins');
HookHelper::setPluginManager($pluginManager);

// Add Twig to available services for plugins
HookHelper::addFilter('get_twig', function () use ($twig) {
   return $twig;
});

// Allow plugins to modify Twig loader
$twig->setLoader(HookHelper::applyFilters('twig_loader', $twig->getLoader()));

// Get routes from plugins
$routes = HookHelper::applyFilters('routes', []);

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

// Get routes from plugins
$routes = HookHelper::applyFilters('routes', []);

// Parse the URL
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);


if (isset($routes[$path])) {
   // Debug message, remove in production
   //echo "Route found, executing controller<br>";
   list($controllerClass, $method) = $routes[$path];

   // Make sure the controller class exists
   if (!class_exists($controllerClass)) {
      require_once BASE_DIR . "/plugins/TrinityRegistration/{$controllerClass}.php";
   }

   $controller = new $controllerClass($twig, $session);
   $content = $controller->$method();
} else {
   // Debug message, remove in production
   //echo "No matching route found, falling back to default routing<br>";
   $segments = explode('/', trim($path, '/'));
   $controllerName = ucfirst($segments[0] ?? 'Home') . 'Controller';
   $action = $segments[1] ?? 'index';
   $params = array_slice($segments, 2);

   if (class_exists($controllerName)) {
      $controller = new $controllerName($twig, $global, $config_object, $session, $pluginManager);
      $content = $controller->handle($action, $params);
      // Apply content filter
      $content = HookHelper::applyFilters('content', $content);
      echo $content;
   } else {
      // 404 handling
      header("HTTP/1.0 404 Not Found");
      $content = $twig->render('404.twig', ['session' => $session->all(), 'global' => $global, 'config' => $config_object]);
      echo HookHelper::applyFilters('content', $content);
   }
}

// Apply content filter
$content = HookHelper::applyFilters('content', $content);
echo $content;

// Clear any one-time session messages
$session->remove('success_message');
$session->remove('error');
