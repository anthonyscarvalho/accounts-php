app.controller( 'viewInvoices', function ( $scope, $http, $rootScope, $routeParams, $uibModal, AuthManager )
{
    $_title = "View Invoices"
    $rootScope.title = $_title;
    $scope.records = '';
    $scope.paid = true;
    $scope.sort = {
        sortType: ( ( $rootScope.recordSort ) ? $rootScope.recordSort : 'due_date' ),
        sortOrder: ( ( $rootScope.recordSortOrder ) ? $rootScope.recordSortOrder : 'DESC' ),
        company: $rootScope.recordSortCompany,
        search: $rootScope.searchPhrase,
        paid: ( ( $rootScope.paidFilter ) ? $rootScope.paidFilter : 'false' ),
    };
    $scope.data = {
        view_type: 'view',
        state: (($rootScope.filter) ? $rootScope.filter : 'false'),
        paid: $scope.sort.paid,
        page: ( ( $rootScope.paginationPage ) ? $rootScope.paginationPage : '1' ),
        records: ( ( $rootScope.records ) ? $rootScope.records : '20' ),
        sort: $scope.sort.sortType,
        sortOrder: $scope.sort.sortOrder,
        sortCompany: $scope.sort.company,
        sortSearch: $scope.sort.search
    };
    if ( $routeParams.client )
    {
        $scope.SetInvoices = true;
        $scope.parent = $routeParams.client;
        $scope.subnav = 'true';
    }
    $scope.load = function ()
    {
        if ( $routeParams.client )
        {
            $scope.data.client = $routeParams.client;
        }
        AuthManager.get( '/invoices/view/', $scope.data ).then( function ( results )
        {
            $scope.results = results.data;
            $scope.totalRecords = results.records;
            if ( $scope.sort.search == "" )
            {
                if ( results.business )
                {
                    $rootScope.title = $_title + ' - ' + results.business;
                }
                else
                {
                    $rootScope.titla = $_title;
                }
            }
            else
            {
                $rootScope.title = "Search Invoices - " + $scope.sort.search;
            }
        } );
    };
    $scope.reset = function ()
    {
        $scope.searchChangeHandler( '' );
    };
    $scope.update = function ( $id, $state )
    {
        if ( $state === 'enable' || $state === 'cancel' || $state == 'delete' )
        {
            if ( confirm( "Are you sure you want to " + $state + "?" ) )
            {
                AuthManager.get( '/invoices/update/',
                {
                    view_type: $state,
                    id: $id
                } ).then( function ( results )
                {
                    if ( results.data == 'true' )
                    {
                        $scope.load();
                    }
                } );
            }
        }
    };
    $scope.addHandler = function ()
    {
        AuthManager.redirect( '/invoices/add/' + ( ( $scope.parent ) ? $scope.parent : '' ) );
    };
    $scope.load();
} );
app.controller( 'editInvoices', function ( $scope, $rootScope, $http, $routeParams, AuthManager, $uibModal )
{
    $scope.add = false;
    $scope.credit = true;
    $scope.print = true;
    $scope.email = true;
    AuthManager.get( '/companies/search/' ).then( function ( results )
    {
        $scope.companies = results.data;
    } )
    $scope.load = function ()
    {
        AuthManager.get( '/invoices/view/',
        {
            view_type: 'edit',
            id: $routeParams.id
        } ).then( function ( results )
        {
            $scope.data = results.data;
            $scope.data.view_type = "save";
            $scope.clientName = results.clientName;
            $rootScope.title = 'Edit Invoice - #' + $scope.data.id;
            return AuthManager.get( '/invoices_items/view',
            {
                view_type: 'view',
                invoice: $routeParams.id
            } );
        } ).then( function ( results )
        {
            $scope.invoices_items = results.data;
            return AuthManager.get( '/invoices_emails/view',
            {
                view_type: 'view',
                invoice: $routeParams.id
            } );
        } ).then( function ( results )
        {
            $scope.invoices_emails = results.data;
            return AuthManager.get( '/transactions/view',
            {
                view_type: 'view',
                invoice: $routeParams.id
            } );
        } ).then( function ( results )
        {
            $scope.transactions = results.data;
            return AuthManager.get( '/transactions/getCredit/' + $scope.data.clients );
        } ).then( function ( results )
        {
            $scope.credit = results.data;
        } );
    };
    $scope.deleteTrans = function ( $id, $invoice )
    {
        if ( confirm( "Are you sure you want to delete?" ) )
        {
            AuthManager.get( '/transactions/deleteTrans',
            {
                id: $id,
                invoice: $invoice
            } ).then( function ( results )
            {
                if ( results.data == 'true' )
                {
                    $scope.load();
                }
            } );
        }
    };
    $scope.deleteItem = function ( $id )
    {
        if ( confirm( "Are you sure you want to delete transaction?" ) )
        {
            AuthManager.get( '/invoices_items/delete',
            {
                id: $id
            } ).then( function ( results )
            {
                if ( results.data == "true" )
                {
                    $scope.load();
                }
            } );
        }
    };
    $scope.creditInvoice = function ( $id )
    {
        AuthManager.get( '/invoices/credit',
        {
            id: $id
        } ).then( function ( results )
        {
            $scope.load();
        } );
    };
    $scope.addTransaction = function ( $id, $parent )
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/transactions/popup.htm',
            controller: 'addTransactionsPopup',
            resolve:
            {
                parent: function ()
                {
                    return $parent;
                }
            }
        } );
        uibModalInstance.result.then( function ( data )
        {
            if ( data == 'true' )
            {
                $scope.creditInvoice( $id );
            }
        } );
    };
    $scope.editItem = function ( $id )
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/invoices_items/edit.htm',
            controller: 'editInvoicesItem',
            resolve:
            {
                itemId: function ()
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
    $scope.sendInvoice = function ( $invoice )
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/invoices/email.htm',
            controller: 'emailInvoice',
            resolve:
            {
                invoice: function ()
                {
                    return $invoice;
                },
                client: function ()
                {
                    return $scope.clientName;
                },
                clients: function ()
                {
                    return $scope.data.clients;
                }
            }
        } );
        uibModalInstance.result.then( function ()
        {
            $scope.load();
        } );
    };
    $scope.save = function ()
    {
        AuthManager.get( '/invoices/save', $scope.data ).then( function ( results ) {} );
    };
    $scope.printHandle = function ( $url )
    {
        AuthManager.openTab( $url );
    };
    $scope.load();
} );
app.controller( 'addInvoices', function ( $scope, $rootScope, $http, AuthManager, $routeParams, $filter )
{
    $scope.add = true;
    $rootScope.title = 'Add Invoice';
    if ( !$routeParams.client )
    {
        $scope.addAll = true;
        $clients = AuthManager.get( '/clients/view',
        {
            view_type: 'view',
            state: 'false',
            sort: 'business',
            sortOrder: 'ASC'
        } );
    }
    else
    {
        $scope.addAll = false;
        $clients = AuthManager.get( '/clients/view',
        {
            view_type: 'edit',
            id: $routeParams.client
        } );
    }
    var _date = new Date();
    _date.setMonth( _date.getMonth() + 1 );
    $scope.data = {
        sendMail: 'true',
        invoiceType: 'due',
        prodDate: $filter( 'date' )( _date, 'yyyy-MM-dd' ),
        clients: $routeParams.client,
        products: [],
        email_type: 'first_invoice'
    };
    $clients.then( function ( results )
    {
        $scope.clients = results.data;
        return AuthManager.get( '/template_emails/retrieve',
        {
            template: 'first_invoice'
        } );
    } ).then( function ( results )
    {
        $scope.subject = results.data.subject;
        $scope.data.emailsubject = results.data.subject;
        $scope.data.emailbody = results.data.body;
        if ( $routeParams.client )
        {
            $scope.data.emailsubject = $scope.subject + ' - ' + $scope.clients.business;
        }
    } );
    $scope.updateDate = function ()
    {
        if ( $scope.data.invoiceType == 'due' )
        {
            var _date = new Date();
            _date.setMonth( _date.getMonth() + 1 );
        }
        else if ( $scope.data.invoiceType == 'now' )
        {
            var _date = new Date();
        }
        $scope.data.prodDate = $filter( 'date' )( _date, 'yyyy-MM-dd' );
        $scope.data.products = [];
        $scope.invoiceProds = [];
    };
    $scope.addToInvoice = function ( $source, $product )
    {
        $_tmp = {
            id: $source.id,
            company: $source.company
        };
        $inArray = $filter( 'filter' )( $scope.data.products, $_tmp, true );
        if ( $inArray == '' )
        {
            $scope.data.products.push( $_tmp );
        }
        $_index = $scope.data.products.findIndex( x => x.company === $source.company );
        if ( $scope.data.products[ $_index ].items.length )
        {
            $inArray = $filter( 'filter' )( $scope.data.products[ $_index ].items, $product, true );
            if ( $inArray == '' )
            {
                $scope.data.products[ $_index ].items.push( $product );
            }
            else
            {
                $_index2 = $scope.data.products[ $_index ].items.findIndex( x => x.id === $product.id );
                $scope.data.products[ $_index ].items.splice( $_index2, 1 );
            }
        }
        else
        {
            $scope.data.products[ $_index ].items.push( $product );
        }
    };
    $scope.getClass = function ( $source, $product )
    {
        $_tmp = {
            id: $source.id,
            company: $source.company
        };
        if ( $scope.data.products.length )
        {
            $_index = $scope.data.products.findIndex( x => x.company === $source.company );
            $inArray = $filter( 'filter' )( $scope.data.products[ $_index ].items, $product, true );
            if ( $inArray != '' )
            {
                return 'row_selected';
            }
            else
            {
                return '';
            }
        }
    }
    $scope.updateSubject = function ()
    {
        $scope.data.emailsubject = $scope.subject + ' - ' + $( 'option:selected', '#clients' ).attr( "data-client" );
    };
    $scope.clear = function ()
    {
        $scope.products = null;
    };
    $scope.load = function ()
    {
        var valid = false;
        var message = '';
        $scope.data.products = [];
        if ( $scope.data.prodDate != '' )
        {
            valid = true;
        }
        else
        {
            valid = false;
            message = +'insert date<br>';
        }
        if ( $scope.data.clients != '' )
        {
            valid = true;
        }
        else
        {
            valid = false;
            message += 'select client';
        }
        if ( valid == true )
        {
            AuthManager.get( '/products/invoice/',
            {
                view_type: 'due',
                state: 'due',
                date: $scope.data.prodDate,
                sortClients: $scope.data.clients,
                sortCompany: $scope.data.company,
                invoice_type: $scope.data.invoiceType
            } ).then( function ( results )
            {
                if ( results.data != 'false' )
                {
                    $scope.invoiceProds = results.data;
                    angular.forEach( results.data, function ( value, key )
                    {
                        $_tempItems = [];
                        if ( value.items.length > 0 )
                        {
                            angular.forEach( value.items, function ( val, key )
                            {
                                $_tempItems.push( val );
                            } );
                        }
                        $scope.data.products.push(
                        {
                            id: value.id,
                            company: value.company,
                            vat: '',
                            deposit: '',
                            items: $_tempItems,
                        } );
                    } );
                }
            } );
        }
        else
        {
            warningAlert( message );
        }
    };
    $scope.save = function ()
    {
        AuthManager.get( '/invoices/create', $scope.data ).then( function ( results )
        {
            if ( results.data == 'true' )
            {
                AuthManager.redirect( '/invoices/view/' + $scope.data.clients );
            }
        } );
    };
    $scope.calculateTotal = function ( $index )
    {
        if ( $scope.data.products.length )
        {
            $_total = 0;
            angular.forEach( $scope.data.products[ $index ].items, function ( value, key )
            {
                $_total += parseFloat( value.price );
            } );
            return $_total;
        }
    };
    if ( $routeParams.client )
    {
        $scope.load();
    }
} );
app.controller( 'emailInvoice', function ( $scope, $http, $rootScope, AuthManager, $uibModalInstance, invoice, client, clients )
{
    $scope.invoiceNumber = invoice;
    $scope.data = {
        view_type: 'view',
        display: 'email',
        invoice: invoice,
        clients: clients
    };
    $scope.load = function ( $type )
    {
        AuthManager.get( '/app/template_emails/retrieve',
        {
            template: $type
        } ).then( function ( data )
        {
            $scope.data.emailsubject = data.data.subject + ' for - ' + client;
            $scope.data.emailbody = data.data.body;
            $scope.data.email_type = $type;
        } );
    };
    $scope.sendEmail = function ()
    {
        AuthManager.get( '/invoices/email_invoice', $scope.data ).then( function ( results )
        {
            if ( results.data == "true" )
            {
                $scope.close();
            }
        } );
    };
    $scope.close = function ()
    {
        $uibModalInstance.close();
    };
} );
