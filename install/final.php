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

require_once("./functions/install.php");
$install = new InstallTinyCMS();
$error_message = '';
$success_message = '';

function getBaseUrl()
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    return $protocol . "://" . $host . rtrim($script, '/install');
}

if (isset($_POST['install'])) {
    $result = $install->install(
        $_POST['db_host'],
        $_POST['db_port'],
        $_POST['db_username'],
        $_POST['db_password'],
        $_POST['db_auth'],
        $_POST['db_characters'],
        $_POST['db_website'],
        $_POST['soap_username'],
        $_POST['soap_password']
    );
    if (is_string($result)) {
        $error_message = $result;
    } else {
        $baseUrl = getBaseUrl();
        header("Location: $baseUrl/?page=home");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TinyCMS Install</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #212121;
            margin: 0;
            color: #ffffff;
        }

        .container {
            max-width: 600px;
        }

        .form-control {
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">TinyCMS Installation</h1>
        <p class="text-center mb-4">World of Warcraft Content Management System</p>
        <hr style="border-color: white; width: 50%;">

        <?php if ($error_message) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php else : ?>

            <form action="" method="post" id="install-form">
                <h4 class="text-center mb-3">MySQL Information</h4>
                <div class="form-group">
                    <label for="db_host">Database Host</label>
                    <input type="text" class="form-control" name="db_host" id="db_host" placeholder="127.0.0.1" required>
                </div>
                <div class="form-group">
                    <label for="db_port">Database Port</label>
                    <input type="number" class="form-control" name="db_port" id="db_port" placeholder="3306" required>
                </div>
                <div class="form-group">
                    <label for="db_username">Database Username</label>
                    <input type="text" class="form-control" name="db_username" id="db_username" placeholder="root" required>
                </div>
                <div class="form-group">
                    <label for="db_password">Database Password</label>
                    <input type="password" class="form-control" name="db_password" id="db_password" placeholder="Password" required>
                </div>

                <h4 class="text-center mt-4 mb-3">Database Information</h4>
                <div class="form-group">
                    <label for="db_auth">Auth Database Name</label>
                    <input type="text" class="form-control" name="db_auth" id="db_auth" placeholder="auth" required>
                </div>
                <div class="form-group">
                    <label for="db_characters">Characters Database Name</label>
                    <input type="text" class="form-control" name="db_characters" id="db_characters" placeholder="characters" required>
                </div>
                <div class="form-group">
                    <label for="db_website">Website Database Name</label>
                    <input type="text" class="form-control" name="db_website" id="db_website" placeholder="website" required>
                </div>

                <h4 class="text-center mt-4 mb-3">SOAP Account Information</h4>
                <p class="text-center"><small>This account will be used by SOAP to send items purchased from the store.</small></p>
                <div class="form-group">
                    <label for="soap_username">SOAP Username</label>
                    <input type="text" class="form-control" name="soap_username" id="soap_username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="soap_password">SOAP Password</label>
                    <input type="password" class="form-control" name="soap_password" id="soap_password" placeholder="Password" required>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary" name="install">Install TinyCMS</button>
                </div>
            </form>

        <?php endif; ?>

    </div>

    <footer class="footer mt-5 py-3">
        <div class="container">
            <p class="text-center">&copy; 2023 TinyCMS. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('install-form').addEventListener('submit', function(event) {
            var inputs = this.getElementsByTagName('input');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].hasAttribute('required') && inputs[i].value === '') {
                    alert('Please fill all required fields');
                    event.preventDefault();
                    return;
                }
            }
        });
    </script>
</body>

</html>