app.controller( 'viewCampaigns', function( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "View All Campaigns";
    $scope.sort = {
        sortType: 'name',
        sortReverse: false
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/campaigns/add.php',
            controller: 'addCampaigns',
            size: 'md'
        } );
        uibModalInstance.result.then( function( results ) {
            $scope.load();
        } );
    };
    $scope.load = function() {
        AuthManager.get( '/campaigns/view', { view_type: 'view' } ).then( function( results ) {
            $scope.results = results.data;
        } );
    };
    $scope.load();
} );
app.controller( 'editCampaigns', function( $scope, $http, $routeParams, $rootScope, AuthManager ) {
    function load() {
        AuthManager.get( '/campaigns/view', { view_type: 'edit', id: $routeParams.id } ).then( function( results ) {
            $scope.results = results.data;
            $scope.results.view_type = 'save';
            $scope.parent = results.data.id;
            $rootScope.title = results.data.name + ' - Campaign';
        } );
    }
    $scope.save = function() {
        AuthManager.get( '/campaigns/save', $scope.results ).then( function( results ) {} );
    };
    load();
} );
app.controller( 'addCampaigns', function( $scope, $uibModalInstance, $location, $http, AuthManager ) {
    $scope.data = {
        view_type: 'create'
    };
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.add = function() {
        AuthManager.get( '/campaigns/save', $scope.data ).then( function( results ) {
            if ( results.data == 'true' ) {
                $scope.close();
            }
        } );
    };
} );
