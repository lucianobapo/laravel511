/**
 * Created by luciano on 22/08/15.
 */
var objectModule = angular.module('appControllers', []);

objectModule.controller('LoginController', ['$scope', '$http', '$rootScope', '$location', function($scope, $http, $rootScope, $location){
    $scope.email = "";
    $scope.password = "";
    $scope.error = {
        valid: false,
        message: ""
    };
    $scope.login = function(){
        $http.post('/oauth/access_token', {
            username: $scope.email,
            password: $scope.password,
            client_id: 1,
            client_secret: 'secret',
            grant_type: 'password'
        })
            .success(function(data){
                if(typeof data.access_token != 'undefined' && data.access_token != '') {
                    $scope.error.valid = false;
                    $rootScope.token = data.access_token;
                    $location.path('productsCardapio');
                }
            })
            .error(function(data){
                $scope.error.valid = true;
                $scope.error.message = data.error_description;
            });
        return false;
    };
}]);

objectModule.controller('ProductsCardapioController', ['$scope', '$http', '$rootScope', '$location', function($scope, $http, $rootScope, $location){
    $scope.products = [];

    $scope.error = {
        valid: false,
        message: ""
    };

    if(typeof $rootScope.token != 'undefined' && $rootScope.token != '') {
        httpOptions = {
            method: 'GET',
            url: '/oauth/productsCardapio',
            params: {access_token: $rootScope.token}
            //headers: {
            //    Authorization: 'Bearer ' + $rootScope.token
            //}
        };

        $http(httpOptions)
            .success(function(data){
                $scope.products = data;
            })
            .error(function(data){
                console.log(httpOptions);
                console.log($scope);
                console.log(data);
                $scope.error.valid = true;
                $scope.error.message = data.error_description;
                $location.path('login');
            });
    }else $location.path('login');
}]);
