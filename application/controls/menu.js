app.controller( 'menuCtrl', function ( $scope, $rootScope, AuthManager )
{
    $scope.management = [];
    $rootScope.title = "Accounts System";
    $scope.$watch( '$root.userAccess', function ()
    {
        if ( $rootScope.userAccess.clients == 'true' )
        {
            $scope.management.push(
            {
                name: 'Clients',
                url: '/clients/view',
                order: 1,
                icon: 'user-circle',
            } );
        }
        if ( $rootScope.userAccess.contacts == 'true' )
        {
            $scope.management.push(
            {
                name: 'Contacts',
                url: '/contacts/view',
                order: 2,
                icon: 'users',
            } );
        }
        if ( $rootScope.userAccess.products == 'true' )
        {
            $scope.management.push(
            {
                name: 'Products',
                url: '/products/view',
                order: 3,
                icon: 'shopping-cart',
            } );
        }
        if ( $rootScope.userAccess.invoices == 'true' )
        {
            $scope.management.push(
            {
                name: 'Invoices',
                url: '/invoices/view',
                order: 4,
                icon: 'file-o',
            } );
        }
        if ( $rootScope.userAccess.quotes == 'true' )
        {
            $scope.management.push(
            {
                name: 'Quotes',
                url: '/quotations/view',
                order: 5,
                icon: 'file-text',
            } );
        }
        if ( $rootScope.userAccess.campaigns == 'true' )
        {
            $scope.management.push(
            {
                name: 'Google Adwords',
                url: '/campaigns/view',
                order: 6,
                icon: 'question',
            } );
        }
        if ( $rootScope.userAccess.statements == 'true' )
        {
            $scope.management.push(
            {
                name: 'Statements',
                url: '/statements/view',
                order: 7,
                icon: 'file-text-o',
            } );
        }
        if ( $rootScope.userAccess.logs == 'true' )
        {
            $scope.management.push(
            {
                name: 'Logs',
                url: '/logs/view',
                order: 8,
                icon: 'list-ol',
            } );
        }
        if ( $rootScope.userAccess.jobs == 'true' )
        {
            $scope.management.push(
            {
                name: 'Jobs',
                url: '/jobs/view',
                order: 9,
                icon: 'vcard',
            } );
        }
        if ( $rootScope.userAccess.tickets == 'true' )
        {
            $scope.management.push(
            {
                name: 'Tickets',
                url: '/tickets/view',
                order: 10,
                icon: 'ticket',
            } );
        }
         $scope.management.push(
        {
            name: 'Scheduler',
            url: '/scheduler/view',
            order: 11,
            icon: 'calendar',
        } );
        $scope.admin = [];
        if ( $rootScope.userAccess.categories == 'true' )
        {
            $scope.admin.push(
            {
                name: 'Categories',
                url: '/categories/view',
                order: 1,
                icon: 'check-square-o',
            } );
        }
        if ( $rootScope.userAccess.companies == 'true' )
        {
            $scope.admin.push(
            {
                name: 'Companies',
                url: '/companies/view',
                order: 2,
                icon: 'building',
            } );
        }
        if ( $rootScope.userAccess.template_attachments == 'true' )
        {
            $scope.admin.push(
            {
                name: 'PDF Templates',
                url: '/template_attachments/view',
                order: 4,
                icon: 'paperclip',
            } );
        }
        if ( $rootScope.userAccess.settings == 'true' )
        {
            $scope.admin.push(
            {
                name: 'Settings',
                url: '/settings/view',
                order: 6,
                icon: 'cogs',
            } );
        }
        if ( $rootScope.userAccess.template_emails == 'true' )
        {
            $scope.admin.push(
            {
                name: 'Email Templates',
                url: '/template_emails/view',
                order: 3,
                icon: 'envelope',
            } );
        }
        if ( $rootScope.userAccess.template_quotations == 'true' )
        {
            $scope.admin.push(
            {
                name: 'Quotation Templates',
                url: '/template_quotations/view',
                order: 5,
                icon: 'file-text',
            } );
        }
        if ( $rootScope.userAccess.users == 'true' )
        {
            $scope.admin.push(
            {
                name: 'Users',
                url: '/users/view',
                order: 6,
                icon: 'users',
            } );
        }
        if ( $rootScope.userAccess.user_roles == 'true' )
        {
            $scope.admin.push(
            {
                name: 'User Roles',
                url: '/roles/view',
                order: 7,
                icon: 'question',
            } );
        }
    }, true );
} );
