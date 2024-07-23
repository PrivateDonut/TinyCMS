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

namespace Install\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class PreInstallCheckController
{
    private $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../Views/install');
        $this->twig = new Environment($loader);
    }

    public function index()
    {
        $requiredExtensions = ['mysqli', 'gmp', 'soap', 'curl'];
        $extensionStatus = [];

        foreach ($requiredExtensions as $extension) {
            $extensionStatus[$extension] = extension_loaded($extension) ? 'Enabled' : 'Disabled';
        }

        $allExtensionsEnabled = !in_array('Disabled', $extensionStatus);

        echo $this->twig->render('pre_install_check.twig', [
            'title' => 'TinyCMS Pre-Installation Check',
            'extensionStatus' => $extensionStatus,
            'allExtensionsEnabled' => $allExtensionsEnabled
        ]);
    }
}