<?php
require_once("functions/install.php");
$install = new InstallTinyCMS();
if (isset($_POST['install'])) {
    $install->install($_POST['db_host'], $_POST['db_port'], $_POST['db_username'], $_POST['db_password'], $_POST['db_auth'], $_POST['db_characters'], $_POST['db_website'], $_POST['soap_username'], $_POST['soap_password']);
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
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background-color: #212121;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mt-4 text-white text-center">TinyCMS</h1>
        <p class="text-white text-center">World of Warcraft Content Management System</p>
        <hr style="border-color: white; width: 50%;">
        <form action="" method="post">
            <h4 class="text-white text-center">MySQL Information</h4>
            <div class="form-group row">
                <div class="col-sm-5 mx-auto">
                    <input type="text" class="form-control" name="db_host" id="db_host" placeholder="Database Host: 127.0.0.1">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mx-auto">
                    <input type="text" class="form-control" name="db_username" id="db_user" placeholder="Database Username: root">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mx-auto">
                    <input type="password" class="form-control" name="db_password" id="db_pass" placeholder="Database Password: ascent">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mx-auto">
                    <input type="text" class="form-control" name="db_port" id="db_port" placeholder="Database Password: 3306">
                </div>
            </div>
            <h4 class="text-white text-center">Database Information</h4>
            <div class="form-group row">
                <div class="col-sm-5 mx-auto">
                    <input type="text" class="form-control" name="db_auth" id="auth_name" placeholder="Auth Database: auth">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mx-auto">
                    <input type="text" class="form-control" name="db_characters" id="character_name" placeholder="Characters Database: characters">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mx-auto">
                    <input type="text" class="form-control" name="db_website" id="db_website" placeholder="Website Database: website">
                </div>
            </div>
            <h4 class="text-white text-center">Account Information</h4>
            <div class="text-center">
                <small class="text-white">This account will be used by SOAP to send items purchased from the store.</small>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mx-auto">
                    <input type="text" class="form-control" name="soap_username" id="username" placeholder="Username">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mx-auto">
                    <input type="password" class="form-control" name="soap_password" id="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mx-auto text-center">
                    <button type="submit" class="btn btn-primary" name="install">Install TinyCMS</button>
                </div>
            </div>
        </form>
    </div>

    <footer class="footer mt-auto py-3">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="text-center text-white">&copy; 2023 TinyCMS. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>