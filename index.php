<?php
// Define the base directory
define('BASE_DIR', __DIR__);

// Include configuration

// Redirect to install page if install.lock is not found.
if (!file_exists(BASE_DIR . '/engine/install.lock')) {
    header('Location: install');
    exit();
}

if (!isset($_SESSION)) {
    session_start();
}

// Include functions
foreach (glob(BASE_DIR . "/engine/functions/*.php") as $filename) {
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

$global = new GlobalFunctions();

$config_object = new gen_config();

?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>TinyCMS Theme</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
      <link rel="stylesheet" href="assets/css/style.css" />
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
   </head>
   <body>
      <!-- Navbar Start -->
      <nav class="navbar navbar-expand-lg navbar-dark">
         <a href="?page=home">
            <div class="logo"></div>
         </a>
         <?php // Check if the user is logged in
            if (!isset($_SESSION['username'])) {
                // User is logged in, display the "Once logged in" box
            ?>
         <div class="userbar">
            <li class="nav-item">
               <a class="nav-link" href="?page=login">Login</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="?page=register">Register</a>
            </li>
         </div>
         <?php
            } else {
                ?>
         <div class="userbar">
            <li class="nav-item">
               <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button">
               <?= $_SESSION['username'] ?></i>
               </a>
               <div class="dropdown-menu" aria-labelledby="userDropdown" id="userDropdownMenu">
                  <!-- Dropdown items -->
                  <a class="dropdown-item" href="?page=account">Account</a>
                  <a class="dropdown-item" href="?page=logout">Logout</a>
                  <!-- Add more items as needed -->
               </div>
            </li>
         </div>
         <?php
            }
            ?>
         <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
               <ul class="navbar-nav mx-auto">
                  <li class="nav-item">
                     <a class="nav-link" href="?page=home">
                     <i class="fas fa-home"></i> Home</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#">
                     <i class="fas fa-link"></i> How To Connect
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="?page=store">
                     <i class="fas fa-store"></i> Store
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
      <!-- Navbar End -->
      <!-- Banner Starts -->
      <img src="assets/images/banner.jpg" alt="" class="banner" />
      <!-- Banner Ends-->
      <!-- Content Starts -->
      <?php
         $page = $_GET['page'] ?? 'home'; // Default to home if no page parameter is set
         $page_parts = explode('/', $page);
         
         foreach ($page_parts as &$part) {
             $part = preg_replace('/[^a-z0-9]/i', '', $part);
         }
         unset($part);
         
         $page_path = implode('/', $page_parts) . '.php';
         
         if (file_exists('pages/' . $page_path)) {
             include 'pages/' . $page_path;
         } else {
             include 'pages/404.php';
         }
         ?>
      <footer class="footer">
         <div class="container">
            <div class="footer-title text-center">
               Connect with us
            </div>
            <div class="footer-social">
               <a href="#" target="_blank" class="social-icons"><i class="fab fa-facebook-f"></i></a>
               <a href="#" target="_blank" class="social-icons"><i class="fab fa-twitter"></i></a>
               <a href="#" target="_blank" class="social-icons"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="footer-copy">
               <p>Proudly powered by: <a href="https://github.com/PrivateDonut/TinyCMS" style="color: var(--title-text);">TinyCMS</a></p>
               <p>&copy; 2023 TinyCMS. All rights reserved.</p>
            </div>
         </div>
      </footer>
      <script>
         $(document).ready(function() {
             $('.dropdown-toggle').dropdown();
             });
      </script>
      <script type="text/javascript" src="http://cdn.cavernoftime.com/api/tooltip.js"></script>
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-qr6s LL7alrTT0mso5C5PL09dww1cmGhyu/wVa+6h9hV6Z9ABnFsIa3C5V4PEmyxL" crossorigin="anonymous"></script>
      <script src="assets/js/custom.js"></script>
   </body>
</html>
