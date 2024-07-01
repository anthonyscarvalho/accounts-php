app.controller( 'mainApp', function( $scope, $uibModal ) {
    $scope.totalItems = '20';
    $scope.pagnation = {
        maxsize: '8',
        dropdown: [
            {
                number: '',
                name: 'All'
        },
            {
                number: '10',
                name: '10'
        },
            {
                number: '20',
                name: '20'
        },
            {
                number: '30',
                name: '30'
        },
            {
                number: '40',
                name: '40'
        },
            {
                number: '50',
                name: '50'
        }
        ]
    };
    $scope.modalOpen = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/modalMenu.php',
            controller: 'menuModal',
            size: 'xl'
        } );
    };
} );
app.controller( 'menuModal', function( $scope, $rootScope, $uibModalInstance ) {
    $scope.management = [];
    if ( $rootScope.userAccess.clients == 'true' ) {
        $scope.management.push( { name: 'Clients', url: '/clients/view', order: 1 } );
    }
    if ( $rootScope.userAccess.contacts == 'true' ) {
        $scope.management.push( { name: 'Contacts', url: '/contacts/view', order: 2 } );
    }
    if ( $rootScope.userAccess.products == 'true' ) {
        $scope.management.push( { name: 'Products', url: '/products/view', order: 3 } );
    }

    if ( $rootScope.userAccess.invoices == 'true' ) {
        $scope.management.push( { name: 'Invoices', url: '/invoices/view', order: 4 } );
    }
    if ( $rootScope.userAccess.campaigns == 'true' ) {
        $scope.management.push({ name: 'Google Adwords', url: '/campaigns/view', order: 5 });
    }
    if ( $rootScope.userAccess.statements == 'true' ) {
        $scope.management.push( { name: 'Statements', url: '/statements/view', order: 6 } );
    }
    if ( $rootScope.userAccess.logs == 'true' ) {
        $scope.management.push( { name: 'Logs', url: '/logs/view', order: 7 } );
    }

    $scope.admin = [];
    if ( $rootScope.userAccess.categories == 'true' ) {
        $scope.admin.push( { name: 'Categories', url: '/categories/view', order: 1 } );
    }
    if ( $rootScope.userAccess.companies == 'true' ) {
        $scope.admin.push( { name: 'Companies', url: '/companies/view', order: 2 } );
    }
    if ( $rootScope.userAccess.template_attachments == 'true' ) {
        $scope.admin.push( { name: 'PDF Templates', url: '/template_attachments/view', order: 4 } );
    }
    if ( $rootScope.userAccess.template_emails == 'true' ) {
        $scope.admin.push( { name: 'Email Templates', url: '/template_emails/view', order: 3 } );
    }
    if ( $rootScope.userAccess.template_quotations == 'true' ) {
        $scope.admin.push( { name: 'Quotation Templates', url: '/template_quotations/view', order: 5 } );
    }
    if ( $rootScope.userAccess.users == 'true' ) {
        $scope.admin.push( { name: 'Users', url: '/users/view', order: 6 } );
    }
    if ( $rootScope.userAccess.user_roles == 'true' ) {
        $scope.admin.push( { name: 'User Roles', url: '/roles/view', order: 7 } );
    }
    $scope.close = function() {
        $uibModalInstance.close();
    };
} );
app.run( [ '$rootScope', '$uibModalStack', function( $rootScope, $uibModalStack )
    {
        // close the opened modal on location change.
        $rootScope.$on( '$locationChangeStart', function() {
            var openedModal = $uibModalStack.getTop();
            if ( openedModal ) {
                $uibModalStack.dismiss( openedModal.key );
            }
        } );
    }
    ] );
