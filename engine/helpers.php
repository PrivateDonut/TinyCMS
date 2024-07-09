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

use DonutCMS\PluginSystem\HookHelper;

if (!function_exists('add_action')) {
    function add_action(string $hookName, callable $callback, int $priority = 10): void
    {
        HookHelper::addAction($hookName, $callback, $priority);
    }
}

if (!function_exists('add_filter')) {
    function add_filter(string $hookName, callable $callback, int $priority = 10): void
    {
        HookHelper::addFilter($hookName, $callback, $priority);
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters(string $filterName, mixed $value, ...$args): mixed
    {
        return HookHelper::applyFilters($filterName, $value, $args);
    }
}

if (!function_exists('do_action')) {
    function do_action(string $hookName, ...$args): void
    {
        HookHelper::doAction($hookName, $args);
    }
}
