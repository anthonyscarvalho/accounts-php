app.controller('viewTransactions', function($scope, $http, $routeParams, $rootScope, $uibModal, AuthManager)
{
    $scope.SetTransactions = true;
    $scope.subnav = 'true';
    $scope.sort = {
        sortType: 'date',
        sortReverse: true
    };
    $scope.parent = $routeParams.client;
    $scope.data = {
        view_type: "search",
        client: $routeParams.client
    };
    $scope.load = function()
    {
        AuthManager.get('/transactions/view/', $scope.data).then(function(results)
        {
            $scope.results = results.data;
            $rootScope.title = results.business + " - Transactions";
        });
    };
    $scope.delete = function($id)
    {
        if (confirm("Are you sure you want to delete?"))
        {
            AuthManager.get('/transactions/delete',
            {
                id: $id,
                clients: $scope.parent
            }).then(function(results)
            {
                if (results.data == "true")
                {
                    $scope.load();
                }
            }).catch(function(error)
            {
                warningAlert(error);
            });
        }
    };
    $scope.load();
});
app.controller('editTransactions', function($scope, $routeParams, $rootScope, AuthManager)
{
    $scope.add = false;
    $scope.data = {
        view_type: "edit",
        id: $routeParams.id
    };
    AuthManager.get('/companies/view',
    {
        view_type: 'search'
    }).then(function(results)
    {
        $scope.companies = results.data;
        return AuthManager.get('/transactions/view',
        {
            view_type: 'edit',
            id: $routeParams.id
        });
    }).then(function(results)
    {
        $scope.results = results.data;
        $scope.results.view_type = 'save';
    });
    $scope.save = function()
    {
        AuthManager.get('/transactions/save', $scope.results).then(function(results) {});
    };
});
app.controller('addTransactions', function($scope, $routeParams, AuthManager)
{
    $scope.add = true;

    $scope.results = {
        companies: '1',
        clients: $routeParams.client,
        sendMail: 'true',
        view_type: 'create'
    };
    AuthManager.get('/companies/view',
    {
        view_type: 'search'
    }).then(function(results)
    {
        $scope.companies = results.data;
    });
    $scope.save = function()
    {
        AuthManager.get('/transactions/save', $scope.results).then(function(results) {});
    };
});
app.controller('addTransactionsPopup', function($scope, $uibModalInstance, $http, AuthManager, parent)
{
    $scope.close = function($data)
    {
        $uibModalInstance.close($data);
    };
    $scope.data = {
        companies: '1',
        clients: parent,
        sendMail: 'true',
        view_type: 'create'
    };
    AuthManager.get('/companies/view',
    {
        view_type: 'search'
    }).then(function(results)
    {
        $scope.companies = results.data;
    }).catch(function(error)
    {
        warningAlert(error);
    });
    $scope.add = function()
    {
        AuthManager.get('/transactions/save', $scope.data).then(function(results)
        {
            if (results.data == "true")
            {
                $scope.close('true');
            }
        }).catch(function(error)
        {
            warningAlert(error);
        });
    };
});
