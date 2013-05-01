var ProductsCtrl = function($scope, $http, Catalog, basket, membershipService){
    $scope.membershipService = membershipService;
    $scope.products = {};
    $scope.basket = basket;
    $scope.quantity = [1,2,3,4,5];

    Catalog.getItems().then(function(response){
        $scope.products = response.data;
    });

    $scope.addItem = function($event, product){
        $event.preventDefault();
        basket.add(product);
    };
};

var MembershipCtrl = function($scope, $http, $location,countriesService, membershipService, loginService, basket, messageManager, mentorManager){
    $scope.customer = {};
    $scope.loginService = loginService;
    $scope.mentor = {};
    $scope.countries = {};
    $scope.mentor_id = '';
    $scope.mentor_name = '';
    $scope. mentor_type ='';
    $scope.step1 = true;
    $scope.step2 = false;
    $scope.username_exists = false;
    $scope.username = '';

    $scope.user = {};
    $scope.usertype = {};
    $scope.login_status = {};

    countriesService.get().then(function(response){
        $scope.countries = response.data.Result;
    });
    mentorManager.get_mentor().then(function(response){
        $scope.mentor_name = response;
    });

   $scope.becomeMember = function($event){
        $event.preventDefault();
        Modal('.become_member', '#become_member');
        // mentorManager.get_mentor().then(function(response){
        //     $scope.mentor_name = response;
        // });

        basket.get().then(function(response){
            var sum = 0;
            angular.forEach(response, function (item) {
                sum += item.product_quantity * item.retailPrice;
            });

            if ( sum >= 50 ){
                Modal('.member_register', '#member_inner');
                $scope.step1 = true;
                $scope.step2 = false;
            }
        });
    };

    $scope.mentorSubmit = function($event){
        $event.preventDefault();
        $scope.mentor.country = $scope.country;
        membershipService.getMember($scope.mentor_name).then(function(response){
            if ( response.Result ){
                $scope.mentor.mentor_name = $scope.mentor_name;
            }else{
                $scope.mentor.mentor_name = "1"; // 1 is the solle master account
            }
        });
        $scope.step1 = false;
        $scope.step2 = true;
    };

    $scope.register = function($event, usertype){
        $event.preventDefault();

        $scope.customer = {
            'firstName' : $scope.firstName,
            'lastName' : $scope.lastName,
            'email' : $scope.email,
            'homePhone' : $scope.homePhone,
            'cellPhone' : $scope.cellPhone,
            'password' : $scope.password,
            'usertype' : usertype
        };

        if ( loginService.is_logged_in() )
        {
            membershipService.updateUser($scope.customer, $scope.username).then(function(response){
                if ( response.updateSuccess == 'true'){
                    loginService.login($scope.username, $scope.password).then(function(response){
                        if ( response == 'true' ){
                            document.location.reload(); // reload page to set proper classes in login_bar
                            $scope.close();
                        }else{
                            messageManager.showError("Something went wrong with your login. Please try logging out and back in."); // some type of error trapping
                        }
                    });
                }
                else{
                    messageManager.showError("Something went wrong while upgrading your account.");
                }
            });
        }
        else
        {
            membershipService.register($scope.customer).success(function(response){
                if ( response.Result.createSuccess == 'true' ){
                    $scope.username = response.Result.username; // get username from response
                    membershipService.setRelationShip($scope.mentor.mentor_name, $scope.username, $scope.customer.usertype).success(function(response){
                        // //auto login after registration
                        loginService.login($scope.username,$scope.password).then(function(response){
                            if ( response == 'true' ){
                                document.location.reload(); // reload page to set proper classes in login_bar
                                loginService.init();
                                $scope.close();
                            }else{
                                messageManager.showError("Something went wrong with your login. Please try again."); // some type of error trapping
                            }
                        });
                    });
                }else if( response.Result.createSuccess == 'false' ){
                    messageManager.showError("Something went wrong while creating your account. Please try again.");
                }
            });
        }
    };

    $scope.memberRegister  = function($event){
        $event.preventDefault();
        basket.get().then(function(response){
            var sum = 0;
            angular.forEach($scope.items, function (item) {
                sum += item.product_quantity * item.retailPrice;
            });

            if ( sum > 50 ){
                $scope.step1 = true;
                $scope.step2 = false;
                Modal('.member_register', '#member_inner');
            }else{
                new Messi('You must purchase at least $50 in product to become a member.', 
                    {
                        title: 'Update Cart', titleClass: 'info', buttons: [{id: 0, label: 'Ok', val: 'Y'}], callback: function() { 
                            var url = window.location.href;
                            if ( url.search('store/store.php') == -1 ){
                                document.location.href = base_path + 'store/store.php';
                            }
                    $scope.close(); 
                }});
            }
        });
    };

    $scope.close = function(){
        $('.modal').hide();
        $.unblockUI();
        $scope.step1 = true;
        $scope.step2 = false;
    };

    loginService.init();

};

