app.controller('viewCategories', function($scope, $http, $routeParams, $rootScope, $uibModal, AuthManager)
{
    $rootScope.title = "View All Categories";
    $scope.sort = {
        sortType: 'category',
        sortReverse: false
    };
    $scope.load = function()
    {
        AuthManager.get('/categories/view',
        {
            view_type: 'view'
        }).then(function(results)
        {
            $scope.results = results.data;
        });
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
                AuthManager.get('/categories/update/' + $state + '/' + $id).then(function(results)
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
            templateUrl: 'application/views/categories/categories.htm',
            controller: 'addCategories',
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
            templateUrl: 'application/views/categories/categories.htm',
            controller: 'editCategories',
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
app.controller('editCategories', function($scope, $http, $uibModalInstance, AuthManager, id)
{
    $scope.load = function()
    {
        AuthManager.get('/categories/view',
        {
            view_type: 'edit',
            id: id
        }).then(function(results)
        {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
        });
    };
    $scope.save = function()
    {
        AuthManager.get('/categories/save', $scope.results).then(function(results) {}).catch(function(error)
        {
            warningAlert(error);
        });
    };
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.load();
});
app.controller('addCategories', function($scope, $uibModalInstance, $http, AuthManager)
{
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.results = {
        price: 0.00,
        link: 'invoice',
        view_type: 'create'
    };
    $scope.save = function()
    {
        AuthManager.get('/categories/save', $scope.results).then(function(results)
        {
            if (results.data == 'true')
            {
                $scope.close();
            }
        });
    };
});
