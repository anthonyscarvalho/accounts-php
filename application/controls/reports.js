
app.controller('reportOverview', function($scope, $http, $rootScope, AuthManager, Excel, $timeout)
{
    $rootScope.title = "Report Overview";
    $scope.reportType = 'income';
    $scope.sort = {
        sortType: 'date',
        sortReverse: false
    };
    AuthManager.get('/companies/view',
    {
        view_type: "search"
    }).then(function(results)
    {
        $scope.companies = results.data;
        return AuthManager.get('/manager/getActiveYear');
    }).then(function(results)
    {
        $scope.exp = {
            year: results.data,
            companies: '0',
            type: 'all',
            summary: 'grouped',
            view_type: 'view'
        };
    }).catch(function(error)
    {
        warningAlert(error);
    });
    $scope.load = function()
    {
        if ($scope.reportType == 'income')
        {
            AuthManager.get('/reports/get_income', $scope.exp).then(function(results)
            {
                $scope.results = results.data;
            }).catch(function(error)
            {
                warningAlert(error);
            });
        }
        else if ($scope.reportType == 'expense')
        {
            AuthManager.get('/reports/get_expense', $scope.exp).then(function(results)
            {
                $scope.results = results.data;
            }).catch(function(error)
            {
                warningAlert(error);
            });
        }
    };
    $scope.clear = function()
    {
        $scope.results = null;
        $scope.adsDate = null;
        $scope.totalCredit = 0;
        $scope.totalDebit = 0;
    };
    $scope.exportToExcel = function(tableId)
    { // ex: '#my-table'
        var exportHref = Excel.tableToExcel(tableId, 'sheet name');
        $timeout(function()
        {
            location.href = exportHref;
        }, 100); // trigger download
    };
    $scope.updateInc = function(item)
    {
        var runningTotal = 0;
        angular.forEach(item, function(value, key)
        {
            if (value.credit != '')
            {
                runningTotal += Number(value.credit);
            }
        });
        return runningTotal;
    };
    $scope.updateExp = function(item)
    {
        var runningTotal = 0;
        angular.forEach(item, function(value, key)
        {

            if (value.amount != '')
            {
                runningTotal += Number(value.amount);
            }
        });
        return runningTotal;
    };
});﻿
