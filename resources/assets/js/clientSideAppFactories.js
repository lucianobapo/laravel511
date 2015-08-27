var modFactories = angular.module('appFactories', []);

modFactories.factory('TokenApi', function(){
    function get_token(credentials){
        var path = '/oauth/access_token';

        return $http.post(path, {
            username: credentials.username,
            password: credentials.password,
            client_id: 1,
            client_secret: 'secret',
            grant_type: 'password'
        });
    };

    return {
        get_token: get_token
    };
});

modFactories.factory('GetApi', function(){
    function get_url(url){        
        return $http({
            method: 'GET',
            url: url,
            params: {access_token: $rootScope.token}
            //headers: {
            //    Authorization: 'Bearer ' + $rootScope.token
            //}
        });
    };

    return {
        get_url: get_url
    };
});
