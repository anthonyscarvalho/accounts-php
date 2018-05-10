app.controller('viewSettings', function($scope, $rootScope, $http, AuthManager, $uibModal)
{
    $rootScope.title = "Website Settings";
    $scope.sort = {
        sortType: 'name',
        sortReverse: false
    };
    $scope.load = function()
    {
        AuthManager.get('/settings/view',
        {
            view_type: "view"
        }).then(function(results)
        {
            $scope.results = results.data;
        });
    };
    $scope.edit = function($id)
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/settings/settings.htm',
            controller: 'editSettings',
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
    $scope.reset = function()
    {
        $scope.searchKeyword = {
            canceled: ''
        };
    };
    $scope.load();
});
app.controller('editSettings', function($scope, $rootScope, $http, AuthManager, $uibModal, $uibModalInstance, id)
{
    $scope.value = {};
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.load = function()
    {
        AuthManager.get('/settings/view/',
        {
            view_type: "edit",
            id: id
        }).then(function(data)
        {
            $scope.results = data.data;
            if (data.data.value)
            {
                $scope.value = JSON.parse(data.data.value);
            }
            if (data.data.name == 'templates')
            {
                AuthManager.get('/template_attachments/view',
                {
                    view_type: "retrieve",
                    status: 'false'
                }).then(function(results)
                {
                    $scope.attachments = results.data;
                });
            }
            else if (data.data.name == "email")
            {
                AuthManager.get('/template_emails/view',
                {
                    view_type: "retrieve"
                }).then(function(results)
                {
                    $scope.emails = results.data;
                });
            }
        });
    };
    $scope.save = function()
    {
        if ($scope.results.name == 'templates')
        {
            $data = {
                id: id,
                name: "templates",
                value: JSON.stringify($scope.value),
                view_type: 'save'
            };
        }
        else if ($scope.results.name == 'email')
        {
            $data = {
                id: id,
                name: "email",
                value: JSON.stringify($scope.value),
                view_type: 'save'
            };
        }
        console.log($data);
        AuthManager.get('/settings/save/', $data).then(function(results)
        {
            if (results.data == 'true')
            {
                $scope.close();
            }
        });
    };
    $scope.load();
});
