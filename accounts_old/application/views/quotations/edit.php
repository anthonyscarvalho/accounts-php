<form method="post" ng-submit="save()">
    <div class="modal-header">Edit Contact #{{data.id}} <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Client #:</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control input-sm" maxlength="250" ng-model="data.clients" value="" disabled="disabled">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <label>Name</label>
                <input type="text" class="form-control" maxlength="250" required ng-model="data.name" value="">
            </div>
            <div class="col-sm-6">
                <label>Surname</label>
                <input type="text" class="form-control" maxlength="250" ng-model="data.surname" value="">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Number 1</label>
                <input type="phone" class="form-control" maxlength="250" ng-model="data.contact_number_1" value="">
            </div>
            <div class="col-sm-4">
                <label>Number 2</label>
                <input type="phone" class="form-control" maxlength="250" ng-model="data.contact_number_2" value="">
            </div>
            <div class="col-sm-4">
                <label>Email</label>
                <input type="email" class="form-control" maxlength="250" required ng-model="data.email" value="">
            </div>
        </div>
        <hr>
        <p class="h4">Notifications</p>
        <div class="row">
            <div class="col-sm-3" style="margin-bottom: 20px;">
                <label>Payments</label>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-default active" ng-model="data.payment" uib-btn-radio="'true'" required>True</label>
                    <label class="btn btn-default" ng-model="data.payment" uib-btn-radio="'false'" required>False</label>
                </div>
            </div>
            <div class="col-sm-3" style="margin-bottom: 20px;">
                <label>Invoices</label>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-default active" ng-model="data.invoice" uib-btn-radio="'true'" required>True</label>
                    <label class="btn btn-default" ng-model="data.invoice" uib-btn-radio="'false'" required>False</label>
                </div>
            </div>
            <div class="col-sm-3" style="margin-bottom: 20px;">
                <label>Receipts</label>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-default active" ng-model="data.receipt" uib-btn-radio="'true'" required>True</label>
                    <label class="btn btn-default" ng-model="data.receipt" uib-btn-radio="'false'" required>False</label>
                </div>
            </div>
            <div class="col-sm-3" style="margin-bottom: 20px;">
                <label>Suspensions</label>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-default active" ng-model="data.suspension" uib-btn-radio="'true'" required>True</label>
                    <label class="btn btn-default" ng-model="data.suspension" uib-btn-radio="'false'" required>False</label>
                </div>
            </div>
            <div class="col-sm-3" style="margin-bottom: 20px;">
                <label>Adwords</label>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-default active" ng-model="data.adwords" uib-btn-radio="'true'" required>True</label>
                    <label class="btn btn-default" ng-model="data.adwords" uib-btn-radio="'false'" required>False</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
        <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" title="Insert Contact"><span class="fa fa-save"></span></button>
    </div>
</form>