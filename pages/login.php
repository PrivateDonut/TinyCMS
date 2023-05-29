<?php

if (isset($_POST['submit'])) {
    $login = new Login($_POST['username'], $_POST['password']);
    //$login->login_checks();

    if ($login->login()) {
        header("Location: ?page=home");
        exit();
    }
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger text-center' role='alert'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}

?>

<div class="custom-container">
    <div class="custom-card mx-auto mt-4">
        <div class="card-body px-4 py-3">
            <h2 class="card-title text-center mb-3" style="color:var(--title-text);border-bottom: 1px solid var(--main-border);padding-bottom: 1rem;
}">Account Login</h2>
            <div class="row">
                <div class="col-12 col-md-6">
                    <form>
                        <div class="form-group">
                            <label for="username" class="info-label">Username</label>
                            <input type="text" class="form-control input-small" id="username" placeholder="Enter username" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="info-label">Password</label>
                            <input type="password" class="form-control input-small" id="password" placeholder="Enter password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block d-block mt-3 change-password-button" style="width:70%;">Login</button>
                    </form>
                </div>
                <div class="col-12 col-md-6 info-section" style="border-left: 1px solid var(--main-border);">
                    <div class="row">
                        <div class="col">
                            <p class="mb-0"><a class="link-opacity-100" href="?page=register" style="color: var(--page-text)">Create Account</a></p>
                            <p class="mb-0"><a class="link-opacity-100" href="#" style="color: var(--page-text)">Forgot Password?</a></p>
                            <p class="mb-0"><a class="link-opacity-100" href="#" style="color: var(--page-text)">Forgot Username?</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
