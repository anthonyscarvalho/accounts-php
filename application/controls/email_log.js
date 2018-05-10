app.controller('searchEmailLog', function($scope, $http, $routeParams, $rootScope, $uibModal, AuthManager)
{
    $scope.SetEmailLog = true;
    $scope.sort = {
        sortType: '',
        sortReverse: false
    };
    $scope.parent = $routeParams.id;
    $scope.load = function()
    {
        AuthManager.get('/email_log/search/' + $scope.parent).then(function(results)
        {
            $scope.results = results.data;
            $rootScope.title = results.business + " - Email Log";
        }).catch(function(error)
        {
            warningAlert(error);
        });
    };
    $scope.preview = function($id)
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/email_log/preview.htm',
            controller: 'previewEmailLog',
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
app.controller('previewEmailLog', function($scope, $http, AuthManager, $sce, $uibModalInstance, id)
{
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.cancel = function()
    {
        $uibModalInstance.dismiss('cancel');
    };
    $scope.$on('$routeChangeStart', function()
    {
        $uibModalInstance.close();
    });
    AuthManager.get('/email_log/preview/' + id).then(function(results)
    {
        $scope.results = results.data;
    }).catch(function(error)
    {
        warningAlert(error);
    });
    $scope.renderHtml = function($html)
    {
        return $sce.trustAsHtml($html);
    };
});
