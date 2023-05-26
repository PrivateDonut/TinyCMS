<?php
$global->check_logged_in();
$account = new Account($_SESSION['username']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['new_password'] == $_POST['confirm_password']){  // check if new password matches confirmed password
        $change_password_status = $account->change_password($_POST['old_password'], $_POST['new_password']);
        if ($change_password_status == "success") {
            echo '<div class="alert alert-success">Password has been successfully changed.</div>';
        } else {
            echo '<div class="alert alert-danger">Failed to change password. Please make sure your old password is correct.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">New password and confirmed password do not match.</div>';
    }
}
?>
<div class="card mx-auto mt-4" style="background-color: var(--boxes-bg); max-width: 600px;">
    <div class="card-body mb-2" style="border:1px solid var(--main-border);">
        <h2 class="card-title text-center title" style="margin-top:unset!important;">Change Password</h2>
        <hr style="border-color: var(--main-border);">
        <div class="row">
            <form method="post" action="/?page=changepassword">
                <div class="form-group mx-auto">
                    <label for="old_password" style="color: var(--page-text);">Old Password</label>
                    <input type="text" class="form-control " id="old_password" name="old_password" placeholder="Enter old password" required>
                </div>

                <div class="form-group mx-auto">
                    <label for="password" class="text-white">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required>
                </div>

                <div class="form-group mx-auto">
                    <label for="confirm_password" class="text-white">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                </div>
            </form>
        </div>
    </div>
    <button type="submit" class="change-password-button">Change Password</button>
</div>