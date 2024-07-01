app.controller( 'searchEmailLog', function( $scope, $http, $routeParams, $rootScope, $uibModal, AuthManager ) {
    $scope.SetEmailLog = true;
    $scope.sort = {
        sortType: '',
        sortReverse: false
    };
    $scope.parent = $routeParams.id;
    $scope.load = function() {
        AuthManager.get( '/email_log/search/' + $scope.parent ).then( function( results ) {
            $scope.results = results.data;
            $rootScope.title = results.business + " - Email Log";
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.preview = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/email_log/preview.php',
            controller: 'previewEmailLog',
            size: 'xl',
            resolve: {
                id: function() {
                    return $id;
                }
            }
        } );
    };
    $scope.load();
} );
app.controller( 'previewEmailLog', function( $scope, $http, AuthManager, $uibModal, $uibModalInstance, id ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.cancel = function() {
        $uibModalInstance.dismiss( 'cancel' );
    };
    $scope.$on( '$routeChangeStart', function() {
        $uibModalInstance.close();
    } );
    AuthManager.get( '/email_log/preview/' + id ).then( function( results ) {
        $scope.results = results.data;
    } ).catch( function( error ) {
        warningAlert( error );
    } );
    $scope.previewInv = function( invoiceId ) {
        console.log( invoiceId );
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/templates/pdfPreview.php',
            controller: 'previewInvoice',
            size: 'xl',
            resolve: {
                invoice: function() {
                    return invoiceId;
                }
            }
        } );
    };
} );
