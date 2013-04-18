SolleApp.directive("equal", function(){
    return {
        restrict : 'A',
        link : function(scope, element, attrs){
            scope.$watch("heights", function(){
               element.equalHeights();
            }, true);
        }
    };
});

SolleApp.directive('isLoggedIn', function () {
  return {
    restrict: 'A',
    link: function (scope, element) {
        scope.login_status = 'crap';
    }
  };
});

SolleApp.directive("removeWithFadeInDirective", function() {
    return function(scope, element, attrs) {
        element.bind('click', function() {
            $(element).parent().parent().fadeOut('slow');
        });
    };
});

// SolleApp.directive("modal", function(){
//     return {
//         restrict : 'A',
//         link : function(scope, element, attrs){
//             scope.$watch("launch", function(){

//             })
//         }
//     };
// });

