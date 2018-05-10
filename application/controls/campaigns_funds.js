app.controller('viewCampaignFunds', function($scope, $http, $routeParams, $rootScope, $filter, AuthManager, Excel, $timeout, $uibModal)
{
    $scope.parent = $routeParams.id;
    $scope.sort = {
        sortType: 'date',
        sortReverse: false
    };
    $scope.ads = {
        year: $filter('date')(new Date(), 'yyyy'),
        month: $filter('date')(new Date(), 'MM'),
        view_type: "view",
        campaigns: $routeParams.id
    };
    var runningTotal = 0;
    AuthManager.get('/campaigns/view',
    {
        view_type: 'edit',
        id: $routeParams.id
    }).then(function(results)
    {
        $rootScope.title = results.data.name + " - Funds";
    });
    $scope.exportToExcel = function(tableId)
    { // ex: '#my-table'
        var exportHref = Excel.tableToExcel(tableId, 'sheet name');
        $timeout(function()
        {
            location.href = exportHref;
        }, 100); // trigger download
    };
    $scope.setTotals = function(item)
    {
        if (item)
        {
            if (item.credit)
            {
                $scope.totalCredit += Number(item.credit);
            }
            if (item.debit)
            {
                $scope.totalDebit += Number(item.debit);
            }
        }
    };
    $scope.updateTotal = function(item)
    {
        if (item)
        {
            if (item.credit)
            {
                runningTotal += Number(item.credit);
            }
            if (item.debit)
            {
                runningTotal -= Number(item.debit);
            }
        }
        return runningTotal;
    };
    $scope.updateCredit = function(item)
    {
        if (item)
        {
            runningTotal = Number(item.adsCredit) - Number(item.adsDebit);
        }
        return runningTotal;
    };
    $scope.load = function()
    {
        $scope.totalCredit = 0;
        $scope.totalDebit = 0;
        runningTotal = 0;
        AuthManager.get('/campaigns_funds/view', $scope.ads).then(function(results)
        {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
        });
    }
    $scope.add = function()
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/campaigns/fundsAdd.htm',
            controller: 'addCampaignFunds',
            resolve:
            {
                parent: function()
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
    $scope.remove = function($id)
    {
        if (confirm("Are you sure you want to delete?"))
        {
            AuthManager.get('/campaigns_funds/delete',
            {
                id: $id
            }).then(function(results)
            {
                if (results.data === "true")
                {
                    successAlert('removed');
                    $scope.load();
                }
                else
                {
                    warningAlert(results);
                }
            });
        }
    };
    $scope.edit = function($id)
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/campaigns/fundsEdit.htm',
            controller: 'editCampaignFunds',
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
    $scope.emailStatement = function($client)
    {
        if ($scope.data.clients != '')
        {
            var uibModalInstance = $uibModal.open(
            {
                templateUrl: 'application/views/statements/email.htm',
                controller: 'emailStatement',
                resolve:
                {
                    client: function()
                    {
                        return $scope.data.clients;
                    },
                    start: function()
                    {
                        return $scope.data.startDate;
                    },
                    end: function()
                    {
                        return $scope.data.endDate;
                    }
                }
            });
        }
        else
        {
            warningAlert('please select client first');
        }
    };
    $scope.previewAdStatement = function()
    {

        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/templates/pdfPreview.htm',
            controller: 'campaignFundsStatement',
            resolve:
            {
                campaigns: function()
                {
                    return $scope.ads.campaigns;
                },
                year: function()
                {
                    return $scope.ads.year;
                },
                month: function()
                {
                    return $scope.ads.month;
                }
            }
        });
    };
    $scope.load();
});
app.controller('editCampaignFunds', function($scope, AuthManager, $uibModalInstance, id)
{
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.save = function()
    {
        AuthManager.get('/campaigns_funds/save', $scope.data).then(function(results)
        {
            $scope.load();
        });
    };
    $scope.load = function()
    {
        AuthManager.get('/campaigns_funds/view/',
        {
            view_type: "edit",
            id: id
        }).then(function(results)
        {
            $scope.data = results.data;
            $scope.data.view_type = 'save';
        });
    };
    $scope.load();
});
app.controller('addCampaignFunds', function($scope, $routeParams, $rootScope, $filter, $uibModalInstance, AuthManager, parent)
{
    $scope.data = {
        view_type: "insert",
        campaigns: parent,
        commission: 'false',
        date: $filter('date')(new Date(), 'yyyy-MM-dd'),
        email: 'true'
    };
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.add = function()
    {
        AuthManager.get('/campaigns_funds/save', $scope.data).then(function(results)
        {
            if (results.data == 'true')
            {
                $scope.close();
            }
        });
    };
    $scope.load = function()
    {
        AuthManager.get('/campaigns_clients/view/',
        {
            view_type: 'view',
            id: parent
        }).then(function(results)
        {
            $scope.clients = results.data;
        });
    }
    $scope.load();
});
app.controller('campaignFundsStatement', function($scope, $http, $sce, $uibModalInstance, campaigns, year, month)
{
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $http.post('/app/campaigns_funds/statement',
    {
        view_type: 'view',
        display: 'print',
        year: year,
        month: month,
        campaigns: campaigns
    },
    {
        responseType: 'arraybuffer'
    }).success(function(results)
    {
        var file = new Blob([results],
        {
            type: 'application/pdf'
        });
        var fileURL = URL.createObjectURL(file);
        $scope.results = $sce.trustAsResourceUrl(fileURL);
    });
});
