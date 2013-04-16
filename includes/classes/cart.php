<?php
class Cart {
    protected $orbsix;

    function __construct()
    {
        $this->cart_setup();
    }

    public function has_items()
    {
        return (bool) $_SESSION['cart'];
    }

    public function add_item($item) {
        if ($this->cart_exists())
        {
            if (array_key_exists($item['productId'], $_SESSION['cart']))
                $_SESSION['cart'][$item['productId']]['product_quantity'] += $item['product_quantity'];
            else
                $_SESSION['cart'][$item['productId']] = $item;
        }
        else
        {
            $this->cart_setup();
            $this->add_item($item);
        }
    }

    public function remove_from_cart($item)
    {
        if (array_key_exists($item['productId'], $_SESSION['cart']))
        {
            unset($_SESSION['cart'][$item['productId']]);
            return true;
        }
    }

    public function clear_cart()
    {
        unset($_SESSION['cart']);
        if ( isset($_SESSION['orderid']) )
            unset( $_SESSION['orderid'] );
    }

    public function update_cart_pricing($items) // update pricing after user has logged in that is member or above
    {
        $current_cart = $this->get_cart();
        $this->orbsix = new Orbsix();
        $order_id = $this->getOrderNumber();

        foreach (json_decode($items,true) as $item_key => $item_value)
        {
            foreach ($item_value as $product_key => $product_value)
            {
                foreach ($current_cart['cart'] as $cart_key => $cart_value)
                {
                    if ( $cart_value['name'] == $product_value['name'] )
                    {
                        $this->orbsix->set_order_product(array('order' => $order_id, 'product' => $product_value['productId'], 'quantity' => $product_value['product_quantity'])); // updates orbsix cart so that session and cart are synced
                        $this->add_item($product_value); // add new items at logged in user rate
                        $this->remove_from_cart($cart_value); // remove items from non logged in user
                    }
                }
            }
        }
    }

    public function update_cart($params)
    {
        foreach ($params['items'] as $key => $item)
        {
            if (array_key_exists($item['productId'], $_SESSION['cart']))
                $_SESSION['cart'][$item['productId']]['product_quantity'] = $item['product_quantity'];
            else
                $_SESSION['cart'][$item['productId']] = $item; // because I am passing in quantity as part of item no need to do checks above
        }
    }

    public function create_order($user)
    {
        if ( ! array_key_exists('username', $user) )
            return false;

        $current_cart = $this->get_cart();
        $this->orbsix = new Orbsix();
        $order_id = $this->getOrderNumber();
        if ( !is_null($order_id) )
        {
            $exisiting_order = json_decode($this->orbsix->get_order($order_id), true);
            foreach ($current_cart['cart'] as $key => $current_cart_value)
            {
                if ( isset($exisiting_order['Result']['products']) )
                {
                    foreach ($exisiting_order['Result']['products'] as $product)
                    {
                        // remove product from orbsix cart
                        if ( array_key_exists($product['product'], $current_cart['cart']) == false && $product['product'] != 152 ) // remove products from order place holder that are no longer in the session. 152 is the gift card product id so dont remove that
                        {
                            $this->orbsix->set_order_product(array('order' => $order_id, 'product' => $product['product'], 'quantity' => 0));
                        }

                        if ( $current_cart_value['productId'] == $product['product'] && $current_cart_value['product_quantity'] != $product['quantity'])
                        {
                           $status = $this->orbsix->set_order_product(array('order' => $order_id, 'product' => $product['product'], 'quantity' => $current_cart_value['product_quantity']));
                        }
                    }
                }
                else
                {
                    return json_encode(array('error' => 'Something went wrong while creating your order. Please reload the page to try.'));
                }
            }
        }
        else
        {
            $order_id = $this->prepare_order($user);
        }
        return $this->orbsix->get_order($order_id); // return order with shipping and new totals etc.
    }

    public function prepare_order($user)
    {
        $current_cart = $this->get_cart();
        $order_response = json_decode($this->orbsix->create_order($user['username']), true); // create order on orbsix
        $order_id = $order_response['Result']['id'];
        $this->set_order_number($order_id); //add order id to cart session

        foreach ($current_cart['cart'] as $product_id => $item)
            $this->orbsix->set_order_product( array( 'order' => $order_id, 'product' => $item['productId'], 'quantity' => $item['product_quantity']) );

        return $order_id;
    }

    public function get_cart()
    {
        if ($this->cart_exists() && $this->has_items())
        {
            return array('cart' => $_SESSION['cart'], 'size' => count($_SESSION['cart']));
        }
        else
        {
            return array('cart' => 0, 'size' => 0);
        }
    }

    private function set_order_number($order)
    {
        $_SESSION['orderid'] = $order;
    }

    private function cart_setup()
    {
        if ($this->cart_exists() == false)
            $_SESSION['cart'] = array();
    }

    private function cart_exists()
    {
        if (isset($_SESSION['cart']))
            return true;
        else
            return false;
    }

    public function getOrderNumber()
    {
        if ($this->cart_exists() && $this->has_items())
        {
            if ( isset($_SESSION['orderid']) )
                return $_SESSION['orderid'];
            else
                return null;
        }
        else
            return array('cart' => 0, 'size' => 0);
    }
}
