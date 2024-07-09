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

abstract class BaseController
{
    protected $twig;
    protected $global;
    protected $config;
    protected $session;

    public function __construct($twig, $global, $config, $session)
    {
        $this->twig = $twig;
        $this->global = $global;
        $this->config = $config;
        $this->session = $session;
    }

    abstract public function handle($action, $params);

    protected function render($template, $data = [])
    {
        return $this->twig->render($template, array_merge([
            'session' => $this->session->all(),
            'global' => $this->global,
            'config' => $this->config
        ], $data));
    }
}
