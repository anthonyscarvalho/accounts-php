app.controller('viewProducts', function($scope, $location, $rootScope, $filter, $routeParams, AuthManager)
{
    $_title = "View Products";
    $rootScope.title = $_title;
    $scope.parent = '';
    $scope.records = '';
    $scope.due = true;
    $scope.sort = {
        sortType: (($rootScope.recordSort) ? $rootScope.recordSort : (($routeParams.client) ? 'date' : 'clientName')),
        sortOrder: ( ( $rootScope.recordSortOrder ) ? $rootScope.recordSortOrder : 'DESC' ),
        company: $rootScope.recordSortCompany,
        search: $rootScope.searchPhrase
    };
    var _date = new Date();
    _date.setMonth(_date.getMonth() + 1);
    $scope.date = $filter('date')(_date, 'yyyy-MM-dd');
    $scope.data = {
        state: (($rootScope.filter) ? $rootScope.filter : 'false'),
        date: (($rootScope.dueDate) ? $rootScope.dueDate : $filter('date')(_date, 'yyyy-MM-dd')),
        page: (($rootScope.paginationPage) ? $rootScope.paginationPage : '1'),
        records: (($rootScope.records) ? $rootScope.records : '20'),
        sort: $scope.sort.sortType,
        sortOrder: $scope.sort.sortOrder,
        sortCompany: $scope.sort.company,
        sortSearch: $scope.sort.search
    };
    if ($routeParams.client)
    {
        $scope.due = false;
        $scope.SetProducts = true;
        $scope.parent = $routeParams.client;
        $scope.subnav = 'true';
    }
    if ($rootScope.filter == 'due')
    {
        $scope.pagnation.totalItems = '';
    }
    else
    {
        $scope.dueChangeHandler('');
        if ($scope.pagnation.totalItems == '')
        {
            $scope.pagnation.totalItems = $rootScope.records;
        }
    }
    $scope.load = function()
    {
        if ($routeParams.client)
        {
            $scope.data.client = $routeParams.client;
        }
        if ($scope.data.state == 'due')
        {
            $scope.data.view_type = 'due';
        }
        else
        {
            $scope.data.view_type = 'view';
        }
        AuthManager.get('/products/view/', $scope.data).then(function(results)
        {

            $scope.results = results.data;

            $scope.totalRecords = results.records;

            if ($scope.sort.search == "")
            {
                if (results.business)
                {
                    $rootScope.title = $_title + ' - ' + results.business;
                }
                else
                {
                    $rootScope.titla = $_title;
                }
            }
            else
            {
                $rootScope.title = "Search Products - " + $scope.sort.search;
            }
        });
    };

    $scope.reset = function()
    {
        $scope.searchChangeHandler('');
    };
    $scope.update = function($id, $state)
    {
        if ($state === 'enable' || $state === 'cancel' || $state == 'delete')
        {
            if (confirm("Are you sure you want to " + $state + "?"))
            {
                AuthManager.get('/products/update/',
                {
                    state: $state,
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
    $scope.addHandler = function()
    {
        AuthManager.redirect('/products/add/' + (($scope.parent) ? $scope.parent : ''));
    };
    $scope.load();
});
app.controller('editProducts', function($rootScope, $scope, $http, $routeParams, AuthManager)
{
    $scope.sort = {
        sortType: 'date',
        sortReverse: false
    };
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.save = function()
    {
        AuthManager.get('/products/save', $scope.results).then(function(results)
        {
            $scope.load();
        });
    };
    $scope.load = function()
    {
        AuthManager.get('/products/view/',
        {
            view_type: "edit",
            id: $routeParams.id
        }).then(function(results)
        {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
            $scope.parent = results.data.id;
            $rootScope.title = 'Edit Product - #' + $scope.results.id;
            return AuthManager.get('/companies/view',
            {
                view_type: "search"
            });
        }).then(function(results)
        {
            $scope.companies = results.data;
            return AuthManager.get('/categories/view',
            {
                view_type: "edit",
                "id": $scope.results.categories
            });
        }).then(function(results)
        {
            $scope.category = results.data;
            return AuthManager.get('/invoices_items/view',
            {
                view_type: "search",
                id: $scope.results.id
            });
        }).then(function(results)
        {
            $scope.invoices_items = results.data;
        }).catch(function(error)
        {
            warningAlert(error);
        });
    };
    $scope.cleanup = function()
    {
        $scope.results.description = cleanupUrl($scope.results.description);
    };
    $scope.disable = function()
    {
        if ($rootScope.userRoles !== '2')
        {
            return true;
        }
        else
        {
            return false;
        }
    };
    $scope.load();
});
app.controller('addProducts', function($scope, $rootScope, AuthManager, $http, $filter, $routeParams)
{
    $scope.add = true;
    $rootScope.title = 'Add Product';
    if (!$routeParams.client)
    {
        $scope.addAll = true;
        $clients = AuthManager.get('/clients/view',
        {
            view_type: 'view',
            state: 'false'
        });
    }
    else
    {
        $scope.addAll = false;
        $clients = AuthManager.resolve();
    }
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    var _date = new Date();
    $scope.results = {
        canceled: "false",
        clients: $routeParams.client,
        date: $filter('date')(_date, 'yyyy-MM-dd'),
        view_type: 'create',
        renewable: 'a'
    };

    $clients.then(function(results)
    {
        if (!$routeParams.client)
        {
            $scope.clients = results.data;
        }
        return AuthManager.get('/categories/view',
        {
            view_type: "retrieve",
            "link": 'invoice'
        });
    }).then(function(results)
    {
        $scope.categories = results.data;
        return AuthManager.get('/companies/view',
        {
            view_type: "search"
        });
    }).then(function(results)
    {
        $scope.companies = results.data;
    });
    $scope.save = function()
    {
        $message = '';
        if ((!$scope.results.clients) || ($scope.results.clients == ''))
        {
            $message += 'select a client!<br>';
        }
        if ((!$scope.results.categories) || ($scope.results.categories == ''))
        {
            $message += 'select item to add!<br>';
        }
        if ((!$scope.results.companies) || ($scope.results.companies == ''))
        {
            $message += 'select a company!<br>';
        }
        if ($message != '')
        {
            warningAlert($message);
        }
        else
        {
            AuthManager.get('/products/save', $scope.results).then(function(results)
            {
                if (results.data == "true")
                {
                    AuthManager.redirect('/products/view/' + $scope.results.clients);
                }
            });
        }
    };
    $scope.updatePrice = function()
    {
        $scope.results.price = $('option:selected', '#categories').attr("data-price");
    };
    $scope.cleanup = function()
    {
        $scope.results.description = cleanupUrl($scope.results.description);
    };
    $scope.disable = function()
    {
        return false;
    };
});

function cleanupUrl(string)
{
    $string = string;
    return $string.toString().replace(/http\:\/\//g, '').replace(/https\:\/\//g, '').replace(/www\./g, '');
}
