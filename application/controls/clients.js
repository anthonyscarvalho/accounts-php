app.controller('viewClients', function($scope, $http, $rootScope, $location, AuthManager)
{
    $rootScope.title = "View Clients";
    $scope.loaded = (($rootScope.filter) ? $rootScope.filter : '');
    $scope.sort = {
        sortType: (($rootScope.recordSort) ? $rootScope.recordSort : 'business'),
        sortOrder: ( ( $rootScope.recordSortOrder ) ? $rootScope.recordSortOrder : 'ASC' ),
        company: $rootScope.recordSortCompany,
        search: $rootScope.searchPhrase
    };
    $scope.data = {
        state: (($rootScope.filter) ? $rootScope.filter : 'false'),
        view_type: 'view',
        page: (($rootScope.paginationPage) ? $rootScope.paginationPage : '1'),
        records: (($rootScope.records) ? $rootScope.records : '20'),
        sort: $scope.sort.sortType,
        sortOrder: $scope.sort.sortOrder,
        sortSearch: $scope.sort.search
    };
    $scope.reset = function()
    {
        $scope.searchKeyword = "";
    };
    $scope.load = function()
    {
        AuthManager.get('/clients/view/', $scope.data).then(function(results)
        {
            $scope.results = results.data;
            $scope.totalRecords = results.records;
            if ($scope.sort.search == "")
            {
                $rootScope.titla = $_title;
            }
            else
            {
                $rootScope.title = "Search Clients - " + $scope.sort.search;
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
                AuthManager.get('/clients/update/',
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
        AuthManager.redirect('/clients/add/' + (($scope.parent) ? $scope.parent : ''));
    };
    $scope.load();
});
app.controller('editClients', function($scope, $http, $routeParams, $rootScope, AuthManager)
{
    $scope.SetClient = true;
    AuthManager.get('/manager/verify').then(function(data)
    {
        if (data.logged_in == 'true')
        {
            AuthManager.updateDetails(data);
            return AuthManager.get('/transactions/getCredit/' + $routeParams.id);
        }
    }).then(function(results)
    {
        $scope.credit = results;
        return AuthManager.get('/statements/incomePA/' + $routeParams.id);
    }).then(function(results)
    {
        $scope.income = {
            labels: results.data.labels,
            series: results.data.series,
            data: [
                results.data.total,
                results.data.paid,
                results.data.unpaid,
                results.data.canceled,
                results.data.predicted
            ]
        };
        $scope.load();
    }).catch(function(error)
    {
        if (error == "logout")
        {
            AuthManager.logOut();
        }
    });
    $scope.load = function()
    {
        AuthManager.get('/clients/view/',
        {
            view_type: "edit",
            id: $routeParams.id
        }).then(function(results)
        {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
            $scope.parent = results.data.id;
            $rootScope.title = results.data.business;
        });
    };
    $scope.save = function()
    {
        AuthManager.get('/clients/save', $scope.results).then(function(results)
        {
            $scope.load();
        });
    };
    $scope.update = function($id, $state)
    {
        if ($state === 'enable' || $state === 'cancel')
        {
            if (confirm("Are you sure you want to " + $state + "?"))
            {
                AuthManager.post('/clients/update/' + $state + '/' + $id).then(function(results)
                {
                    $scope.load();
                });
            }
        }
    };
    //invoices
    //set colours
    $scope.lineGraph = ['#010180', '#05AB05', '#F50707', '#4BE0E8', '#FF8000'];
    //set global graph options
    $scope.graphoptions = {
        animate: false,
        tooltips:
        {
            intersect: false,
            mode: 'label',
            position: 'myCustomPosition',
            bodySpacing: 5,
            xPadding: 10,
            yPadding: 10,
            titleSpacing: 10
        },
        hover:
        {
            mode: 'label'
        },
        elements:
        {
            line:
            {
                fill: false,
            }
        }
    };
});
app.controller('addClients', function($scope, $http, AuthManager)
{
    $scope.add = true;
    $scope.results = {
        business: '',
        city: '',
        postal_code: '',
        view_type: 'create',
        bad_client: 'false'
    };
    $scope.save = function()
    {
        $message = '';
        if ($scope.results.business == "")
        {
            $message += 'insert business name<br>';
        }
        if ($scope.results.city == '')
        {
            $message += 'insert city<br>';
        }
        if ($scope.results.postal_code == '')
        {
            $message += 'insert postal code';
        }
        if ($message == '')
        {
            AuthManager.get('/clients/save', $scope.results).then(function(results)
            {
                if (results.data == 'true')
                {
                    AuthManager.redirect('/clients/edit/' + results.id);
                }
            });
        }
        else
        {
            warningAlert($message);
        }
    };
});
