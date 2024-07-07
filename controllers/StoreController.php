<?php
class StoreController extends BaseController
{
    public function handle($action, $params)
    {
        switch ($action) {
            case 'view':
                return $this->viewStore();
            case 'cart':
                return $this->viewCart();
            case 'addToCart':
                return $this->addToCart();
            case 'removeFromCart':
                return $this->removeFromCart();
            case 'checkout':
                //return $this->checkout();
            default:
                return $this->viewStore();
        }
    }

    private function viewStore()
    {
        $this->global->check_logged_in();
        $store = new Store();
        $account = new Account($_SESSION['username']);
        $category = $_GET['category'] ?? 0;
        $categories = $store->get_categories();
        $items = $store->get_items($category);

        return $this->render('store.twig', [
            'store' => $store,
            'account' => $account,
            'category' => $category,
            'categories' => $categories,
            'items' => $items
        ]);
    }

    private function viewCart()
    {
        $this->global->check_logged_in();
        $store = new Store();
        $account = new Account($_SESSION['username']);
        $account_id = $_SESSION['account_id'];
        $cart = $store->get_cart($account_id);
        $character = new Character();
        $characters = $character->get_characters($account_id);

        return $this->render('cart.twig', [
            'store' => $store,
            'account' => $account,
            'cart' => $cart,
            'characters' => $characters
        ]);
    }

    private function addToCart()
    {
        $this->global->check_logged_in();
        $store = new Store();
        $account_id = $_SESSION['account_id'];
        $item_id = $_POST['item_id'];
        $quantity = $_POST['quantity'];
        $character_id = $_POST['character_id'] ?? 0;
        $store->add_to_cart($account_id, $item_id, $quantity, $character_id);

        return $this->viewStore();
    }

    private function removeFromCart()
    {
        $this->global->check_logged_in();
        $store = new Store();
        $account_id = $_SESSION['account_id'];
        $item_id = $_POST['item_id'];
        $store->remove_from_cart($account_id, $item_id);

        return $this->viewCart();
    }


    // Implement checkout method here
    private function checkout()
    {
    }
}
