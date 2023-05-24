<?php
// Redirect to install page if install.lock is not found.
if (!file_exists('engine/install.lock')) {
    header('Location: install');
    exit;
}
if (!isset($_SESSION)) {
    session_start();
}
foreach (glob("engine/functions/*.php") as $filename) {
    require_once $filename;
}

foreach (glob("engine/configs/*.php") as $filename) {
    require_once $filename;
}

if (!isset($_GET['page'])) {
    $page = 'home';
} else {
    if (preg_match('/[^a-zA-Z]/', $_GET['page'])) {
        $page = 'home';
    } else {
        $page = $_GET['page'];
    }
}

$config_object = new gen_config();
$config = $config_object->get_config();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TinyCMS Theme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/custom.css" />
</head>

<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?page=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">How To Connect</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=store">Store</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=account">Account</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Banner Starts -->
    <img src="assets/images/banner.jpg" alt="" style="height: 238px; width: 100%; object-fit: cover;" />
    <!-- Banner Ends-->

    <!-- Content Starts -->
    <?php if (file_exists('pages/' . $page . '.php')) {
        include 'pages/' . $page . '.php';
    } else {
        include 'pages/404.php';
    } ?>
    <footer class="footer mt-auto py-3">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="text-center">&copy; 2023 TinyCMS. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    <script type="text/javascript" src="http://cdn.cavernoftime.com/api/tooltip.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qr6s LL7alrTT0mso5C5PL09dww1cmGhyu/wVa+6h9hV6Z9ABnFsIa3C5V4PEmyxL"
        crossorigin="anonymous"></script>
	<script src="assets/js/custom.js"></script>
</body>

</html>
