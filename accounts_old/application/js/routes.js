app.config( [ '$routeProvider', function ( $routeProvider )
    {
        var _baseView = "application/views";
        /* manager */
        $routeProvider.when( '/login',
        {
            templateUrl: _baseView + '/manager/login.php',
            controller: 'LoginCtrl'
        } ).
        when( '/logout',
        {
            resolve:
            {
                factory: checkRouting
            },
            controller: 'LogoutCtrl',
            template: ''
        } ).
        when( '/dashboard',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/manager/dashboard.php',
            controller: 'DashCtrl'
        } );
        /* clients */
        $routeProvider.when( '/clients/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/clients/view.php',
            controller: 'viewClients'
        } ).
        when( '/clients/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/clients/edit.php',
            controller: 'editClients'
        } );
        /* contacts */
        $routeProvider.when( '/contacts/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/contacts/view.php',
            controller: 'viewContacts'
        } ).
        when( '/contacts/search/:client',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/contacts/search.php',
            controller: 'searchContacts'
        } ).
        when( '/contacts/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/contacts/edit.php',
            controller: 'editContacts'
        } );
        /* products */
        $routeProvider.when( '/products/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/products/view.php',
            controller: 'viewProducts'
        } ).
        when( '/products/search/:clients',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/products/search.php',
            controller: 'searchProducts'
        } ).
        when( '/products/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/products/edit.php',
            controller: 'editProducts'
        } );
        /* invoices */
        $routeProvider.when( '/invoices/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/invoices/view.php',
            controller: 'viewInvoices'
        } ).
        when( '/invoices/search/:clients',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/invoices/search.php',
            controller: 'searchInvoices'
        } ).
        when( '/invoices/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/invoices/edit.php',
            controller: 'editInvoices'
        } );
        /* attachments */
        $routeProvider.when( '/attachments/search/:clients',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/attachments/search.php',
            controller: 'searchAttachments'
        } );
        /* quotations */
        $routeProvider.when( '/quotations/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/quotations/view.php',
            controller: 'viewQuotations'
        } ).
        when( '/quotations/search/:clients',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/quotations/search.php',
            controller: 'searchQuotations'
        } ).
        when( '/quotations/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/quotations/edit.php',
            controller: 'editQuotations'
        } );
        /* google ads */
        $routeProvider.when( '/campaigns/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: 'application/views/campaigns/view.php',
            controller: 'viewCampaigns'
        } ).
        when( '/campaigns/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: 'application/views/campaigns/edit.php',
            controller: 'editCampaigns'
        } ).
        when( '/campaigns/clients/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: 'application/views/campaigns/clients.php',
            controller: 'viewCampaignClients'
        } ).
        when( '/campaigns/funds/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: 'application/views/campaigns/funds.php',
            controller: 'viewCampaignFunds'
        } ).
        when( '/campaigns/emails/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: 'application/views/campaigns/emails.php',
            controller: 'viewCampaignEmails'
        } ).
        when( '/campaigns/logs/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: 'application/views/campaigns/logs.php',
            controller: 'viewCampaignLogs'
        } ).
        when( '/campaigns/statements/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: 'application/views/campaigns/statements.php',
            controller: 'viewCampaignStatements'
        } );
        /* transactions */
        $routeProvider.when( '/transactions/search/:client',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/transactions/search.php',
            controller: 'searchTransactions'
        } ).
        when( '/transactions/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/transactions/edit.php',
            controller: 'editTransactions'
        } );
        /* email log */
        $routeProvider.when( '/email_log/search/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/email_log/search.php',
            controller: 'searchEmailLog'
        } );
        /* logs */
        $routeProvider.when( '/logs/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/logs/view.php',
            controller: 'viewLogs'
        } ).
        when( '/logs/search/:client',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/logs/search.php',
            controller: 'searchLogs'
        } );
        /* statement */
        $routeProvider.when( '/statements/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/statements/view.php',
            controller: 'viewStatements'
        } );
        /* reports */
        $routeProvider.when( '/reports/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/reports/report.php',
            controller: 'reportOverview'
        } );
        /* categories */
        $routeProvider.when( '/categories/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/categories/view.php',
            controller: 'viewCategories'
        } ).
        when( '/categories/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/categories/edit.php',
            controller: 'editCategories'
        } );
        /* companies */
        $routeProvider.when( '/companies/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/companies/view.php',
            controller: 'viewCompanies'
        } ).
        when( '/companies/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/companies/edit.php',
            controller: 'editCompanies'
        } );
        /* templates - emails */
        $routeProvider.when( '/template_emails/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/template_emails/view.php',
            controller: 'viewTemplateEmails'
        } );
        /* templates - quotes */
        $routeProvider.when( '/template_quotations/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/template_quotations/view.php',
            controller: 'viewTemplateQuotations'
        } );
        /* templates - pdf's */
        $routeProvider.when( '/template_attachments/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/template_attachments/view.php',
            controller: 'viewTemplatesPdf'
        } );
        /* users */
        $routeProvider.when( '/users/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/users/view.php',
            controller: 'viewUsers'
        } ).
        when( '/users/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/users/edit.php',
            controller: 'editUsers'
        } );
        /* user roles */
        $routeProvider.when( '/user_roles/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/user_roles/view.php',
            controller: 'viewUserRoles'
        } ).
        when( '/user_roles/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/user_roles/edit.php',
            controller: 'editUserRoles'
        } );
        /* expenditure */
        $routeProvider.when( '/expenditure/view',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/expenditure/view.php',
            controller: 'viewExpenditure'
        } ).
        when( '/expenditure/edit/:id',
        {
            resolve:
            {
                factory: checkRouting
            },
            templateUrl: _baseView + '/expenditure/edit.php',
            controller: 'editExpenditure'
        } );
        /* default redirect url */
        $routeProvider.otherwise(
        {
            redirectTo: '/dashboard'
        } );
} ] );
var checkRouting = function ( AuthManager )
{
    AuthManager.set();
};
