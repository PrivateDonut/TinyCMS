<!-- 
Made By: PrivateDonut
Project Name: TinyCMS
Website: https://privatedonut.com
-->
<?php
if (!isset($_SESSION)) {
    session_start();
}
foreach (glob("engine/functions/*.php") as $filename) {
    require_once $filename;
}

foreach (glob("engine/configs/*.php") as $filename) {
    require_once $filename;
}

if (!isset($_GET['page']))
    $page = 'home';
else {
    if (preg_match('/[^a-zA-Z]/', $_GET['page']))
        $page = 'home';
    else
        $page = $_GET['page'];
}

$config_object = new gen_config();
$config = $config_object->get_config();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['website_title'] ?></title>
    <link href="https://bootswatch.com/5/darkly/bootstrap.css" rel="stylesheet">
    <link href="assets/css/prism-okaidia.css" rel="stylesheet">
    <style>
        .custom-card {
            max-width: 600px;
            margin: 0 auto;
        }

        .banner {
            background-image: url('assets/images/banner.jpg');
            background-size: cover;
            height: 175px;
            width: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            text-shadow: 2px 2px #000;
        }

        .card-height {
        height: 150px; /* Set a fixed height for the card bodies */
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="index">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="?page=store">Store</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="pvpstats">PvP Statistics</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="banner">
        <img src="banner.png" alt="<?php echo $config['website_title'] ?>">
    </div>
    <?php

    if (file_exists('pages/' . $page . '.php')) {
        include 'pages/' . $page . '.php';
    } else {
        include 'pages/404.php';
    }
    ?>

</body>

<footer class="footer mt-auto py-3">
    <div class="container">
        <div class="row">
            <div class="col">
                <p class="text-center">&copy; 2023 TinyCMS. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
</body>

</html>