var path = base_path + "/store/includes/api/api.php";

SolleApp.factory('Catalog', function($http){
    return{
        getItems : function(){
           return $http.get(path + "?action=get_products");
        }
    };
});

SolleApp.factory('orderService', function($http, basket){
    var orderHasBeenTotaled = false;
    return {
        createOrder : function(username, items){
            return $http.post(path + "?action=create_order", {'username' : username}).then(function(response){
                return response.data;
            });
        },
        getOrder : function(){
          return $http.get(path + "?action=get_order").then(function(response){
            return response.data;
          });
        },
        shippingTypes : function(){
          return $http.get(path + "?action=shipping_types").then(function(response){
            return response.data;
          });
        },
        updateCart : function(items){
            $http.post(path + "?action=update_cart", {'items' : items});
        },
        completeOrder: function(details,total, payment){
            return $http.post(path + "?action=complete_order", {'details' :details, 'total': total, 'payment' : payment}).then(function(response){
                // console.log(response);
                return response.data;
            });
        },
        process_gift_card : function(card_number){
            return $http.post(path + "?action=process_gift_card", {'card_number' : card_number}).then(function(response){
                //console.log(response);
                return response.data;
            });
        },
        calculateTotals : function(gift_card_number, details){
            return $http.post(path + "?action=calculate_totals", {'gift_card_number' : gift_card_number, 'details' : details}).then(function(response){
                orderHasBeenTotaled = true;
                return response.data;

            });
        },
        orderHasBeenTotaled : function(){
            return orderHasBeenTotaled;
        }
    };
});

SolleApp.factory('basket', function($http){
    var basket = {};
    var count = 0;
    return {
        get : function(){
            return $http.get(path + "?action=get_cart").then(function(response){
                count = response.data.size;
                basket = response.data.cart;
                return basket;
            });
        },
        count : function(){
            return count;
        },
        add : function(item){
            $http.post(path + "?action=add_to_cart", item );
            if (basket[item.productId]){
            }else{
                basket[item.productId] = {
                     type : item
                 };
                count += 1;
            }
        },
        remove : function(item){
            return $http.post(path + "?action=remove_from_cart", item ).then(function(response){
                count -= 1; //basket[item.type.productId].quantity;
                delete basket[item.productId];
                item.product_quantity = 0; // set quantity to 0 for call to api    
            });
        },
        clear : function(){
            count = 0;
            return $http.get(path + "?action=clear_cart");
        }
    };
});

SolleApp.factory('loginService', function($http){
    var logged_in;
    var usertype = {};
    var user = {};

    return {
        init : function(){
            return $http.get(path + "?action=check_authorized").then(function(response){
                user = response.data;
                logged_in = response.data.status;
                usertype = response.data.usertype;
                // return response.data;
            });
        },
        login : function(username, password){
            var user = {'username' : username, 'password' : password };
            return $http.post(path + "?action=login", {'user' : user}).then(function(response){
                var data = response.data.Result.authenticateSuccess;
                logged_in = data;
                return data;
            });
        },
        request_password : function(username){
            var user = { 'username' : username };
            return $http.post(path + "?action=password_request", {'user' : user}).then(function(response){
                return response.data.Result;
            });
        },
        get_username : function(){
            return $http.get(path + "?action=get_username").then(function(response){
                return response;
            });
        },
        check_authorized : function(){
            return $http.get(path + "?action=check_authorized").then(function(response){
                var data = response.data;
                logged_in = data.status;
                user = data.user;
                usertype = data.usertype;
                return data;
            });
        },
         is_logged_in : function(){
            return logged_in;
        },
        logout : function(){
            return $http.get(path +"?action=logout").then(function(response){
                logged_in = false;    
            });            
        },
        getUser : function(){
            return user;
        },
        getUserType : function() {
            return usertype;
        }
    };
});

SolleApp.factory('countriesService', function($http){
    return {
        get : function(){
            return $http.get(path + "?action=get_countries").then(function(response){
                return response;
            });
        }
    };
});

SolleApp.factory('statesService', function($http){
    return {
        get : function(){
            return $http.get(path + "?action=get_states").then(function(response){
                return response;
            });
        }
    };
});

SolleApp.factory('membershipService', function($http, basket, $rootScope){
    return {
        register : function(user){
            return $http.post(path + "?action=create_customer", { "customer": user });
        },
        checkusername : function(username){
          return $http.post(path + "?action=check_username", { "username": username });
        },
        setRelationShip : function(parent, username, relationship){
            return $http.post(path + "?action=set_user_relationship", {
                        'parentUsername' : parent,
                        'username' : username,
                        'relationshipType' : 2 // 1 - SmartPlan Tree, 2 - Mentor Tree, 3 - Member Tree
                    });
        },
        updateUser : function(user, username){
            return $http.post(path + "?action=update_customer", {"customer":user, "username" : username}).then(function(response){
                return response.data.Result;
            });
        },
        getMember : function(username){
            return $http.post(path + "?action=get_user", {"username" : username}).then(function(response){
                return response.data;
            });
        }
    };
});

SolleApp.factory('messageManager', function($http){
    return {
        showError : function(message){
            new Messi(message, {title: 'There was an error', titleClass: 'anim error', buttons: [{id: 0, label: 'Close', val: 'X'}]});
        },
        showInfo : function(message){
            new Messi(message, {title: 'Update Cart', titleClass: 'info', buttons: [{id: 0, label: 'Close', val: 'X'}]});
        },
        alert : function(message){
            Messi.alert(message);
        }
    }
});

SolleApp.factory('mentorManager', function($http){
    return {
        check_for_mentor : function(){
            this.get_mentor().then(function(response){
                if ( response === '' || 'false'){
                    var current_url = window.location.href;    
                    if ( window.location.href.indexOf('sponsor') == -1 ){
                        window.location.href = "http://onesolle.com/redirect_with_sponsor.php?url=" + current_url; //https://www.sollenaturals.com";
                    }
                    var results = new RegExp('[\\?&]sponsor=([^&#]*)').exec(window.location.href);
                    if ( results ){
                        $http.post(path + "?action=set_mentor_session", {"mentorId" : results[1] });
                    }
                }
            });
        },
        get_mentor : function(){
            return $http.get(path + "?action=get_mentor_session").then(function(response){
                var mentorId = response.data;
                return mentorId.replace(/"/g, ''); // remove quotes around int that are returned from the api
            });
        }
    };
});