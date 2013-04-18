<?php
class Orbsix{
    private $url = 'https://sollestaging.orbsix.com/rest/v3/index.php?transactionkey=Eqb41V5mEpgVUm6d58QjPjj5WrlRoziv';
    // private $url = 'https://solle.orbsix.com/rest/v3/index.php?transactionkey=0a85fn7zSLoY0rXo4VifJ4bo89P2W317JJ8FiDW2Qzwv1mvko9kk7CFd4ch0zK60';
    // private $key = 'Eqb41V5mEpgVUm6d58QjPjj5WrlRoziv';
    private $cart;
    private $relationshipTypes = array(1, 2); //// 1 - SmartPlan Tree, 2 - Mentor Tree, 3 - Member Tree

    public function get_products($member = false)
    {
        if ( $member == true )
        {
            $order_types = array(22 => 'Balancing', 23 => 'Lifting', 24 => 'Calming', 25 => 'Clarifying');
        }
        else
        {
            $order_types = array(17 => 'Balancing', 18 => 'Lifting', 19 => 'Calming', 20 => 'Clarifying');
        }

        $products = array();
        $pages = array(
            'Solle Vital' => 'http://sollenaturals.com/vital.php',
            'SolleFlex PI' => 'http://sollenaturals.com/solle_flex.php',
            'AdaptAble' => 'http://sollenaturals.com/adaptable.php',
            'Solle Essentials - Balance' => 'http://sollenaturals.com/balance_oil.php',
            'CinnaMate' => 'http://sollenaturals.com/cinnamate.php',
            'Solle Essentials - Lift' => 'http://sollenaturals.com/lift_oil.php',
            'Verdezymes' => 'http://sollenaturals.com/verdezymes.php',
            'Solle Essentials - Calm' => 'http://sollenaturals.com/calm_oil.php',
            'ProBio IQ' => 'http://sollenaturals.com/pro_bio.php',
            'SolleMegas' => 'http://sollenaturals.com/solle_megas.php',
            'Solle Essentials - Clarify' => 'http://sollenaturals.com/clarify_oil.php'
        );
        $data = array();
        $data['type'] = 'GetProducts';
        foreach ($order_types as $id => $category)
        {
            $data['ordertype'] = $id;
            $response = json_decode($this->curl_request($data), true);
            foreach ($response['Result'] as $index => $product)
            {
                // not ideal but in a hurry and need to get it done
                foreach ($pages as $title => $page)
                {
                    if ( $product['name'] == $title)
                        $products[$category][$index]['link'] = $page;
                    elseif ( $product['name'] == 'Refill - Solle Vital')
                        $products[$category][$index]['link'] = 'http://sollenaturals.com/vital.php';
                    elseif ( $product['name'] == 'Refill - CinnaMate')
                        $products[$category][$index]['link'] = 'http://sollenaturals.com/cinnamate.php';

                    $products[$category][$index]['productId'] = $product['productId'];
                    $products[$category][$index]['sku'] = $product['sku'];
                    $products[$category][$index]['country'] = $product['country'];
                    $products[$category][$index]['name'] = $product['name'];
                    $products[$category][$index]['description'] = $product['description'];
                    $products[$category][$index]['wholeSalePrice'] = $product['wholeSalePrice'];
                    $products[$category][$index]['retailPrice'] = $product['retailPrice'];
                    $products[$category][$index]['weight'] = $product['weight'];
                    $products[$category][$index]['thumbnailImage'] = $product['thumbnailImage'];
                    $products[$category][$index]['product_quantity'] = 1;
                }
            }
        }
        return json_encode($products);
    }

    public function get_user_types()
    {
        $data = array();
        $data['type'] = 'GetUserTypes';
        return $this->curl_request($data);
    }

    public function get_gift_card_value($params)
    {
        $data = array();
        $data['type'] = 'GetGiftCardValue';
        $data['giftCardCode'] = $params;
        return json_decode($this->curl_request($data),true);
    }

    public function use_gift_card($params)
    {
        $data = array();
        $data['type'] = 'UseGiftCard';
        $data['giftCardCode'] = $params['card_number'];
        $data['order'] = $params['order'];
        return $this->curl_request($data);
    }

    public function process_gift_card($params)
    {
        $data = array();
        $gift_card_value = $this->get_gift_card_value($params);
        if ( $gift_card_value['Result']['value'] != 'false' )
        {
            $this->cart = new Cart();
            $ordernumber = $this->cart->getOrderNumber();
                //update order with gift card
                $this->update_order($ordernumber, array('details' => array('giftCardCode' => $params)));
                // mark gift card as used
                return $this->use_gift_card(array('card_number' => $params, 'order' => $ordernumber));
        }
        else
        {
            return json_encode(array('error' => 'Unable to find gift card. Please check your card and try again.'));
        }
    }

    public function update_order($ordernumber, $params)
    {
        $data = array();
        $data['type'] = 'UpdateOrder';
        foreach ($params['details'] as $key => $value)
        {
            if ( $key == 'shippingState' ){
                $data['shippingState'] = $value['id'];
                continue;
            }

            if ( $key == 'shippingCountry' ){
                $data['shippingCountry'] = $value['id'];
                continue;
            }

            $data[$key] = $value;
        }
        $data['order'] = $ordernumber;
        return $this->curl_request($data);
    }

