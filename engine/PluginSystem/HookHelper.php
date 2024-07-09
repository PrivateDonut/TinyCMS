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

class HookHelper
{
    private static ?PluginManager $pluginManager = null;

    public static function setPluginManager(PluginManager $manager): void
    {
        self::$pluginManager = $manager;
    }

    public static function addAction(string $hookName, callable $callback, int $priority = 10): void
    {
        self::$pluginManager?->addHook($hookName, $callback, $priority);
    }

    public static function addFilter(string $hookName, callable $callback, int $priority = 10): void
    {
        self::$pluginManager?->addHook($hookName, $callback, $priority);
    }

    public static function doAction(string $hookName, array $args = []): void
    {
        self::$pluginManager?->executeHook($hookName, $args);
    }

    public static function applyFilters(string $filterName, mixed $value, array $args = []): mixed
    {
        return self::$pluginManager ? self::$pluginManager->applyFilters($filterName, $value, $args) : $value;
    }
}
