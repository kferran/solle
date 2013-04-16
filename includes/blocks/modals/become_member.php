     <!-- Become Member Modal-->
            <div class="modal become_member" ng-controller="MembershipCtrl" style="display:none;">
                <div class="modal_inner" id="become_member">
                    <div class="modal_header" class="cf">
                        <h3>Create an Account</h3>
                        <img src="<?php echo $base_path; ?>/images/products/buttons/close-btn.png" class="close_modal" ng-click="close()">
                    </div>
                   <div id="membership">
                        <p>In order to purchase products from Solle, you need to create an account by becoming either an Online Customer or a Member.</p>
                        <span>Click below to choose your option:</span>
                        <br>
                        <div class="left">
                            <div>
                                <img src="<?php echo $base_path; ?>/images/products/membership/member-leaf-blue.png" />
                                <h2>Online</h2>
                                <h1>Customer</h1>
                                <p>
                                    Solle Online Customers can purchase products for less than retail and products are shipped directly to their home or office.
                                </p>
                                <ul>
                                    <li>Buy Products at Reduced Rate</li>
                                </ul>
                                <a href="#" id="online_customer" class="link">Become a Online Customer</a>
                            </div>
                        </div>
                        <div id="or">
                            <h1>-Or-</h1>
                        </div>
                        <div class="right">
                            <div>
                                <img src="<?php echo $base_path; ?>/images/products/membership/member-leaf-green.png" />
                                <h1>Member</h1>
                                <p>
                                    Solle Members can buy product at wholesale prices and participate in SolleRewards, the Solle Natural products savings program. Any product purchase greater than $50 automatically qualifies you for Member status with absolutely no obligation to buy more.
                                </p>
                                <ul>
                                    <li>Buy Products at Wholesale</li>
                                    <li>Any $50 purchase qualifies you as a Member</li>
                                    <li><a href="/comp_plan.php">Participate in SolleRewards</a></li>
                                </ul>
                                <a href="#" id="member_register" ng-click="memberRegister($event)" class="link">Become a Member</a> <span>|</span> <a href="/comp_plan.php" class="link">Learn More</a>
                            </div>
                        </div>
                   </div>
                </div>
            </div>
