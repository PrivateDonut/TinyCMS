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

class PluginManager
{
    public $plugins = [];
    public $hooks = [];

    public function loadPlugins($pluginDirectory)
    {
        $pluginFiles = glob($pluginDirectory . '/*/*.php');
        foreach ($pluginFiles as $pluginFile) {
            // echo "Attempting to load: $pluginFile<br>";
            require_once $pluginFile;
            $pluginName = basename(dirname($pluginFile));
            $pluginClass = $pluginName . 'Plugin';
            if (class_exists($pluginClass)) {
                // Debug message, remove in production
                //echo "Loaded plugin: $pluginClass<br>";
                $plugin = new $pluginClass();
                $this->plugins[$pluginName] = $plugin;
                $plugin->register();
            } else {
                // Debug message, remove in production
                //echo "Failed to load plugin: $pluginClass<br>";
            }
        }
    }

    public function addHook($hookName, $callback, $priority = 10)
    {
        if (!isset($this->hooks[$hookName])) {
            $this->hooks[$hookName] = [];
        }
        $this->hooks[$hookName][] = ['callback' => $callback, 'priority' => $priority];
        usort($this->hooks[$hookName], function ($a, $b) {
            return $a['priority'] - $b['priority'];
        });
        // Debug message, remove in production
        //echo "Hook added: $hookName<br>";
    }

    public function executeHook($hookName, $args = [])
    {
        $result = null;
        if (isset($this->hooks[$hookName])) {
            foreach ($this->hooks[$hookName] as $hook) {
                $result = call_user_func_array($hook['callback'], $args);
                if ($result !== null) {
                    break; // Stop execution if a hook returns a non-null value
                }
            }
        }
        return $result;
    }

    public function applyFilters($filterName, $value, $args = [])
    {
        if (isset($this->hooks[$filterName])) {
            foreach ($this->hooks[$filterName] as $hook) {
                $value = call_user_func_array($hook['callback'], array_merge([$value], $args));
            }
        }
        return $value;
    }
}
