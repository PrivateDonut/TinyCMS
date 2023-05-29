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
         <h2 class="title">
            <i class="fas fa-newspaper" style="color: var(--title-tex);color: var(--title-tex); font-size: 25px; margin-right: 0.2rem;"></i>
            Latest News
         </h2>
         <?php
            $newsHome = new news_home();
            $newsList = $newsHome->get_news();
            
            $count = 0;
            
            foreach ($newsList as $news) :
               if ($count % 3 === 0) {
                  echo '<div class="row">';
               }
            ?>
         <div class="col-md-4 mb-2">
            <div class="card custom-card">
               <?php if($news['thumbnail'] != null) : ?>
               <div class="card-thumbnail">
                  <img src="<?= $news['thumbnail'] ?>" class="card-img-top" alt="...">
               </div>
               <?php endif; ?>
               <div class="card-body custom-card-body d-flex justify-content-between">
                  <div style="width:100%;">
                     <h5 class="card-title custom-card-title text-center"><?= $news['title'] ?></h5>
                     <hr style="border-color: white; margin: 10px 0;">
                     <div class="card-body">
                        <p class="card-text" style="color: white;"><?= $news['content'] ?></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <?php
            $count++;
            if ($count % 3 === 0) {
               echo '</div>';
            }
            endforeach;
            
            if ($count % 3 !== 0) {
               echo '</div>';
            }
            ?>
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
<div class="server-status" style="padding: 20px;">
   <p class="title text-center" style="font-size: 22px;">
      <i class="fas fa-server" style="top: -3px;position: relative;right: 5px; margin-right: 10px;"></i>Server Status
   </p>
</div>
<div class="server-status-inner mx-auto" style="padding: 20px;">
   <div class="card-title custom-card-title text-center" style="margin-bottom: 20px; font-size:22px;">
      <img src="/tinycms/assets/images/wotlk.png" style="width:25px; margin-right: 10px;" alt="">
      <span><?= $server->get_realm_name(); ?></span>
   </div>
   <p class="text-center" style="font-size: 18px;">
      <i class="fas fa-exclamation-circle" style="font-size: 1.5em; margin-right: 10px;"></i>
      Currently the realm is <span style="text-transform:uppercase; font-weight: bold;">Online</span>
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