app.directive("mAppLoading", function($animate, $http)
{
    return (
    {
        link: function(scope, element, attributes)
        {
            scope.isLoading = function()
            {
                return $http.pendingRequests.length > 0;
            };
            scope.$watch(scope.isLoading, function(v)
            {
                if (v)
                {
                    element.show();
                }
                else
                {
                    element.remove();
                }
            });
        },
        restrict: "C",
        template: '<div class="animated-container"><div id="cssloader"></div><div class="messaging"><h1>ZAWebs Is Making Money</h1><p>Please stand by for your ticket to awesome-town!</p></div></div>'
    });
});
app.directive('chosen', function($timeout)
{
    var linker = function(scope, element, attrs)
    {
        var list = attrs['chosen'];
        scope.$watch(list, function()
        {
            element.trigger('chosen:updated');
        });
        scope.$watch(attrs['ngModel'], function()
        {
            element.trigger('chosen:updated');
        });
        $timeout(function()
        {
            element.chosen();
        }, 0, false);
    };
    return {
        restrict: 'A',
        link: linker
    };
});
app.directive("customSort", function($location)
{
    return {
        restrict: 'A',
        transclude: true,
        scope:
        {
            order: '=',
            sort: '='
        },
        template: '<a href="" ng-click="sort_by(order); sortChangeHandler(order);"><span ng-transclude></span> <i ng-class="selectedCls(order)"></i></a>',
        controller: function($scope, $location)
        {
            $scope.sortChangeHandler = function(newSort)
            {
                $location.search('recordSort', newSort);
            };

            $scope.selectedCls = function(column)
            {
                $res = '';
                if (column === $scope.sort.sortType)
                {
                    $res = ('fa fa-caret-' + (($scope.sort.sortOrder == "DESC") ? 'down' : 'up'));
                }
                return $res;
            };
        }
    };
});
app.directive('back', ['$window', function($window)
{
    return {
        restrict: 'A',
        link: function(scope, elem, attrs)
        {
            elem.bind('click', function()
            {
                $window.history.back();
            });
        }
    };
}]);
