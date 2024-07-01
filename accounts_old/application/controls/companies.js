app.controller( 'viewCompanies', function( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "View All Companies";
    $scope.sort = {
        sortType: 'company',
        sortReverse: false
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.update = function( $id, $state ) {
        if ( $state === 'enable' || $state === 'cancel' ) {
            if ( confirm( "Are you sure you want to " + $state + "?" ) ) {
                AuthManager.post( '/companies/update/' + $state + '/' + $id ).then( function( results ) {
                    $scope.load();
                } );
            }
        }
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            templateUrl: 'application/views/companies/add.php',
            controller: 'addCompanies',
            size: 'xl'
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.edit = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            templateUrl: 'application/views/companies/edit.php',
            controller: 'editCompanies',
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
    $scope.load = function() {
        AuthManager.get( '/companies/view', { view_type: 'view' } ).then( function( results ) {
            $scope.results = results.data;
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    }
    $scope.load();
} );
app.controller( 'editCompanies', function( $scope, $http, AuthManager, $uibModalInstance, id ) {
    AuthManager.get( '/companies/view', { view_type: 'edit', id: id } ).then( function( results ) {
        $scope.results = results.data;
        $scope.results.view_type = 'save';
    } ).catch( function( error ) {
        warningAlert( error );
    } );
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.save = function() {
        AuthManager.get( '/companies/save', $scope.results ).then( function( results ) {} ).catch( function( error ) {
            warningAlert( error );
        } );
    };
} );
app.controller( 'addCompanies', function( $scope, $uibModalInstance, $location, $http, AuthManager ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.add = function() {
        $scope.submitted = true;
        $http.post( '/app/companies/create', $scope.data ).success( function( data ) {
            AuthManager.update( data.user, data.redirect );
            if ( data.data === "true" ) {
                successAlert( data.message );
                $scope.close();
            } else {
                warningAlert( data );
            }
        } ).error( function() {
            warningAlert( 'a network error occurred' );
        } );
        $scope.submitted = false;
    };
} );
