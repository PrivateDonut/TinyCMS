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

namespace DonutCMS\PluginSystem;

use Exception;

class PluginManager
{
    public array $plugins = [];
    public array $hooks = [];
    private array $config = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function loadPlugins(string $pluginDirectory): void
    {
        error_log("Starting to load plugins from directory: $pluginDirectory");
        $pluginFiles = glob($pluginDirectory . '/*/*.php');
        error_log("Found " . count($pluginFiles) . " potential plugin files");
        foreach ($pluginFiles as $pluginFile) {
            error_log("Attempting to load plugin file: $pluginFile");
            try {
                require_once $pluginFile;
                $pluginName = basename(dirname($pluginFile));
                $pluginClass = "Plugins\\{$pluginName}\\{$pluginName}Plugin";
                error_log("Looking for plugin class: $pluginClass");
                if (class_exists($pluginClass)) {
                    error_log("Plugin class $pluginClass found");
                    $plugin = new $pluginClass();
                    $this->plugins[$pluginName] = $plugin;
                    $plugin->register();
                    error_log("Plugin $pluginName registered successfully");
                } else {
                    error_log("Plugin class $pluginClass not found");
                }
            } catch (\Exception $e) {
                error_log("Failed to load plugin from file $pluginFile: " . $e->getMessage());
            }
        }
        error_log("Finished loading plugins. Total plugins loaded: " . count($this->plugins));
    }

    public function addHook(string $hookName, callable $callback, int $priority = 10): void
    {
        if (!isset($this->hooks[$hookName])) {
            $this->hooks[$hookName] = [];
        }
        $this->hooks[$hookName][] = ['callback' => $callback, 'priority' => $priority];
        usort($this->hooks[$hookName], function ($a, $b) {
            return $a['priority'] - $b['priority'];
        });
    }

    public function executeHook(string $hookName, array $args = []): mixed
    {
        $result = null;
        if (isset($this->hooks[$hookName])) {
            foreach ($this->hooks[$hookName] as $hook) {
                $result = call_user_func_array($hook['callback'], $args);
                if ($result !== null) {
                    break;
                }
            }
        }
        return $result;
    }

    public function applyFilters(string $filterName, mixed $value, array $args = []): mixed
    {
        if (isset($this->hooks[$filterName])) {
            foreach ($this->hooks[$filterName] as $hook) {
                $value = call_user_func_array($hook['callback'], array_merge([$value], $args));
            }
        }
        return $value;
    }

    private function isPluginEnabled(string $pluginName): bool
    {
        return !isset($this->config['disabled_plugins']) || !in_array($pluginName, $this->config['disabled_plugins']);
    }
}
