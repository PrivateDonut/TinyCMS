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
   
   $global = new GlobalFunctions();

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
      <?php if (file_exists('pages/' . $page . '.php')) {
         include 'pages/' . $page . '.php';
         } else {
         include 'pages/404.php';
         } ?>
      <footer class="footer">
         <div class="footer-title text-center">
            SOCIAL MEDIA
         </div>
         <div class="footer-social text-center">
            <a target="_blank" href="#" class="social-icons">
               <svg class="svg-inline--fa fa-facebook-f fa-w-10" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                  <path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path>
               </svg>
            </a>
            <a target="_blank" href="#" class="social-icons">
               <svg class="svg-inline--fa fa-twitter fa-w-16" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="twitter" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                  <path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path>
               </svg>
            </a>
            <a target="_blank" href="#" class="social-icons">
               <svg class="svg-inline--fa fa-youtube fa-w-18" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="youtube" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                  <path fill="currentColor" d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"></path>
               </svg>
            </a>
         </div>
         <p class="text-center">Proudly powered by: TinyCMS</p>
         <p class="text-center text-white">&copy; 2023 TinyCMS. All rights reserved.</p>
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