app.controller( 'viewInvoices', function( $scope, $http, $rootScope, $uibModal, AuthManager ) {
    $rootScope.title = "View All Invoices";
    $scope.loaded = 'false';
    $scope.paid = 'false';
    $scope.sort = {
        sortType: 'clientName',
        sortReverse: false
    };
    $scope.load = function() {
        AuthManager.get( '/invoices/view/', {
            view_type: 'view',
            state: $scope.loaded,
            paid: $scope.paid
        } ).then( function( results ) {
            $scope.results = results.data;
        } ).catch( function( error ) {
            if ( error == "logout" ) {
                AuthManager.logOut();
            }
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.update = function( $id, $state ) {
        if ( $state === 'enable' || $state === 'cancel' ) {
            if ( confirm( "Are you sure you want to " + $state + "?" ) ) {
                AuthManager.post( '/invoices/update/' + $state + '/' + $id ).then( function( results ) {
                    $scope.load();
                } );
            }
        }
    };
    $scope.delete = function( $id, $client ) {
        if ( confirm( "Are you sure you want to delete?" ) ) {
            $http.post( '/app/invoices/delete/' + $id + '/' + $client ).success( function( data, status, headers, config ) {
                // AuthManager.update( data.user, data.redirect );
                if ( data.data === "true" ) {
                    successAlert( data.message );
                    $scope.load();
                } else {
                    warningAlert( data );
                }
            } ).error( function() {
                warningAlert( 'a network error occured' );
            } );
        }
    };
    $scope.edit = function( $invoice ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/invoices/edit.php',
            controller: 'editInvoices',
            size: 'xl',
            resolve: {
                invoice: function() {
                    return $invoice;
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/invoices/add.php',
            controller: 'addInvoice',
            size: 'xl',
            resolve: {
                client: function() {
                    return '';
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.load();
} );
app.controller( 'searchInvoices', function( $scope, $http, $routeParams, $rootScope, $uibModal, AuthManager ) {
    $scope.SetInvoices = true;
    $scope.sort = {
        sortType: 'creation_date',
        sortReverse: true
    };
    $scope.loaded = 'false';
    $scope.parent = $routeParams.clients;
    $scope.load = function() {
        AuthManager.get( '/invoices/view', {
            view_type: 'search',
            clients: $scope.parent,
            state: $scope.loaded
        } ).then( function( results ) {
            $scope.results = results.data;
            if ( results.business ) {
                $rootScope.title = results.business + " - Invoices";
            }
        } );
    };
    $scope.reset = function() {
        $scope.searchKeyword = "";
    };
    $scope.update = function( $id, $state ) {
        if ( $state === 'enable' || $state === 'cancel' ) {
            if ( confirm( "Are you sure you want to " + $state + "?" ) ) {
                AuthManager.post( '/invoices/update/' + $state + '/' + $id ).then( function( results ) {
                    $scope.load();
                } );
            }
        }
    };
    $scope.delete = function( $id ) {
        if ( confirm( "Are you sure you want to delete?" ) ) {
            $http.post( '/app/invoices/delete/' + $id + '/' + $scope.parent ).success( function( data ) {
                // AuthManager.update( data.user, data.redirect );
                if ( data.data === "true" ) {
                    successAlert( data.message );
                    $scope.load();
                } else {
                    warningAlert( data );
                }
            } ).error( function() {
                warningAlert( 'a network error occured' );
            } );
        }
    };
    $scope.add = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/invoices/add.php',
            controller: 'addInvoice',
            size: 'xl',
            resolve: {
                client: function() {
                    return $scope.parent;
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.edit = function( $invoice ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/invoices/edit.php',
            controller: 'editInvoices',
            size: 'xl',
            resolve: {
                invoice: function() {
                    return $invoice;
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load( $scope.loaded );
        } );
    };
    $scope.load( "" );
} );
app.controller( 'editInvoices', function( $scope, $http, $uibModalInstance, $uibModal, AuthManager, invoice ) {
    $http.get( '/app/companies/search' ).success( function( data ) {
        $scope.companies = data.data;
    } );
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.load = function() {
        AuthManager.get( '/invoices/view/', {
            view_type: 'edit',
            id: invoice
        } ).then( function( results ) {
            $scope.data = results.data;
            $scope.data.view_type = "save";
            $scope.clientName = results.clientName;
            return AuthManager.get( '/invoices_items/view', {
                view_type: 'view',
                invoice: invoice
            } );
        } ).then( function( results ) {
            $scope.invoices_items = results.data;
            return AuthManager.get( '/invoices_emails/view', {
                view_type: 'view',
                invoice: invoice
            } );
        } ).then( function( results ) {
            $scope.invoices_emails = results.data;
            return AuthManager.get( '/transactions/view', {
                view_type: 'view',
                invoice: invoice
            } );
        } ).then( function( results ) {
            $scope.transactions = results.data;
            return AuthManager.get( '/transactions/getCredit/' + $scope.data.clients );
        } ).then( function( results ) {
            $scope.credit = results.data;
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.deleteTrans = function( $id, $invoice ) {
        if ( confirm( "Are you sure you want to delete?" ) ) {
            AuthManager.get( '/transactions/deleteTrans', { id: $id, invoice: $invoice } ).then( function( results ) {
                if ( results.data == 'true' ) {
                    $scope.load();
                }
            } ).catch( function( error ) {
                warningAlert( error );
            } );
        }
    };
    $scope.deleteItem = function( $id ) {
        if ( confirm( "Are you sure you want to enable?" ) ) {
            AuthManager.get( '/invoices_items/delete', { id: $id } ).then( function( results ) {
                if ( results.data == "true" ) {
                    $scope.load();
                }
            } ).catch( function( error ) {
                warningAlert( error );
            } );
        }
    };
    $scope.creditInvoice = function( $id ) {
        AuthManager.get( '/invoices/credit', { id: $id } ).then( function( results ) {
            $scope.load();
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.addTrans = function( $id, $parent ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/transactions/add.php',
            controller: 'addTransactions',
            size: 'xl',
            resolve: {
                invoice: function() {
                    return $id;
                },
                parent: function() {
                    return $parent;
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.creditInvoice( $id );
            // getCredit();
        } );
    };
    $scope.editItem = function( $id ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/invoices_items/edit.php',
            controller: 'editInvoicesItem',
            size: 'xl',
            resolve: {
                itemId: function() {
                    return $id;
                }
            }
        } );
        uibModalInstance.result.then( function() {
            $scope.load();
        } );
    };
    $scope.sendInvoice = function( $invoice ) {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/views/invoices/email.php',
            controller: 'emailInvoice',
            size: 'xl',
            resolve: {
                invoice: function() {
                    return $invoice;
                },
                client: function() {
                    return $scope.clientName;
                }
            }
        } );
        uibModalInstance.results.then( function() {
            $scope.load();
        } );
    };
    $scope.previewInvoice = function() {
        var uibModalInstance = $uibModal.open( {
            animation: false,
            templateUrl: 'application/templates/pdfPreview.php',
            controller: 'previewInvoice',
            size: 'xl',
            resolve: {
                invoice: function() {
                    return $scope.data.id;
                }
            }
        } );
    };
    $scope.save = function() {
        AuthManager.get( '/invoices/save', $scope.data ).then( function( results ) {} ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.load();

    function retrieve( $invoice, $client ) {}
} );
app.controller( 'addInvoice', function( $scope, $http, AuthManager, $uibModalInstance, client, $filter ) {
    $scope.client = client;
    if ( client == '' ) {
        $scope.addAll = true;
        $clients = AuthManager.get( '/clients/view', { view_type: 'view', state: 'false' } );
    } else {
        $scope.addAll = false;
        $clients = AuthManager.resolve();
    }
    var _date = new Date();
    _date.setMonth( _date.getMonth() + 1 );
    $scope.data = {
        sendMail: 'true',
        invoiceType: 'due',
        company: '1',
        prodDate: $filter( 'date' )( _date, 'yyyy-MM-dd' ),
        clients: client,
        products: []
    };
    $clients.then( function( results ) {
        if ( client == '' ) {
            $scope.clients = results.data;
        }
        return AuthManager.get( '/template_emails/view', { view_type: 'edit', id: '1' } );
    } ).then( function( results ) {
        $scope.subject = results.data.subject;
        $scope.data.emailsubject = results.data.subject;
        $scope.data.emailbody = results.data.body;
        return AuthManager.get( '/companies/view', { view_type: "search" } );
    } ).then( function( results ) {
        $scope.companies = results.data;
    } ).catch( function( error ) {
        warningAlert( error );
    } );
    $scope.updateDate = function() {
        if ( $scope.data.invoiceType == 'due' ) {
            var _date = new Date();
            _date.setMonth( _date.getMonth() + 1 );
        } else if ( $scope.data.invoiceType == 'now' ) {
            var _date = new Date();
        }
        $scope.data.prodDate = $filter( 'date' )( _date, 'yyyy-MM-dd' );
        $scope.data.products = null;
        $scope.products = null;
    };
    $scope.addToSelected = function( $product ) {
        $inArray = $filter( 'filter' )( $scope.data.products, $product, true );
        if ( $inArray == '' ) {
            $scope.data.products.push( $product );
        } else {
            $scope.data.products.splice( $scope.data.products.indexOf( $product ), 1 );
        }
    };
    $scope.getClass = function( $product ) {
        $inArray = $filter( 'filter' )( $scope.data.products, $product, true );
        if ( $inArray != '' ) {
            return 'row_selected';
        } else {
            return '';
        }
    }
    $scope.updateSubject = function() {
        $scope.data.emailsubject = $scope.subject + ' - ' + $( 'option:selected', '#clients' ).attr( "data-client" );
    };
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $scope.clear = function() {
        $scope.products = null;
    };
    $scope.load = function() {
        var valid = false;
        var message = '';
        $scope.data.products = [];
        if ( $scope.data.company != '' ) {
            valid = true;
        } else {
            valid = false;
            message += 'select company<br>';
        }
        if ( $scope.data.prodDate != '' ) {
            valid = true;
        } else {
            valid = false;
            message = +'insert date<br>';
        }
        if ( $scope.data.clients != '' ) {
            valid = true;
        } else {
            valid = false;
            message += 'select client';
        }
        if ( valid == true ) {
            AuthManager.get( '/products/view/', { view_type: 'view', state: 'due', date: $scope.data.prodDate, clients: $scope.data.clients, companies: $scope.data.company, invoice_type: $scope.data.invoiceType } ).then( function( results ) {
                $scope.products = results.products;
                angular.forEach( results.products, function( value, key ) {
                    $scope.data.products.push( value );
                } );
            } );
        } else {
            warningAlert( message );
        }
    };
    $scope.create = function() {
        AuthManager.get( '/invoices/create', $scope.data ).then( function( results ) {
            if ( results.data == 'true' ) {
                $scope.close();
            }
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
} );
app.controller( 'emailInvoice', function( $scope, $http, $rootScope, AuthManager, $uibModalInstance, invoice, client ) {
    $scope.invoiceNumber = invoice;
    $scope.data = {
        view_type: 'view',
        display: 'email',
        invoice: invoice
    };
    $scope.load = function( $type ) {
        $http.get( '/app/template_emails/retrieve/' + $type ).success( function( data ) {
            $scope.data.emailsubject = data.data.subject + ' - ' + client;
            $scope.data.emailbody = data.data.body;
            $scope.data.email_type = $type;
        } );
    };
    $scope.sendEmail = function() {
        AuthManager.get( '/invoices/printInvoice', $scope.data ).then( function( results ) {
            if ( results.data == "true" ) {
                $scope.close();
            }
        } ).catch( function( error ) {
            warningAlert( error );
        } );
    };
    $scope.close = function() {
        $uibModalInstance.close();
    };
} );
app.controller( 'previewInvoice', function( $scope, $http, $sce, $uibModalInstance, invoice ) {
    $scope.close = function() {
        $uibModalInstance.close();
    };
    $http.post( '/app/invoices/printInvoice', {
        view_type: 'view',
        display: 'print',
        invoice: invoice
    }, {
        responseType: 'arraybuffer'
    } ).success( function( results ) {
        var file = new Blob( [ results ], { type: 'application/pdf' } );
        var fileURL = URL.createObjectURL( file );
        $scope.results = $sce.trustAsResourceUrl( fileURL );
    } );
} );
