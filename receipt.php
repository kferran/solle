<?php include 'includes/blocks/header.php'; ?>
   <!-- Add any other styles here -->
    </head>
    <body ng-controller="AppCtrl">
        <?php include 'includes/blocks/login_bar.php'; ?>
        <?php include 'includes/blocks/nav.php'; ?>
    <div id="content">
    <div class="columns" ng-controller="ReceiptCtrl" class="ng-cloak" ng-cloak>
        <div id="cart">
            <div style="clear:both; margin-bottom:30px;"></div>
            <div id="cart_header">
                <h1>Order Receipt</h1>
                <img src="<?php echo $base_path; ?>/images/products/cart-receipt.png" id="cart_image">
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Unit Price</th>
                        <th>QTY</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="product in items" >
                        <td>{{product.name}}</td>
                        <td>{{product.retailPrice}}</td>
                        <td>{{ product.product_quantity }}</td>
                        <td>{{ product.product_quantity * product.retailPrice | currency}}</td>
                    </tr>
                </tbody>
            </table>
            <div class="receipt_footer" >
                <div class="column">
                    <h3>Shipping Details</h3>
                    <ul>
                        <li>{{ order.shippingName }}</li>
                        <li>{{ order.shippingAddressLine1 }}</li>
                        <li>{{ order.shippingCity}}, {{ state() }} {{ order.shippingPostalCode }}</li>
                        <li>{{ country() }}</li>
                    </ul>
                </div>
                <div class="column">
                    <h3>Shipping Method</h3>
                    <span>{{ shippingType() }}</span>
                    <h3>Payment Method</h3>
                    <span>Credit Card:  ***********{{order.payments.account.substr(11,4)}}</span>
                </div>
                <div class="column column_right">
                    <h3>Totals</h3>
                    <ul class="labels">
                        <li>Subtotal</li>
                        <li>Shipping</li>
                        <li>Discount</li>
                        <li>Tax</li>
                        <li class="total">Totals</li>
                    </ul>
                    <ul>
                        <li>{{ total() | currency }} (USD)</li>
                        <li>{{ order.shipping | currency }} (USD)</li>
                        <li>{{ discount() | currency }} (USD)</li>
                        <li>{{order.tax | currency }} (USD)</li>
                        <li class="total">{{ order.total | currency }} (USD)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php include '../contact_modal.php'; ?>
<?php include 'includes/blocks/footer.php'; ?>
