app.controller( 'searchAttachments', function( $scope, $http, $routeParams, $rootScope, $uibModal, AuthManager ) {
    $scope.SetAttachments = true;
    $scope.sort = {
        sortType: 'date',
        sortReverse: true
    };
    $scope.parent = $routeParams.clients;
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/attachments/add.php',
            controller: 'addAttachments',
            resolve: {
                client: function() {
                    return $scope.parent;
                }
            }
        } );
        uibModalInstance.result.then( function( results ) {
            $scope.load();
        } );
    };
    $scope.load = function() {
        AuthManager.get( '/attachments/view', { id: $scope.parent, view_type: 'view' } ).then( function( results ) {
            $scope.results = results.data;
            $rootScope.title = results.business + " - Email Log";
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.previewAtt = function( id ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/templates/pdfPreview.php',
            controller: 'previewAttachment',
            resolve: {
                id: function() {
                    return id;
                }
            }
        } );
    };
    $scope.load();
} );
app.controller( 'addAttachments', function( $scope, $http, $filter, AuthManager, $uibModalInstance, client ) {
    var _date = new Date();
    $scope.data = {
        date: $filter( 'date' )( _date, 'yyyy-MM-dd' )
    };
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.add = function() {
        var tmpFfile = document.getElementById( 'file' ).files[ 0 ];
        var formData = new FormData();
        formData.append( 'file', tmpFfile );
        $scope.data.content = formData;
        $http( {
            url: '/app/attachments/add',
            method: "POST",
            data: formData,
            headers: { 'Content-Type': undefined }
        } ).success( function( results ) {
            if ( results.data == 'true' ) {
                AuthManager.get( '/attachments/save', { id: results.id, date: $scope.date, clients: client, description: $scope.description, view_type: 'create' } ).then( function( results ) {
                    if ( results.data == 'true' ) {
                        $scope.close();
                    }
                } );
            }
        } );
    };
} );
app.controller( 'previewAttachment', function( $scope, $http, $sce, $uibModalInstance, id ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $http.post( '/app/attachments/preview', { view_type: 'edit', id: id }, { responseType: 'arraybuffer' } ).success( function( results ) {
        if ( results.byteLength > 0 ) {
            var file = new Blob( [ results ], { type: 'application/pdf' } );
            var fileURL = URL.createObjectURL( file );
            $scope.results = $sce.trustAsResourceUrl( fileURL );
        } else {
            warningAlert( 'no pdf to show!' );
            $scope.close();
        }
    } );
} );
