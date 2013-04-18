<?php include 'includes/blocks/header.php'; ?>
   <!-- Add any other styles here -->
    </head>
    <body ng-controller="AppCtrl">

        <?php include 'includes/blocks/login_bar.php'; ?>
        <?php include 'includes/blocks/nav.php'; ?>
        <div id="content" ng-cloak>
            <div class="columns" ng-controller="CartCtrl" >
                <div ng-cloak>
                    <div ng-controller="MembershipCtrl" class="create_account" ng-hide="loginService.is_logged_in()" ng-cloak>
                        <img src="<?php echo $base_path; ?>/images/products/cart/create-account.png" style="float:left;"/>
                        <h1>In order to purchase Solle Naturals products you first must create an account and login!</h1>
                        <p>
                            Also, did you know you could be saving 15% on this order by becoming a Member and that any purchase of $50 or more automatically qualifies you for Member status?
                        </p>
                        <a href="javascript:void(0);" class="links member" ng-click="becomeMember($event)">Create an Account</a> |
                        <a href="javascript:void(0);" class="links" id="member_register" ng-click="becomeMember($event)">Become a Member</a>
                        <!-- <a href="javascript:void(0);" ng-click="auth($event,'login')">Login</a> -->
                    </div>
                    <div ng-controller="MembershipCtrl" class="create_account"
                        ng-show="loginService.is_logged_in() && loginService.getUserType().name != 'Member'" ng-cloak>
                        <img src="<?php echo $base_path; ?>/images/products/membership/upgrade.png" style="float:left;"/>
                        <h1>Did you know...</h1>
                        <p>
                            Did you know that you could be saving 15% on this order by becoming a Solle Member? <br />
                            Plus, membership is INCLUDED when you spend more than $50!
                        </p>
                        <a href="javascript:void(0);" class="links member" ng-click="becomeMember($event)" >Upgrade Today</a> |
                        <a href="/comp_plan.php" class="links" id="member_register">Learn More</a>
                    </div>

                    <div style="clear:both; margin-bottom:30px;"></div>
                    <div id="cart" >
                        <div id="cart_header">
                            <h1>Cart</h1>
                            <img src="<?php echo $base_path; ?>/images/products/cart-receipt.png" id="cart_image">
                        </div>
                        <table >
                            <thead>
                                <tr>
                                    <th>Remove</th>
                                    <th>Item</th>
                                    <th>Unit Price</th>
                                    <th>QTY</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="product in items" class="ng-cloak" >
                                    <td><img src="<?php echo $base_path; ?>/images/products/buttons/delete.png" ng-click="remove($event,product)" remove-with-fade-in-directive/></td>
                                    <td>{{product.name}}</td>
                                    <td>{{product.retailPrice}}</td>
                                    <td><input type="text" class="product_quantity" ng-model="product.product_quantity"></td>
                                    <td>{{ product.product_quantity * product.retailPrice | currency}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="cart_footer">
                            <ul>
                                <li><span>Subtotal</span> {{total() | currency}}</li>
                            </ul>
                            <button ng-click="UpdateCart($event, items)" 
                                ng-class="{overlay: loginService.is_logged_in() == false}"
                                ng-disabled="loginService.is_logged_in() == false" >Update Cart</button>
                        </div>
                    </div>
                    <div id="billing" equal="heights" 
                        ng-class="{overlay: loginService.is_logged_in() == false}">
                        <div class="details shipping">
                            <h2>Shipping Details</h2>
                            <form name="details_form">
                                <fieldset>
                                    <div>
                                        <span id="required">* - required</span>
                                    </div>
                                    <div>
                                        <label for="shipping_name">Shipping Name
                                            <span ng-show="details_form.shippingName.$error.required" class="help-inline">*</span>
                                        </label>
                                        <input type="text"
                                            id="shippingName"
                                            name="shippingName"
                                            ng-model="details.shippingName"
                                            ng-disabled="loginService.is_logged_in() == false" required/>
                                    </div>

                                    <div>
                                        <label for="shippingAddressLine1">Address Line 1
                                            <span ng-show="details_form.shippingAddressLine1.$error.required" class="help-inline">*</span>
                                        </label>
                                        <input type="text"
                                            id="shippingAddressLine1"
                                            name="shippingAddressLine1"
                                            ng-model="details.shippingAddressLine1"
                                            ng-disabled="loginService.is_logged_in() == false" required />
                                    </div>
                                    <div>
                                        <label for="shippingAddressLine2">Address Line 2</label>
                                        <input type="text"
                                            id="shippingAddressLine2"
                                            name="shippingAddressLine2"
                                            ng-model="details.shippingAddressLine2"
                                            ng-disabled="loginService.is_logged_in() == false">
                                    </div>

                                    <div>
                                        <label for="shippingCity" class="city">City
                                            <span ng-show="details_form.shippingCity.$error.required" class="help-inline">*</span>
                                        </label>
                                        <input type="text"
                                            name="shippingCity"
                                            id="shippingCity"
                                            ng-model="details.shippingCity"
                                            ng-disabled="loginService.is_logged_in() == false" required>
                                    </div>
                                    <div>
                                        <label for="shippingState">State
                                            <span ng-show="details_form.shippingState.$error.required" class="help-inline">*</span>
                                        </label>
                                        <select name="shippingState"
                                            id="shippingState"
                                            ng-model="details.shippingState"
                                            ng-options="s.name for s in states"
                                            ng-disabled="loginService.is_logged_in() == false" required>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="shippingPostalCode">Postal Code
                                            <span ng-show="details_form.shippingPostalCode.$error.required" class="help-inline">*</span>
                                        </label>
                                        <input type="text"
                                            name="shippingPostalCode"
                                            ng-model="details.shippingPostalCode"
                                            ng-disabled="loginService.is_logged_in() == false" required>
                                    </div>
                                    <div>
                                        <label for="shipping_name">Country
                                            <span ng-show="details_form.countries.$error.required" class="help-inline">*</span>
                                        </label>
                                        <select name="countries"
                                            id="countries"
                                            ng-model="details.shippingCountry"
                                            ng-options="c.name for c in countries"
                                            ng-disabled="loginService.is_logged_in() == false" required></select>
                                    </div>
                                    <div>
                                        <label for="shippingPhoneNumber">Shipping Phone #</label>
                                        <input type="text"
                                            id="shippingPhoneNumber"
                                            name="shippingPhoneNumber"
                                            ng-model="details.shippingPhoneNumber"
                                            ng-disabled="loginService.is_logged_in() == false">
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="details method">
                            <h2>Shipping Method</h2>
                            <form name="shipping_method">
                                <fieldset>
                                    <ul>
                                        <li>
                                            <input type="radio"
                                                    name="shippingType"
                                                    ng-model="details.shippingType"
                                                    value="5"
                                                    required
                                                    ng-disabled="loginService.is_logged_in() == false">
                                        </li>
                                        <li><label for="usps">US Postal ($10.00)</label></li>
                                        <li>
                                            <input type="radio"
                                                    name="shippingType"
                                                    ng-model="details.shippingType"
                                                    value="4"
                                                    required
                                                    ng-disabled="loginService.is_logged_in() == false">
                                        </li>
                                        <li><label for="ups">UPS Ground ($10.00)</label></li>
                                    </ul>
                                </fieldset>
                            </form>
                        </div>
                        <div class="details payment">
                            <h2>Payment Method</h2>
                            <form name="payment_method">
                                <fieldset>
                                    <div><span id="required">* - required</span></div>
                                    <div>
                                        <label for="credit_card_number">Card Number
                                            <span ng-show="payment_method.credit_card.$error.required" class="help-inline">*</span>
                                        </label>
                                        <input type="text"
                                            name="credit_card"
                                            ng-model="payment.credit_card_number"
                                            required
                                            ng-disabled="loginService.is_logged_in() == false">
                                    </div>
                                    <div>
                                        <label for="shipping_name">CVV2
                                            <span ng-show="payment_method.cvv.$error.required" class="help-inline">*</span>
                                        </label>
                                        <input type="text"
                                            name="cvv"
                                            ng-model="payment.cvv"
                                            required
                                            id="cvv"
                                            ng-disabled="loginService.is_logged_in() == false">
                                    </div>
                                    <div>
                                        <label for="exp_month">Expiration Month
                                            <span ng-show="payment_method.exp_month.$error.required" class="help-inline">*</span>
                                        </label>
                                        <select name="exp_month" id="exp_month" ng-model="payment.exp_month" required ng-disabled="loginService.is_logged_in() == false">
                                            <option value="01">Jan</option>
                                            <option value="02">Feb</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">Sept</option>
                                            <option value="10">Oct</option>
                                            <option value="11">Nov</option>
                                            <option value="12">Dec</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="exp_year">Expiration Year
                                            <span ng-show="payment_method.exp_year.$error.required" class="help-inline">*</span>
                                        </label>
                                        <select name="exp_year" id="exp_year" ng-model="payment.exp_year" required ng-disabled="loginService.is_logged_in() == false" >
                                            <?php
                                                $yearRange = 10;
                                                $thisYear = date('y');
                                                $startYear = ($thisYear + $yearRange);
                                                foreach (range($thisYear, $startYear) as $year): ?>
                                                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="gift_cart_form">
                                        <label for="gift_cart">Gift Card</label>
                                        <input type="text" name="gift_cart" id="gift_cart" ng-model="gift_card_number" 
                                        ng-class="{overlay: loginService.is_logged_in() == false}"
                                        ng-disabled="loginService.is_logged_in() == false" />
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div id="calculate">
                        <button ng-click="CalculateTotals($event,gift_card_number,details)"
                            ng-class="{overlay: loginService.is_logged_in() == false}"
                            ng-disabled="details_form.$invalid || shipping_method.$invalid || payment_method.$invalid"
                            ng-hide="finalize"
                            id="calculate">
                            Calculate Total
                        </button>
                    </div>
                    <div class="totals" ng-show="finalize">
                        <ul >
                            <li>{{order.total - order.shipping - order.tax - discount()| currency}} (USD)</li>
                            <li>{{order.shipping | currency}} (USD)</li>
                            <li>{{order.tax | currency}} (USD)</li>
                            <li>{{discount() | currency}} (USD)</li>
                            <li class="total">{{order.total | currency}} (USD)</li>
                        </ul>
                        <ul class="labels">
                            <li>Subtotal</li>
                            <li>Shipping</li>
                            <li>Tax</li>
                            <li>Discount</li>
                            <li class="total">Totals</li>
                        </ul>
                        <br>
                        <button id="complete_order"
                            ng-click="CompleteOrder($event, details, order.total, payment)"
                            ng-disabled="details_form.$invalid || shipping_method.$invalid || payment_method.$invalid"
                            ng-class="{overlay: loginService.is_logged_in() == false}">
                            <img src="<?php echo $base_path; ?>images/products/buttons/complete_order.png" />
                        </button>
                    </div>
                </div>
            </div>
        <?php include 'includes/blocks/footer.php'; ?>
