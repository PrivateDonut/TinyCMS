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

require_once __DIR__ . '/../CSRFTrait.php';

abstract class BaseController
{
    use CSRFTrait;

    protected $twig;
    protected $global;
    protected $config;
    protected $navbarModel;

    public function __construct($twig, $global, $config)
    {
        $this->twig = $twig;
        $this->global = $global;
        $this->config = $config;
        $this->navbarModel = new NavbarModel();
    }

    protected function render($template, $data = [])
    {
        $navbarModel = new NavbarModel();
        $navbarItems = $navbarModel->getNavbarItems();

        $socialMediaModel = new SocialMediaModel();
        $socialMediaLinks = $socialMediaModel->getSocialMediaLinks();

        return $this->twig->render($template, array_merge([
            'session' => $_SESSION,
            'global' => $this->global,
            'config' => $this->config,
            'navbar_items' => $navbarItems,
            'social_media_links' => $socialMediaLinks
        ], $data));
    }
}
