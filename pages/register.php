<!-- 
Made By: PrivateDonut
Project Name: TinyCMS
Website: https://privatedonut.com
-->
<?php

if (isset($_POST['submit'])) {
   $reg = new Registration($_POST['username'], $_POST['email'], $_POST['password'], $_POST['password_confirmation']);
   $reg->register_checks();
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger text-center' role='alert'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}
?>
<div class="card custom-card mt-4">
    <div class="card-header">
        <h5 class="card-title text-center">Registration</h5>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your name" aria-describedby="nameHelp">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your e-mail" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" aria-describedby="passwordHelp">
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm your password" aria-describedby="password_confirmationHelp">
            </div>
            <div class="text-center">
            <button type="submit" name="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>
</div>