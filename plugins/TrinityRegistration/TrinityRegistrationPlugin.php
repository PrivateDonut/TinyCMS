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

class TrinityRegistrationPlugin extends BasePlugin
{
    private $twig;
    private $database;
    private $session;

    public function register()
    {
        // Debug message, remove in production
        //echo "TrinityRegistrationPlugin register method called<br>";
        add_action('init', [
            $this, 'initPlugin'
        ]);
        add_filter('routes', [$this, 'addRoutes']);
        add_filter('twig_loader', [$this, 'addTwigPath']);
    }

    public function initPlugin()
    {
        // Debug message, remove in production
        //echo "TrinityRegistrationPlugin initPlugin method called<br>";
        $this->database = new Database();
        $this->session = new Symfony\Component\HttpFoundation\Session\Session();
        $this->twig = apply_filters('get_twig', null);
    }

    public function addRoutes($routes)
    {
        // Debug message, remove in production
        //echo "TrinityRegistrationPlugin addRoutes method called<br>";
        $routes['/register'] = [RegistrationController::class, 'index'];
        $routes['/register/submit'] = [RegistrationController::class, 'submit'];
        // Debug message, remove in production
        //print_r($routes);
        return $routes;
    }

    public function addTwigPath($loader)
    {
        // Debug message, remove in production
        //echo "TrinityRegistrationPlugin addTwigPath method called<br>";
        $loader->addPath(__DIR__ . '/views', 'trinity_registration');
        return $loader;
    }
}
