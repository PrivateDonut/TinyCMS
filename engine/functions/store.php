<?php

class Store{
    private $website_connection;

    public function __construct()
    {
        $config = new Configuration();
        $this->website_connection = $config->getDatabaseConnection('website');
    }

    
    public function get_categories()
    {
        $stmt = $this->website_connection->prepare("SELECT * FROM categories");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function get_items($category)
    {
        $stmt = $this->website_connection->prepare("SELECT id, item_id, title, vote_points, donor_points FROM products WHERE id IN (SELECT product_id FROM category_products WHERE category_id = ?)");
        $stmt->bind_param("i", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function get_title($id)
    {
        $stmt = $this->website_connection->prepare("SELECT title FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($title);
        $stmt->fetch();
        $stmt->close();
        return $title;
    }

    public function add_to_cart($user_id, $product_id, $quantity)
    {
        $stmt = $this->website_connection->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    public function remove_from_cart($id)
    {
        $stmt = $this->website_connection->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function get_item_name($id)
    {
        $stmt = $this->website_connection->prepare("SELECT title FROM products WHERE item_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($title);
        $stmt->fetch();
        $stmt->close();
        return $title;
    }

    public function get_cart($user_id)
    {
        $stmt = $this->website_connection->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cartData = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $cartData;
    }

    public function calculate_total_cost($cart)
    {

    }

    

}
