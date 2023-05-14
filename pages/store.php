<div class="container">
    <div class="card mt-5">
        <div class="card-body">
            <div class="row">
                <!-- Left Sidebar -->
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="?page=store&category=features" class="list-group-item list-group-item-action">Features</a>
                        <a href="?page=store&category=mounts" class="list-group-item list-group-item-action">Mounts</a>
                        <a href="?page=store&category=gear" class="list-group-item list-group-item-action">Gear</a>
                        <a href="?page=store&category=items" class="list-group-item list-group-item-action">Items</a>
                    </div>
                </div>

                <!-- Right Content -->
                <div class="col-md-9">
                    <?php
                    // Check the selected category
                    if (isset($_GET['category'])) {
                        $category = $_GET['category'];

                        // Display the items based on the selected category
                        switch ($category) {
                            case 'features':
                                // Display features
                                echo "<h2>Features</h2>";
                                // Fetch and display features from the database
                                break;
                            case 'mounts':
                                // Display mounts
                                echo "<h2>Mounts</h2>";
                                // Fetch and display mount items from the database
                                break;
                            case 'gear':
                                // Display gear
                                echo "<h2>Gear</h2>";
                                // Fetch and display gear items from the database
                                break;
                            case 'items':
                                // Display items
                                echo "<h2>Items</h2>";
                                // Fetch and display other items from the database
                                break;
                            default:
                                // Invalid category selected, display an error message or redirect to an error page
                                echo "<h2>Invalid Category</h2>";
                                break;
                        }
                    } else {
                        // No category selected, display a default message or redirect to a default page
                        echo "<h2>Store</h2>";
                        echo "<p>Select a category from the left menu to browse items.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>