app.controller('viewUsers', function($scope, $http, $rootScope, $uibModal, AuthManager)
{
    $rootScope.title = "View All Users";
    $scope.sort = {
        sortType: 'name',
        sortReverse: false
    };
    $scope.load = function($state)
    {
        AuthManager.get('/users/view',
        {
            view_type: 'view'
        }).then(function(results)
        {
            $scope.results = results.data;
        }).catch(function(error)
        {
            warningAlert(error);
        });
    };
    $scope.reset = function()
    {
        $scope.searchKeyword = "";
    };
    $scope.update = function($id, $state)
    {
        if ($state === 'enable' || $state === 'cancel')
        {
            if (confirm("Are you sure you want to " + $state + "?"))
            {
                AuthManager.post('/users/update/' + $state + '/' + $id).then(function(results)
                {
                    $scope.load();
                }).catch(function(error)
                {
                    warningAlert(error);
                });
            }
        }
    };
    $scope.add = function()
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/users/users.htm',
            controller: 'addUsers'
        });
        uibModalInstance.result.then(function()
        {
            $scope.load();
        });
    };
    $scope.edit = function($id)
    {
        var uibModalInstance = $uibModal.open(
        {
            templateUrl: 'application/views/users/users.htm',
            controller: 'editUsers',
            resolve:
            {
                id: function()
                {
                    return $id;
                }
            }
        });
        uibModalInstance.result.then(function()
        {
            $scope.load();
        });
    };
    $scope.load();
});
app.controller('editUsers', function($scope, $http, $uibModalInstance, AuthManager, id)
{
    $scope.add = false;
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $roles = AuthManager.get('/user_roles/view',
    {
        view_type: 'view'
    });
    $scope.load = function()
    {
        $roles.then(function(results)
        {
            $scope.roles = results.data;
            return AuthManager.get('/users/view',
            {
                view_type: 'edit',
                id: id
            });
        }).then(function(results)
        {
            $scope.results = results.data;
            if (results.data.access != '')
            {
                $scope.access = JSON.parse(results.data.access);
            }
            else
            {
                $scope.access = {
                    campaigns: 'false',
                    categories: 'false',
                    clients: 'false',
                    companies: 'false',
                    contacts: 'false',
                    email_log: 'false',
                    expenditure: 'false',
                    invoices: 'false',
                    invoices_emails: 'false',
                    invoices_items: 'false',
                    logs: 'false',
                    products: 'false',
                    statements: 'false',
                    template_attachments: 'false',
                    template_emails: 'false',
                    template_quotations: 'false',
                    transactions: 'false',
                    users: 'false',
                    user_roles: 'false',
                    company_income: 'false',
                    report_overview: 'false',
                    report_controlsheet: 'false',
                    quotations: 'false',
                    settings: 'false'
                };
            }
            $scope.results.view_type = 'save';
        });
    };
    $scope.save = function()
    {
        $message = '';
        if ((!$scope.results.name) || ($scope.results.name == ''))
        {
            $message += 'Please insert a name!<br>';
        }
        if ((!$scope.results.username) || ($scope.results.username == ''))
        {
            $message += 'Please insert a username!<br>';
        }
        if ($message == '')
        {
            $scope.results.access = JSON.stringify($scope.access);
            AuthManager.get('/users/save', $scope.results).then(function(results)
            {
                $scope.load();
            });
        }
        else
        {
            console.log($message);
            warningAlert($message);
        }
    };
    // $scope.accessUpdate = function()
    // {
    //     AuthManager.get('/user_access/save', $scope.access).then(function(results)
    //     {
    //         $scope.load();
    //     });
    // };
    // $scope.permissions = function($id)
    // {
    //     var uibModalInstance = $uibModal.open(
    //     {
    //         templateUrl: 'application/views/user_access/edit.htm',
    //         controller: 'editPermissions',
    //         size: 'xl',
    //         resolve:
    //         {
    //             id: function()
    //             {
    //                 return $id;
    //             }
    //         }
    //     });
    //     uibModalInstance.result.then(function()
    //     {
    //         $scope.load();
    //     });
    // };
    $scope.load();
});
app.controller('addUsers', function($scope, $uibModalInstance, $location, $http, AuthManager)
{
    $scope.add = true;
    $scope.results = {
        view_type: 'create',
        roles: '1'
    };
    AuthManager.get('/user_roles/view',
    {
        view_type: 'view'
    }).then(function(results)
    {
        $scope.roles = results.data;
    });
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
    $scope.add = function()
    {
        $message = '';
        if ((!$scope.results.name) || ($scope.results.name == ''))
        {
            $message += 'Please insert a name!<br>';
        }
        if ((!$scope.results.username) || ($scope.results.username == ''))
        {
            $message += 'Please insert a username!<br>';
        }
        if ((!$scope.results.new_password) || ($scope.results.new_password == ''))
        {
            $message += 'Please insert a password!<br>';
        }

        if ($message != '')
        {
            AuthManager.get('/users/save', $scope.results).then(function(results)
            {
                if (results.data == 'true')
                {
                    $scope.close();
                }
            });
        }
        else
        {
            warningAlert($message);
        }
    };
});
app.controller('editPermissions', function($scope, $http, $rootScope, AuthManager, id)
{
    $scope.load = function()
    {
        $http.get('/app/user_access/view/' + id).success(function(data)
        {
            AuthManager.update(data.user, data.redirect);
            $scope.results = data.data;
        });
    };
    $scope.save = function()
    {
        $scope.submitted = true;
        save($http, AuthManager, 'users', $scope.data);
        $scope.submitted = false;
        load();
    };
    $scope.close = function()
    {
        $uibModalInstance.close();
    };
});
