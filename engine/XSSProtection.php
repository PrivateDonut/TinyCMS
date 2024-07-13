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

namespace DonutCMS;

use voku\helper\AntiXSS;

class XSSProtection
{
    private static $antiXss = null;

    public static function clean($data)
    {
        if (self::$antiXss === null) {
            self::$antiXss = new AntiXSS();
        }

        if (is_array($data)) {
            return array_map([self::class, 'clean'], $data);
        }

        return self::$antiXss->xss_clean($data);
    }
}
