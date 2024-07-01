// Factories
app.factory( 'AuthManager', function( $location, $rootScope, $http, $q ) {
    var obj = {};
    var _baseUrl = "/app";
    var _loggedIn = 'false';
    var _user = '';
    var _userRoles = '';
    var _userAccess = '';
    var _verified = 'false';
    obj.set = function() {
        var deferred = $q.defer();
        if ( _verified == 'false' ) {
            $http.get( _baseUrl + '/manager/verify' ).success( function( data ) {
                if ( data.logged_in == 'true' ) {
                    obj.updateDetails( data );
                    _verified = 'true';
                    deferred.resolve( 'true' );
                    $rootScope.showMenu = true;
                } else {
                    deferred.reject( "false" );
                    obj.logOut();
                }
            } );
        } else {
            deferred.resolve( 'true' );
        }
        return deferred.promise;
    };
    obj.updateDetails = function( data ) {
        if ( data.logged_in == 'true' ) {
            $rootScope.showMenu = true;
        } else {
            $rootScope.showMenu = false;
        }
        if ( data ) {
            if ( !angular.equals( data.user_roles, _userRoles ) ) {
                _userRoles = data.user_roles;
                $rootScope.userRoles = _userRoles;
            }
            if ( !angular.equals( data.user_access, _userAccess ) ) {
                _userAccess = data.user_access;
                $rootScope.userAccess = _userAccess;
            }
            if ( !angular.equals( data.logged_in, _loggedIn ) ) {
                _loggedIn = data.logged_in;
            }
            if ( data.logged_in == 'false' ) {
                obj.logOut();
            }
            if ( data.redirect ) {
                if ( data.message ) {
                    warningAlert( data.message );
                }
                if ( user.logged_in == "false" ) {
                    obj.logOut();
                } else {
                    obj.redirect();
                }
            }
        }
    };
    obj.logIn = function( _user, _pass ) {
        $rootScope.submitted = true;
        var deferred = $q.defer();
        $http.post( _baseUrl + '/manager/signin', {
            'username': _user,
            'password': _pass
        } ).success( function( data ) {
            if ( data.data == 'true' ) {
                successAlert( data.message );
                $rootScope.showMenu = true;
            } else if ( data.data == 'false' ) {
                warningAlert( data.message );
            }
            $rootScope.submitted = false;
            deferred.resolve( data.data );
        } ).error( function() {
            $rootScope.submitted = false;
            deferred.reject( 'error' );
        } );
        return deferred.promise;
    };
    obj.logOut = function() {
        $http.get( _baseUrl + '/manager/logout' ).success( function( data ) {
            _loggedIn = "false";
            _user = "";
            _userRoles = "";
            _userAccess = "";
            _verified = "false";
            obj.redirect( "/login" );
            $rootScope.showMenu = false;
        } ).error( function() {
            $rootScope.submitted = false;
        } );
    };
    obj.redirect = function( $path ) {
        if ( !$path ) {
            $location.path( "/" );
        } else {
            $location.path( $path );
        }
    };
    obj.get = function( _url, _data ) {
        $rootScope.submitted = true;
        var deferred = $q.defer();
        $http.post( _baseUrl + _url, _data ).success( function( data ) {
            // obj.updateDetails( data );
            $rootScope.submitted = false;
            if ( data.message && data.logged_in !== 'false' ) {
                if ( data.data !== '' ) {
                    successAlert( data.message );
                } else if ( data.data === '' ) {
                    warningAlert( data.message );
                }
            } else if ( data.message && data.logged_in === 'false' ) {
                warningAlert( data.message );
            }
            if ( data.logged_in === 'false' ) {
                deferred.reject( 'logout' );
                obj.logOut();
            } else {
                deferred.resolve( data );
            }
        } ).error( function( error ) {
            $rootScope.submitted = false;
            deferred.reject( 'network' );
            warningAlert( error );
        } );
        return deferred.promise;
    };
    obj.post = function( _url ) {
        $rootScope.submitted = true;
        var deferred = $q.defer();
        $http.post( _baseUrl + _url ).success( function( data ) {
            $rootScope.submitted = false;
            if ( data.message && data.logged_in !== 'false' ) {
                if ( data.data === 'true' ) {
                    successAlert( data.message );
                } else if ( data.data === 'false' ) {
                    warningAlert( data.message );
                }
            } else if ( data.message && data.logged_in === 'false' ) {
                warningAlert( data.message );
            }
            if ( data.logged_in === 'false' ) {
                deferred.reject( 'logout' );
            } else {
                deferred.resolve( 'true' );
            }
        } ).error( function( error ) {
            $rootScope.submitted = false;
            deferred.reject( 'network' );
            warningAlert( error );
        } );
        return deferred.promise;
    };
    obj.resolve = function() {
        var deferred = $q.defer();
        deferred.resolve( 'true' );
        return deferred.promise;
    };
    obj.remove = function( _url ) {
        $rootScope.submitted = true;
        var deferred = $q.defer();
        $http.post( _baseUrl + _url ).success( function( data ) {
            obj.updateDetails( data );
            if ( data.message ) {
                if ( data.data == 'true' ) {
                    successAlert( data.message );
                } else if ( data.data == 'false' ) {
                    warningAlert( data.message );
                }
            }
            $rootScope.submitted = false;
            deferred.resolve( data.data );
        } ).error( function() {
            $rootScope.submitted = false;
            deferred.reject( 'there was an error' );
        } );
        return deferred.promise;
    };
    // obj.create = function ( _url, _data )
    // {
    //     $rootScope.submitted = true;
    //     var deferred = $q.defer();
    //     $http.post( _baseUrl + _url, _data ).success( function ( data )
    //     {
    //         obj.updateDetails( data );
    //         if ( data.message )
    //         {
    //             if ( data.data == 'true' )
    //             {
    //                 successAlert( data.message );
    //             }
    //             else if ( data.data == 'false' )
    //             {
    //                 warningAlert( data.message );
    //             }
    //         }
    //         $rootScope.submitted = false;
    //         deferred.resolve( data.data );
    //     } ).error( function ()
    //     {
    //         $rootScope.submitted = false;
    //         deferred.reject( 'there was an error' );
    //     } );
    //     return deferred.promise;
    // };
    // obj.update = function ( _url, _data )
    // {
    //     $rootScope.submitted = true;
    //     var deferred = $q.defer();
    //     $http.post( _baseUrl + _url, _data ).success( function ( data )
    //     {
    //         obj.updateDetails( data );
    //         if ( data.message )
    //         {
    //             if ( data.data == 'true' )
    //             {
    //                 successAlert( data.message );
    //             }
    //             else if ( data.data == 'false' )
    //             {
    //                 warningAlert( data.message );
    //             }
    //         }
    //         $rootScope.submitted = false;
    //         deferred.resolve( data.data );
    //     } ).error( function ()
    //     {
    //         $rootScope.submitted = false;
    //         deferred.reject( 'there was an error' );
    //     } );
    //     return deferred.promise;
    // };
    // obj.delete = function ( _url )
    // {
    //     $rootScope.submitted = true;
    //     var deferred = $q.defer();
    //     $http.get( _baseUrl + _url ).success( function ( data )
    //     {
    //         obj.updateDetails( data );
    //         if ( data.message )
    //         {
    //             if ( data.data == 'true' )
    //             {
    //                 successAlert( data.message );
    //             }
    //             else if ( data.data == 'false' )
    //             {
    //                 warningAlert( data.message );
    //             }
    //         }
    //         $rootScope.submitted = false;
    //         deferred.resolve( data.data );
    //     } ).error( function ()
    //     {
    //         $rootScope.submitted = false;
    //         deferred.reject( 'there was an error' );
    //     } );
    //     return deferred.promise;
    // };
    return obj;
} );
app.factory( 'Excel', function( $window ) {
    var uri = 'data:application/vnd.ms-excel;base64,';
    var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
    var base64 = function( s ) {
        return $window.btoa( unescape( encodeURIComponent( s ) ) );
    };
    var format = function( s, c ) {
        return s.replace( /{(\w+)}/g, function( m, p ) {
            return c[ p ];
        } );
    };
    return {
        tableToExcel: function( tableId, worksheetName ) {
            var table = $( tableId );
            var ctx = { worksheet: worksheetName, table: table.html() };
            var href = uri + base64( format( template, ctx ) );
            return href;
        }
    };
} );
