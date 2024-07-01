app.controller( 'viewCampaignClients', function ( $scope, $http, $routeParams, $rootScope, AuthManager, $uibModal )
{
    $scope.parent = $routeParams.id;
    $scope.sort = {
        sortType: 'business',
        sortReverse: false
    };
    AuthManager.get( '/campaigns/view',
    {
        view_type: 'edit',
        id: $routeParams.id
    } ).then( function ( results )
    {
        $rootScope.title = results.data.name + " - Clients";
    } );
    $scope.load = function ()
    {
        AuthManager.get( '/campaigns_clients/view',
        {
            view_type: 'view',
            id: $routeParams.id
        } ).then( function ( results )
        {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
        } );
    }
    $scope.add = function ()
    {
        var uibModalInstance = $uibModal.open(
        {
            animation: false,
            templateUrl: 'application/views/campaigns/clientsAdd.php',
            controller: 'addCampaignClients',
            size: 'md',
            resolve:
            {
                parent: function ()
                {
                    return $scope.parent;
                }
            }
        } );
        uibModalInstance.result.then( function ( results )
        {
            $scope.load();
        } );
    };
    $scope.delete = function ( $id )
    {
        if ( confirm( "Are you sure you want to delete?" ) )
        {
            AuthManager.get( '/campaigns_clients/delete',
            {
                campaigns: $routeParams.id,
                clients: $id
            } ).then( function ( results )
            {
                if ( results.data === "true" )
                {
                    successAlert( 'removed' );
                    $scope.load();
                }
                else
                {
                    warningAlert( results );
                }
            } );
        }
    };
    $scope.load();
} );
app.controller( 'addCampaignClients', function ( $scope, $http, $routeParams, $rootScope, $uibModalInstance, AuthManager, parent )
{
    $scope.data = {
        view_type: "create",
        campaigns: parent
    };
    $scope.close = function ()
    {
        $uibModalInstance.close();
    };
    $scope.add = function ()
    {
        AuthManager.get( '/campaigns_clients/save', $scope.data ).then( function ( results )
        {
            if ( results.data == 'true' )
            {
                $scope.close();
            }
        } );
    };
    $scope.load = function ()
    {
        AuthManager.get( '/campaigns_clients/view/',
        {
            view_type: 'view',
            id: parent,
            notin: 'true'
        } ).then( function ( results )
        {
            $scope.results = results.data;
        } );
    }
    $scope.load();
} );
