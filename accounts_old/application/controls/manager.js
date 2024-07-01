app.controller( 'LoginCtrl', function ( $scope, $http, $rootScope, $location, AuthManager )
{
    $scope.requestLogIn = function ()
    {
        AuthManager.logIn( $scope.username, $scope.password ).then( function ( results )
        {
            if ( results === "true" )
            {
                AuthManager.redirect( "/dashboard" );
            }
            else if ( results === "false" )
            {
                $( "#username" ).focus();
            }
        } );
    };
    $scope.submitted = false;
} );
app.controller( 'LogoutCtrl', function ( $http, AuthManager )
{
    AuthManager.logOut();
} );