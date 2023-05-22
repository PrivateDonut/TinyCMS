<?php
$store = new Store();
$account_id = $_SESSION['account_id'];
$cart = $store->get_cart($account_id);

if (isset($_POST['add_to_cart'])) {
    $store->add_to_cart($account_id, $_POST['product_id'], $_POST['quantity']);
}

if (isset($_POST['remove_from_cart'])) {
    $store->remove_from_cart($_POST['id']);
}

?>

<div class="container">
    <div class="card mt-3 mx-auto" style="max-width: 700px;">
        <div class="card-body custom-card-body">
            <div class="row">
                <h3 class="custom-card-text text-center">Cart</h3>
            </div>
            <hr>
            <div class="row">
                <table class="table mx-auto text-white" style="max-width: 500px;">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item) : ?>
                            <?php $item_name = $store->get_item_name($item['product_id']); ?>
                            <tr>
                                <td>
                                    <a href="http://wotlk.cavernoftime.com/item=<?= $item['product_id'] ?>" class="item">
                                        <?= $item_name ?>
                                    </a>
                                </td>
                                <td>Item Price</td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>
                                    <form method="POST"">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <input type="submit" name="remove_from_cart" value="Remove" class="btn btn-danger">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="row">
                <div class="col text-center text-white">
                    <p>Total: DP</p>
                    <button class="btn btn-primary">Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>
