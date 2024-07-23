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

use Install\Models\InstallModel;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class InstallController
{
    private $installModel;
    private $twig;

    public function __construct()
    {
        $this->installModel = new InstallModel();

        $loader = new FilesystemLoader(__DIR__ . '/../../Views/install');
        $this->twig = new Environment($loader);
    }

    public function index()
    {
        $this->debugLog("Entering index method. REQUEST_URI: " . $_SERVER['REQUEST_URI']);

        if ($this->installModel->isInstalled()) {
            $this->debugLog("TinyCMS is already installed. Rendering already_installed.twig");
            echo $this->twig->render('already_installed.twig', ['title' => 'TinyCMS Already Installed']);
        } else {
            $this->debugLog("Rendering installation form");
            $data = [
                'title' => 'TinyCMS Installation',
                'error_message' => '',
                'success_message' => ''
            ];
            echo $this->twig->render('index.twig', $data);
        }
    }

    public function install()
    {
        $this->debugLog("Entering install method. REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);

        if ($this->installModel->isInstalled()) {
            $this->debugLog("TinyCMS is already installed. Rendering already_installed.twig");
            echo $this->twig->render('already_installed.twig', ['title' => 'TinyCMS Already Installed']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->debugLog("Processing POST request for installation");
            $userInput = [
                'website_db_host' => $_POST['website_db_host'] ?? '',
                'website_db_port' => $_POST['website_db_port'] ?? '',
                'website_db_username' => $_POST['website_db_username'] ?? '',
                'website_db_password' => $_POST['website_db_password'] ?? '',
                'website_db_name' => $_POST['website_db_name'] ?? '',
                'auth_db_host' => $_POST['auth_db_host'] ?? '',
                'auth_db_port' => $_POST['auth_db_port'] ?? '',
                'auth_db_username' => $_POST['auth_db_username'] ?? '',
                'auth_db_password' => $_POST['auth_db_password'] ?? '',
                'auth_db_name' => $_POST['auth_db_name'] ?? '',
                'char_db_host' => $_POST['char_db_host'] ?? '',
                'char_db_port' => $_POST['char_db_port'] ?? '',
                'char_db_username' => $_POST['char_db_username'] ?? '',
                'char_db_password' => $_POST['char_db_password'] ?? '',
                'char_db_name' => $_POST['char_db_name'] ?? ''
            ];

            $result = $this->installModel->install($userInput);

            if ($result === true) {
                $this->debugLog("Installation successful");
                echo $this->twig->render('success.twig', [
                    'title' => 'TinyCMS Installation Complete',
                    'success_message' => 'Installation completed successfully!'
                ]);
            } else {
                $this->debugLog("Installation failed: $result");
                echo $this->twig->render('index.twig', [
                    'title' => 'TinyCMS Installation',
                    'error_message' => $result,
                    'success_message' => ''
                ]);
            }
        } else {
            $this->debugLog("Non-POST request to install method, rendering installation form");
            $this->index();
        }
    }

    private function debugLog($message)
    {
        $logFile = __DIR__ . '/../logs/install.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}