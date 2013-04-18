<?php include 'includes/blocks/header.php'; ?>
    <!-- Add any other styles here -->
    </head>
    <body ng-controller="AppCtrl">
        <?php include 'includes/blocks/login_bar.php'; ?>
        <?php include 'includes/blocks/nav.php'; ?>
        <div id="content">
            <?php include 'includes/blocks/breadcrumbs.php'; ?>
            <div class="columns">
                <div class="left">
                   <?php include 'includes/blocks/categories.php'; ?>
                </div>  
                <div class="right products" ng-controller="ProductsCtrl" ng-cloak class="ng-cloak">
                    <div class="product balancing"  ng-repeat="product in products.Balancing" ng-bind-html="product.description">
                        <div class="roll" ng-class="status"></div>
                        <img ng-src="{{product.thumbnailImage}}" >
                        <h4>{{ product.name }} <small>&trade;</small></h4>
                        <p>
                            {{product.description | noHTML | truncate:200}}
                            <a href="{{product.link}}">Learn More</a>
                        </p>
                        <ul>
                            <li ng-controller="MembershipCtrl">
                                <span>Price:</span><small>{{product.retailPrice | currency}}</small>
                                <a href="javascript:void(0);" ng-hide="loginService.getUserType().name == 'Member' || loginService.getUserType().id >= 10" ng-click="becomeMember($event)">You could save 15%!</a>
                            </li>
                            <li>
                                <span>Quantity:</span>
                                <select name="quantity" ng-model="product.product_quantity" ng-options="n for n in quantity"></select>
                                <a class="btn" ng-click="addItem($event,product); status='active'">
                                    <img ng-src="<?php echo $base_path; ?>/images/products/buttons/add-btn.png" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="/store/cart.php">Checkout</a>
                            </li>
                        </ul>
                        </form>
                    </div>
                    <div class="product lifting"  ng-repeat="product in products.Lifting" ng-bind-html="product.description" >
                        <div class="roll" ng-class="status"></div>
                        <img ng-src="{{product.thumbnailImage}}" >
                        <h4>{{ product.name }} <small>&#0153;</small></h4>
                        <p>
                            {{product.description | noHTML | truncate:200}}
                            <a href="{{product.link}}">Learn More</a>
                        </p>
                        <ul>
                            <li ng-controller="MembershipCtrl">
                                <span>Price:</span><small>${{product.retailPrice}}</small>
                                <a href="javascript:void(0);" ng-hide="loginService.getUserType().name == 'Member' || loginService.getUserType().id >= 10" ng-click="becomeMember($event)">You could save 15%!</a>
                            </li>
                             <li>
                                <span>Quantity:</span>
                                <select name="quantity" ng-model="product.product_quantity" ng-options="n for n in quantity"></select>
                                <a class="btn" ng-click="addItem($event,product); status='active'">
                                    <img ng-src="<?php echo $base_path; ?>/images/products/buttons/add-btn.png" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="/store/cart.php">Checkout</a>
                            </li>
                        </ul>
                        </form>
                    </div>
                    <div class="product calming"  ng-repeat="product in products.Calming" ng-bind-html="product.description" >
                        <div class="roll" ng-class="status"></div>
                        <img ng-src="{{product.thumbnailImage}}" >
                        <h4>{{ product.name }} <small>&#0153;</small></h4>
                        <p>
                            {{product.description | noHTML | truncate:200}}
                            <a href="{{product.link}}">Learn More</a>
                        </p>
                        <ul>
                            <li ng-controller="MembershipCtrl">
                                <span>Price:</span><small>${{product.retailPrice}}</small>
                                <a href="javascript:void(0);" ng-hide="loginService.getUserType().name == 'Member' || loginService.getUserType().id >= 10" ng-click="becomeMember($event)">You could save 15%!</a>
                            </li>
                            <li>
                                <span>Quantity:</span>
                                <select name="quantity" ng-model="product.product_quantity" ng-options="n for n in quantity"></select>
                                <a class="btn" ng-click="addItem($event,product); status='active'">
                                    <img ng-src="<?php echo $base_path; ?>/images/products/buttons/add-btn.png" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="/store/cart.php">Checkout</a>
                            </li>
                        </ul>
                        </form>
                    </div>
                    <div class="product clarifying"  ng-repeat="product in products.Clarifying" ng-bind-html="product.description">
                        <div class="roll" ng-class="status" ></div>
                        <img ng-src="{{product.thumbnailImage}}" >
                        <h4>{{ product.name }} <small>&#0153;</small></h4>
                        <p>
                            {{product.description | noHTML | truncate:200}}
                            <a href="{{product.link}}">Learn More</a>
                        </p>
                        <ul>
                            <li ng-controller="MembershipCtrl">
                                <span>Price:</span><small>${{product.retailPrice}}</small>
                                <a href="javascript:void(0);" ng-hide="loginService.getUserType().name == 'Member' || loginService.getUserType().id >= 10" ng-click="becomeMember($event)">You could save 15%!</a>
                            </li>
                             <li>
                                <span>Quantity:</span>
                                <select name="quantity" ng-model="product.product_quantity" ng-options="n for n in quantity"></select>
                                <a class="btn" ng-click="addItem($event,product); status='active'">
                                    <img ng-src="<?php echo $base_path; ?>/images/products/buttons/add-btn.png" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="/store/cart.php">Checkout</a>
                            </li>
                        </ul>
                        </form>
                    </div>
                </div>
            </div>

        <?php include 'includes/blocks/footer.php'; ?>
