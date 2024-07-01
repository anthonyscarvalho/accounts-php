app.controller( 'viewUsers', function( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "View All Users";
    $scope.sort = {
        sortType: 'name',
        sortReverse: false
    };
    $scope.load = function( $state ) {
        AuthManager.get( '/users/view', { view_type: 'view' } ).then( function( results ) {
            $scope.results = results.data;
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.update = function( $id, $state ) {
        if ( $state === 'enable' || $state === 'cancel' ) {
            if ( confirm( "Are you sure you want to " + $state + "?" ) ) {
                AuthManager.post( '/users/update/' + $state + '/' + $id ).then( function( results ) {
                    $scope.load();
                } ).catch( function( error ) {
                    warningAlert( error );
                } );
            }
        }
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/users/add.php',
            controller: 'addUsers',
            size: 'xl'
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.edit = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            templateUrl: 'application/views/users/edit.php',
            controller: 'editUsers',
            size: 'xl',
            resolve: {
                id: function() {
                    return $id;
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.load();
} );
app.controller( 'editUsers', function( $scope, $http, $uibModalInstance, AuthManager, id ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $roles = AuthManager.get( '/user_roles/view', { view_type: 'view' } );
    $scope.load = function() {
        $roles.then( function( results ) {
            $scope.roles = results.data;
            return AuthManager.get( '/users/view', { view_type: 'edit', id: id } );
        } ).then( function( results ) {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
            return AuthManager.get( '/user_access/view', { view_type: 'edit', id: $scope.results.access_list } );
        } ).then( function( results ) {
            $scope.access = results.data;
            $scope.access.view_type = 'save';
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.save = function() {
        AuthManager.get( '/users/save', $scope.results ).then( function( results ) {
            $scope.load();
        } );
    };
    $scope.accessUpdate = function() {
        AuthManager.get( '/user_access/save', $scope.access ).then( function( results ) {
            $scope.load();
        } );
    };
    $scope.permissions = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            templateUrl: 'application/views/user_access/edit.php',
            controller: 'editPermissions',
            size: 'xl',
            resolve: {
                id: function() {
                    return $id;
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.load();
} );
app.controller( 'addUsers', function( $scope, $uibModalInstance, $location, $http, AuthManager ) {
    $scope.data = {
        view_type: 'create'
    };
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.add = function() {
        AuthManager.get( '/users/save', $scope.data ).then( function( results ) {
            if ( results.data == 'true' ) {
                $scope.close();
            }
        } );
    };
} );
app.controller( 'editPermissions', function( $scope, $http, $rootScope, AuthManager, id ) {
    $scope.load = function() {
        $http.get( '/app/user_access/view/' + id ).success( function( data ) {
            AuthManager.update( data.user, data.redirect );
            $scope.data = data.data;
        } );
    };
    $scope.save = function() {
        $scope.submitted = true;
        save( $http, AuthManager, 'users', $scope.data );
        $scope.submitted = false;
        load();
    };
    $scope.close = function() {
        $uibModalInstance.close();
    };
} );
