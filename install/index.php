<?php
if (file_exists('../engine/install.lock')) {
    header('Location: /?page=home');
    exit;
}
require_once("functions/install.php");
$check = new InstallTinyCMS();
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

        <h4 class="text-white text-center">PHP Extensions</h4>
        <div class="d-flex justify-content-center mb-2">
            <small class="text-white ">All the following extensions must be enabled for TinyCMS to work properly.</small>
        </div>

        <p class="text-white text-center">MySQLI: <?= $check->checkExtension('mysqli'); ?></p>
        <p class="text-white text-center">GMP: <?= $check->checkExtension('gmp'); ?></p>
        <p class="text-white text-center">SOAP: <?= $check->checkExtension('soap'); ?></p>
        <p class="text-white text-center">CURL: <?= $check->checkExtension('curl'); ?></p>
        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-success" onclick="window.location.href = 'final';">Proceed To Install</button>

        </div>
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
</body>

</html>