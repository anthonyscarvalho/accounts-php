app.controller( 'viewAdwords', function ( $scope, $rootScope, $filter, $uibModal, AuthManager )
{
    $rootScope.title = "View All Adwords";
    var runningTotal = 0;
    $scope.sort = {
        sortType: '',
        sortReverse: false
    };
    $scope.ads = {
        year: $filter( 'date' )( new Date(), 'yyyy' ),
        month: $filter( 'date' )( new Date(), 'MM' ),
        view_type: "view"
    };
    $scope.load = function ()
    {
        $scope.totalCredit = 0;
        $scope.totalDebit = 0;
        runningTotal = 0;
        AuthManager.get( '/adwords/view/', $scope.ads ).then( function ( results )
        {
            $scope.results = results;
        } );
    };
    $scope.getcredit = function ()
    {
        AuthManager.get( '/adwords/credit' ).then( function ( results )
        {
            $scope.credit = results;
        } );
    };
    $scope.setTotals = function ( item )
    {
        if ( item )
        {
            if ( item.credit )
            {
                $scope.totalCredit += Number( item.credit );
            }
            if ( item.debit )
            {
                $scope.totalDebit += Number( item.debit );
            }
        }
    };
    $scope.updateTotal = function ( item )
    {
        if ( item )
        {
            if ( item.credit )
            {
                runningTotal += Number( item.credit );
            }
            if ( item.debit )
            {
                runningTotal -= Number( item.debit );
            }
        }
        return runningTotal;
    };
    $scope.updateCredit = function ( item )
    {
        if ( item )
        {
            runningTotal = Number( item.adsCredit ) - Number( item.adsDebit );
        }
        return runningTotal;
    };
    $scope.edit = function ( $id )
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/adwords/edit.php',
            controller: 'editAdwords',
            size: 'lg',
            resolve:
            {
                id: function ()
                {
                    return $id;
                }
            }
        } );
        uibModalInstance.result.then( function ()
        {
            $scope.load();
            $scope.getcredit();
        } );
    };
    $scope.add = function ()
    {
        var uibModalInstance = $uibModal.open(
        {
            animation: false,
            templateUrl: 'application/views/adwords/add.php',
            controller: 'addAdwords',
            size: 'lg',
            resolve:
            {
                client: function ()
                {
                    return '';
                }
            }
        } );
        uibModalInstance.result.then( function ()
        {
            $scope.load();
            $scope.getcredit();
        } );
    };
    $scope.delete = function ( $id )
    {
        if ( confirm( "Are you sure you want to delete record?" ) )
        {
            AuthManager.delete( '/adwords/delete/' + $id ).then( function ( results )
            {
                if ( results == 'true' )
                {
                    $scope.load();
                }
            } );
        }
    };
    $scope.getcredit();
    $scope.load();
} );
app.controller( 'searchAdwords', function ( $scope, $routeParams, $rootScope, $filter, $uibModal, AuthManager )
{
    $scope.SetAdwords = true;
    $scope.sort = {
        sortType: '',
        sortReverse: false
    };
    $scope.config = {
        itemsPerPage: 20,
        fillLastPage: false
    };
    $scope.ads = {
        year: $filter( 'date' )( new Date(), 'yyyy' ),
        month: $filter( 'date' )( new Date(), 'MM' ),
        view_type: "search",
        client: $routeParams.clients
    };
    var runningTotal = 0;
    $scope.totalCredit = 0;
    $scope.totalDebit = 0;
    $scope.parent = $routeParams.clients;
    $scope.load = function ()
    {
        runningTotal = 0;
        AuthManager.get( '/adwords/view/', $scope.ads ).then( function ( results )
        {
            $scope.results = results.data;
            $rootScope.title = results.business + " - Adwords";
        } );
    };
    $scope.setTotals = function ( item )
    {
        if ( item )
        {
            if ( item.credit )
            {
                $scope.totalCredit += Number( item.credit );
            }
            if ( item.debit )
            {
                $scope.totalDebit += Number( item.debit );
            }
        }
    };
    $scope.updateTotal = function ( item )
    {
        if ( item )
        {
            if ( item.credit )
            {
                runningTotal += Number( item.credit );
            }
            if ( item.debit )
            {
                runningTotal -= Number( item.debit );
            }
        }
        return runningTotal;
    };
    $scope.remove = function ( $id )
    {
        if ( confirm( "Are you sure you want to delete record?" ) )
        {
            AuthManager.delete( '/adwords/delete/' + $id ).then( function ( results )
            {
                if ( results == 'true' )
                {
                    $scope.load();
                }
            } );
        }
    };
    $scope.add = function ( $client )
    {
        var uibModalInstance = $uibModal.open(
        {
            animation: false,
            templateUrl: 'application/views/adwords/add.php',
            controller: 'addAdwords',
            size: 'lg',
            resolve:
            {
                client: function ()
                {
                    return $client;
                }
            }
        } );
        uibModalInstance.result.then( function ()
        {
            $scope.load();
        } );
    };
    $scope.edit = function ( $id )
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/adwords/edit.php',
            controller: 'editAdwords',
            size: 'lg',
            resolve:
            {
                id: function ()
                {
                    return $id;
                }
            }
        } );
        uibModalInstance.result.then( function ()
        {
            $scope.load();
        } );
    };
    $scope.reminder = function ( $id )
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/adwords/reminder.php',
            controller: 'reminderAdwords',
            size: 'xl',
            resolve:
            {
                id: function ()
                {
                    return $id;
                }
            }
        } );
    };
    $scope.load();
} );
app.controller( 'editAdwords', function ( $scope, $uibModalInstance, AuthManager, id )
{
    $scope.close = function ()
    {
        $uibModalInstance.close();
    };
    $scope.data = {
        view_type: "edit",
        id: id
    };
    $scope.load = function ()
    {
        AuthManager.get( '/adwords/view/', $scope.data ).then( function ( results )
        {
            $scope.results = results;
        } );
    };
    $scope.save = function ()
    {
        AuthManager.update( '/adwords/save', $scope.results ).then( function ( results )
        {
            $scope.load();
        } );
    };
    $scope.load();
} );
app.controller( 'reminderAdwords', function ( $scope, $filter, $uibModalInstance, AuthManager, id )
{
    $scope.close = function ()
    {
        $uibModalInstance.close();
    };
    $scope.data = {
        clients: id,
        date: $filter( 'date' )( new Date(), 'yyyy-MM-dd' ),
        month: $filter( 'date' )( new Date(), 'MMMM' ),
        due_date: $filter( 'date' )( new Date(), 'yyyy-MM-dd' )
    };
    $scope.load = function ()
    {
        AuthManager.get( '/template_emails/edit/7' ).then( function ( results )
        {
            $scope.data.emailsubject = results.subject;
            $scope.data.emailbody = results.body;
        } );
    };
    $scope.send = function ()
    {
        AuthManager.update( '/adwords/email/', $scope.data ).then( function ()
        {
            $scope.close();
        } );
    };
    $scope.load();
} );
app.controller( 'addAdwords', function ( $scope, AuthManager, $uibModalInstance, client )
{
    if ( client == '' )
    {
        $scope.addAll = true;
        AuthManager.get( '/clients/retrieve' ).then( function ( results )
        {
            $scope.clients = results;
        } );
    }
    else
    {
        $scope.addAll = false;
    }
    $scope.data = {
        clients: client
    };
    $scope.close = function ()
    {
        $uibModalInstance.close();
    };
    $scope.add = function ()
    {
        AuthManager.update( '/adwords/create/', $scope.data ).then( function ( results )
        {
            if ( results.data === "true" )
            {
                $scope.close();
            }
        } );
    };
} );
