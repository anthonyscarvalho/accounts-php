app.controller( 'viewExpenditure', function( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "View All Expenditure";
    $scope.sort = {
        sortType: 'date',
        sortReverse: true
    };
    AuthManager.get( '/manager/getActiveYear' ).then( function( results ) {
        $scope.exp = {
            year: results.data,
            companies: '0',
            view_type: 'view'
        };
        return AuthManager.get( '/companies/view', { view_type: "search" } );
    } ).then( function( results ) {
        $scope.companies = results.data;
        $scope.load();
    } ).catch( function( error ) {
        warningAlert( error );
    } );
    $scope.load = function() {
        AuthManager.get( '/expenditure/view', $scope.exp ).then( function( results ) {
            $scope.results = results.data;
            return AuthManager.get( '/expenditure/categories', $scope.exp );
        } ).then( function( results ) {
            $scope.categories = results.data
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.delete = function( $id ) {
        if ( confirm( "Are you sure you want to delete?" ) ) {
            deleteData( $http, AuthManager, 'expenditure', $id );
            $scope.load();
        }
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/expenditure/add.php',
            controller: 'addExpenditure',
            size: 'xl'
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.edit = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/expenditure/edit.php',
            controller: 'editExpenditure',
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
} );
app.controller( 'editExpenditure', function( $scope, $http, $uibModalInstance, AuthManager, id ) {
    AuthManager.get( '/categories/retrieve/expense' ).then( function( results ) {
        $scope.categories = results.data;
        return AuthManager.get( '/companies/view', { view_type: "search" } );
    } ).then( function( results ) {
        $scope.companies = results.data;
        return AuthManager.get( '/expenditure/view', { view_type: 'edit', id: id } );
    } ).then( function( results ) {
        $scope.results = results.data;
        $scope.results.view_type = "save";
    } ).catch( function( error ) {
        warningAlert( error );
    } );
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.save = function() {
        AuthManager.get( '/expenditure/save', $scope.results ).then( function( results ) {
            if ( results.data == 'true' ) {
                $scope.close();
            }
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
} );
app.controller( 'addExpenditure', function( $scope, $uibModalInstance, $location, $http, AuthManager ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.data = {
        companies: '1',
        type: 'Fixed Cost',
        view_type: 'create'
    };
    $scope.recentRecords = [];
    AuthManager.get( '/categories/retrieve/expense' ).then( function( results ) {
        $scope.categories = results.data;
        return AuthManager.get( '/companies/view', { view_type: "search" } );
    } ).then( function( results ) {
        $scope.companies = results.data;
    } ).catch( function( error ) {
        warningAlert( error );
    } );
    $scope.add = function() {
        AuthManager.get( '/expenditure/save', $scope.data ).then( function( results ) {
            if ( results.data == 'true' ) {
                $scope.data.amount = null;
                $scope.data.description = '';
                $scope.recentRecords.push( results.recent );
            }
        } ).catch( function( error ) {
            warningAlert( error );
        } );
        $( "#amount" ).focus();
    };
} );
