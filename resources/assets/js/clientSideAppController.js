/**
 * Created by luciano on 22/08/15.
 */
var modControllers = angular.module('appControllers', []);

modControllers.controller('LoginController', ['$scope', '$http', '$rootScope', '$location', function($scope, $http, $rootScope, $location){
    $scope.email = "";
    $scope.password = "";
    $scope.error = {
        valid: false,
        message: ""
    };
    $scope.login = function(){
        $scope.loading = true;
        TokenApi.get_token({
            username: $scope.email,
            password: $scope.password
        })
            .success(function(data){
                $scope.loading = false;
                if(typeof data.access_token != 'undefined' && data.access_token != '') {
                        $scope.error.valid = false;
                        $rootScope.token = data.access_token;
                        $scope.$digest();
                        $location.path('productsCardapio');
                    }                
            });

            .error(function(data){
                $scope.loading = false;
                $scope.error.valid = true;
                $scope.error.message = data.error_description;
                $scope.$digest();
            });
        return false;
    };
}]);

modControllers.controller('ProductsCardapioController', ['$scope', '$http', '$rootScope', '$location', function($scope, $http, $rootScope, $location){
    $scope.products = [];

    $scope.error = {
        valid: false,
        message: ""
    };

    if(typeof $rootScope.token != 'undefined' && $rootScope.token != '') {
        $scope.loading = true;    
        GetApi('/oauth/productsCardapio')
            .success(function(data){
                $scope.loading = false;
                $scope.products = data;
                $scope.$digest();
            })
            .error(function(data){
                $scope.loading = false;
                console.log(httpOptions);
                console.log($scope);
                console.log(data);
                $scope.error.valid = true;
                $scope.error.message = data.error_description;
                $scope.$digest();
                $location.path('login');                
            });
    }else $location.path('login');
}]);
