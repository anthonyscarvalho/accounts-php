app.controller( 'viewStatements', function( $scope, $http, $rootScope, AuthManager, $uibModal ) {
    $rootScope.title = "Generate Statement";
    $scope.sort = {
        sortType: '',
        sortReverse: false
    };
    AuthManager.get( '/clients/view/', {
        view_type: 'view',
        state: 'false'
    } ).then( function( results ) {
        $scope.clients = results.data;
    } );
    $scope.load = function() {
        $scope.results = null;
        if ( $scope.data.clients != '' ) {
            AuthManager.get( '/statements/view/', $scope.data ).then( function( results ) {
                $scope.data.statements = results.data;
            } );
        }
    };
    $scope.emailStatement = function( $client ) {
        if ( $scope.data.clients != '' ) {
            var uibModalInstance = $uibModal.open( {
                animation: false,
                templateUrl: 'application/views/statements/email.php',
                controller: 'emailStatement',
                size: 'xl',
                resolve: {
                    client: function() {
                        return $scope.data.clients;
                    },
                    start: function() {
                        return $scope.data.startDate;
                    },
                    end: function() {
                        return $scope.data.endDate;
                    }
                }
            } );
        } else {
            warningAlert( 'please select client first' );
        }
    };
    $scope.previewStatement = function() {
        if ( $scope.data.clients != '' ) {
            var uibModalInstance = $uibModal.open( {
                animation: false,
                templateUrl: 'application/templates/pdfPreview.php',
                controller: 'previewStatement',
                size: 'xl',
                resolve: {
                    client: function() {
                        return $scope.data.clients;
                    },
                    start: function() {
                        return $scope.data.startDate;
                    },
                    end: function() {
                        return $scope.data.endDate;
                    }
                }
            } );
        } else {
            warningAlert( 'please select a client first' );
        }
    };
    $scope.clear = function() {
        $scope.data = {
            clients: '',
            startDate: '',
            endDate: '',
            view_type: 'view',
            statements: ''
        };
    };
    $scope.clear();
} );
app.controller( 'emailStatement', function( $scope, $http, AuthManager, $uibModalInstance, client, start, end ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.start = start;
    $scope.end = end;
    AuthManager.get( '/template_emails/view', { view_type: 'edit', id: 8 } ).then( function( results ) {
        $scope.emailsubject = results.data.subject;
        $scope.emailbody = results.data.body;
    } );
    $scope.sendMail = function() {
        AuthManager.get( '/statements/preview/', { view_type: 'view', display: 'email', startDate: start, endDate: end, clients: client, emailbody: $scope.emailbody, emailsubject: $scope.emailsubject } ).then( function( results ) {
            if ( results.data === "true" ) {
                // successAlert('removed');
                $scope.close();
            }
        } ).catch(function(error){
            warningAlert(error);
        });
    };
} );
app.controller( 'previewStatement', function( $scope, $http, $sce, $uibModalInstance, client, start, end ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $http.post( '/app/statements/preview', {
        view_type: 'view',
        display: 'print',
        startDate: start,
        endDate: end,
        clients: client
    }, {
        responseType: 'arraybuffer'
    } ).success( function( results ) {
        var file = new Blob( [ results ], {
            type: 'application/pdf'
        } );
        var fileURL = URL.createObjectURL( file );
        $scope.results = $sce.trustAsResourceUrl( fileURL );
    } );
} );
