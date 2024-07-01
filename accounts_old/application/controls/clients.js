app.controller( 'viewClients', function( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "View All Clients";
    $scope.loaded = 'false';
    $scope.sort = {
        sortType: 'business',
        sortReverse: false
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.update = function( $id, $state ) {
        if ( $state === 'enable' || $state === 'cancel' ) {
            if ( confirm( "Are you sure you want to " + $state + "?" ) ) {
                AuthManager.post( '/clients/update/' + $state + '/' + $id ).then( function( results ) {
                    $scope.load();
                } );
            }
        }
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/clients/add.php',
            controller: 'addClients',
            size: 'xl'
        } );
        uibModalInstance.result.then( function( results ) {
            if ( results == 'true' ) {
                AuthManager.redirect( '/clients/edit/' + results );
            } else {
                $scope.load();
            }
        } );
    };
    $scope.load = function() {
        AuthManager.get( '/clients/view/', { view_type: 'view', state: $scope.loaded } ).then( function( results ) {
            $scope.results = results.data;
        } ).catch( function( error ) {
            if ( error == "logout" ) {
                AuthManager.logOut();
            }
        } );
    };
    $scope.load();
} );
app.controller( 'editClients', function( $scope, $http, $routeParams, $rootScope, AuthManager ) {
    $scope.SetClient = true;
    AuthManager.get( '/manager/verify' ).then( function( data ) {
        if ( data.logged_in == 'true' ) {
            AuthManager.updateDetails( data );
            return AuthManager.get( '/transactions/getCredit/' + $routeParams.id );
        }
    } ).then( function( results ) {
        $scope.credit = results;
        return AuthManager.get( '/statements/incomePA/' + $routeParams.id );
    } ).then( function( results ) {
        $scope.income = {
            labels: results.data.labels,
            series: results.data.series,
            data: [
                results.data.total,
                results.data.paid,
                results.data.unpaid,
                results.data.canceled,
                results.data.predicted

            ]
        };
        $scope.load();
    } ).catch( function( error ) {
        if ( error == "logout" ) {
            AuthManager.logOut();
        }
    } );
    $scope.load = function() {
        AuthManager.get( '/clients/view/', {
            view_type: "edit",
            id: $routeParams.id
        } ).then( function( results ) {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
            $scope.parent = results.data.id;
            $rootScope.title = results.data.business;
        } );
    };
    $scope.save = function() {
        AuthManager.get( '/clients/save', $scope.results ).then( function( results ) {
            $scope.load();
        } );
    };
    $scope.update = function( $id, $state ) {
        if ( $state === 'enable' || $state === 'cancel' ) {
            if ( confirm( "Are you sure you want to " + $state + "?" ) ) {
                AuthManager.post( '/clients/update/' + $state + '/' + $id ).then( function( results ) {
                    $scope.load();
                } );
            }
        }
    };
    //invoices
    //set colours
    $scope.lineGraph = [ '#010180', '#05AB05', '#F50707', '#4BE0E8', '#FF8000' ];
    //set global graph options
    $scope.graphoptions = {
        scaleShowVerticalLines: false,
        animation: false
    };
} );
app.controller( 'addClients', function( $scope, $http, AuthManager, $uibModalInstance ) {
    $scope.data = {
        view_type: 'create',
        bad_client: 'false'
    };
    $scope.close = function( $complete ) {
        $uibModalInstance.close( $complete );
    };
    $scope.add = function() {
        AuthManager.get( '/clients/save', $scope.data ).then( function( results ) {
            if ( results.data == 'true' ) {
                $scope.close( 'true' );
            }
        } );
    };
} );