var CartCtrl = function($scope, $http, basket,countriesService, orderService,  statesService, loginService,messageManager){
    $scope.basket = basket;
    $scope.loginService = loginService;
    $scope.orderService = orderService;
    $scope.items = {};
    $scope.countries = {};
    $scope.states = {};
    $scope.details = {};
    $scope.order = {};

    $scope.getCountries = function(){
        countriesService.get().then(function(response){
            $scope.countries = response.data.Result;
        });    
    }

    $scope.getStates = function(){
        statesService.get().then(function(response){
            $scope.states = response.data.Result;
        });    
    }
    
    $scope.checkCart = function(){
        basket.get().then(function(response){
            var url = window.location.href;
            if ( response === 0 && url.search('store/cart.php') > 0 ){
                document.location.href = base_path + 'store/store.php';
            }
            $scope.items = response;
            var sum = 0;
            angular.forEach(response, function (item) {
                sum += item.product_quantity * item.retailPrice;
            });
            
            if ( document.location.href == base_path + 'store/cart.php' ){
                if ( sum >= 50 && loginService.is_logged_in() === false ){
                    Modal('.member_register', '#member_inner');
                }
            }
        });
    };

    //********************************************************************************
    //* Update the cart session. Check if order has been totaled and total again
    //********************************************************************************
    $scope.UpdateCart = function($event, items){
        $event.preventDefault();
        orderService.updateCart(items);
        //* order has already been calculated once just do it here if they update the cart
        if ( orderService.orderHasBeenTotaled() ){
            $scope.CalculateTotals($event, $scope.gift_card_number, $scope.details);
        }
    };

    //********************************************************************************
    //* Calculate the order totals
    //********************************************************************************
    $scope.CalculateTotals = function($event, gift_card_number, details){
        $event.preventDefault();
        orderService.calculateTotals(gift_card_number, details).then(function(response){
            if ( response.error ){
                messageManager.showError(response.error);
            }else{
                $scope.order = response.Result;
            }
        });
    };

    $scope.CompleteOrder = function($event, details, total, payment){
        $event.preventDefault();
        orderService.completeOrder(details,total, payment).then(function(response){
            if (response.success ){
                window.location.href = base_path + 'store/receipt.php';
            }else{
                messageManager.showError(response.error);
            }
        });
    };

    $scope.remove = function($event,item){
        basket.remove(item).then(function(response){
            if ( orderService.orderHasBeenTotaled() ){
                $scope.CalculateTotals($event, $scope.gift_card_number, $scope.details);
            }
            $scope.$watch('basket.count()', function(newValue, oldValue,scope){
                var url = window.location.href;
                if ( newValue === 0 && url.search('store/cart.php') > 0 ){
                    document.location.href = base_path + 'store/store.php';
                }
            });    
        });
    };

    $scope.total = function () {
        var sum = 0;
        angular.forEach($scope.items, function (item) {
            sum += item.product_quantity * item.retailPrice;
        });
        return sum;
    };

    $scope.discount = function(){
        var order_discount = 0;
        if ( $scope.order ){
            angular.forEach($scope.order.products, function(item) {
                if ( item.product == 152){
                    order_discount = item.cost;
                }
            });
            return order_discount;
        }else{
            return 0;
        }
    };

    $scope.getCountries();
    $scope.getStates();
    $scope.checkCart();
    // $scope.authorized();
};

