app.controller('viewAttachments', function($scope, $http, $routeParams, $rootScope, $uibModal, AuthManager)
{
    $_title = "View Attachments";
    $scope.subnav = 'false';
    $rootScope.title = $_title;
    $scope.sort = {
        sortType: 'date',
        sortReverse: false
    };
    $scope.data = {
        view_type: "view"
    };
    if ($routeParams.client)
    {
        $scope.SetAttachments = true;
        $scope.parent = $routeParams.client;
        $scope.subnav = 'true';
    }

    $scope.load = function()
    {
        if ($routeParams.client)
        {
            $scope.data.client = $routeParams.client;
        }
        AuthManager.get('/attachments/view', $scope.data).then(function(results)
        {
            $scope.results = results.data;
            if (results.business)
            {
                $rootScope.title = $_title + ' - ' + results.business;
            }
        });
    };

    $scope.add = function()
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/attachments/add.htm',
            controller: 'addAttachments',
            resolve:
            {
                client: function()
                {
                    return $scope.parent;
                }
            }
        });
        uibModalInstance.result.then(function(results)
        {
            $scope.load();
        });
    };
    $scope.load();
});
app.controller('addAttachments', function($scope, $http, $filter, AuthManager, $uibModalInstance, client)
{
    var _date = new Date();
    $scope.data = {
        date: $filter('date')(_date, 'yyyy-MM-dd')
    };
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.add = function()
    {
        var tmpFfile = document.getElementById('file').files[0];
        var formData = new FormData();
        formData.append('file', tmpFfile);
        $scope.data.content = formData;
        $http(
        {
            url: '/app/attachments/add',
            method: "POST",
            data: formData,
            headers:
            {
                'Content-Type': undefined
            }
        }).success(function(results)
        {
            if (results.data == 'true')
            {
                AuthManager.get('/attachments/save',
                {
                    id: results.id,
                    date: $scope.date,
                    clients: client,
                    description: $scope.description,
                    view_type: 'create'
                }).then(function(results)
                {
                    if (results.data == 'true')
                    {
                        $scope.close();
                    }
                });
            }
        });
    };
});
app.controller('previewAttachment', function($scope, $http, $sce, $uibModalInstance, id)
{
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $http.post('/app/attachments/preview',
    {
        view_type: 'edit',
        id: id
    },
    {
        responseType: 'arraybuffer'
    }).success(function(results)
    {
        if (results.byteLength > 0)
        {
            var file = new Blob([results],
            {
                type: 'application/pdf'
            });
            var fileURL = URL.createObjectURL(file);
            $scope.results = $sce.trustAsResourceUrl(fileURL);
        }
        else
        {
            warningAlert('no pdf to show!');
            $scope.close();
        }
    });
});
