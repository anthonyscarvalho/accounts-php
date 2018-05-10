app.controller('viewTemplatesPdf', function($scope, $http, $rootScope, $uibModal, AuthManager)
{
    $rootScope.title = "View Attachment Templates";
    $scope.sort = {
        sortType: 'name',
        sortReverse: false
    };
    $scope.load = function()
    {
        AuthManager.get('/template_attachments/view',
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
    $scope.add = function()
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/template_attachments/template_attachments.htm',
            controller: 'addTemplatesPdf',
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
            templateUrl: 'application/views/template_attachments/template_attachments.htm',
            controller: 'editTemplatesPdf',
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
app.controller('editTemplatesPdf', function($scope, $http, $uibModalInstance, AuthManager, id)
{
    $scope.add = false;
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.cancel = function()
    {
        $uibModalInstance.dismiss('cancel');
    };
    $scope.save = function()
    {
        AuthManager.get('/template_attachments/save', $scope.results).then(function(results) {}).catch(function(error)
        {
            warningAlert(error);
        });
    };
    $scope.load = function()
    {
        AuthManager.get('/template_attachments/view',
        {
            view_type: 'edit',
            id: id
        }).then(function(results)
        {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
        }).catch(function(error)
        {
            warningAlert(error);
        });
    };
    $scope.load();
});
app.controller('addTemplatesPdf', function($scope, $uibModalInstance, $location, $http, AuthManager)
{
    $scope.add = true;
    $scope.results = {
        view_type: 'create'
    };
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.cancel = function()
    {
        $uibModalInstance.dismiss('cancel');
    };
    $scope.save = function()
    {
        AuthManager.get('/template_attachments/save', $scope.results).then(function(results)
        {
            if (results.data == 'true')
            {
                $scope.close();
            }
        });
    };
});
