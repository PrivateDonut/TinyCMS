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

function add_action($hook, $callback, $priority = 10)
{
    HookHelper::addAction($hook, $callback, $priority);
}

function add_filter($hook, $callback, $priority = 10)
{
    HookHelper::addFilter($hook, $callback, $priority);
}

function apply_filters($hook, $value, ...$args)
{
    return HookHelper::applyFilters($hook, $value, $args);
}
