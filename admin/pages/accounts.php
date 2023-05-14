<?php
$account = new adminAccounts();
$pageNum = isset($_GET['pg']) ? $_GET['pg'] : 1; // Get the current page number from the URL
$perPage = 4; // Number of accounts to display per page
$accounts = $account->get_accounts($pageNum, $perPage);
?>

<h1 class="mt-5">Account List</h1>
<hr>

<div class="table-responsive">
    <table class="table table-striped">
        <!-- Table header -->
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Creation Date</th>
                <th>Last Login</th>
                <th>Last IP</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accounts as $user) : ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['joindate']; ?></td>
                    <td><?php echo $user['last_login']; ?></td>
                    <td><?php echo $user['last_ip']; ?></td>
                    <td>
                        <a href="#" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Pagination navigation links -->
<div class="pagination">
    <?php
    // Calculate total number of pages
    $totalAccounts = $account->get_total_accounts();
    $totalPages = ceil($totalAccounts / $perPage);

    // Display pagination links
    for ($i = 1; $i <= $totalPages; $i++) {
        $isActive = $i == $pageNum ? "active" : "";
        echo "<li class='page-item $isActive'><a class='page-link' href='?page=accounts&pg=$i'>$i</a></li>";
    }
    ?>
</div>




