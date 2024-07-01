app.controller( 'viewUserRoles', function ( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "View All Users";
    $scope.sort = {
        sortType: 'clientName',
        sortReverse: false
    };
    $scope.load = function ( $state ) {
        $scope.loaded = $state;
        $scope.results = null;
        load( $state );
    };
    $scope.reset = function () {
        $scope.searchKeyword = "";
    };
    $scope.add = function () {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/users/add.php',
            controller: 'addUsers',
            size: 'lg'
        } );
    };
    function load( $state ) {
        $scope.loaded = $state;
        viewData( $scope, $http, AuthManager, 'user_roles', $state );
    }
    load( '' );
} );
app.controller( 'editUserRoles', function ( $scope, $http, $routeParams, $rootScope, AuthManager ) {
    function load() {
        $http.get( '/app/users/edit/' + $routeParams.id ).success( function ( data ) {
            if ( data.logged_in === "false" ) {
                AuthManager.logOut();
            }
            AuthManager.update( data.user_roles, data.user_access );
            $scope.data = data.data;
            $scope.parent = data.data.id;
            $scope.balance = data.balance;
            $rootScope.title = ( data.data.business );
        } );
    }
    $scope.save = function () {
        $scope.submitted = true;
        save( $http, AuthManager, 'user_roles', $scope.data );
        $scope.submitted = false;
        load();
    };
} );

app.controller( 'addUserRoles', function ( $scope, $uibModalInstance, $location, $http, AuthManager ) {
    $scope.close = function ( ) {
        $uibModalInstance.close(  );
    };
    $scope.cancel = function ( ) {
        $uibModalInstance.dismiss( 'cancel' );
    };
    $scope.add = function () {
        $scope.submitted = true;
        insert( $http, $location, AuthManager, 'user_roles', $scope.data );
        $scope.submitted = false;
    };
} );