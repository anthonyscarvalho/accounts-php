app.controller( 'viewCampaignEmails', function( $scope, $http, $routeParams, $rootScope, $uibModal, AuthManager ) {
    $scope.SetClientLog = true;
    $scope.sort = {
        sortType: 'date',
        sortReverse: true
    };
    $scope.parent = $routeParams.id;
    AuthManager.get( '/campaigns/view', {
        view_type: 'edit',
        id: $routeParams.id
    } ).then( function( results ) {
        $rootScope.title = results.data.name + " - Emails";
    } );
    $scope.load = function() {
        AuthManager.get( '/campaigns_emails/view', { view_type: 'view', campaigns: $routeParams.id } ).then( function( results ) {
            $scope.results = results.data;
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.preview = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/campaigns/emailsPreview.php',
            controller: 'previewCampaignEmails',
            resolve: {
                id: function() {
                    return $id;
                }
            }
        } );
    };
    $scope.reminder = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            templateUrl: 'application/views/campaigns/emailsReminder.php',
            controller: 'campaignsEmailsReminder',
            size: 'xl',
            resolve: {
                id: function() {
                    return $id;
                }
            }
        } );
        uibModalInstance.result.then( function( results ) {
            $scope.load();
        } );
    };
    $scope.load();
} );
app.controller( 'previewCampaignEmails', function( $scope, $http, AuthManager, $uibModal, $uibModalInstance, id ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.cancel = function() {
        $uibModalInstance.dismiss( 'cancel' );
    };
    AuthManager.get( '/campaigns_emails/view/', { view_type: 'edit', id: id } ).then( function( results ) {
        $scope.results = results.data;
    } ).catch( function( error ) {
        warningAlert( error );
    } );
    $scope.previewInvoice = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/templates/pdfPreview.php',
            controller: 'previewAdReminder',
            size: 'xl',
            resolve: {
                id: function() {
                    return $scope.results.id;
                }
            }
        } );
    };
} );
app.controller( 'campaignsEmailsReminder', function( $scope, $filter, $uibModalInstance, AuthManager, id ) {
    $scope.data = {
        clients: id,
        date: $filter( 'date' )( new Date(), 'yyyy-MM-dd' ),
        month: $filter( 'date' )( new Date(), 'MMMM' ),
        due_date: $filter( 'date' )( new Date(), 'yyyy-MM-dd' ),
        campaigns: id
    };
    AuthManager.get( '/campaigns_clients/view', {
        view_type: 'view',
        id: id
    } ).then( function( results ) {
        $scope.results = results.data;
    } );
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.load = function() {
        AuthManager.get( '/template_emails/edit/7' ).then( function( results ) {
            $scope.data.emailsubject = results.data.subject;
            $scope.data.emailbody = results.data.body;
        } );
    };
    $scope.send = function() {
        AuthManager.get( '/campaigns_emails/email/', $scope.data ).then( function( results ) {
            if ( results.data == 'true' ) {
                $scope.close();
            }
        } );
    };
    $scope.load();
} );
app.controller( 'previewAdReminder', function( $scope, $http, $sce, $uibModalInstance, id ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $http.post( '/app/campaigns_emails/preview', { view_type: 'edit', id: id }, { responseType: 'arraybuffer' } ).success( function( results ) {
        if ( results.byteLength > 0 ) {
            var file = new Blob( [ results ], { type: 'application/pdf' } );
            var fileURL = URL.createObjectURL( file );
            $scope.results = $sce.trustAsResourceUrl( fileURL );
        } else {
            warningAlert( 'no pdf to show!' );
            $scope.close();
        }
    } ).error( function( data, status ) {
        warningAlert( 'no pdf to show!' );
        $scope.close();
    } );
} );
