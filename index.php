<?php

/*********************************************************************************
 * DonutCMS is free software: you can redistribute it and/or modify              *
 * it under the terms of the GNU General Public License as published by          *
 * the Free Software Foundation, either version 3 of the License, or             *
 * (at your option) any later version.                                           *
 *                                                                               *
 * DonutCMS is distributed in the hope that it will be useful,                   *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of                *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                  *
 * GNU General Public License for more details.                                  *
 *                                                                               *
 * You should have received a copy of the GNU General Public License             *
 * along with DonutCMS. If not, see <https://www.gnu.org/licenses/>.             *
 *********************************************************************************/

// Check if install.lock
if (!file_exists(__DIR__ . '/engine/install.lock')) {
   header('Location: /install/index.php');
   exit();
}

// Define the base directory
define('BASE_DIR', __DIR__);

// Include required files
require_once BASE_DIR . '/vendor/autoload.php';
require_once BASE_DIR . '/engine/configs/db_config.php';
require_once BASE_DIR . '/engine/models/database.php';
require_once BASE_DIR . '/engine/TinyCMSSessionHandler.php';
require_once BASE_DIR . '/engine/PluginSystem/PluginManager.php';
require_once BASE_DIR . '/engine/PluginSystem/HookHelper.php';
require_once BASE_DIR . '/engine/PluginSystem/BasePlugin.php';
require_once BASE_DIR . '/engine/helpers.php';

use DonutCMS\Models\Database;
use DonutCMS\Models\GlobalFunctions;
use DonutCMS\PluginSystem\PluginManager;
use DonutCMS\PluginSystem\HookHelper;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use DonutCMS\Models\Configuration;

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
$storage = new NativeSessionStorage([], $handler);

// Start the Symfony session
$session = new Session($storage);
$session->start();

// Generate CSRF token
$token = $session->get('_csrf/token');
if (!$token) {
   $token = bin2hex(random_bytes(32));
   $session->set('_csrf/token', $token);
}

// Log the token
error_log("CSRF token set in session: " . $token);

// Add CSRF token to Twig global variables
$twig->addGlobal('csrf_token', $token);

// Initialize plugin manager
$pluginManager = new PluginManager();
HookHelper::setPluginManager($pluginManager);

// Load plugins
$pluginManager->loadPlugins(BASE_DIR . '/plugins');

// Add Twig to available services for plugins
add_filter('get_twig', function () use ($twig) {
   return $twig;
});

// Allow plugins to modify Twig loader
$twig->setLoader(apply_filters('twig_loader', $twig->getLoader()));

// Get routes from plugins
$routes = apply_filters('routes', []);

// Include functions and controllers
foreach (glob(BASE_DIR . "/engine/models/*.php") as $filename) {
   require_once $filename;
}
foreach (glob(BASE_DIR . "/engine/controllers/*.php") as $filename) {
   require_once $filename;
}

// Initialize global functions and config
$global = new GlobalFunctions($database, $session);
$config_object = new gen_config();

// Execute 'init' action for plugins
do_action('init');

// Parse the URL
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Redirect to /home if the path is empty
if ($path == '/' || $path == '') {
   header('Location: /home');
   exit();
}

// Check if the path exists in the plugin routes
if (isset($routes[$path])) {
   error_log("Route found for path: $path");
   list($controllerClass, $method) = $routes[$path];
   if (class_exists($controllerClass)) {
      error_log("Controller class exists: $controllerClass");
      $controller = new $controllerClass($twig, $session);
      $content = $controller->$method();
   } else {
      error_log("Controller class does not exist: $controllerClass");
   }
} else {
   // Existing routing logic
   $segments = explode('/', trim($path, '/'));
   $controllerName = ucfirst($segments[0] ?? 'Home') . 'Controller';
   $action = $segments[1] ?? 'index';
   $params = array_slice($segments, 2);

   if (class_exists($controllerName)) {
      $controller = new $controllerName($twig, $global, $config_object, $session, $pluginManager);
      $content = $controller->handle($action, $params);
   } else {
      // 404 handling
      header("HTTP/1.0 404 Not Found");
      $content = $twig->render('404.twig', ['session' => $session->all(), 'global' => $global, 'config' => $config_object]);
   }
}

// Apply content filter
$content = apply_filters('content', $content);

// Output the content
echo $content;

// Clear any one-time session messages
$session->remove('success_message');
$session->remove('error');
