<!-- 
Made By: PrivateDonut
Project Name: TinyCMS
Website: https://privatedonut.com
-->

<?php 

if (isset($_POST['submit'])) {
    $login = new Login($_POST['username'], $_POST['password']);
    //$login->login_checks();


    if ($login->login()) {
        header("Location: /?page=home");
        exit();
    } 
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger text-center' role='alert'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}


?>

<div class="card custom-card mt-5">
    <div class="card-body">
        <h5 class="card-title">Login</h5>
        <form action="" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Username" aria-describedby="usernameHelp">
                <div id="usernameHelp" class="form-text">Enter your username.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" name="password" id="password" class="form-control" placeholder="Password" aria-describedby="passwordHelp">
                <div id="passwordHelp" class="form-text">Enter your password.</div>
            </div>
            <div class="mb-3">
                <input type="submit" name="submit" value="Login" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
