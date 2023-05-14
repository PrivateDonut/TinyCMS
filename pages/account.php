<!-- 
- Made By : PrivateDonut
- Project Name : TinyCMS
- Website : https://privatedonut.com
-->
<?php
if (isset($_SESSION['username'])) {
    $account = new Account($_SESSION['username']);
} else {
    header("Location: /?page=login");
}
?>
<div class="card custom-card mt-5">
    <div class="card-header text-center fw-bold">
        Account Information
    </div>
    <div class="card-body">
        <p class="card-text">Username: <?php echo $account->get_username(); ?></p>
        <p class="card-text">Email: <?php echo $account->get_email(); ?></p>
        <p class="card-text">Joined: <?php echo $account->get_joindate(); ?></p>
        <p class="card-text">Last IP: <?php echo $account->get_last_ip(); ?></p>
        <p class="card-text">Last Login: <?php echo $account->get_last_login(); ?></p>
        <div class='row justify-content-center'>
            <div class='col-md-2 text-center'>
                <a href='/?page=account' class='btn btn-primary'>Edit</a>
            </div>
            <div class='col-md-1 text-center'>
                <form action='/engine/logout.php' method='POST'>
                    <button type='submit' class='btn btn-danger' name='logout'>Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
