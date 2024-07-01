<div class="modal-header">
    ZAWebs Billing System - Logged in as: {{loggedInUser.name}} {{loggedInUser.surname}}
    <button class="close" ng-click="close()">&times;</button>
</div>
<div class="modal-body">
    <div class="menuModal">
        <div class="row">
            <div class="col-md-6">
                <div class="well well-sm" ng-hide="!management.length">
                    <legend>Management</legend>
                    <a class="btn btn-primary" href="#{{menu.url}}" ng-repeat="menu in management | orderBy:'order'">{{menu.name}}</a>
                </div>
                <div class="well well-sm" ng-hide="!admin.length">
                    <legend>Admin</legend>
                    <a class="btn btn-primary" href="#{{menu.url}}" ng-repeat="menu in admin | orderBy:'order'">{{menu.name}}</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="well well-sm">
                    <legend>Reports</legend>
                    <a class="btn btn-sm btn-primary" href="#/reports/view" ng-if="userAccess.report_overview === 'true'">Reports</a>
                    <legend>Expenditure</legend>
                    <a class="btn btn-sm btn-primary" href="#/expenditure/view" ng-if="userAccess.expenditure === 'true'">Expenditure</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a class="btn btn-primary" href="#/dashboard"><span class="fa fa-home"></span></a>
    <?php //<a class="btn" href="/profile" data-toggle="tooltip" title="Edit Profile"><span class="fa fa-user fa-2x"></span></a>    ?>
    <a class="btn btn-warning" href="#/logout"><span class="fa fa-power-off "></span></a>
</div>
