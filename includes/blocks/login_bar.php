<div id="login_bar" class="cf">
    <div id="login_bar_center" >
        <ul ng-cloak class="ng-cloak">
            <li>
                <a href="cart.php" id="view_cart" ng-hide="basket.count() == 0 ">
                    <img src="<?php echo $base_path; ?>images/products/cart.png" >
                    <span id="cart_count" class="ng-cloak">View Cart ({{basket.count()}})</span>
                </a>
            </li>
            <li>
                <a href="cart.php" id="checkout" ng-hide="basket.count() == 0 ">Checkout</a>
            </li>
            <li>
                <a href="#" ng-hide="login_status" class="member_btn" ng-click="becomeMember($event)"></a>
            </li>
            <li>
                <a href="" ng-class="login_class" ng-click="auth($event,login_class)"></a>
            </li>
            <li style="margin-top:6px;">
                <a href="https://solle.orbsix.com/login.php" >Back Office</a>
            </li>
        </ul>
    </div>
</div>
