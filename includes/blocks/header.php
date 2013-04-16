<?php
    session_start();
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

    include dirname(__FILE__) .'/../classes/functions.php';
    include dirname(__FILE__) .'/../classes/cart.php';
    include dirname(__FILE__) .'/../classes/orbsix.php';
    include dirname(__FILE__) .'/../classes/user.php';
    $user = new User();
    $authenticated = $user->checkAuthenticated();
    $userType = $user->getUserType();
?>
<!doctype html>
<html id="ng-app" ng-app="SolleApp">
    <head>
        <title>Solle Naturals</title>
        <?php include 'page_start.php'; ?>


