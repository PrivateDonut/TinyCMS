<?php

class Store
{
    private $website_connection;
    private $soap_username = "test";
    private $soap_password = "test";
    private $soap_port = "7878";

    public function __construct() {
        $database = new Database();
        $this->website_connection = $database->getConnection('website');
    }

    public function get_categories()
    {
        return $this->website_connection->select('categories', '*');
    }

    public function get_items($category)
    {
        // Fetch product IDs first
        $product_ids = $this->website_connection->select('category_products', 'product_id', [
            'category_id' => $category
        ]);

        if (!empty($product_ids)) {
            // Fetch product details for the fetched product IDs
            return $this->website_connection->select('products', [
                'id', 'item_id', 'title', 'vote_points', 'donor_points'
            ], [
                'id' => $product_ids
            ]);
        } else {
            return []; // Return an empty array if no product IDs were found
        }
    }

    public function get_title($id)
    {
        return $this->website_connection->get('categories', 'title', ['id' => $id]);
    }
    public function add_to_cart($user_id, $product_id, $quantity)
    {
        try {
            $result = $this->website_connection->insert('cart', [
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $quantity
            ]);
    
            if (!$result) {
                // Fetch the last error information
                $errorInfo = $this->website_connection->error();
                echo "Error adding to cart: " . $errorInfo[2];
            }
        } catch (Exception $e) {
            echo "Exception caught: " . $e->getMessage();
        }
    }
    

    public function remove_from_cart($id)
    {
        $this->website_connection->delete('cart', ['id' => $id]);
    }

    public function get_item_name($id)
    {
        return $this->website_connection->get('products', 'title', ['item_id' => $id]);
    }

    public function get_cart($user_id)
    {
        return $this->website_connection->select('cart', '*', ['user_id' => $user_id]);
    }

    public function get_item_price($id)
    {
        return $this->website_connection->get('products', ['title', 'vote_points', 'donor_points'], ['item_id' => $id]);
    }

    public function check_cart($user_id)
    {
        $cart = $this->get_cart($user_id);
        $total = 0;
        $account = new Account($_SESSION['username']);

        foreach ($cart as $item) {
            $item_price = $this->get_item_price($item['product_id']);
            $total += $item_price['vote_points'] * $item['quantity'];
        }

        if ($account->get_account_currency()['donor_points'] <= $total) {
            echo "You don't have enough points!";
            return false;
        }

        $item_ids = array_column($cart, 'product_id');
        $quantities = array_column($cart, 'quantity');

        $character = isset($_POST['character']) ? $_POST['character'] : null;

        if ($character === null) {
            echo "Character is not set. Please select a character.";
            return false;
        }

        $this->soap($character, $item_ids, $quantities, $total);
        return true;
    }

    public function remove_from_cart_all($user_id)
    {
        $this->website_connection->delete('cart', ['user_id' => $user_id]);
    }

    public function remove_donor_points($user_id, $amount)
    {
        $this->website_connection->update('users', [
            'donor_points[-]' => $amount
        ], ['account_id' => $user_id]);
    }

    public function soap($character, $item_ids, $quantities, $total)
    {
        // TO DO //
        /*
        Move errors into a log file instead of printing them on the screen
        */

        $db_host = "127.0.0.1";
        $soapErrors = [];
        foreach (array_combine($item_ids, $quantities) as $item_id => $quantity) {
            $command = 'send items ' . $character . ' "test" "Body" ' . $item_id . ':' . $quantity;
            $client = new SoapClient(NULL, array(
                'location' => "http://$db_host:$this->soap_port/",
                'uri' => 'urn:TC',
                'style' => SOAP_RPC,
                'login' => $this->soap_username,
                'password' => $this->soap_password,
            ));

            try {
                $result = $client->executeCommand(new SoapParam($command, 'command'));
                if ($result == 0) {
                    $soapErrors[] = "Failed to execute SOAP command for item id $item_id";
                }
            } catch (SoapFault $fault) {
                $soapErrors[] = "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})";
            }
        }

        if (empty($soapErrors)) {
            $this->remove_from_cart_all($_SESSION['account_id']);
            $this->remove_donor_points($_SESSION['account_id'], $total);
            $_SESSION['success_message'] = "Your purchase was successful! You can find your items in your mailbox in-game.";
            header("Location: ?page=store");
        } else {
            echo "Something went wrong! Errors: " . implode(", ", $soapErrors);
        }
    }
}
?>
