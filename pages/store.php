<?php
$store = new Store();
$account = new Account($_SESSION['username']);
$category = 0;
if (isset($_GET['category'])) {
    $category = $_GET['category'];
}

$categories = $store->get_categories();
$items = $store->get_items($category);

if (isset($_POST['add_to_cart'])) {
    $store->add_to_cart($account->get_id(), $_POST['product_id'], $_POST['quantity']);
}
?>

<div class="container">
    <div class="card mt-5">
        <div class="card-body custom-card-body">
            <div class="row">
                <!-- Left Sidebar -->
                <div class="col-md-3">
                    <div class="list-group">
                        <h2 class="custom-card-text mb-2">Store</h2>
                        <a href="?page=cart" class="list-group-item list-group-item-action">View Cart</a>
                        <?php while ($row = $categories->fetch_assoc()) : ?>
                            <a href="?page=store&category=<?= $row['id'] ?>" class="list-group-item list-group-item-action">
                                <?= $row['title'] ?>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Right Content -->
                <div class="col-md-9">
                    <h2 class="custom-card-text">Store</h2>
                    <table class="table custom-card">
                        <thead class="thead-light">
                            <tr class="custom-card-text">
                                <th>Item</th>
                                <th>Vote Points</th>
                                <th>Donor Points</th>
                                <th>Buy</th>
                            </tr>
                        </thead>
                        <tbody class="text-white">
                            <?php while ($row = $items->fetch_assoc()) : ?>
                                <tr>
                                    <td>
                                        <a href="http://wotlk.cavernoftime.com/item=<?= $row['item_id'] ?>" class="item">
                                            <?= $row['title'] ?>
                                        </a>
                                    </td>
                                    <td><?= $row['vote_points'] ?></td>
                                    <td><?= $row['donor_points'] ?></td>
                                    <td>
                                        <form method="POST" action="index.php?page=store&category=<?= $category ?>">
                                            <input type="hidden" name="product_id" value="<?= $row['item_id'] ?>">
                                            <input type="number" name="quantity" value="1" min="1" max="10">
                                            <input type="submit" name="add_to_cart" value="Add to Cart" class="btn btn-primary">
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>