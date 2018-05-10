app.controller('userJobs', function($scope, $http, $rootScope, $routeParams, $uibModal, AuthManager)
{
    $scope.sort = {
        sortType: 'received',
        sortReverse: false
    };
    $scope.data = {
        view_type: "user"
    };
    $scope.load = function()
    {
        AuthManager.get('/jobs/view/', $scope.data).then(function(results)
        {
            $scope.results = results.data;
        });
    };
    $scope.update = function($id, $state)
    {
        if ($state === 'enable' || $state === 'cancel')
        {
            if (confirm("Are you sure you want to " + $state + "?"))
            {
                AuthManager.post('/jobs/update/' + $state + '/' + $id).then(function(results)
                {
                    $scope.load();
                });
            }
        }
    };
    $scope.add = function()
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/jobs/jobs.htm',
            controller: 'addJobs',
            resolve:
            {
                client: function()
                {
                    return '';
                }
            }
        });
        uibModalInstance.result.then(function()
        {
            $scope.load();
        });
    };
    $scope.edit = function($id)
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/jobs/jobs.htm',
            controller: 'editJobs',
            resolve:
            {
                id: function()
                {
                    return $id;
                }
            }
        });
        uibModalInstance.result.then(function()
        {
            $scope.load();
        });
    };
    $scope.delete = function($id)
    {
        if (confirm("Are you sure you want to delete?"))
        {
            AuthManager.delete('/jobs/delete/' + $id).then(function(results)
            {
                if (results == 'true')
                {
                    $scope.load();
                }
            });
        }
    };
    $scope.load();
});
app.controller('viewJobs', function($scope, $http, $rootScope, $routeParams, $uibModal, AuthManager)
{
    $_title = "View Jobs";
    $scope.subnav = 'false';
    $rootScope.title = $_title;
    $scope.sort = {
        sortType: 'business',
        sortReverse: false
    };
    $scope.data = {
        view_type: "view",
        state: "incomplete"
    };
    if ($routeParams.client)
    {
        $scope.SetJobs = true;
        $scope.parent = $routeParams.client;
        $scope.subnav = 'true';
    }
    AuthManager.get('/categories/view',
    {
        view_type: 'retrieve',
        link: 'jobs'
    }).then(function(results)
    {
        $scope.categories = results.data;
        return AuthManager.get('/users/view',
        {
            view_type: 'retrieve'
        });
    }).then(function(results)
    {
        $scope.users = results.data;
    });
    $scope.load = function()
    {
        if ($routeParams.client)
        {
            $scope.data.client = $routeParams.client;
        }
        AuthManager.get('/jobs/view/', $scope.data).then(function(results)
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
                AuthManager.get('/jobs/update/',
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
    $scope.add = function()
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/jobs/jobs.htm',
            controller: 'addJobs',
            resolve:
            {
                client: function()
                {
                    return '';
                }
            }
        });
        uibModalInstance.result.then(function()
        {
            $scope.load();
        });
    };
    $scope.edit = function($id)
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/jobs/jobs.htm',
            controller: 'editJobs',
            resolve:
            {
                id: function()
                {
                    return $id;
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
app.controller('editJobs', function($scope, $http, $uibModalInstance, AuthManager, id)
{
    $scope.add = false;
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.save = function()
    {
        AuthManager.get('/jobs/save', $scope.results).then(function(results)
        {
            $scope.load();
        });
    };
    $scope.load = function()
    {
        AuthManager.get('/jobs/view/',
        {
            view_type: "edit",
            id: id
        }).then(function(results)
        {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
        });
    };
    AuthManager.get('/categories/view',
    {
        view_type: 'retrieve',
        link: 'jobs'
    }).then(function(results)
    {
        $scope.categories = results.data;
    });
    $scope.load();
});
app.controller('addJobs', function($scope, AuthManager, $uibModalInstance, $location, $http, $filter, client)
{
    $scope.add = true;
    if (client == '')
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
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.results = {
        complete: "false",
        quoted: "0.00",
        received: $filter('date')(new Date(), 'yyyy-MM-dd'),
        view_type: 'create',
        categories: ''
    };
    $scope.save = function()
    {
        $message = '';
        if (!$scope.results.clients)
        {
            $message += 'Please select Client<br>';
        }
        if (!$scope.results.users)
        {
            $message += 'Please selct User<br>'
        }
        if (!$scope.results.categories)
        {
            $message += 'Please select Job Type<br>';
        }
        if ($message == '')
        {
            AuthManager.get('/jobs/save', $scope.results).then(function(results)
            {
                if (results.data == 'true')
                {
                    $scope.close();
                }
            });
        }
        else
        {
            warningAlert($message);
        }
    };
    AuthManager.get('/categories/view',
    {
        view_type: 'retrieve',
        link: 'jobs'
    }).then(function(results)
    {
        $scope.categories = results.data;
        return AuthManager.get('/users/view',
        {
            view_type: 'retrieve'
        });
    }).then(function(results)
    {
        $scope.users = results.data;
    });
});
