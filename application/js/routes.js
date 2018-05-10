app.config(['$routeProvider', function($routeProvider)
{
    var _baseView = "application/views";
    /* manager */
    $routeProvider.when('/login',
    {
        templateUrl: _baseView + '/manager/login.htm',
        controller: 'LoginCtrl'
    }).when('/logout',
    {
        controller: 'LogoutCtrl',
        template: ''
    }).when('/dashboard',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/manager/dashboard.htm',
        controller: 'DashCtrl'
    }).when('/menu',
    {
        resolve:
        {
            factory: checkRouting
        },
        templateUrl: _baseView + '/manager/menu.htm',
        controller: 'menuCtrl'
    });
    /* clients */
    $routeProvider.when('/clients/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/clients/view.htm',
        controller: 'viewClients'
    }).when('/clients/add',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/clients/clients.htm',
        controller: 'addClients'
    }).when('/clients/edit/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/clients/clients.htm',
        controller: 'editClients'
    });
    /* contacts */
    $routeProvider.when('/contacts/view/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/contacts/view.htm',
        controller: 'viewContacts'
    }).when('/contacts/add/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/contacts/contacts.htm',
        controller: 'addContacts'
    }).when('/contacts/edit/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/contacts/contacts.htm',
        controller: 'editContacts'
    });
    /* products */
    $routeProvider.when('/products/view/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/products/view.htm',
        controller: 'viewProducts'
    }).when('/products/add/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/products/products.htm',
        controller: 'addProducts'
    }).when('/products/edit/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/products/products.htm',
        controller: 'editProducts'
    });
    /* invoices */
    $routeProvider.when('/invoices/view/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/invoices/view.htm',
        controller: 'viewInvoices'
    }).when('/invoices/edit/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/invoices/invoices.htm',
        controller: 'editInvoices'
    }).when('/invoices/add/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/invoices/add.htm',
        controller: 'addInvoices'
    });
    /* attachments */
    $routeProvider.when('/attachments/view/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/attachments/view.htm',
        controller: 'viewAttachments'
    });
    /* quotations */
    $routeProvider.when('/quotations/view/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/quotations/view.htm',
        controller: 'viewQuotations'
    }).when('/quotations/edit/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/quotations/quotations.htm',
        controller: 'editQuotations'
    }).when('/quotations/add/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/quotations/quotations.htm',
        controller: 'addQuotations'
    });
    /* google ads */
    $routeProvider.when('/campaigns/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: 'application/views/campaigns/view.htm',
        controller: 'viewCampaigns'
    }).when('/campaigns/edit/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: 'application/views/campaigns/edit.htm',
        controller: 'editCampaigns'
    }).when('/campaigns/clients/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: 'application/views/campaigns/clients.htm',
        controller: 'viewCampaignClients'
    }).when('/campaigns/funds/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: 'application/views/campaigns/funds.htm',
        controller: 'viewCampaignFunds'
    }).when('/campaigns/emails/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: 'application/views/campaigns/emails.htm',
        controller: 'viewCampaignEmails'
    }).when('/campaigns/logs/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: 'application/views/campaigns/logs.htm',
        controller: 'viewCampaignLogs'
    }).when('/campaigns/statements/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: 'application/views/campaigns/statements.htm',
        controller: 'viewCampaignStatements'
    });
    /* transactions */
    $routeProvider.when('/transactions/view/:client',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/transactions/view.htm',
        controller: 'viewTransactions'
    }).when('/transactions/add/:client',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/transactions/transactions.htm',
        controller: 'addTransactions'
    }).when('/transactions/edit/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/transactions/transactions.htm',
        controller: 'editTransactions'
    });
    /* email log */
    $routeProvider.when('/email_log/view/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/email_log/view.htm',
        controller: 'searchEmailLog'
    });
    /* logs */
    $routeProvider.when('/logs/view/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/logs/view.htm',
        controller: 'searchLogs'
    });
    /* jobs */
    $routeProvider.when('/jobs/view/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/jobs/view.htm',
        controller: 'viewJobs'
    });
    /* scheduler */
    $routeProvider.when('/scheduler/view/:client?',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/scheduler/view.htm',
        controller: 'userScheduler'
    });
    /* statement */
    $routeProvider.when('/statements/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/statements/view.htm',
        controller: 'viewStatements'
    });
    /* reports */
    $routeProvider.when('/reports/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/reports/report.htm',
        controller: 'reportOverview'
    });
    /* settings */
    $routeProvider.when('/settings/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/settings/view.htm',
        controller: 'viewSettings'
    });
    /* categories */
    $routeProvider.when('/categories/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/categories/view.htm',
        controller: 'viewCategories'
    });
    /* companies */
    $routeProvider.when('/companies/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/companies/view.htm',
        controller: 'viewCompanies'
    }).when('/companies/edit/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/companies/edit.htm',
        controller: 'editCompanies'
    });
    /* templates - emails */
    $routeProvider.when('/template_emails/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/template_emails/view.htm',
        controller: 'viewTemplateEmails'
    });
    /* templates - quotes */
    $routeProvider.when('/template_quotations/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/template_quotations/view.htm',
        controller: 'viewTemplateQuotations'
    });
    /* templates - pdf's */
    $routeProvider.when('/template_attachments/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/template_attachments/view.htm',
        controller: 'viewTemplatesPdf'
    });
    /* users */
    $routeProvider.when('/users/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: remCookies
        },
        templateUrl: _baseView + '/users/view.htm',
        controller: 'viewUsers'
    });
    /* user roles */
    $routeProvider.when('/user_roles/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: remCookies
        },
        templateUrl: _baseView + '/user_roles/view.htm',
        controller: 'viewUserRoles'
    }).when('/user_roles/edit/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: remCookies
        },
        templateUrl: _baseView + '/user_roles/edit.htm',
        controller: 'editUserRoles'
    });
    /* expenditure */
    $routeProvider.when('/expenditure/view',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/expenditure/view.htm',
        controller: 'viewExpenditure'
    }).when('/expenditure/edit/:id',
    {
        resolve:
        {
            factory: checkRouting,
            url: setCookies
        },
        templateUrl: _baseView + '/expenditure/edit.htm',
        controller: 'editExpenditure'
    });
    /* default redirect url */
    $routeProvider.otherwise(
    {
        redirectTo: '/dashboard'
    });
}]);
var checkRouting = function(AuthManager, $location, $rootScope)
{
    var queryString = $location.search();
    if (queryString.page)
    {
        $rootScope.paginationPage = queryString.page;
    }
    else
    {
        $rootScope.paginationPage = 1;
        $location.search('page', null);
    }
    if (queryString.filter)
    {
        $rootScope.filter = queryString.filter;
    }
    else
    {
        $rootScope.filter = '';
    }
    if (queryString.records)
    {
        $rootScope.records = queryString.records;
    }
    else
    {
        $rootScope.records = '20';
    }
    if (queryString.recordSort)
    {
        $rootScope.recordSort = queryString.recordSort;
    }
    else
    {
        $rootScope.recordSort = '';
    }
    if (queryString.recordSortOrder)
    {
        $rootScope.recordSortOrder = queryString.recordSortOrder;
    }
    else
    {
        $rootScope.recordSortOrder = '';
    }
    if (queryString.recordSortCompany)
    {
        $rootScope.recordSortCompany = queryString.recordSortCompany;
    }
    else
    {
        $rootScope.recordSortCompany = '0';
    }
    if (queryString.searchPhrase)
    {
        $rootScope.searchPhrase = queryString.searchPhrase;
    }
    else
    {
        $rootScope.searchPhrase = '';
    }
    if (queryString.paidFilter)
    {
        $rootScope.paidFilter = queryString.paidFilter;
    }
    else
    {
        $rootScope.paidFilter = '';
    }
    if (queryString.searchYear)
    {
        $rootScope.searchYear = queryString.searchYear;
    }
    else
    {
        $rootScope.searchYear = '';
    }
    if (queryString.dueDate)
    {
        $rootScope.dueDate = queryString.dueDate;
    }
    else
    {
        $rootScope.dueDate = '';
    }
    AuthManager.set();
};

var setCookies = function($cookies, $location)
{
    var expireDate = new Date();
    expireDate.setDate(expireDate.getDate() + 7);
    if ($cookies.get('lasturl'))
    {
        $cookies.remove('lasturl');
    }
    return $cookies.put('lasturl', $location.path(),
    {
        expires: expireDate
    });
};

var remCookies = function($cookies)
{
    var expireDate = new Date();
    expireDate.setDate(expireDate.getDate() + 7);
    if ($cookies.get('lasturl'))
    {
        $cookies.remove('lasturl');
    }
    return $cookies.put('lasturl', '/',
    {
        expires: expireDate
    });
};
