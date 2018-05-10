app.controller('mainApp', function($scope, $rootScope, $location, $locale, AuthManager)
{
    $locale.NUMBER_FORMATS.GROUP_SEP = '';
    $scope.$watch('$root.records', function()
    {
        $scope.pagnation = {
            maxsize: '10',
            totalItems: (($rootScope.records) ? $rootScope.records : '20')
        };
    });
    $scope.recordsChangeHandler = function()
    {
        if ($scope.pagnation.totalItems > 1)
        {
            $location.search('records', $scope.pagnation.totalItems);
        }
        else
        {
            $location.search('records', null);
        }
    };
    $scope.pageChangeHandler = function($page)
    {
        if ($page > 1)
        {
            $location.search('page', $page);
        }
        else
        {
            $location.search('page', null);
        }
    };
    $scope.sortChangeHandler = function($sort)
    {
        if ($sort > 1)
        {
            $location.search('recordSort', $sort);
        }
        else
        {
            $location.search('recordSort', null);
        }
    };
    $scope.sortComapnyChangeHandler = function($company)
    {
        if ($company > 0)
        {
            $location.search('recordSortCompany', $company);
        }
        else
        {
            $location.search('recordSortCompany', null);
        }
    };
    $scope.sortOrderChangeHandler = function($order)
    {
        if ($order != '')
        {
            $location.search('recordSortOrder', $order);
        }
        else
        {
            $location.search('recordSortOrder', 'ASC');
        }
    };
    $scope.searchChangeHandler = function($search)
    {
        if ($search != '')
        {
            $location.search('searchPhrase', $search);
        }
        else
        {
            $location.search('searchPhrase', null);
        }
    };
     $scope.yearChangeHandler = function($year)
    {
        if ($year != '')
        {
            $location.search('searchYear', $year);
        }
        else
        {
            $location.search('searchYear', null);
        }
    };
    $scope.filterChangeHandler = function($filter)
    {
        if ($filter != '')
        {
            $location.search('filter', $filter);
        }
        else
        {
            $location.search('filter', null);
        }
    };
    $scope.filterPaidHandler = function($paid)
    {
        if ($paid != '')
        {
            $location.search('paidFilter', $paid);
        }
        else
        {
            $location.search('paidFilter', null);
        }
    };

    $scope.dueChangeHandler = function($date)
    {
        if ($rootScope.filter == 'due')
        {
            if ($date != '')
            {
                $location.search('dueDate', $date);
            }
            else
            {
                $location.search('dueDate', null);
            }
        }
        else
        {
            $location.search('dueDate', null);
        }
    };
});

app.run(['$rootScope', '$uibModalStack', function($rootScope, $uibModalStack)
{
    $rootScope.userAccess = {};
    $rootScope.user_roles = {};
    // close the opened modal on location change.
    $rootScope.$on('$locationChangeStart', function()
    {
        var openedModal = $uibModalStack.getTop();
        if (openedModal)
        {
            $uibModalStack.dismiss(openedModal.key);
        }
    });
}]);
