<?php
  session_start();
  require '../classes/orbsix.php';
  require '../classes/cart.php';
  require '../classes/user.php';
  require '../classes/functions.php';

  $orbsix = new Orbsix;
  $user = new User();
  $cart = new Cart();

  switch ($_GET['action']) {

      case 'get_products':
        $user_type = $user->getUserType();
        if ( $user_type['name'] == 'Member' || $user_type['id'] >= 10)
          echo $orbsix->get_products(true);
        else
          echo $orbsix->get_products(false);
      break;

      case 'login':
        $params = json_decode(file_get_contents("php://input"), true);
        $response = $orbsix->authenticate_user($params);
        $decoded_response = json_decode($response, true);

        if ( $decoded_response['Result']['authenticateSuccess'] == 'true' ) //login was good get user info
        {
          $user = json_decode( $orbsix->get_user($params['user']['username']), true); // user
          $userTypes = json_decode( $orbsix->get_user_types(),true);
          $type = array();
          foreach ($userTypes['Result'] as $key => $value)
          {
            if ( $user['Result']['userType'] == $value['id'] )
            {
              $type = $value;
            }
          }
          // update cart to pricing for logged in user
          if ( ($type['name'] == 'Member' || $type['id'] >= 10) && count($_SESSION['cart']) > 0)
          {
            $cart->update_cart_pricing(json_decode($orbsix->get_products(true),true));
          }
          // set variables in session for authentication
          $_SESSION['authenticated'] = array('status' => 'true', 'username'=> $params['user']['username'], 'usertype' => $type, 'user' => $user['Result']);
        }
        else
        {
          $_SESSION['authenticated'] = array('status' => 'false');
        }
        echo $response;
      break;

      case 'password_request':
        $params = json_decode(file_get_contents("php://input"), true);
        echo $orbsix->password_request($params);
      break;

      case 'check_username' :
        $params = json_decode(file_get_contents("php://input"), true);
        echo $orbsix->check_username($params);
      break;

      case 'create_customer':
        $params = json_decode(file_get_contents("php://input"), true);
        echo $orbsix->create_customer($params);
      break;

      case 'update_customer':
        $params = json_decode(file_get_contents("php://input"), true);
        echo $orbsix->update_user($params);
      break;

      case 'get_mentor':
        $params = json_decode(file_get_contents("php://input"), true);
        echo $orbsix->get_mentor($params);
      break;

      case 'get_user':
        $params = json_decode(file_get_contents("php://input"), true);
        echo $orbsix->get_user($params);
      break;

      case 'get_username':
        echo json_encode($user->getUserName());
      break;

      case 'get_countries':
        echo $orbsix->get_countries();
      break;

      case 'set_user_relationship':
        $params = json_decode(file_get_contents("php://input"), true);
        echo $orbsix->set_user_relationship($params);
      break;

      case 'check_authorized':
        echo json_encode($user->checkAuthenticated());
      break;

      case 'get_order' :
        $order_number = $cart->getOrderNumber();
        if ( $order_number )
        {
          echo $orbsix->get_order($order_number);
        }
        else
        {
          echo json_encode(false);
        }
      break;

      case 'submit_payment' :
        $params = json_decode(file_get_contents("php://input"), true);
        echo $orbsix->submit_payment($params);
      break;

      case 'create_order':
        $user = json_decode(file_get_contents("php://input"), true);
        echo $cart->create_order($user);
      break;

      case 'update_cart' :
        $params = json_decode(file_get_contents("php://input"), true);
        echo $cart->update_cart($params);
      break;

      case 'clear_cart':
        echo $cart->clear_cart();
      break;

      case 'process_gift_card':
        $params = json_decode(file_get_contents("php://input"), true);
        echo $orbsix->process_gift_card($params);
      break;

      case 'calculate_totals':
        $params = json_decode(file_get_contents("php://input"), true);
        echo $orbsix->create_or_update_order($params);
      break;

      case 'complete_order':
        $params = json_decode(file_get_contents("php://input"), true);
        $order_number = $cart->getOrderNumber();
        $params['order'] = $order_number;

        $valid_cc = luhn_mod10($params['payment']['credit_card_number']); // make sure cc is valid

        if ( ! $valid_cc ){
          echo json_encode(array('error' => 'Invalid credit card number. Please check your card and try again.'));
          return;
        }

        if ( ! is_null($order_number) )
        {
          $update_response = json_decode( $orbsix->update_order($order_number, $params), true );
          if ( isset($update_response['Result']['updateSuccess']) && $update_response['Result']['updateSuccess'] == true )
          {
            $payment_response = json_decode($orbsix->process_payment($params),true);
            // var_dump($payment_response);
            if ( isset($payment_response['Result']['createSuccess']) && $payment_response['Result']['createSuccess'] == true )
            {
              echo json_encode(array('success' => 'Successfully posted payment.'));
            }
            else
            {
              // echo json_encode(array('success' => true));
              echo json_encode(array('error' => 'Unable to process payment to complete the order.'));
            }
          }
          else
          {
            echo json_encode(array('error'=> 'Something went wrong while updating your order. Please try again.'));
          }
        }
        else
        {
          echo json_encode(array('error'=> 'Unable to find order.'));
        }
      break;

      case 'get_states':
        echo $orbsix->get_states();
      break;

      case 'shipping_types':
        echo $orbsix->shipping_types();
      break;

      case 'add_to_cart':
        $params = json_decode(file_get_contents("php://input"), true);
        echo $cart->add_item($params);
      break;

      case 'get_cart' :
        echo json_encode( $cart->get_cart() );
      break;

      case 'remove_from_cart':
        $params = json_decode(file_get_contents("php://input"), true);
        $cart->remove_from_cart($params);
        $order_id = $cart->getOrderNumber();

        if ( $order_id )
        {
          $params['order'] = $order_id;
          $orbsix->remove_product($params);
        }
        
        
      break;

      case 'logout':
        unset($_SESSION['authenticated']);
        unset($_SESSION['orderid']);
        echo json_encode(array('status' => 'false')); // login status false
      break;
  }
