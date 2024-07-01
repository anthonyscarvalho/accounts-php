app.controller( 'searchTransactions', function( $scope, $http, $routeParams, $rootScope, $uibModal, AuthManager ) {
    $scope.SetTransactions = true;
    $scope.sort = {
        sortType: 'date',
        sortReverse: true
    };
    $scope.parent = $routeParams.client;
    $scope.data = {
        view_type: "search",
        client: $routeParams.client
    };
    $scope.load = function() {
        AuthManager.get( '/transactions/view/', $scope.data ).then( function( results ) {
            $scope.results = results.data;
            $rootScope.title = results.business + " - Transactions";
        } );
    };
    $scope.delete = function( $id ) {
        if ( confirm( "Are you sure you want to delete?" ) ) {
            AuthManager.get( '/transactions/delete', { id: $id, clients: $scope.parent } ).then( function( results ) {
                if ( results.data == "true" ) {
                    $scope.load();
                }
            } ).catch( function( error ) {
                warningAlert( error );
            } );
        }
    };
    $scope.edit = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/transactions/edit.php',
            controller: 'editTransactions',
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
    $scope.addTrans = function( $parent ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/transactions/add.php',
            controller: 'addTransactions',
            size: 'xl',
            resolve: {
                parent: function() {
                    return $parent;
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.load();
} );
app.controller( 'editTransactions', function( $scope, $http, $rootScope, AuthManager, $uibModalInstance, id ) {
    $scope.data = {
        view_type: "edit",
        id: id
    };
    AuthManager.get( '/companies/view', { view_type: 'search' } ).then( function( results ) {
        $scope.companies = results.data;
        return AuthManager.get( '/transactions/view', { view_type: 'edit', id: id } );
    } ).then( function( results ) {
        $scope.results = results.data;
        $scope.results.view_type = 'save';
    } ).catch( function( error ) {
        warningAlert( error );
    } );
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.cancel = function() {
        $uibModalInstance.dismiss( 'cancel' );
    };
    $scope.save = function() {
        AuthManager.get( '/transactions/save', $scope.results ).then( function( results ) {
            if ( results.data == 'true' ) {
                $scope.close();
            }
        } );
    };
} );
app.controller( 'addTransactions', function( $scope, $uibModalInstance, $http, AuthManager, parent ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.data = {
        companies: '1',
        clients: parent,
        sendMail: 'true',
        view_type: 'create'
    };
    AuthManager.get( '/companies/view', { view_type: 'search' } ).then( function( results ) {
        $scope.companies = results.data;
    } ).catch( function( error ) {
        warningAlert( error );
    } );
    $scope.add = function() {
        AuthManager.get( '/transactions/save', $scope.data ).then( function( results ) {
            if ( results.data == "true" ) {
                $scope.close();
            }
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
} );
