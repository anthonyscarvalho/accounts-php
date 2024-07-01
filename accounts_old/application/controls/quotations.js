app.controller( 'viewQuotations', function ( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "All Quotations";
    $scope.sort = {
        sortType: 'business',
        sortReverse: false
    };
    $scope.load = function ( $state ) {
        $scope.loaded = $state;
        $scope.results = null;
        $http.get( 'app/quotations/view/' + $state ).success( function ( data ) {
            AuthManager.update( data.user_roles, data.user_access );
            $scope.results = data.data;
        } );
    };
    $scope.reset = function ( ) {
        $scope.searchKeyword = "";
    };
    $scope.cancel = function ( $id ) {
        if ( confirm( "Are you sure you want to cancel?" ) ) {
            update( $http, 'contacts', 'cancel', $id );
            $scope.load( $scope.loaded );
        }
    };
    $scope.enable = function ( $id ) {
        if ( confirm( "Are you sure you want to enable?" ) ) {
            update( $http, 'contacts', 'enable', $id );
            $scope.load( $scope.loaded );
        }
    };
    $scope.add = function () {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/quotations/add.php',
            controller: 'addQuotations',
            size: 'lg',
            resolve: {
                client: function ( ) {
                    return '';
                }
            }
        } );
        uibModalInstance.result.then( function () {
            $scope.load( $scope.loaded );
        } );
    };
    $scope.edit = function ( $id ) {
        var uibModalInstance = $uibModal.open( {
            templateUrl: 'application/views/quotations/edit.php',
            controller: 'editQuotations',
            size: 'lg',
            resolve: {
                id: function () {
                    return $id;
                }
            }
        } );
        uibModalInstance.result.then( function () {
            $scope.load( $scope.loaded );
        } );
    };
    $scope.delete = function ( $id ) {
        if ( confirm( "Are you sure you want to delete?" ) ) {
            deleteData( $http, AuthManager, 'contacts', $id );
            $scope.load( $scope.loaded );
        }
    };
    $scope.load( '' );
} );
app.controller( 'searchQuotations', function ( $scope, $http, $routeParams, $rootScope, $uibModal, AuthManager ) {
    $scope.SetContacts = true;
    $scope.sort = {
        sortType: 'name',
        sortReverse: false
    };
    $scope.parent = $routeParams.client;
    function load( ) {
        search( $rootScope, $scope, $http, AuthManager, 'contacts', $scope.parent );
    }
    $scope.reset = function ( ) {
        $scope.searchKeyword = "";
    };
    $scope.cancel = function ( $id ) {
        if ( confirm( "Are you sure you want to cancel?" ) ) {
            update( $http, AuthManager, 'contacts', 'cancel', $id );
            load();
        }
    };
    $scope.enable = function ( $id ) {
        if ( confirm( "Are you sure you want to enable?" ) ) {
            update( $http, AuthManager, 'contacts', 'enable', $id );
            load();
        }
    };
    $scope.edit = function ( $id ) {
        var uibModalInstance = $uibModal.open( {
            templateUrl: 'application/views/contacts/edit.php',
            controller: 'editContacts',
            size: 'lg',
            resolve: {
                id: function () {
                    return $id;
                }
            }
        } );
        uibModalInstance.result.then( function () {
            load( $scope.loaded );
        } );
    };
    $scope.add = function () {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/contacts/add.php',
            controller: 'addContacts',
            size: 'lg'
        } );
        uibModalInstance.result.then( function () {
            load( $scope.loaded );
        } );
    };
    load( );
} );

app.controller( 'editQuotations', function ( $scope, $http, $uibModalInstance, AuthManager, id ) {
    $scope.close = function ( ) {
        $uibModalInstance.close( );
    };
    $scope.cancel = function ( ) {
        $uibModalInstance.dismiss( 'cancel' );
    };
    $scope.save = function ( ) {
        $scope.submitted = true;
        save( $http, AuthManager, 'contacts', $scope.data );
        $scope.submitted = false;
        load();
    };
    load();
    function load() {
        $http.get( '/app/contacts/edit/' + id ).success( function ( data ) {

            if ( data.logged_in === "false" ) {
                AuthManager.logOut( );
            }
            AuthManager.update( data.user_roles, data.user_access );
            $scope.data = data.data;
        } );
    }
} );
app.controller( 'addQuotations', function ( $scope, AuthManager, $uibModalInstance, $http ) {

    $scope.data = {
        companies: '1'
    };
    $scope.close = function ( ) {
        $uibModalInstance.close( );
    };
    $scope.cancel = function ( ) {
        $uibModalInstance.dismiss( 'cancel' );
    };
    $http.get( '/app/companies/retrieve/' ).success( function ( data ) {
        $scope.companies = data.data;
    } );
    $http.get( '/app/template_quotations/retrieve/' ).success( function ( data ) {
        $scope.teplates = data.data;
    } );
    $scope.add = function ( ) {
        $scope.submitted = true;
        $http.post( '/app/quotations/create', $scope.data ).success( function ( data ) {
            if ( data.redirect )
            {
                $.growl( { title: data.message }, { type: "warning", delay: 10000 } );
                AuthManager.redirect();
            }
            if ( data.data === "true" ) {
                $.growl( { title: data.message }, { type: "success", delay: 6000 } );
            }
            else {
                $.growl( { title: data }, { type: "warning", delay: 10000 } );
            }
        } ).error( function ( data ) {
            $.growl( { title: data }, { type: "warning", delay: 10000 } );
        } );
        $scope.submitted = false;
    };
} );
