var ProductsCtrl = function($scope, $http, Catalog, basket, loginService){
    $scope.products = {};
    $scope.basket = basket;
    $scope.quantity = [1,2,3,4,5];
    $scope.loginService = loginService;

    Catalog.getItems().then(function(response){
        $scope.products = response.data;
    });

    $scope.addItem = function($event, product){
        $event.preventDefault();
        basket.add(product);
    };
};

var MembershipCtrl = function($scope, $http, countriesService, membershipService, loginService, basket){
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

    loginService.check_authorized().then(function(response){ // calling this populates user info too
        $scope.user = response.user;
        $scope.usertype = response.usertype;
        $scope.login_status = response.status;
        $scope.username = response.username;
    });


    countriesService.get().then(function(response){
        $scope.countries = response.data.Result;
    });

    $scope.mentorSubmit = function($event){
        $event.preventDefault();
        $scope.mentor = {'mentor_name' : $scope.mentor_name, 'country' : $scope.country };
        $scope.step1 = false;
        $scope.step2 = true;
    };

    $scope.register = function($event, usertype){
        $event.preventDefault();
        // console.log("User Type " + usertype);

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
            // loginService.get_username().then(function(response){
            //     $scope.username = response.data;
            // });
            console.log($scope.username);
            membershipService.updateUser($scope.customer, $scope.username).then(function(response){
                if ( response.updateSuccess == 'true'){
                    loginService.login($scope.username, $scope.password).then(function(response){
                        if ( response == 'true' ){
                            document.location.reload(); // reload page to set proper classes in login_bar
                            $scope.close();
                        }else{
                            alert("Something went wrong with your login. Please try logging out and back in."); // some type of error trapping
                        }
                    });
                }
                else{
                    alert("Something went wrong while upgrading your account.");
                }
            });
        }
        else
        {
            membershipService.register($scope.customer).success(function(response){
                if ( response.Result.createSuccess == 'true' ){
                    $scope.username = response.Result.username; // get username from response

                    if ( $scope.mentor.mentor_name.length == 0 ){ $scope.mentor.mentor_name = "1"; }

                    membershipService.setRelationShip($scope.mentor.mentor_name, $scope.username, $scope.customer.usertype).success(function(response){
                        // //auto login after registration
                        loginService.login($scope.username,$scope.password).then(function(response){
                            if ( response == 'true' ){
                                document.location.reload(); // reload page to set proper classes in login_bar
                                $scope.close();
                            }else{
                                alert("Something went wrong with your login. Please try again."); // some type of error trapping
                            }
                        });
                    });
                }else if( response.Result.createSuccess == 'false' ){
                    alert("Something went wrong while creating your account. Please try again.");
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

            if ( sum > 50 )
                Modal('.member_register', '#member_inner');
            else{
                alert("You must purchase at least $50 in product to become a member.");
                if ( window.location.href != base_path + 'store/store.php'){
                    document.location.href = base_path + 'store/store.php';
                }
                $scope.close();
            }
        });
    };

    $scope.close = function(){
        $('.modal').hide();
        $.unblockUI();
        $scope.step1 = true;
        $scope.step2 = false;
    };
};

var CartCtrl = function($scope, $http, basket,countriesService, orderService,  statesService, loginService, sessionManager){
    $scope.basket = basket;
    $scope.loginService = loginService;
    $scope.items = {};
    $scope.countries = {};
    $scope.states = {};
    $scope.user = {};
    $scope.usertype = {};
    $scope.login_status = {};
    $scope.username = '';
    $scope.order = {};
    $scope.shippingDetails = {};
    $scope.finalize = false;
    var items;

    countriesService.get().then(function(response){
        $scope.countries = response.data.Result;
    });

    statesService.get().then(function(response){
        $scope.states = response.data.Result;
    });

    basket.get().then(function(response){
        if ( response === 0 && window.location.href == base_path + 'store/cart.php'){
            document.location.href = base_path + 'store/store.php';
        }
       $scope.items = response;
       items = response;
    });

    loginService.check_authorized().then(function(response){ // calling this populates user info too
        if ( response.status === 'true' ){ // logged in
             $scope.user = response.user;
            // $scope.usertype = response.usertype;
            // $scope.login_status = response.status;
            $scope.username = response.username;

            orderService.getOrder().then(function(response){ // check if order exists
                if (typeof response.Result == 'undefined'){ // if not exists create it
                    orderService.createOrder($scope.username).then(function(response){
                        if ( response.error )
                            alert(response.error);
                        else
                        {
                            $scope.order = response.Result;
                            if ( typeof response.Result != 'undefined' && response.Result.tax > 0 ) //  if tax has been set the order can be completed
                            {
                                $scope.finalize = true;
                            }
                        }
                    });
                }
            });
        }

    });

    // sessionManager.getShippingDetails().then(function(response){
    //     if (response.data){
    //         angular.forEach(response.data, function(value){
    //             console.log(value);
    //             $scope.details = value.shippingName;
    //         });
    //     }else{
    //         console.log(response);
    //     }
    // });

    $scope.UpdateCart = function($event, items){
        $event.preventDefault();
        orderService.updateCart($scope.username, items).then(function(response){
            document.location.reload();
        });
    };

    $scope.CaclulateTotals = function($event,gift_card_number,details){
        $event.preventDefault();
        // sessionManager.storeShippingDetails(details);
        orderService.calculateTotals(gift_card_number, details).then(function(response)
        {
            console.log(response);
            if ( response.error )
            {
                alert(response.error);
            }
            else
            {
                $scope.order = response.Result;
                $scope.finalize = true;
            }
        });
    };

    $scope.CompleteOrder = function($event, details, total, payment){
        $event.preventDefault();
        orderService.completeOrder(details,total, payment).then(function(response){
            if (response.success )
            {
                window.location.href = base_path + 'store/receipt.php';
            }
            else
            {
                alert(response.error);
            }
        });
    };


    $scope.remove = function(item){
        basket.remove(item);
        // $scope.$watch('basket.count()', function(newValue, oldValue,scope){
        //     if ( newValue === 0 && window.location.href == base_path + 'store/cart.php')
        //         document.location.href = base_path + 'store/store.php';
        // });
        document.location.reload();
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

var AppCtrl = function($scope, $http, basket, countriesService, orderService,  statesService, loginService){
    $scope.basket = basket;
    $scope.loginService = loginService;
    $scope.password_reset = false;
    $scope.bad_username = false;
    $scope.fatal = false;
    $scope.login_class = '';
    $scope.user = {};
    $scope.usertype = {};
    $scope.login_status = {};
    $scope.items = {};

    basket.get().then(function(response){
       $scope.items = response;
    });

    loginService.check_authorized().then(function(response){ // calling this populates user info too
        // console.log(response);
        if ( response.status == 'true'){
            $scope.login_class = 'logout';
        }else{
            $scope.login_class = 'login';
        }
        $scope.user = response.user;
        $scope.usertype = response.usertype;
        $scope.login_status = response.status;
    });

    $scope.total = function () {
        var sum = 0;
        angular.forEach($scope.items, function (item) {
            sum += item.product_quantity * item.retailPrice;
        });
        return sum;
    };

    $scope.becomeMember = function($event){
        $event.preventDefault();
        Modal('.become_member', '#become_member');
        if ( loginService.is_logged_in() )
        {
            $scope.firstName = $scope.user.firstName;
            $scope.lastName = $scope.user.lastName;
            $scope.email = $scope.user.email;
            $scope.email_confirm = $scope.user.email;
            $scope.homephone = $scope.user.homephone;
            $scope.cellPhone = $scope.user.cellPhone;
        }
    };

    $scope.login = function(){
        loginService.login($scope.username,$scope.password).then(function(response){
            $scope.logged_in = response;
            if ( response == 'true' ){
                document.location.reload(); // reload page to set proper classes in login_bar
                $scope.close();
            }else{
                alert("Something went wrong with your login. Please try again."); // some type of error trapping
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

    $scope.auth = function($event, auth_status){
        if (auth_status == 'login' ){
            Modal('.sign_in','#login_modal');
        }else{
            loginService.logout().then(function(response){
                $scope.auth_status = 'login';
                document.location.reload();
            });
        }
    };

    $scope.close = function(){
        $('.modal').hide();
        $('.modal-backdrop').hide();
        $.unblockUI();
    };
};
