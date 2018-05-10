app.controller('viewQuotations', function($scope, $http, $rootScope, $routeParams, $uibModal, AuthManager)
{
    $_title = "View Quotations";
    $scope.subnav = 'false';
    $rootScope.title = $_title;
    $scope.sort = {
        sortType: 'business',
        sortReverse: false
    };
    $scope.data = {
        view_type: "view",
        state: "pending"
    };
    if ($routeParams.client)
    {
        $scope.SetQuotations = true;
        $scope.parent = $routeParams.client;
        $scope.subnav = 'true';
    }
    $scope.load = function()
    {
        if ($routeParams.client)
        {
            $scope.data.client = $routeParams.client;
        }

        AuthManager.get('/quotations/view/', $scope.data).then(function(results)
        {
            $scope.results = results.data;
            if (results.business)
            {
                $rootScope.title = $_title + ' - ' + results.business;
            }
        });
    };
    $scope.reset = function()
    {
        $scope.searchKeyword = "";
    };
    $scope.update = function($id, $state)
    {
        if ($state === 'enable' || $state === 'cancel' || $state == 'delete')
        {
            if (confirm("Are you sure you want to " + $state + "?"))
            {
                AuthManager.get('/quotations/update/',
                {
                    view_type: $state,
                    id: $id
                }).then(function(results)
                {
                    if (results.data == 'true')
                    {
                        $scope.load();
                    }
                });
            }
        }
    };
    $scope.load('');
});
app.controller('editQuotations', function($scope, $rootScope, $http, $uibModal, $routeParams, AuthManager)
{
    $scope.save = function()
    {
        $message = '';
        if ($scope.data.clients == '')
        {
            $message += 'Please select a client<br>';
        }
        if ($scope.products.length == 0)
        {
            $message += 'Please add products to the quote<br>';
        }
        if ($message != '')
        {
            warningAlert($message);
        }
        else
        {
            $scope.data.products = JSON.stringify($scope.products);
            AuthManager.get('/quotations/save', $scope.data).then(function() {});
        }
    };
    AuthManager.get('/companies/view',
    {
        view_type: "search"
    }).then(function(results)
    {
        $scope.companies = results.data;
    });
    $scope.load = function()
    {
        AuthManager.get('/quotations/view',
        {
            view_type: 'edit',
            id: $routeParams.id
        }).then(function(results)
        {
            $scope.data = results.data;
            $scope.data.view_type = "save";
            $rootScope.title = 'Edit Quotation - #' + $scope.data.id;
            if (results.data.products)
            {
                $scope.products = JSON.parse(results.data.products);
            }
            return AuthManager.get('/quotations_emails/view',
            {
                view_type: 'view',
                quotations: $scope.data.id
            });
        }).then(function(data)
        {
            $scope.emails = data.data;
        });
    };
    $scope.addProducts = function()
    {
        console.log('test');
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/quotations/add_items.htm',
            controller: 'addQuotationItems'
        });
        uibModalInstance.result.then(function(results)
        {
            $scope.products.push(results);
        });
    };
    $scope.deleteProducts = function($id)
    {
        $scope.products.splice($id, 1);
    };
    $scope.sendInvoice = function($quote)
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/quotations/email.htm',
            controller: 'emailQuote',
            resolve:
            {
                quote: function()
                {
                    return $quote;
                },
                client: function()
                {
                    return $scope.data.clientName;
                }
            }
        });
        uibModalInstance.result.then(function()
        {
            $scope.load();
        });
    };
    $scope.load();
});
app.controller('addQuotations', function($scope, $rootScope, $routeParams, AuthManager, $uibModal, $filter)
{
    $scope.client = $routeParams.client;
    $scope.add = true;
    $scope.products = [];
    $scope.data = {
        sendMail: 'true',
        clients: $routeParams.client,
        companies: '1',
        products: [],
        content: '',
        view_type: 'create'
    };
    if (!$routeParams.client)
    {
        $scope.addAll = true;
        $scope.data.clients = '';
        $clients = AuthManager.get('/clients/view',
        {
            view_type: 'view',
            state: 'false'
        });
    }
    else
    {
        $scope.addAll = false;
        $clients = AuthManager.get('/clients/view',
        {
            view_type: 'edit',
            id: $routeParams.client
        });
    }

    $clients.then(function(results)
    {
        $scope.clients = results.data;

        return AuthManager.get('/companies/view',
        {
            view_type: "search"
        });
    }).then(function(results)
    {
        $scope.companies = results.data;
        return AuthManager.get('/template_quotations/retrieve',
        {
            view_type: 'view'
        });
    }).then(function(results)
    {
        $scope.templates = results.data
    });
    $scope.save = function()
    {
        $message = '';
        if ($scope.data.clients == '')
        {
            $message += 'Please select a client<br>';
        }
        if ($scope.products.length == 0)
        {
            $message += 'Please add products to the quote<br>';
        }
        if ($message != '')
        {
            warningAlert($message);
        }
        else
        {
            $scope.data.products = JSON.stringify($scope.products);
            AuthManager.get('/quotations/save', $scope.data).then(function(results)
            {
                if (results.data == 'true')
                {
                    window.history.back();
                }
            });
        }
    };

    $scope.loadTemplate = function($id)
    {
        AuthManager.get('/template_quotations/view',
        {
            view_type: 'single',
            id: $id
        }).then(function(results)
        {
            $scope.data.scope = results.data.scope;
            $scope.data.content = results.data.content;
            $scope.data.signature = results.data.signature;
            $scope.data.annexure = results.data.annexure;
        });
    };
    $scope.addProducts = function()
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/quotations/add_items.htm',
            controller: 'addQuotationItems'
        });
        uibModalInstance.result.then(function(results)
        {
            $scope.products.push(results);
        });
    };
    $scope.deleteProducts = function($id)
    {
        $scope.products.splice($id, 1);
    };
});
app.controller('addQuotationItems', function($scope, AuthManager, $uibModalInstance, $filter)
{
    $scope.add = true;
    var _date = new Date();
    $scope.results = {
        canceled: "false",
        date: $filter('date')(_date, 'yyyy-MM-dd'),
        renewable: 'a'
    };
    $scope.renewable = [
    {
        "name": "Annual",
        "id": "a"
    },
    {
        "name": "Monthly",
        "id": "m"
    },
    {
        "name": "Once Off",
        "id": "o"
    }];
    $scope.close = function()
    {
        $scope.results.categoryName = $('option:selected', '#categories').attr("data-name");
        $uibModalInstance.close($scope.results);
    };
    $scope.load = function()
    {
        AuthManager.get('/categories/view',
        {
            view_type: "retrieve",
            "link": 'invoice'
        }).then(function(data)
        {
            $scope.categories = data.data;
        });
    };
    $scope.updatePrice = function()
    {
        $scope.results.price = $('option:selected', '#categories').attr("data-price");
    };
    $scope.load();
});
app.controller('emailQuote', function($scope, $http, $rootScope, AuthManager, $uibModalInstance, quote, client)
{
    $scope.quoteNumber = quote;
    $scope.data = {
        view_type: 'email',
        display: 'email',
        quote: quote
    };
    AuthManager.get('/app/template_emails/retrieve',
    {
        template: 'quotation'
    }).then(function(data)
    {
        $scope.data.emailsubject = data.data.subject + ' - ' + client;
        $scope.data.emailbody = data.data.body;
        $scope.data.email_type = 'quote';
    });
    $scope.sendEmail = function()
    {
        AuthManager.get('/quotations/email_quote', $scope.data).then(function(results)
        {
            if (results.data == "true")
            {
                $scope.close();
            }
        });
    };
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
});
