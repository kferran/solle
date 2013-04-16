<?php
class Orbsix{
    private $url = 'https://sollestaging.orbsix.com/rest/v3/index.php?transactionkey=Eqb41V5mEpgVUm6d58QjPjj5WrlRoziv';
    // private $url = 'https://solle.orbsix.com/rest/v3/index.php?transactionkey=0a85fn7zSLoY0rXo4VifJ4bo89P2W317JJ8FiDW2Qzwv1mvko9kk7CFd4ch0zK60';
    // private $key = 'Eqb41V5mEpgVUm6d58QjPjj5WrlRoziv';
    private $postfields = array(); // fields for API
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

        $this->postfields['type'] = 'GetProducts';
        foreach ($order_types as $id => $category)
        {
            $this->postfields['ordertype'] = $id;
            $response = json_decode($this->curl_request($this->postfields), true);
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
        $this->postfields['type'] = 'GetUserTypes';
        return $this->curl_request($this->postfields);
    }

    public function get_gift_card_value($params)
    {
        $this->postfields['type'] = 'GetGiftCardValue';
        $this->postfields['giftCardCode'] = $params;
        return json_decode($this->curl_request($this->postfields),true);
    }

    public function use_gift_card($params)
    {
        $this->postfields['type'] = 'UseGiftCard';
        $this->postfields['giftCardCode'] = $params['card_number'];
        $this->postfields['order'] = $params['order'];

        return json_decode($this->curl_request($this->postfields),true);
    }

    public function process_gift_card($params)
    {
        $gift_card_value = $this->get_gift_card_value($params);

        if ( $gift_card_value['Result']['value'] != 'false' )
        {
            $this->cart = new Cart();
            $ordernumber = $this->cart->getOrderNumber();
            if ( !is_null($ordernumber) )
            {

                //update order with gift card
                $update_order_response = json_decode($this->update_order($ordernumber, array('details' => array('giftCardCode' => $params))),true);
                // mark gift card as used
                if ( isset($update_order_response['Result']) && $update_order_response['Result']['updateSuccess'] == true )
                {
                    $used_card_response = $this->use_gift_card(array('card_number' => $params, 'order' => $ordernumber));
                    return $used_card_response['Result']['useSuccess'];
                }
            }
            else
            {
                return json_encode(array('error' => 'Unable to find order to update discount.'));
            }
        }
        else
        {
            return json_encode(array('error' => 'Unable to find gift card. Please check your card and try again.'));
        }
    }

    public function update_order($ordernumber, $params)
    {
        $this->postfields = array();
        $this->postfields['type'] = 'UpdateOrder';

        foreach ($params['details'] as $key => $value)
        {
            if ( $key == 'shippingState' ){
                $this->postfields['shippingState'] = $value['id'];
                continue;
            }

            if ( $key == 'shippingCountry' ){
                $this->postfields['shippingCountry'] = $value['id'];
                continue;
            }

            $this->postfields[$key] = $value;
        }
        $this->postfields['order'] = $ordernumber;
        return $this->curl_request($this->postfields);
    }

    public function process_payment($params)
    {
        $this->postfields = array();

        $this->postfields['type'] = 'CreateOrderPayment';
        $this->postfields['processPayment'] = true;
        $this->postfields['billingAddressLine1'] = $params['details']['shippingAddressLine1'];
        $this->postfields['billingCity'] = $params['details']['shippingCity'];
        $this->postfields['billingState'] = $params['details']['shippingState']['id'];
        $this->postfields['billingPostalCode'] = $params['details']['shippingPostalCode'];
        $this->postfields['billingCountry'] = $params['details']['shippingCountry']['id'];
        $this->postfields['order'] = $params['order'];
        $this->postfields['amount'] = $params['total'];
        $this->postfields['account'] = $params['payment']['credit_card_number'];
        $this->postfields['month'] = $params['payment']['exp_month'];
        $this->postfields['year'] = $params['payment']['exp_year'];
        $this->postfields['cvv'] = $params['payment']['cvv'];

        return $this->curl_request($this->postfields);
    }

    public function get_states()
    {
        $this->postfields['type'] = 'GetStates';
        return $this->curl_request($this->postfields);
    }

    public function shipping_types()
    {
        $this->postfields['type'] = 'GetShippingTypes';
        return $this->curl_request($this->postfields);
    }

    public function authenticate_user($params)
    {
        $this->postfields['type'] = 'AuthenticateUser';
        $this->postfields['username'] = $params['user']['username'];
        $this->postfields['password'] = $params['user']['password'];
        return $this->curl_request($this->postfields);
    }

    public function password_request($params) // call update user to reset password
    {
        $this->postfields['type'] = 'UpdateUser';
        $this->postfields['username'] = $params['user']['username'];
        $this->postfields['password'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
        return $this->curl_request($this->postfields);
    }

    public function create_customer($params)
    {
        $this->postfields['type'] = 'CreateUser';
        foreach ($params['customer'] as $key => $value)
            $this->postfields[$key] = $value;
        return $this->curl_request($this->postfields);
    }

    public function update_user($params)
    {
        $this->postfields['type'] = 'UpdateUser';
        foreach ($params['customer'] as $key => $value)
            $this->postfields[$key] = $value;
        $this->postfields['username'] = $params['username'];
        return $this->curl_request($this->postfields);
    }

    public function get_countries()
    {
        $this->postfields['type'] = 'GetCountries';
        return $this->curl_request($this->postfields);
    }

    public function get_user($params)
    {
        $this->postfields['type'] = 'GetUser';
        return $this->curl_request($this->postfields);
    }

    public function set_user_relationship($params)
    {
        foreach($this->relationshipTypes as $type ){
            if ( $type == 1 )
                $this->postfields['parentUsername'] = 1;
            else
                $this->postfields['parentUsername'] = $params['parentUsername'];

            $this->postfields['type'] = 'SetUserRelationship';
            $this->postfields['username'] = $params['username'];
            $this->postfields['relationshipType'] = $type;
            $this->curl_request($this->postfields);
        }

        // return $this->curl_request($this->postfields);
    }

    private function set_smart_plan($params){

    }
    public function check_username($params)
    {
        $this->postfields['type'] = 'UsernameAvailable';
        $this->postfields['username'] = $params['username'];
        return $this->curl_request($this->postfields);
    }

    public function create_order($username) // use this to create placeholder for order
    {
        $this->postfields['type'] = 'CreateOrder';

        $this->postfields['shippingName'] = 'NewOrder';
        $this->postfields['username'] = $username;
        return $this->curl_request($this->postfields);
    }

    public function set_order_product($params)
    {
        $this->postfields['type'] = 'SetOrderProduct';
        $this->postfields['order'] = $params['order'];
        $this->postfields['product'] = $params['product'];
        $this->postfields['quantity'] = $params['quantity'];
        return $this->curl_request($this->postfields);
    }

    public function get_order($order_id)
    {
        $this->postfields['type'] = 'GetOrder';
        $this->postfields['order'] = $order_id;
        return $this->curl_request($this->postfields);
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