var ReceiptCtrl = function($scope, $http, $timeout, basket,orderService, loginService, countriesService, statesService){
    $scope.order = {};
    $scope.countries = {};
    $scope.states = {};
    $scope.loginService = loginService;
    $scope.basket = basket;
    $scope.shippingTypes = {};
    $scope.items = {};

    loginService.check_authorized().then(function(response){ // calling this populates user info too
        if ( response.status === false){
            document.location.href = base_path + 'store/store.php';
        }

        countriesService.get().then(function(response){
            $scope.countries = response.data.Result;
        });

        orderService.shippingTypes().then(function(response){
            // console.log(response);
            $scope.shippingTypes = response.Result;
        });

        statesService.get().then(function(response){
            $scope.states = response.data.Result;
        });

        orderService.getOrder().then(function(response){
            // console.log(response);
            $scope.order = response.Result;
        });

        basket.get().then(function(response){
            $scope.items = response;
            if ( response === 0 ) // nothing to display redirect to store
            {
                document.location.href = base_path + 'store/store.php';
            }
        });

    });

    $scope.shippingType = function(){
        var shippingType;
        angular.forEach($scope.shippingTypes, function(type){
            if ( type.id == $scope.order.shippingType ){
                shippingType = type.name;
            }
        });
        return shippingType;
    };

    $scope.state = function(){
        var shippingState;
        angular.forEach($scope.states, function(state){
            if ( state.id == $scope.order.shippingState ){
                shippingState = state.name;
            }
        });
        return shippingState;
    };

    $scope.country = function(){
        var shippingCountry;
        angular.forEach($scope.countries, function(country){
            if ( country.id == $scope.order.shippingCountry ){
                shippingCountry = country.name;
            }
        });
        return shippingCountry;
    };

    $scope.discount = function(){
        var order_discount = 0;
        angular.forEach($scope.order.products, function(item) {
            if ( item.product == 152){
                order_discount = item.cost;
            }
        });
        return order_discount;
    };

    $timeout(function(){
        basket.clear();
    }, 5000); // clear cart session after 5

};

var AppCtrl = function($scope, $http, $location, loginService,messageManager, membershipService, mentorManager){
    $scope.loginService = loginService;
    $scope.membershipService = membershipService;
    $scope.password_reset = false;
    $scope.bad_username = false;
    $scope.fatal = false;
    
    mentorManager.check_for_mentor();

    $scope.init = function(){
        loginService.init();        
    }
    $scope.login = function(){
        loginService.login($scope.username,$scope.password).then(function(response){
            if ( response == 'true' ){
                $scope.close();
                document.location.reload();
            }else{
                messageManager.showError("Something went wrong with your login. Please try again.");// some type of error trapping
            }
        });
    };

    $scope.passwordRequest = function($event){
        $event.preventDefault();
        loginService.request_password($scope.username).then(function(response){
            $scope.password_reset = false;
            $scope.bad_username = false;
            $scope.fatal = false;

            if ( response !== undefined ){
                if ( response.updateSuccess == 'true' ){
                    $scope.password_reset = true;
                }else{
                    $scope.password_reset = true;
                }
            }else{
                $scope.fatal = true;
            }
        });
    };

    $scope.auth = function($event,auth_status){
        $event.preventDefault();
        console.log(auth_status);
        if (auth_status === false ){
            Modal('.sign_in','#login_modal');
        }else{
            loginService.logout().then(function(response){
                document.location.reload();    
            });
            // loginService.init();
            
        }
    };

    $scope.close = function(){
        $('.modal').hide();
        $('.modal-backdrop').hide();
        $.unblockUI();
    };

    $scope.init();
};
