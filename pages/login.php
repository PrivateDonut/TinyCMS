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

<div class="card mx-auto mt-4" style="background-color: #1a1a1a; max-width: 600px;">
            <div class="card-body mb-2">
                <h2 class="card-title text-center text-white">Account Login</h2>
                <hr style="border-color: white;">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <form>
                            <div class="form-group ">
                                <label for="username" class="text-white">Username</label>
                                <input type="text" class="form-control input-small" id="username" placeholder="Enter username" required>
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-white">Password</label>
                                <input type="password" class="form-control input-small" id="password" placeholder="Enter password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block d-block mt-2" style="width: 80%;">Login</button>
                        </form>
                    </div>
                    <div class="col-12 col-md-6 info-section">
                        <div class="row">
                            <div class="col">
                                <p><a class="link-opacity-100" href="#">Forgot Password?</a></p>
                                <p><a class="link-opacity-100" href="#">Forgot Username?</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
