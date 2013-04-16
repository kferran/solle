<div class="modal customer" ng-controller="MembershipCtrl" style="display:none;">
    <div class="modal_inner" id="customer_inner">
        <div class="modal_header" class="cf">
            <h3>Create an Account</h3>
            <img src="<?php echo $base_path; ?>/images/products/buttons/close-btn.png" class="close_modal" ng-click="close()">
        </div>
       <div id="online">
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
                </div>
            </div>
            <div class="form">
                <form name="mentorForm" ng-show="step1" id="step1">
                    <div>
                        <span id="required">* - required</span>
                    </div>
                    <div>
                        <label for="countries">Country<span ng-show="mentorForm.countries.$error.required" class="help-inline">*</span></label>
                        <select name="countries" id="countries" ng-model="country" ng-options="c.name for c in countries" required></select>
                    </div>
                    <p>
                        If you know the name or ID of the person who introduced you to SolleNaturals, enter their name below. If not, click "Next".
                    </p>
               <!--      <div>
                        <label for="mentor_id">Mentor ID</label>
                        <input type="text" name="mentor_id" id="mentor_id" ng-model="mentor_id">
                    </div> -->
                    <div>
                        <label for="mentor_name">Mentor ID</label>
                        <input type="text" name="mentor_name" id="mentor_name" ng-model="mentor_name">
                    </div>

                    <button ng-click="mentorSubmit($event)" ng-disabled="mentorForm.$invalid">Next</button>
                </form>
                <div class="form">
                     <form name="customerForm" ng-show="step2" id="step2">
                        <div>
                            <span id="required">* - required</span>
                        </div>
                        <div>
                            <label for="firstName">First Name <span ng-show="customerForm.firstName.$error.required" class="help-inline">*</span></label>
                            <input type="text" name="firstName" id="firstName" ng-model="firstName" required>
                        </div>
                        <div>
                            <label for="lastName">Last Name <span ng-show="customerForm.lastName.$error.required" class="help-inline">*</span></label>
                            <input type="text" name="lastName" id="lastName" ng-model="lastName" required>
                        </div>
                        <!-- <div>
                            <label for="username">User Name <span ng-show="customerForm.username.$error.required" class="help-inline">*</span></label>
                            <input type="text" name="username" id="username" ng-model="username" required>
                        </div> -->
                        <div>
                            <label for="email">Email <span ng-show="customerForm.email.$error.required" class="help-inline">*</span></label>
                            <input type="text" name="email" id="email" ng-model="email" required>
                        </div>
                        <div>
                            <label for="email_confirm" >Email Confirm<span ng-show="customerForm.email_confirm.$error.required" class="help-inline">*</span></label>
                            <input type="text" name="email_confirm" id="email_confirm" ng-model="email_confirm" required>
                        </div>
                        <div>
                            <label for="homePhone">Home Phone <span ng-show="customerForm.homePhone.$error.required" class="help-inline">*</span></label>
                            <input type="text" name="homePhone" id="homePhone" ng-model="homePhone" required>
                        </div>
                        <div>
                            <label for="cellPhone">Cell Phone</label>
                            <input type="text" name="cellPhone" id="cellPhone" ng-model="cellPhone">
                            </div>
                        <div>
                            <label for="password">Password <span ng-show="customerForm.password.$error.required" class="help-inline">*</span></label>
                            <input type="text" name="password" id="password" ng-model="password" required>
                        </div>
                        <div>
                            <label for="password_confirm">Confirm Password <span ng-show="customerForm.password_confirm.$error.required" class="help-inline">*</span></label>
                            <input type="text" name="password_confirm" id="password_confirm" ng-model="password_confirm" required>
                        </div>

                        <span ng-show="username_exists" id="username_exists">Username already exists. Please login.</span>
                        <button ng-click="register($event,3)" ng-hide="username_exists" ng-disabled="customerForm.$invalid">Next</button>
                        <!-- pass in 3 for online customer type-->
                    </form>
                </div>
            </div>
       </div>
    </div>
</div>
