<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<?php
   if (isset($_SESSION['success_message'])) {
       echo '<div class="text-center">';
       echo '<div class="alert alert-dismissible alert-success">';
       echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
       echo '<strong>Well done!</strong> ' . $_SESSION['success_message'] . '';
       echo '</div>';
       echo '</div>';
       unset($_SESSION['success_message']);
   }
   
   if (isset($_SESSION['error'])) {
       echo '<div class="text-center">';
       echo '<div class="alert alert-dismissible alert-danger">';
       echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
       echo '<strong>Hey there!</strong> ' . $_SESSION['error'] . '';
       echo '</div>';
       echo '</div>';
       unset($_SESSION['error']);
   }
   
   if (isset($_SESSION['username'])) {
       $account = new Account($_SESSION['username']);
   }
   
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $username = $_POST['username'];
       $password = $_POST['password'];
   
       $login = new Login($username, $password);
       $login->login_checks();
       $login->login();
   }
   
   $config = new Configuration();
   $newsHome = new news_home();
   $latestNews = $newsHome->get_news();
   $server = new ServerInfo();
   ?>
<div class="container">
   <div class="row">
      <div class="col-md-12 mx-auto">
         <h2 class="title"><i class="fas fa-newspaper" style="color: var(--title-tex);color: var(--title-tex); font-size: 25px; margin-right: 0.2rem;"></i>Latest News</h2>
         <div class="row">
            <?php
               $newsHome = new news_home();
               $newsList = $newsHome->get_news();
               
               foreach ($newsList as $news) :
               ?>
            <div class="col-md-12 mb-2">
               <div class="card custom-card">
                  <div class="card-body custom-card-body d-flex justify-content-between">
                     <div style="width:100%;">
                        <h5 class="card-title custom-card-title"><?= $news['title'] ?></h5>
                        <p class="card-text mb-0" style="color: white;">Posted by: <?= $news['author'] ?></p>
                        <hr style="border-color: white; margin: 10px 0;">
                        <div class="card-body">
                           <p class="card-text" style="color: white;"><?= $news['content'] ?></p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <?php endforeach; ?>
         </div>
      </div>
      <!-- Add pagination support -->
   </div>
</div>
<div class="howto-section">
   <div class="text-center title-connect">HOW TO CONNECT</div>
   <p class="text-center realmlist">set realmlist logon.tinycms.com</p>
   <p class="text-center"> <a class="howto-links" href="#">Download Client</a> <span style="color:white;font-size:23px;">|</span> <a class="howto-links" href="#">Connection Guide</a> </p>
   <p><?php $server->get_realm_name(); ?></p>
</div>
<div class="server-status">
   <p class="title text-center"><i class="fas fa-server" style="top: -3px;position: relative;right: 5px;"></i>Server Status</p>
</div>
<div class="server-status-inner mx-auto">
   <div class="card-title custom-card-title text-center" style="margin-bottom: 0; font-size:20px;">
      <img src="/tinycms/assets/images/wotlk.png" style="width:25px;" alt="">
      <span><?= $server->get_realm_name(); ?></span>
   </div>
   <p class="text-center">
      <svg style="top:4px;margin-top:1rem;" class="svg-inline--fa fa-exclamation-circle fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="exclamation-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
         <path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path>
      </svg>
      Currently the realm is <span style="text-transform:uppercase;">Online</span>
   </p>
</div>
<div class="community-section">
   <div class="community-title text-center">become a part of the community</div>
   <div class="discord-widget text-center">
      <a href="#">
      <img src="https://discordapp.com/api/guilds/938237660017872896/widget.png?style=banner2">
      </a>
   </div>
   <div class="community-text text-center">
      Join our <a href="#" style="color:var(--title-text);">Discord</a> or <a href="#" style="color:var(--title-text);">Community Forum.</a> <br />
      Remember to familiarize yourself with our <a href="#" style="color:var(--title-text);">Rules & Regulations.</a> <br />
      Thank you!
   </div>
</div>