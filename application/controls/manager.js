app.controller('LoginCtrl', function($scope, $http, $rootScope, $location, $cookies, AuthManager)
{
    $scope.requestLogIn = function()
    {
        AuthManager.logIn($scope.username, $scope.password).then(function(results)
        {
            if (results === "true")
            {
                if ($cookies.get('lasturl'))
                {
                    if ($cookies.get('lasturl') != '/')
                    {
                        $_url = $cookies.get('lasturl');
                    }
                    else
                    {
                        $_url = "/dashboard";
                    }
                }
                else
                {
                    $_url = "/dashboard";
                }
                AuthManager.redirect($_url);
            }
            else if (results === "false")
            {
                $("#username").focus();
            }
        });
    };
    $scope.submitted = false;
});
app.controller('LogoutCtrl', function($http, AuthManager)
{
    AuthManager.logOut();
});
