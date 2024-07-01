app.controller( 'viewContacts', function( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "View All Contacts";
    $scope.sort = {
        sortType: 'business',
        sortReverse: false
    };
    $scope.data = {
        view_type: "view",
        state: "false"
    };
    $scope.load = function() {
        AuthManager.get( '/contacts/view/', $scope.data ).then( function( results ) {
            $scope.results = results.data;
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.update = function( $id, $state ) {
        if ( $state === 'enable' || $state === 'cancel' ) {
            if ( confirm( "Are you sure you want to " + $state + "?" ) ) {
                AuthManager.post( '/contacts/update/' + $state + '/' + $id ).then( function( results ) {
                    $scope.load();
                } );
            }
        }
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            templateUrl: 'application/views/contacts/add.php',
            controller: 'addContacts',
            size: 'xl',
            resolve: {
                client: function() {
                    return '';
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.edit = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            templateUrl: 'application/views/contacts/edit.php',
            controller: 'editContacts',
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
    $scope.delete = function( $id ) {
        if ( confirm( "Are you sure you want to delete?" ) ) {
            AuthManager.delete( '/contacts/delete/' + $id ).then( function( results ) {
                if ( results == 'true' ) {
                    $scope.load();
                }
            } );
        }
    };
    $scope.load();
} );
app.controller( 'searchContacts', function( $scope, $http, $routeParams, $rootScope, $uibModal, AuthManager ) {
    $scope.SetContacts = true;
    $scope.parent = $routeParams.client;
    $scope.sort = {
        sortType: 'name',
        sortReverse: false
    };
    $scope.data = {
        view_type: "search",
        id: $routeParams.client
    };
    $scope.load = function() {
        AuthManager.get( '/contacts/view/', $scope.data ).then( function( results ) {
            $scope.results = results.data;
            $rootScope.title = results.business + " - Contacts";
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.update = function( $id, $state ) {
        if ( $state === 'enable' || $state === 'cancel' ) {
            if ( confirm( "Are you sure you want to " + $state + "?" ) ) {
                AuthManager.post( '/contacts/update/' + $state + '/' + $id ).then( function( results ) {
                    $scope.load();
                } );
            }
        }
    };
    $scope.delete = function( $id ) {
        if ( confirm( "Are you sure you want to delete?" ) ) {
            AuthManager.delete( '/contacts/delete/' + $id ).then( function( results ) {
                if ( results == 'true' ) {
                    $scope.load();
                }
            } );
        }
    };
    $scope.edit = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            templateUrl: 'application/views/contacts/edit.php',
            controller: 'editContacts',
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
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/contacts/add.php',
            controller: 'addContacts',
            size: 'xl',
            resolve: {
                client: function() {
                    return $routeParams.client;
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.load();
} );
app.controller( 'editContacts', function( $scope, $http, $uibModalInstance, AuthManager, id ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.save = function() {
        AuthManager.get( '/contacts/save', $scope.results ).then( function( results ) {
            $scope.load();
        } );
    };
    $scope.load = function() {
        AuthManager.get( '/contacts/view/', {
            view_type: "edit",
            id: id
        } ).then( function( results ) {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
        } );
    };
    $scope.load();
} );
app.controller( 'addContacts', function( $scope, AuthManager, $uibModalInstance, $location, $http, client ) {
    if ( client == '' ) {
        $scope.addAll = true;
        AuthManager.get( '/clients/view/', {
            "view_type": 'view',
            "state": 'false'
        } ).then( function( results ) {
            $scope.clients = results.data;
        } );
    } else {
        $scope.addAll = false;
    }
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.data = {
        payment: "true",
        invoice: "true",
        receipt: "true",
        suspension: "true",
        adwords: "true",
        clients: client,
        view_type: 'create'
    };
    $scope.add = function() {
        AuthManager.get( '/contacts/save', $scope.data ).then( function( results ) {
            if ( results.data == 'true' ) {
                $scope.close();
            }
        } );
    };
} );
