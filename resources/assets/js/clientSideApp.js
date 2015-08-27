/**
 * Created by luciano on 22/08/15.
 */
var modApp = angular.module('clientSideApp', ['appControllers', 'appFactories', 'ngRoute']);
modApp.config(function($routeProvider){
    $routeProvider.when('/login', {
        templateUrl: '/angularTemplates/login',
        controller: 'LoginController'
    });

    $routeProvider.when('/productsCardapio', {
        templateUrl: '/angularTemplates/productsCardapio',
        controller: 'ProductsCardapioController'
    });
});