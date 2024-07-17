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

namespace Plugins\TrinityRegistration;

use DonutCMS\PluginSystem\BasePlugin;
use DonutCMS\PluginSystem\HookHelper;
use Plugins\TrinityRegistration\Controllers\RegistrationController;
use DonutCMS\Models\Database;
use Symfony\Component\HttpFoundation\Session\Session;

class TrinityRegistrationPlugin extends BasePlugin
{
    private $twig;
    private $database;
    private $session;

    public function register(): void
    {
        $this->addAction('init', [$this, 'initPlugin']);
        $this->addFilter('routes', [$this, 'addRoutes']);
        $this->addFilter('twig_loader', [$this, 'addTwigPath']);
    }

    public function initPlugin(): void
    {
        $this->database = new Database();
        $this->session = new Session();
        $this->twig = HookHelper::applyFilters('get_twig', null);
    }

    public function addRoutes(array $routes): array
    {
        // error_log("TrinityRegistration adding routes"); // Uncomment this line to debug
        $routes['/register'] = [RegistrationController::class, 'index'];
        $routes['/register/submit'] = [RegistrationController::class, 'submit'];
        error_log("Routes after adding: " . print_r($routes, true));
        return $routes;
    }

    public function addTwigPath($loader)
    {
        $fullPath = __DIR__ . '/views';
        // error_log("Adding Twig path: " . $fullPath); // Uncomment this line to debug
        $loader->addPath($fullPath, 'trinity_registration');
        $loader->addPath(BASE_DIR . '/templates/default', 'main'); // Add the default template path
        return $loader;
    }

    public function activate(): void
    {
        // Activation logic (if needed)
    }

    public function deactivate(): void
    {
        // Deactivation logic (if needed)
    }
}
