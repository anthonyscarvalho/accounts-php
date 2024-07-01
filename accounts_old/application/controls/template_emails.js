app.controller( 'viewTemplateEmails', function( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "View All Email Templates";
    $scope.sort = {
        sortType: 'name',
        sortReverse: false
    };
    $scope.load = function() {
        AuthManager.get( '/template_emails/view', { view_type: 'view' } ).then( function( results ) {
            $scope.results = results.data;
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/template_emails/add.php',
            controller: 'addUsers',
            size: 'xl'
        } );
    };
    $scope.edit = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/template_emails/edit.php',
            controller: 'editTemplateEmails',
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
app.controller( 'editTemplateEmails', function( $scope, $http, $uibModalInstance, AuthManager, id ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.save = function() {
        AuthManager.get('/template_emails/save',$scope.results).then(function(results){}).catch(function(error){
            warningAlert(error);
        });
    };
    $scope.load = function() {
        AuthManager.get( '/template_emails/view', { view_type: 'edit', id: id } ).then( function( results ) {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.load();
} );
app.controller( 'addTemplateEmails', function( $scope, $uibModalInstance, $location, $http, AuthManager ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.cancel = function() {
        $uibModalInstance.dismiss( 'cancel' );
    };
    $scope.add = function() {
        $scope.submitted = true;
        insert( $http, $location, AuthManager, 'template_emails', $scope.data );
        $scope.submitted = false;
    };
} );
