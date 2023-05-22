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
<div class="card mx-auto mt-4" style="background-color: #1a1a1a; max-width: 600px;">
    <div class="card-body">
        <h2 class="text-center text-white">REGISTER ACCOUNT</h2>
        <hr style="border-color: white;">
        <form>
            <div class="form-group mx-auto mt-2">
                <input type="text" class="form-control" id="username" placeholder="Enter username" required>
            </div>
            <div class="form-group mx-auto mt-2">
                <input type="email" class="form-control" id="email" placeholder="Enter email" required>
            </div>
            <div class="form-group mx-auto mt-2">
                <input type="password" class="form-control" id="password" placeholder="Enter password" required>
            </div>
            <div class="form-group mx-auto mt-2">
                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password" required>
            </div>
            <button type="submit" class="btn btn-primary mx-auto d-block mt-2">Register Account</button>
        </form>
        <p class="text-center text-white">Already have an account? Login <a href="/page=register" />Here</a></p>
    </div>
</div>