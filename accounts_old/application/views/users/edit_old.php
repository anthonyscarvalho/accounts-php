<div class="m-app-loading" ng-animate-children></div>
<div class="row">
    <div class="col-lg-6">
        <form method="post" ng-submit="save( )">
            <div class="panel panel-default">
                <div class="panel-heading">Information</div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Name</label>
                        <input type="text" class="form-control" maxlength="300" ng-model="data.name">
                    </div>
                    <div class="col-md-6">
                        <label>Surname</label>
                        <input type="text" class="form-control" maxlength="300" ng-model="data.surname">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Username</label>
                        <input type="text" class="form-control" maxlength="100" ng-model="data.username">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Email Address</label>
                        <input type="text" class="form-control" maxlength="20" ng-model="data.email_address">
                    </div>
                </div>
            </div>
            <nav class="navbar navbar-default navbar-bottom" role="navigation">
                <input type="hidden" name="id" value="{{data.id}}" />
                <input type="hidden" name="signup_date" value="{{data.signup_date}}" />
                <ul class="nav navbar-nav">
                    <li><a href="#/users/view" data-toggle="tooltip" title="View All Clients"><span class="fa fa-backward fa-lg"></span></a></li>
                    <li ng-if="submitted"><button type="button" class="btn-save"><span class="fa fa-lg fa-refresh fa-spin"></span></button></li>
                    <li ng-if="!submitted"><button type="submit" class="btn-save"><span class="fa fa-lg fa-save"></span></button></li>
                    <li>
                        <span ng-if="userRoles.cancel">
                            <button type="button"ng-click="enable( data.id )" class="btn-save" ng-show="data.canceled == 'true'"><span class="fa fa-lg fa-times"></span></button>
                            <button type="button"ng-click="cancel( data.id )" class="btn-save" ng-show="data.canceled == 'false'"><span class="fa fa-lg fa-check"></span></button>
                        </span>
                    </li>
                </ul>
            </nav>
        </form>
    </div>
    <div class="col-lg-6">
        <form method="post" ng-submit="saveRoles( )">
            <div class="panel panel-default">
                <div class="panel-heading">General Permissions</div>
                <div class="row">
                    <div class="col-md-3">
                        <label>Clients</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.clients == 'true'}" ng-model="roles.clients" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.clients == 'false'}" ng-model="roles.clients" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Contacts</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.contacts == 'true'}" ng-model="roles.contacts" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.contacts == 'false'}" ng-model="roles.contacts" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Products</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.products == 'true'}" ng-model="roles.products" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.products == 'false'}" ng-model="roles.products" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Invoices</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.invoices == 'true'}" ng-model="roles.invoices" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.invoices == 'false'}" ng-model="roles.invoices" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Invoice Items</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.invoices_items == 'true'}" ng-model="roles.invoices_items" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.invoices_items == 'false'}" ng-model="roles.invoices_items" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Transactions</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.transactions == 'true'}" ng-model="roles.transactions" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.transactions == 'false'}" ng-model="roles.transactions" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Quotations</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.quotations == 'true'}" ng-model="roles.quotations" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.quotations == 'false'}" ng-model="roles.quotations" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Statements</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.statements == 'true'}" ng-model="roles.statements" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.statements == 'false'}" ng-model="roles.statements" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Email Log</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.email_log == 'true'}" ng-model="roles.email_log" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.email_log == 'false'}" ng-model="roles.email_log" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Client Log</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.logs == 'true'}" ng-model="roles.logs" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.logs == 'false'}" ng-model="roles.logs" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Adwords</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.adwords == 'true'}" ng-model="roles.adwords" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.adwords == 'false'}" ng-model="roles.adwords" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Expenditure</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.expenditure == 'true'}" ng-model="roles.expenditure" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.expenditure == 'false'}" ng-model="roles.expenditure" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Admin Permissions</div>
                <div class="row">
                    <div class="col-md-3">
                        <label>Company Income</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.company_income == 'true'}" ng-model="roles.company_income" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.company_income == 'false'}" ng-model="roles.company_income" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Companies</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.companies == 'true'}" ng-model="roles.companies" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.companies == 'false'}" ng-model="roles.companies" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Categories</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.categories == 'true'}" ng-model="roles.categories" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.categories == 'false'}" ng-model="roles.categories" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Users</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.users == 'true'}" ng-model="roles.users" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.users == 'false'}" ng-model="roles.users" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>User Roles</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.user_roles == 'true'}" ng-model="roles.user_roles" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.user_roles == 'false'}" ng-model="roles.user_roles" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Email Templates</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.template_emails == 'true'}" ng-model="roles.template_emails" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.template_emails == 'false'}" ng-model="roles.template_emails" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>PDF Templates</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.template_attachments == 'true'}" ng-model="roles.template_attachments" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.template_attachments == 'false'}" ng-model="roles.template_attachments" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Reports Permissions</div>
                <div class="row">
                    <div class="col-md-3">
                        <label>Payments</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.report_payments == 'true'}" ng-model="roles.report_payments" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.report_payments == 'false'}" ng-model="roles.report_payments" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Control Sheet</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.report_controlsheet == 'true'}" ng-model="roles.report_controlsheet" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.report_controlsheet == 'false'}" ng-model="roles.report_controlsheet" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Expenses</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.report_expenses == 'true'}" ng-model="roles.report_expenses" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.report_expenses == 'false'}" ng-model="roles.report_expenses" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Income</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default" ng-class="{'active': roles.report_income == 'true'}" ng-model="roles.report_income" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-class="{'active': roles.report_income == 'false'}" ng-model="roles.report_income" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
                    </div>

                </div>
            </div>
            <nav class="navbar navbar-default navbar-bottom" role="navigation">
                <ul class="nav navbar-nav">
                    <li ng-if="submitted"><button type="button" class="btn-save"><span class="fa fa-lg fa-refresh fa-spin"></span></button></li>
                    <li ng-if="!submitted"><button type="submit" class="btn-save"><span class="fa fa-lg fa-save"></span></button></li>
                </ul>
            </nav>
        </form>
    </div>
</div>