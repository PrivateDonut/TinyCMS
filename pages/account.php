<?php

$global->check_logged_in();
$account = new Account($_SESSION['username']);

if (isset($_POST['change_password'])) {
    header("Location: ?page=changepassword");
    exit();
}
?>
       
<div class="custom-container">
    <div class="custom-card">
        <div class="title-container">
            <div class="title">Account Information</div>
            <div class="subtitle">In this section you'll find basic information about your account</div>
        </div>
        <div class="info-container">
            <div class="info-item">
                <span class="info-label">Username:</span>
                <span class="info-value"><?= $account->get_username(); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value"><?= $account->get_email(); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Last Login:</span>
                <span class="info-value"><?= $account->get_last_login(); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Account Status:</span>
                <span class="info-value"><?= $account->is_banned(); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Donation Points:</span>
                <span class="info-value"><?= $account->get_account_currency()['donor_points'] ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Vote Points:</span>
                <span class="info-value"><?= $account->get_account_currency()['vote_points'] ?></span>
            </div>
    
        </div>
    </div>
    <form method="POST">
    <button class="change-password-button" name="change_password">Change Password</button>
    </form>
    
</div>
<div class="custom-container">
    <div class="custom-card">
        <div class="title-container">
            <div class="title">Characters</div>
            <div class="subtitle">In this section you'll find a list of your characters</div>
        </div>
        <div class="info-container">
            <p style="margin-left:40px;color:red;">Will populate this section when backend is finished</p>
            <p style="margin-left:40px;color:red;">it will look insane, i promise :D </p>
            <p style="margin-left:40px;color:red;">if it doesnt, i will not do anything kek </p>
        </div>
    </div>
</div>