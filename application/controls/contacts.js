app.controller('viewContacts', function($scope, $http, $rootScope, $routeParams, $location, AuthManager)
{
    $_title = "View Contacts";
    $scope.subnav = 'false';
    $rootScope.title = $_title;
    $scope.parent = '';
    $scope.sort = {
        sortType: (($rootScope.recordSort) ? $rootScope.recordSort : (($routeParams.client) ? 'name' : 'business')),
        sortOrder: ( ( $rootScope.recordSortOrder ) ? $rootScope.recordSortOrder : 'ASC' ),
        search: $rootScope.searchPhrase
    };
    $scope.data = {
        view_type: "view",
        state: (($rootScope.filter) ? $rootScope.filter : 'false'),
        page: (($rootScope.paginationPage) ? $rootScope.paginationPage : '1'),
        records: (($rootScope.records) ? $rootScope.records : '20'),
        sort: $scope.sort.sortType,
        sortOrder: $scope.sort.sortOrder,
        sortSearch: $scope.sort.search
    };
    if ($routeParams.client)
    {
        $scope.SetContacts = true;
        $scope.parent = $routeParams.client;
        $scope.subnav = 'true';
        $scope.addAll = 'true';
    }
    $scope.load = function()
    {
        if ($routeParams.client)
        {
            $scope.data.client = $routeParams.client;
        }
        AuthManager.get('/contacts/view/', $scope.data).then(function(results)
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
                AuthManager.get('/contacts/update/',
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
    $scope.pageChangeHandler = function(page)
    {
        updatePaginationPage($location, page);
    };
    $scope.addHandler = function()
    {
        AuthManager.redirect('/contacts/add/' + (($scope.parent) ? $scope.parent : ''));
    };
    $scope.load();
});
app.controller('editContacts', function($scope, $rootScope, $http, $routeParams, AuthManager)
{
    $scope.add = false;

    $scope.save = function()
    {
        AuthManager.get('/contacts/save', $scope.results).then(function(results)
        {
            $scope.load();
        });
    };
    $scope.load = function()
    {
        AuthManager.get('/contacts/view/',
        {
            view_type: "edit",
            id: $routeParams.id
        }).then(function(results)
        {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
            $rootScope.title = 'Edit Contact - #' + $scope.results.id;
        });
    };
    $scope.load();
});
app.controller('addContacts', function($scope, $rootScope, AuthManager, $routeParams)
{
    $scope.add = true;
    $rootScope.title = 'Add Contact';
    if (!$routeParams.client)
    {
        $scope.addAll = true;
        AuthManager.get('/clients/view/',
        {
            "view_type": 'view',
            "state": 'false'
        }).then(function(results)
        {
            $scope.clients = results.data;
        });
    }
    else
    {
        $scope.addAll = false;
    }
    $scope.results = {
        payment: "true",
        invoice: "true",
        receipt: "true",
        suspension: "true",
        adwords: "true",
        quotes: "false",
        clients: (($routeParams.client) ? $routeParams.client : ''),
        view_type: 'create'
    };
    $scope.save = function()
    {
        console.log('testing');
        $message = '';
        if ($scope.results.clients == '')
        {
            $message += 'select a client<br>';
        }
        if ((!$scope.results.name) || ($scope.results.name == ''))
        {
            $message += 'insert a name<br>';
        }
        if ((!$scope.results.email) || ($scope.results.email == ''))
        {
            $message += 'insert an email address';
        }
        if ($message == '')
        {
            AuthManager.get('/contacts/save', $scope.results).then(function(results)
            {
                if (results.data == 'true')
                {
                    AuthManager.redirect('/contacts/edit/' + data.id);
                }
            });
        }
        else
        {
            warningAlert($message);
        }
    };
});
