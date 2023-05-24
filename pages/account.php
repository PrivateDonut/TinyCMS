<?php
if (isset($_SESSION['username'])) {
    $account = new Account($_SESSION['username']);
} else {
    header("Location: ?page=login");
}
?>
       <div class="card custom-card mx-auto mt-4" style="background-color: #1a1a1a; max-width: 600px;">
            <div class="card-body">
                <h2 class="card-title text-center text-white">Account Information</h2>
                <hr style="border-color: white;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="username" class="text-white">Username:</label>
                                <p class="text-white"><?= $account->get_username(); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="text-white">Email:</label>
                                <p class="text-white"><?= $account->get_email(); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="last-login" class="text-white">Last Login:</label>
                                <p class="text-white"><?= $account->get_last_login(); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label for="account-status" class="text-white">Account Status:</label>
                                <p class="text-white">TO DO</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="donation-points" class="text-white">Donation Points:</label>
                                <p class="text-white">TO DO</p>
                            </div>
                            <div class="col-md-6">
                                <label for="vote-points" class="text-white">Vote Points:</label>
                                <p class="text-white">TO DO</p>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block d-block mx-auto mt-4" style="width: 80%;">Update Information</button>
                    </div>
                </div>
            </div>
        </div>
