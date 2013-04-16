  <!-- Login Modal -->
            <div class="modal sign_in" style="display:none;">
                <div class="modal_inner " id="login_modal" >
                    <div class="modal_header" class="cf">
                        <h3>Login</h3>
                        <img src="<?php echo $base_path; ?>images/products/buttons/close-btn.png" class="close_modal" ng-click="close()">
                    </div>
                    <div id="login_modal_content">
                        <form name="loginForm"  id="loginForm">
                            <fieldset>
                                <label for="username">User ID </label><span ng-show="loginForm.username.$error.required" class="help-inline">Required</span>
                                <input type="text" id="username" name="username" ng-model="username" required/>

                                <label for="password">Password </label><span ng-show="loginForm.password.$error.required" class="help-inline">Required</span>
                                <input type="password" id="password" name="password" ng-model="password" required/>
                                <br />
                                <button type="submit" class="login_btn" ng-click="login()" ng-disabled="loginForm.$invalid" ng-class="{loginForm.$invalid: overlay}">
                                    <img src="<?php echo $base_path; ?>/images/products/buttons/login-btn.png" />
                                </button>
                               <a class="link forgot_password" >Forgot Your Password?</a>
                           </fieldset>
                        </form>
                        <p>
                            If you have not yet received your username and password, please call your <br> Mentor or contact customer support at <a class="link" href="mailto:support@sollenaturals.com">support@sollenaturals.com</a>
                        </p>
                    </div>
                </div>
            </div>
