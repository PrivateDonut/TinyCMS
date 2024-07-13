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

namespace Plugins\TrinityLogin;

use DonutCMS\PluginSystem\BasePlugin;
use DonutCMS\PluginSystem\HookHelper;
use Plugins\TrinityLogin\Controllers\LoginController;

class TrinityLoginPlugin extends BasePlugin
{
    public function register(): void
    {
        $this->addAction('init', [$this, 'initPlugin']);
        $this->addFilter('routes', [$this, 'addRoutes']);
        $this->addFilter('twig_loader', [$this, 'addTwigPath']);
    }

    public function initPlugin(): void
    {
        // Any initialization code if needed
    }

    public function addRoutes(array $routes): array
    {
        $routes['/login'] = [LoginController::class, 'view'];
        $routes['/login/authenticate'] = [LoginController::class, 'authenticate'];
        $routes['/logout'] = [LoginController::class, 'logout'];
        return $routes;
    }

    public function addTwigPath($loader)
    {
        $loader->addPath(__DIR__ . '/views', 'trinity_login');
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
