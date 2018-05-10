app.controller('viewLogs', function($scope, $http, $rootScope, AuthManager, $uibModal, AuthManager)
{
    $rootScope.title = "All Admin Logs";
    $scope.sort = {
        sortType: 'date',
        sortReverse: true
    };
    $scope.load = function()
    {
        AuthManager.get('/logs/view',
        {
            view_type: 'view'
        }).then(function(results)
        {
            $scope.results = results.data;
        }).catch(function(error)
        {
            warningAlert(error);
        });
    };
    $scope.reset = function()
    {
        $scope.searchKeyword = "";
    };
    $scope.preview = function($id)
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/logs/preview.htm',
            controller: 'previewClientLog',
            resolve:
            {
                id: function()
                {
                    return $id;
                }
            }
        });
    };
    $scope.load();
});
app.controller('searchLogs', function($scope, $http, $routeParams, $rootScope, $uibModal, AuthManager)
{
    $scope.SetClientLog = true;
    $scope.sort = {
        sortType: 'date',
        sortReverse: true
    };
    $scope.parent = $routeParams.client;
    $scope.subnav = 'true';
    $scope.load = function()
    {
        AuthManager.get('/logs/view',
        {
            view_type: 'search',
            id: $scope.parent
        }).then(function(results)
        {
            $scope.results = results.data;
            $rootScope.title = results.business + " - Contacts";
        }).catch(function(error)
        {
            warningAlert(error);
        });
    };
    $scope.reset = function()
    {
        $scope.searchKeyword = "";
    };
    $scope.preview = function($id)
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/logs/preview.htm',
            controller: 'previewClientLog',
            resolve:
            {
                id: function()
                {
                    return $id;
                }
            }
        });
    };
    $scope.load();
});
app.controller('previewClientLog', function($scope, $http, AuthManager, $uibModalInstance, id)
{
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.cancel = function()
    {
        $uibModalInstance.dismiss('cancel');
    };
    AuthManager.get('/logs/view',
    {
        view_type: 'edit',
        id: id
    }).then(function(results)
    {
        $scope.results = results.data;
        $scope.data = JSON.parse(results.data.data);
    }).catch(function(error)
    {
        warningAlert(error);
    });
});
