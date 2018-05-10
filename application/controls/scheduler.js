app.controller( 'userScheduler', function ( $scope, $http, $rootScope, $routeParams, $filter, $uibModal, AuthManager, $compile, $timeout, uiCalendarConfig )
{
    $scope.sort = {
        sortType: 'received',
        sortReverse: false
    };
    var _date = new Date();
    $scope.date = $filter( 'date' )( _date, 'yyyy-MM-dd' );
    $scope.data = {
        view_type: "view",
        date: $scope.date
    };
    AuthManager.get( '/users/view/',
    {
        view_type: 'retrieve'
    } ).then( function ( results )
    {
        $scope.users = results.data;
    } );
    $scope.load = function ()
    {
        AuthManager.get( '/scheduler/view/', $scope.data ).then( function ( results )
        {
            $scope.results = results.data;
            uiCalendarConfig.calendars[ 'myCalendar1' ].fullCalendar( 'removeEvents' );
            uiCalendarConfig.calendars[ 'myCalendar1' ].fullCalendar( 'addEventSource', $scope.results );
        } );
    };
    $scope.updateDate = function ()
    {
        var moment = uiCalendarConfig.calendars[ 'myCalendar1' ].fullCalendar( 'getDate' );
        $scope.data.date = moment;
        $scope.load();
    }
    /* alert on Resize */
    $scope.alertOnResize = function ( event, delta, revertFunc, jsEvent, ui, view )
    {
        alert( 'Event Resized to make dayDelta ' + event.end.format() );
    };
    /* Change View */
    $scope.changeView = function ( view, calendar )
    {
        uiCalendarConfig.calendars[ calendar ].fullCalendar( 'changeView', view );
    };
    /* config object */
    $scope.uiConfig = {
        calendar:
        {
            height: 800,
            editable: true,
            customButtons:
            {
                myCustBtnPrev:
                {
                    text: 'Prev',
                    icon: 'left-single-arrow',
                    click: function ()
                    {
                        uiCalendarConfig.calendars[ 'myCalendar1' ].fullCalendar( 'prev' );
                        $scope.updateDate();
                    }
                },
                myCustBtnNext:
                {
                    text: 'Next',
                    icon: 'right-single-arrow',
                    click: function ()
                    {
                        uiCalendarConfig.calendars[ 'myCalendar1' ].fullCalendar( 'next' );
                        $scope.updateDate();
                    }
                },
                myCustBtnToday:
                {
                    text: 'Today',
                    click: function ()
                    {
                        uiCalendarConfig.calendars[ 'myCalendar1' ].fullCalendar( 'today' );
                        $scope.updateDate();
                    }
                }
            },
            firstDay: 1,
            header:
            {
                left: 'title',
                center: '',
                right: 'myCustBtnPrev myCustBtnToday myCustBtnNext'
            },
            // eventClick: $scope.alertOnEventClick,
            // eventDrop: $scope.alertOnDrop,
            eventResize: $scope.alertOnResize,
            // eventRender: $scope.eventRender
        }
    };
    /* event sources array*/
    $scope.load();
} );
