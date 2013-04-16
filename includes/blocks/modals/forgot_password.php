 <!-- Forgotten Password Modal -->
<div class="modal forgot"  style="display:none;">
    <div class="modal_inner" id="forgot_password" >
         <div class="modal_header" class="cf">
            <h3>Password Request</h3>
            <img src="<?php echo $base_path; ?>/images/products/buttons/close-btn.png" class="close_modal" ng-click="close()">
        </div>
        <div id="forgot_password_content">
            <form name="password_form" id="password_form">
                <label for="username">User ID</label><span ng-show="password_form.username.$error.required" class="help-inline">Required</span>
                <input type="text" id="username" name="username" ng-model="username" required/>
                <br />
                <button class="login_btn" ng-click="passwordRequest($event)" ng-disabled="password_form.$invalid" >
                    <img src="<?php echo $base_path; ?>/images/products/buttons/retrieve-btn.png" />
                </button>
                <br>
                <p ng-show="password_reset">Please check your email for your password.</p>
                <p ng-show="bad_username">Unable to reset password. Please contact a representative.</p>
                <p ng-show="fatal">Something went wrong. Please contact a representative.</p>
            </form>
        </div>
    </div>
</div>
