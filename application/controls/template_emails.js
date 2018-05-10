app.controller('viewTemplateEmails', function($scope, $http, $rootScope, $uibModal, AuthManager)
{
    $rootScope.title = "View All Email Templates";
    $scope.sort = {
        sortType: 'name',
        sortReverse: false
    };
    $scope.load = function()
    {
        AuthManager.get('/template_emails/view',
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
            templateUrl: 'application/views/template_emails/template_emails.htm',
            controller: 'addTemplateEmails',
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
            templateUrl: 'application/views/template_emails/template_emails.htm',
            controller: 'editTemplateEmails',
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
app.controller('editTemplateEmails', function($scope, $http, $uibModalInstance, AuthManager, id)
{
    $scope.add = false;
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.save = function()
    {
        AuthManager.get('/template_emails/save', $scope.results).then(function(results)
        {
            if (results.data == 'true')
            {
                $scope.close();
            }
        });
    };
    $scope.load = function()
    {
        AuthManager.get('/template_emails/view',
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
app.controller('addTemplateEmails', function($scope, $uibModalInstance, $location, $http, AuthManager)
{
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
        AuthManager.get('/template_emails/save', $scope.results).then(function(results)
        {
            if (results.data == 'true')
            {
                $scope.close();
            }
        });
    };
});
