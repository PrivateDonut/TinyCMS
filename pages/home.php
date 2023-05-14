<!-- 
Made By: PrivateDonut
Project Name: TinyCMS
Website: https://privatedonut.com
-->
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

$config = new Configuration();
$newsHome = new news_home();
$latestNews = $newsHome->get_news();
$displayCards = count($latestNews) > 1;

?>
<div class="container mt-5">
    <!-- Main Content -->
    <div class="container-fluid mt-3">
        <div class="row">
            <!-- News Section -->
            <div class="col-md-8">
                <div class="card mb-1">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center">Latest News</h5>
                    </div>
                </div>

                <?php if ($displayCards) : ?>
                    <div class="row">
                        <?php for ($i = 0; $i < min(4, count($latestNews)); $i++) : ?>
                            <div class="col-sm-6 mb-1">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold"><?php echo $latestNews[$i]['title']; ?></h5>
                                        <p class="card-text"><?php echo $latestNews[$i]['content']; ?></p>
                                        <div class="col-12 text-end">
                                        <a href="/?page=news&id=<?php echo $latestNews[$i]['id']; ?>" class="btn btn-primary btn-sm">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
            <!-- Information Boxes -->
            <div class="col-md-4">
                <?php

                if (isset($_SESSION['username'])) {
                    // User is logged in, display account information box
                    echo "<div class='card'>
              <div class='card-body'>
                  <h5 class='card-title fw-bold'>Account Information</h5>
                  <p>Username: " . $account->get_username() . " <br>
                    Email: " . $account->get_email() . " <br>
                  Joined: " . $account->get_joindate() . "<br>
                  </p>

                  <div class='row'>
                  <div class='col-md-3'>
                    <a href='/?page=account' class='btn btn-primary'>Account</a>
                  </div>
                  <div class='col-md-1'>
                    <form action='/engine/logout.php' method='POST'>
                      <button type='submit' class='btn btn-danger' name='logout'>Logout</button>
                    </form>
                  </div>
                </div>
                
                  
                  
              </div>
          </div>";
                } else {
                    // User is not logged in, display login form
                    echo "<div class='card'>
              <div class='card-body'>
                  <h5 class='card-title fw-bold'>Login</h5>
                  <form action='/?page=login' method='POST'>
                      <div class='mb-3'>
                          <input type='text' class='form-control' id='username' name='username' aria-describedby='usernameHelp' placeholder='Enter Username'>
                      </div>
                      <div class='mb-3'>
                          <input type='text' class='form-control' id='password' name='password' aria-describedby='passwordHelp' placeholder='Enter Password'>
                      </div>
                      <button type='submit' class='btn btn-primary'>Login</button>
                  </form>
              </div>
          </div>";
                }
                ?>

                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-center">Realm Name</h5>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">Horde (50)</div>
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">Alliance (50)</div>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Discord</h5>
                        <p class="card-text">TODO</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>