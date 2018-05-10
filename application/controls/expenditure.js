app.controller('viewExpenditure', function($scope, $http, $rootScope, $uibModal, AuthManager)
{
    $rootScope.title = "View All Expenditure";
    $scope.sort = {
        sortType: (($rootScope.recordSort) ? $rootScope.recordSort : 'business'),
        sortOrder: ( ( $rootScope.recordSortOrder ) ? $rootScope.recordSortOrder : 'ASC' ),
        company: $rootScope.recordSortCompany,
        search: $rootScope.searchPhrase,
        searchYear:$rootScope.searchYear
    };
    AuthManager.get('/manager/getActiveYear').then(function(results)
    {
        if($scope.sort.searchYear=='')
        {
            $scope.sort.searchYear = results.data;
        }

        return AuthManager.get('/companies/view',
        {
            view_type: "search"
        });
    }).then(function(results)
    {
        $scope.companies = results.data;
        $scope.load();
    }).catch(function(error)
    {
        warningAlert(error);
    });
    $scope.load = function()
    {
        $scope.sort.view_type='view';
        AuthManager.get('/expenditure/view', $scope.sort).then(function(results)
        {
            $scope.results = results.data;
            return AuthManager.get('/expenditure/categories', $scope.sort);
        }).then(function(results)
        {
            $scope.categories = results.data
        }).catch(function(error)
        {
            warningAlert(error);
        });
    };
    $scope.reset = function()
    {
        $scope.searchKeyword = "";
    };
    $scope.delete = function($id)
    {
        if (confirm("Are you sure you want to delete?"))
        {
            AuthManager.get('/expenditure/delete/' + $id).then(function(results)
            {
                if (results.data == 'true')
                {
                    $scope.load();
                }
            });
        }
    };
    $scope.add = function()
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/expenditure/expenditure.htm',
            controller: 'addExpenditure',
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
            templateUrl: 'application/views/expenditure/expenditure.htm',
            controller: 'editExpenditure',
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
});
app.controller('editExpenditure', function($scope, $http, $uibModalInstance, AuthManager, id)
{
    $scope.add = false;
    AuthManager.get('/categories/retrieve/expense').then(function(results)
    {
        $scope.categories = results.data;
        return AuthManager.get('/companies/view',
        {
            view_type: "search"
        });
    }).then(function(results)
    {
        $scope.companies = results.data;
        return AuthManager.get('/expenditure/view',
        {
            view_type: 'edit',
            id: id
        });
    }).then(function(results)
    {
        $scope.results = results.data;
        $scope.results.view_type = "save";
    }).catch(function(error)
    {
        warningAlert(error);
    });
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.save = function()
    {
        AuthManager.get('/expenditure/save', $scope.results).then(function(results)
        {
            if (results.data == 'true')
            {
                $scope.close();
            }
        }).catch(function(error)
        {
            warningAlert(error);
        });
    };
});
app.controller('addExpenditure', function($scope, $uibModalInstance, $location, $http, AuthManager)
{
    $scope.add = true;
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.results = {
        companies: '1',
        type: 'Fixed Cost',
        view_type: 'create'
    };
    $scope.recentRecords = [];
    AuthManager.get('/categories/retrieve/expense').then(function(results)
    {
        $scope.categories = results.data;
        return AuthManager.get('/companies/view',
        {
            view_type: "search"
        });
    }).then(function(results)
    {
        $scope.companies = results.data;
    }).catch(function(error)
    {
        warningAlert(error);
    });
    $scope.save = function()
    {
        AuthManager.get('/expenditure/save', $scope.results).then(function(results)
        {
            if (results.data == 'true')
            {
                $scope.results.amount = null;
                $scope.results.description = '';
                $scope.recentRecords.push(results.recent);
            }
        }).catch(function(error)
        {
            warningAlert(error);
        });
        $("#amount").focus();
    };
});
