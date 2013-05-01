<div id="login_bar" class="cf">
    <div id="login_bar_center" >
        <ul ng-cloak class="ng-cloak">
            <li ng-controller="CartCtrl">
                <a href="cart.php" id="view_cart" ng-hide="basket.count() == 0 ">
                    <img src="<?php echo $base_path; ?>images/products/cart.png" >
                    <span id="cart_count" class="ng-cloak">View Cart ({{basket.count()}})</span>
                </a>
            </li>
            <li ng-controller="CartCtrl">
                <a href="cart.php" id="checkout" ng-hide="basket.count() == 0 ">Checkout</a>
            </li>
            <li ng-controller="MembershipCtrl">
                <a href="javascript:void(0);" ng-hide="loginService.is_logged_in()" class="member_btn" ng-click="becomeMember($event)"></a>
            </li>
            <li ng-controller="AppCtrl">
                <div ng-switch on="loginService.is_logged_in()">
                    <div ng-switch-when="true">
                        <span class="logout" ng-click="auth($event, true)"></span>
                    </div>
                    <div ng-switch-when="false">
                        <span class="login" ng-click="auth($event, false)"></span>
                    </div>
                </div>
                <!-- <span ng-class="{logout: loginService.is_logged_in() === true, login: loginService.is_logged_in() === false}" 
                ng-click="auth($event, loginService.is_logged_in())"></span> -->
            </li>
            <li style="margin-top:6px;">
                <a href="https://solle.orbsix.com/login.php" >Back Office</a>
            </li>
        </ul>
    </div>
    <div id="header_top"></div>
</div>
