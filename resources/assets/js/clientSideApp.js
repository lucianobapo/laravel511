/**
 * Created by luciano on 22/08/15.
 */
var objectModule = angular.module('clientSideApp', ['appControllers', 'ngRoute']);
objectModule.config(function($routeProvider){
    $routeProvider.when('/login', {
        templateUrl: '/angularTemplates/login',
        controller: 'LoginController'
    });

    $routeProvider.when('/productsCardapio', {
        templateUrl: '/angularTemplates/productsCardapio',
        controller: 'ProductsCardapioController'
    });
});