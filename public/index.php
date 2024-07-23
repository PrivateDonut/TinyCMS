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
 * *******************************************************************************/

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Core\Router;
use Core\PluginManager;
use Core\Database;

// Initialize the request and session
$request = Request::createFromGlobals();
$session = new Session();
$session->start();

// Initialize router
$router = new Router();

// Load routes
$routesLoader = require __DIR__ . '/../src/Config/routes.php';
$routesLoader($router);

// Check if the application is already installed
if (!file_exists(__DIR__ . '/../src/install.lock') && $_SERVER['REQUEST_URI'] !== '/pre-install-check' && $_SERVER['REQUEST_URI'] !== '/install') {
    error_log("Install lock file not found. Redirecting to /pre-install-check");
    header("Location: /pre-install-check");
    exit();
}

if (file_exists(__DIR__ . '/../src/install.lock')) {
    require_once __DIR__ . '/../src/Config/config.php';

    // Initialize database
    $database = Database::getInstance();

    // Initialize plugin manager
    $pluginManager = new PluginManager();
    $pluginManager->loadPlugins();
}

// Dispatch the request
$router->dispatch();