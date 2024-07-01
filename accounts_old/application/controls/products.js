app.controller( 'viewProducts', function( $scope, $http, $rootScope, $filter, $uibModal, AuthManager ) {
    $rootScope.title = "View All Products";
    $scope.loaded = 'false';
    $scope.sort = {
        sortType: 'clientName',
        sortReverse: false
    };
    var _date = new Date();
    _date.setMonth( _date.getMonth() + 1 );
    $scope.date = $filter( 'date' )( _date, 'yyyy-MM-dd' );
    $scope.data = {
        view_type: 'view',
        state: 'false',
        date: $filter( 'date' )( _date, 'yyyy-MM-dd' )
    };
    $scope.load = function() {
        AuthManager.get( '/products/view/', $scope.data ).then( function( results ) {
            if($scope.data.state =="due")
            {
                $scope.results = results.products;
            }
            else
            {
                $scope.results = results.data;
            }
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.update = function( $id, $state ) {
        if ( $state === 'enable' || $state === 'cancel' ) {
            if ( confirm( "Are you sure you want to " + $state + "?" ) ) {
                AuthManager.post( '/products/update/' + $state + '/' + $id ).then( function( results ) {
                    $scope.load();
                } );
            }
        }
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/products/add.php',
            controller: 'addProducts',
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
            animation: false,
            templateUrl: 'application/views/products/edit.php',
            controller: 'editProducts',
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
            AuthManager.delete( '/products/delete/' + $id ).then( function( results ) {
                if ( results.data == 'true' ) {
                    $scope.load();
                }
            } );
        }
    };
    $scope.load();
} );
app.controller( 'searchProducts', function( $scope, $http, $routeParams, $rootScope, $uibModal, AuthManager ) {
    $scope.SetProducts = true;
    $scope.loaded = 'false';
    $scope.sort = {
        sortType: 'date',
        sortReverse: false
    };
    $scope.data = {
        view_type: 'search',
        state: 'false',
        client: $routeParams.clients
    };
    $scope.parent = $routeParams.clients;
    $scope.load = function() {
        AuthManager.get( '/products/view/', $scope.data ).then( function( results ) {
            $scope.results = results.data;
            if ( results.business ) {
                $rootScope.title = results.business + " - Products";
            }
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.update = function( $id, $state ) {
        if ( $state === 'enable' || $state === 'cancel' ) {
            if ( confirm( "Are you sure you want to " + $state + "?" ) ) {
                AuthManager.post( '/products/update/' + $state + '/' + $id ).then( function( results ) {
                    $scope.load();
                } );
            }
        }
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/products/add.php',
            controller: 'addProducts',
            size: 'xl',
            resolve: {
                client: function() {
                    return $scope.parent;
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.edit = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/products/edit.php',
            controller: 'editProducts',
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
            AuthManager.delete( '/products/delete/' + $id ).then( function( results ) {
                if ( results == 'true' ) {
                    $scope.load();
                }
            } );
        }
    };
    $scope.load();
} );
app.controller( 'editProducts', function( $scope, $http, $uibModalInstance, id, AuthManager ) {
    $scope.sort = {
        sortType: 'date',
        sortReverse: false
    };
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.renewable = [
    { "name": "Annual", "id": "a" },
    { "name": "Monthly", "id": "m" },
    { "name": "Once Off", "id": "o" },
    { "name": "Reusable", "id": "r" }
    ];
    $fillValues = function( $categories, $invoice ) {};
    $scope.save = function() {
        AuthManager.get( '/products/save', $scope.results ).then( function( results ) {
            $scope.load();
        } );
    };
    $scope.load = function() {
        AuthManager.get( '/products/view/', { view_type: "edit", id: id } ).then( function( results ) {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
            $scope.parent = results.data.id;
            // $fillValues( results.categories, results.id );
            return AuthManager.get( '/companies/view', { view_type: "search" } );
        } ).then( function( results ) {
            $scope.companies = results.data;
            return AuthManager.get( '/categories/view', { view_type: "edit", "id": $scope.results.categories } );
        } ).then( function( results ) {
            $scope.category = results.data;
            return AuthManager.get( '/invoices_items/view', { view_type: "search", id: $scope.results.id } );
        } ).then( function( results ) {
            $scope.invoices_items = results.data;
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.load();
} );
app.controller( 'addProducts', function( $scope, AuthManager, $uibModalInstance, $http, $filter, client ) {
    if ( client == '' ) {
        $scope.addAll = true;
        $clients = AuthManager.get( '/clients/view', { view_type: 'view', state: 'false' } );
    } else {
        $scope.addAll = false;
        $clients = AuthManager.resolve();
    }
    $scope.close = function() {
        $uibModalInstance.close();
    };
    var _date = new Date();
    $scope.data = {
        canceled: "false",
        clients: client,
        date: $filter( 'date' )( _date, 'yyyy-MM-dd' ),
        view_type: 'create',
        renewable: 'a'
    };
    $scope.renewable = [
    { "name": "Annual", "id": "a" },
    { "name": "Monthly", "id": "m" },
    { "name": "Once Off", "id": "o" },
    { "name": "Reusable", "id": "r" }
    ];
    $clients.then( function( results ) {
        if ( client == '' ) {
            $scope.clients = results.data;
        }
        return AuthManager.get( '/categories/view', { view_type: "retrieve", "link": 'invoice' } );
    } ).then( function( results ) {
        $scope.categories = results.data;
        return AuthManager.get( '/companies/view', { view_type: "search" } );
    } ).then( function( results ) {
        $scope.companies = results.data;
    } );
    $scope.add = function() {
        if ( !$scope.data.clients ) {
            warningAlert( "select a client!" );
        }
        if ( !$scope.data.categories ) {
            warningAlert( "select an item!" );
        }
        if ( !$scope.data.companies ) {
            warningAlert( "select a company!" );
        }
        if ( ( $scope.data.companies ) && ( $scope.data.clients ) && ( $scope.data.categories ) ) {
            AuthManager.get( '/products/save', $scope.data ).then( function( results ) {
                if ( results.data == "true" ) {
                    $scope.close();
                }
            } ).catch( function( error ) {
                warningAlert( error );
            } );
        }
    };
    $scope.updatePrice = function() {
        $scope.data.price = $( 'option:selected', '#categories' ).attr( "data-price" );
    };
} );