    public function process_payment($params)
    {
        $data = array();

        $data['type'] = 'CreateOrderPayment';
        $data['processPayment'] = true;
        $data['billingAddressLine1'] = $params['details']['shippingAddressLine1'];
        $data['billingCity'] = $params['details']['shippingCity'];
        $data['billingState'] = $params['details']['shippingState']['id'];
        $data['billingPostalCode'] = $params['details']['shippingPostalCode'];
        $data['billingCountry'] = $params['details']['shippingCountry']['id'];
        $data['order'] = $params['order'];
        $data['amount'] = $params['total'];
        $data['account'] = $params['payment']['credit_card_number'];
        $data['month'] = $params['payment']['exp_month'];
        $data['year'] = $params['payment']['exp_year'];
        $data['cvv'] = $params['payment']['cvv'];

        return $this->curl_request($data);
    }

    public function get_states()
    {
        $data = array();
        $data['type'] = 'GetStates';
        return $this->curl_request($data);
    }

    public function shipping_types()
    {
        $data = array();
        $data['type'] = 'GetShippingTypes';
        return $this->curl_request($data);
    }

    public function authenticate_user($params)
    {
        $data = array();
        $data['type'] = 'AuthenticateUser';
        $data['username'] = $params['user']['username'];
        $data['password'] = $params['user']['password'];
        return $this->curl_request($data);
    }

    public function password_request($params) // call update user to reset password
    {
        $data = array();
        $data['type'] = 'UpdateUser';
        $data['username'] = $params['user']['username'];
        $data['password'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
        return $this->curl_request($data);
    }

    public function create_customer($params)
    {
        $data = array();
        $data['type'] = 'CreateUser';
        foreach ($params['customer'] as $key => $value)
            $data[$key] = $value;
        return $this->curl_request($data);
    }

    public function update_user($params)
    {
        $data = array();
        $data['type'] = 'UpdateUser';
        foreach ($params['customer'] as $key => $value){
            switch ($key) {
                case 'shippingState':
                    $data['state'] = $value['id'];
                    continue;
                break;
                case 'shippingCountry':
                    $data['country'] = $value['id'];
                    continue;
                break;
                case 'shippingPostalCode':
                    $data['postalcode'] = $value;
                    continue;
                break;
                case 'shippingCity':
                    $data['city'] = $value;
                    continue;
                break;

                default:
                    $data[$key] = $value;
                    break;
            }
        }

        $data['username'] = $params['username'];
        return $this->curl_request($data);
    }

    public function get_countries()
    {
        $data = array();
        $data['type'] = 'GetCountries';
        return $this->curl_request($data);
    }

    public function get_user($username)
    {   
        $data = array();
        $data['type'] = 'GetUser';
        if ( is_array($username) )
            $username = $username['username'];
        $data['username'] = $username;
        return $this->curl_request($data);
    }

    public function set_user_relationship($params)
    {
        $data = array();
        foreach($this->relationshipTypes as $type ){
            if ( $type == 1 )
                $data['parentUsername'] = 1;
            else
                $data['parentUsername'] = $params['parentUsername'];

            $data['type'] = 'SetUserRelationship';
            $data['username'] = $params['username'];
            $data['relationshipType'] = $type;
            $this->curl_request($data);
        }

        // return $this->curl_request($data);
    }

    public function check_username($params)
    {
        $data = array();
        $data['type'] = 'UsernameAvailable';
        $data['username'] = $params['username'];
        return $this->curl_request($data);
    }

    public function create_or_update_order($params)
    {
        $data = array();
        $this->cart = new Cart();        
        $user = new User();
        $order_number = $this->cart->getOrderNumber();

        if ( $order_number == false ){
            $order_number = json_decode($this->create_order($user->getUserName()), true);
            $order_number = $order_number['Result']['id'];
            $this->cart->set_order_number($order_number);
        }

        $params['order'] = $order_number;
        if ( isset($params['gift_card_number']) )
        {
            $result = json_decode( $this->process_gift_card( $params['gift_card_number'] ) );
            if ( $result->error )
                return $result;
        }

        $this->update_user(array('customer' => $params['details'], 'username' => $user->getUserName()));
        $this->update_order($order_number, $params);
        $this->add_products_to_order($order_number, $this->cart->get_products_from_cart());

        return $this->get_order($order_number);
    }

    public function add_products_to_order($order_id, $params)
    {
        $data = array();
        foreach ($params as $product) 
        {
            if ( $product['product_quantity'] == 0 )
                continue;
            
            $data['type'] = 'SetOrderProduct';
            $data['order'] = $order_id;
            $data['product'] = $product['productId'];
            $data['quantity'] = $product['product_quantity'];
            $this->curl_request($data);
        }
    }

    public function create_order($username) // use this to create placeholder for order
    {
        $data = array();
        $data['type'] = 'CreateOrder';
        $data['username'] = $username;
        return $this->curl_request($data);
    }

    public function set_order_product($params)
    {
        $data = array();
        $data['type'] = 'SetOrderProduct';
        $data['order'] = $params['order'];
        $data['product'] = $params['product'];
        $data['quantity'] = $params['quantity'];
        return $this->curl_request($data);
    }

    public function remove_product($params)
    {
        $data = array();
        $data['type'] = 'SetOrderProduct';
        $data['order'] = $params['order'];
        $data['product'] = $params['productId'];
        $data['quantity'] = $params['product_quantity'];
        return $this->curl_request($data);
    }

    public function get_order($order_id)
    {
        $data = array();
        $data['type'] = 'GetOrder';
        $data['order'] = $order_id;
        return $this->curl_request($data);
    }

    private function curl_request($params)
    {
        // var_dump($params);
        $query_string = "";
        foreach ($params AS $k=>$v)
            $query_string .= "$k=".urlencode($v)."&";

        // var_dump($this->url . $query_string);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        $data = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

        if (curl_error($ch)) die("Connection Error: ".curl_errno($ch).' - '.curl_error($ch));
        curl_close($ch);

        return json_encode($data);
    }



}
