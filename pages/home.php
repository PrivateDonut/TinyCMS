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
?>

<div class="container">
    <h2 class="" style="color: gold;">Latest News</h2>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <?php
                $newsHome = new news_home();
                $newsList = $newsHome->get_news();

                foreach ($newsList as $news) :
                ?>
                    <div class="col-md-12 mb-2">
                        <div class="card custom-card">
                            <div class="card-body custom-card-body d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title custom-card-title" style="margin-bottom: 0;"><?= $news['title'] ?></h5>
                                    <p class="card-text mb-0" style="color: white;">Posted by: <?= $news['author'] ?></p>
                                    <hr style="border-color: white; margin: 10px 0;">
                                    <div class="card-body custom-card-body">
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
        <div class="col-md-4">
            <?php // Check if the user is logged in
            if (isset($_SESSION['username'])) {
                // User is logged in, display the "Once logged in" box
            ?>
                <div class="col-md-12">
                    <!-- Logged In Form -->
                    <div class="card custom-card mb-4">
                        <div class="card-body custom-card-body">
                            <h5 class="card-title custom-card-title" style="margin-bottom: 0;">Account Details</h5>
                            <hr style="border-color: white; margin: 10px 0;">
                            <p class="" style="color: white;">
                                Username: <?= $_SESSION['username'] ?>
                                <br />
                                Email: <?= $account->get_email() ?>
                                <br />
                                Rank: <?= $account->get_rank() ?>
                                <br />
                                Vote Points: TO DO
                                <br />
                                Donation Points: TO DO
                                <br />
                            <form action='/engine/logout.php' method='POST'>
                                <button type='submit' class='btn btn-danger' name='logout'>Logout</button>
                            </form>
                            </p>
                        </div>
                    </div>
                </div>
            <?php
            } else {
                // User is not logged in, display the "Login" box
            ?>
                <div class="col-md-12">
                    <!-- Login Form -->
                    <div class="card custom-card mb-4">
                        <div class="card-body custom-card-body">
                            <h5 class="card-title custom-card-title" style="margin-bottom: 0;">Login</h5>
                            <p class="card-text mb-0" style="color: white;">Enter your credentials below</p>
                            <hr style="border-color: white; margin: 10px 0;">
                            <form action="" method="post">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" />
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                                </div>
                                <button type="submit" class="btn btn-primary" style="background-color: #ffd700; color: #36454F;">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <!-- Information Box -->
            <div class="card custom-card">
                <div class="card-body custom-card-body">
                    <h5 class="card-title custom-card-title" style="margin-bottom: 0;">Blackrock</h5>
                    <p class="card-text mb-0" style="color: white;">Server stats</p>
                    <hr style="border-color: white; margin: 10px 0;">
                    <p class="card-text custom-card-text text-center" style="color: white">
                        <h7 style="color: #ffffff;">Total Online:</h7> 0<br />
                        Horde: 45% | Alliance: 65%<br />
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>