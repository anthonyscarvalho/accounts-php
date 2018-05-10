app.controller('DashCtrl', function($scope, $rootScope, AuthManager)
{
    $rootScope.title = "Welcome";
    if ($rootScope.loggedinUser)
    {
        $rootScope.title += " - " + $rootScope.loggedinUser;
    }
    $scope.data = {
        activeCompany: '1'
    };
    AuthManager.get('/dashboard/recentemails').then(function(results)
    {
        $scope.emails = results.data;
        return AuthManager.get('/companies/view',
        {
            view_type: 'search'
        });
    }).then(function(results)
    {
        $scope.companies = results.data;
        return AuthManager.get('/manager/getActiveYear');
    }).then(function(results)
    {
        $scope.year = results.data;
        $scope.loadInvoices();
    });
    $scope.loadInvoices = function()
    {
        // invoices
        AuthManager.get('/dashboard/invoices/',
        {
            company: $scope.data.activeCompany,
            year: $scope.year
        }).then(function(results)
        {
            $scope.invoices = {
                labels: results.months,
                series: results.series,
                data: [
                    results.total,
                    results.canceled,
                    results.unpaid,
                    results.paid
                ]
            };
            return AuthManager.get('/dashboard/invoicesMonthly',
            {
                company: $scope.data.activeCompany,
                year: $scope.year
            });
        }).then(function(results)
        {
            $scope.monthly = {
                labels: results.months,
                series: results.series,
                data: [
                    results.total,
                    results.paid,
                    results.unpaid,
                    results.canceled
                ]
            };
            return AuthManager.get('/dashboard/invoicesAnual',
            {
                company: $scope.data.activeCompany,
                year: $scope.year
            });
        }).then(function(results)
        {
            $scope.anual = {
                labels: results.years,
                series: results.series,
                data: [
                    results.total,
                    results.paid,
                    results.unpaid,
                    results.canceled,
                    results.predicted
                ]
            };
            return AuthManager.get('/dashboard/income',
            {
                company: $scope.data.activeCompany,
                year: $scope.year
            });
        }).then(function(results)
        {
            $scope.monthlyPred = {
                labels: results.months,
                series: results.series,
                data: [
                    results.estimated,
                    results.actual,
                    results.expense
                ]
            };
            return AuthManager.get('/dashboard/incomeMonthly',
            {
                company: $scope.data.activeCompany,
                year: $scope.year
            });
        }).then(function(results)
        {
            $scope.monthlyPrev = {
                labels: results.months,
                series: results.series,
                data: [
                    results.income.res3,
                    results.income.res2,
                    results.income.res1
                ]
            };
            return AuthManager.get('/dashboard/incomeAnual',
            {
                company: $scope.data.activeCompany,
                year: $scope.year
            });
        }).then(function(results)
        {
            $scope.annualExp = {
                labels: results.years,
                series: results.series,
                data: [
                    results.total,
                    results.income,
                    results.expense
                ]
            };
        });
    };
    //invoices
    //set colours
    $scope.lineGraph = ['#010180', '#4BE0E8', '#F50707', '#05AB05'];
    //income
    //set colours
    $scope.barGraph = [
    {
        "backgroundColor": "rgba(1, 1, 128, 1)",
        "borderColor": "rgba(1, 1, 128, 0)"
    },
    {
        "backgroundColor": "rgba(5, 171, 5, 1)",
        "borderColor": "rgba(5, 171, 5, 0)"
    },
    {
        "backgroundColor": "rgba(245, 7, 7, 1)",
        "borderColor": "rgba(245, 7, 7, 0)"
    },
    {
        "backgroundColor": "rgba(75, 224, 232, 1)",
        "borderColor": "rgba(75, 224, 232, 0)"
    },
    {
        "backgroundColor": "rgba(255, 128, 0, 1)",
        "borderColor": "rgba(255, 128, 0, 0)"
    }];
    //set global graph options
    $scope.graphoptions = {
        animation: false,
        tooltips:
        {
            intersect: false,
            mode: 'label',
            position: 'myCustomPosition',
            bodySpacing: 5,
            xPadding: 10,
            yPadding: 10,
            titleSpacing: 10
        },
        hover:
        {
            mode: 'label'
        },
        elements:
        {
            line:
            {
                fill: false,
            }
        }
    };
});
