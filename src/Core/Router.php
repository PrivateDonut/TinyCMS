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

namespace Core;

class Router
{
    private $routes = [];

    public function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $this->executeHandler($route['handler']);
                return;
            }
        }

        // 404 Not Found
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }

    private function executeHandler($handler)
    {
        list($controller, $method) = explode('@', $handler);

        // Check if it's an install controller
        if (strpos($controller, 'PreInstallCheck') === 0 || strpos($controller, 'Install') === 0) {
            $controllerClass = "\\Install\\Controllers\\$controller";
        } else {
            $controllerClass = "\\Controllers\\$controller";
        }

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller class not found: $controllerClass");
        }

        $controllerInstance = new $controllerClass();
        $controllerInstance->$method();
    }
}