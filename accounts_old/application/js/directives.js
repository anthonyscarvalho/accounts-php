app.directive( "mAppLoading", [ '$animate', '$http',
    function ( $animate, $http )
    {
        return (
        {
            link: function ( scope, element, attributes )
            {
                scope.isLoading = function ()
                {
                    return $http.pendingRequests.length > 0;
                };
                scope.$watch( scope.isLoading, function ( v )
                {
                    if ( v )
                    {
                        element.show();
                    }
                    else
                    {
                        element.remove();
                    }
                } );
            },
            restrict: "C",
            template: '<div class="animated-container"><div class="messaging"><h1>ZAWebs Is Making Money</h1><p>Please stand by for your ticket to awesome-town!</p></div></div>'
        } );
    }
] );
app.directive( 'chosen', function ( $timeout )
{
    var linker = function ( scope, element, attrs )
    {
        var list = attrs[ 'chosen' ];
        scope.$watch( list, function ()
        {
            element.trigger( 'chosen:updated' );
        } );
        scope.$watch( attrs[ 'ngModel' ], function ()
        {
            element.trigger( 'chosen:updated' );
        } );
        $timeout( function ()
        {
            element.chosen();
        }, 0, false );
    };
    return {
        restrict: 'A',
        link: linker
    };
} );
app.directive( "customSort", function ()
{
    return {
        restrict: 'A',
        transclude: true,
        scope:
        {
            order: '=',
            sort: '='
        },
        template: '<a href="" ng-click="sort_by(order)"><span ng-transclude></span> <i ng-class="selectedCls(order)"></i></a>',
        link: function ( $scope )
            {
                // change sorting order
                $scope.sort_by = function ( newSortingOrder )
                {
                    var sort = $scope.sort;
                    if ( sort.sortType === newSortingOrder )
                    {
                        sort.sortReverse = !sort.sortReverse;
                    }
                    sort.sortType = newSortingOrder;
                };
                $scope.selectedCls = function ( column )
                {
                    if ( column === $scope.sort.sortType )
                    {
                        return ( 'fa fa-caret-' + ( ( $scope.sort.sortReverse ) ? 'down' : 'up' ) );
                    }
                    else
                    {
                        return 'fa fa-sort';
                    }
                };
            } // end link
    };
} );