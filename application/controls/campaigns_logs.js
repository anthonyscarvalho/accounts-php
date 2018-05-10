app.controller('viewCampaignLogs', function($scope, $http, $routeParams, $rootScope, $uibModal, AuthManager)
{
    $scope.SetClientLog = true;
    $scope.sort = {
        sortType: 'date',
        sortReverse: true
    };
    $scope.parent = $routeParams.id;
    $scope.load = function()
    {
        AuthManager.get('/campaigns_logs/view',
        {
            view_type: 'view',
            campaigns: $routeParams.id
        }).then(function(results)
        {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
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
            templateUrl: 'application/views/campaigns/logsPreview.htm',
            controller: 'previewCampaignsLog',
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
app.controller('previewCampaignsLog', function($scope, $http, AuthManager, $uibModalInstance, id)
{
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.cancel = function()
    {
        $uibModalInstance.dismiss('cancel');
    };
    AuthManager.get('/campaigns_logs/view',
    {
        view_type: 'edit',
        id: id
    }).then(function(results)
    {
        $scope.results = results.data;
        $scope.data = JSON.parse(results.data.data);
    });
});
