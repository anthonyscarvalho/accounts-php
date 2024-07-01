app.controller( 'editInvoicesItem', function( $scope, $http, $uibModalInstance, itemId, AuthManager ) {
    function load() {
        AuthManager.get( '/invoices_items/view', { view_type: 'edit', id: itemId } ).then( function( results ) {
            $scope.data = results.data;
            $scope.data.view_type = 'save';
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    }
    $scope.save = function() {
        AuthManager.get( '/invoices_items/save', $scope.data ).then( function( results ) {
            if ( results.data == "true" ) {
                $scope.close();
            }
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.close = function() {
        $uibModalInstance.close();
    };
    load();
} );
