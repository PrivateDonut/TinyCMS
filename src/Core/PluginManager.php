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

class PluginManager
{
    private $plugins = [];
    private static $actions = [];

    public function loadPlugins()
    {
        $pluginDir = __DIR__ . '/../Plugins';
        $pluginFiles = glob($pluginDir . '/*.php');

        foreach ($pluginFiles as $pluginFile) {
            require_once $pluginFile;
            $className = 'Plugins\\' . basename($pluginFile, '.php');
            if (class_exists($className)) {
                $this->plugins[] = new $className();
            }
        }
    }

    public static function addAction($hook, $callback)
    {
        self::$actions[$hook][] = $callback;
    }

    public static function doAction($hook)
    {
        if (isset(self::$actions[$hook])) {
            foreach (self::$actions[$hook] as $callback) {
                call_user_func($callback);
            }
        }
    }
}

function add_action($hook, $callback)
{
    PluginManager::addAction($hook, $callback);
}