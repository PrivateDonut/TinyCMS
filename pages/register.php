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
<div class="custom-container">
    <div class="custom-card mx-auto mt-4">
        <div class="card-body px-4 py-3">
            <h2 class="text-center title text-white mb-4" style="color:var(--title-text)!important;">REGISTER ACCOUNT</h2>
            <hr class="custom-hr mb-4" style="border-color: var(--main-border);">
            <form method="post">
                <div class="form-group mx-auto mt-2">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                </div>
                <div class="form-group mx-auto mt-2">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                </div>
                <div class="form-group mx-auto mt-2">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                </div>
                <div class="form-group mx-auto mt-2">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary mx-auto d-block mt-3 change-password-button" style="width:43%;">Register Account</button>
            </form>
            <p class="text-center text-white mt-3">Already have an account? Login <a href="?page=login" style="color: var(--page-text)">Here</a></p>
        </div>
    </div>
</div>
