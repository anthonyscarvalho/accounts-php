app.controller( 'viewTemplateQuotations', function( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "View Attachment Templates";
    $scope.sort = {
        sortType: 'name',
        sortReverse: false
    };
    $companies = AuthManager.get( '/companies/view', { view_type: "search" } );
    $scope.load = function() {
        $companies.then( function( results ) {
            $scope.companies = results.data;
            return AuthManager.get( '/template_quotations/view/', { view_type: 'view' } );
        } ).then( function( results ) {
            $scope.results = results.data;
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/template_quotations/add.php',
            controller: 'addTemplatesQuotations',
            size: 'xl'
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.edit = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/template_quotations/edit.php',
            controller: 'editTemplatesQuotations',
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
app.controller( 'editTemplatesQuotations', function( $scope, $http, $uibModalInstance, AuthManager, id ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.save = function() {
        AuthManager.get( '/template_quotations/save', $scope.data );
    };
    AuthManager.get( '/companies/view', { view_type: "search" } ).then( function( results ) {
        $scope.companies = results.data;
        return AuthManager.get( '/template_quotations/view', { view_type: 'edit', id: id } );
    } ).then( function( results ) {
        $scope.data = results.data;
        $scope.data.view_type = 'save';
    } ).catch( function( error ) {
        warningAlert( error );
    } );
} );
app.controller( 'addTemplatesQuotations', function( $scope, $uibModalInstance, $http, AuthManager ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    AuthManager.get( '/companies/view', { view_type: 'search' } ).then( function( results ) {
        $scope.companies = results.data;
    } ).catch( function( error ) {
        warningAlert( error );
    } );
    $scope.data = {
        view_type: 'create',
        companies: '1'
    };
    $scope.add = function() {
        $scope.submitted = true;
        AuthManager.get( '/template_quotations/save', $scope.data ).then( function( results ) {
            if ( results.data == 'true' ) {
                $scope.close();
            }
        } ).catch( function( error ) {
            warningAlert( error );
        } );
        $scope.submitted = false;
    };
} );
