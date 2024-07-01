app.controller( 'DashCtrl', function( $scope, $http, $rootScope, AuthManager ) {
    $rootScope.title = "Dashboard";
    $scope.data = {
        activeCompany: '1'
    };
    AuthManager.get( '/manager/verify' ).then( function( data ) {
        if ( data.logged_in == 'true' ) {
            AuthManager.updateDetails( data );
            return AuthManager.get( '/dashboard/recentemails' );
        }
    } ).then( function( results ) {
        $scope.emails = results.data;
        return AuthManager.get( '/companies/view', { view_type: 'search' } );
    } ).then( function( results ) {
        $scope.companies = results.data;
        return AuthManager.get( '/manager/getActiveYear' );
    } ).then( function( results ) {
        $scope.year = results.data;
        $scope.loadInvoices();
    } ).catch( function() {
        AuthManager.logOut();
    } );
    $scope.loadInvoices = function() {
        // invoices
        AuthManager.get( '/dashboard/invoices/', { company: $scope.data.activeCompany, year: $scope.year } ).then( function( results ) {
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
            return AuthManager.get( '/dashboard/invoicesMonthly', { company: $scope.data.activeCompany, year: $scope.year } );
        } ).then( function( results ) {
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
            return AuthManager.get( '/dashboard/invoicesAnual', { company: $scope.data.activeCompany, year: $scope.year } );
        } ).then( function( results ) {
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
            return AuthManager.get( '/dashboard/income', { company: $scope.data.activeCompany, year: $scope.year } );
        } ).then( function( results ) {
            $scope.monthlyPred = {
                labels: results.months,
                series: results.series,
                data: [
                    results.estimated,
                    results.actual,
                    results.expense
                ]
            };
            return AuthManager.get( '/dashboard/incomeMonthly', { company: $scope.data.activeCompany, year: $scope.year } );
        } ).then( function( results ) {
            $scope.monthlyPrev = {
                labels: results.months,
                series: results.series,
                data: [
                    results.income.res3,
                    results.income.res2,
                    results.income.res1
                ]
            };
            return AuthManager.get( '/dashboard/incomeAnual', { company: $scope.data.activeCompany, year: $scope.year } );
        } ).then( function( results ) {
            $scope.annualExp = {
                labels: results.years,
                series: results.series,
                data: [
                    results.total,
                    results.income,
                    results.expense
                ]
            };
        } ).catch( function() {
            AuthManager.logOut();
        } );
    };
    //invoices
    //set colours
    $scope.lineGraph = [ '#010180', '#4BE0E8', '#F50707', '#05AB05' ];
    //income
    //set colours
    $scope.barGraph = [
        { "fillColor": "rgba(1, 1, 128, 1)", "strokeColor": "rgba(1, 1, 128, 0)" },
        { "fillColor": "rgba(5, 171, 5, 1)", "strokeColor": "rgba(5, 171, 5, 0)" },
        { "fillColor": "rgba(245, 7, 7, 1)", "strokeColor": "rgba(245, 7, 7, 0)" },
        { "fillColor": "rgba(75, 224, 232, 1)", "strokeColor": "rgba(75, 224, 232, 0)" },
        { "fillColor": "rgba(255, 128, 0, 1)", "strokeColor": "rgba(255, 128, 0, 0)" }
        ];
    //set global graph options
    $scope.graphoptions = {
        animation: false
    };
} );
