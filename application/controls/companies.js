app.controller('viewCompanies', function($scope, $http, $rootScope, $uibModal, AuthManager)
{
    $rootScope.title = "View All Companies";
    $scope.sort = {
        sortType: 'company',
        sortReverse: false
    };
    $scope.reset = function()
    {
        $scope.searchKeyword = "";
    };
    $scope.update = function($id, $state)
    {
        if ($state === 'enable' || $state === 'cancel')
        {
            if (confirm("Are you sure you want to " + $state + "?"))
            {
                AuthManager.post('/companies/update/' + $state + '/' + $id).then(function(results)
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
            templateUrl: 'application/views/companies/companies.htm',
            controller: 'addCompanies',
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
            templateUrl: 'application/views/companies/companies.htm',
            controller: 'editCompanies',
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
    $scope.load = function()
    {
        AuthManager.get('/companies/view',
        {
            view_type: 'view'
        }).then(function(results)
        {
            $scope.results = results.data;
        }).catch(function(error)
        {
            warningAlert(error);
        });
    }
    $scope.load();
});
app.controller('editCompanies', function($scope, $http, AuthManager, $uibModalInstance, id)
{
    $scope.add = false;
    AuthManager.get('/companies/view',
    {
        view_type: 'edit',
        id: id
    }).then(function(results)
    {
        $scope.results = results.data;
        $scope.results.view_type = 'save';
    });
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.save = function()
    {
        AuthManager.get('/companies/save', $scope.results).then(function(results) {}).catch(function(error)
        {
            warningAlert(error);
        });
    };
});
app.controller('addCompanies', function($scope, $uibModalInstance, $location, $http, AuthManager)
{
    $scope.add = true;
    $scope.results = {
        view_type: 'create',
        company: '',
        account_details: '',
        invoice_header: ''
    };
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.save = function()
    {
        $message = '';
        if ($scope.results.company == '')
        {
            $message += 'Company name required!<br>';
        }
        if ($scope.results.account_details == '')
        {
            $message += 'Account details are required!<br>';
        }
        if ($scope.results.invoice_header == '')
        {
            $message += 'Invoice header is required!<br>';
        }
        if ($message == '')
        {
            AuthManager.get('/companies/save', $scope.results).then(function(results)
            {
                if (results.data == "true")
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
});
