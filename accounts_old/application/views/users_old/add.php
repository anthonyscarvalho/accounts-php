<form ng-submit="add()" method="post" >
    <div class="modal-header">Add New User <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <label>Business Name</label>
                <input type="text" class="form-control" required maxlength="300" ng-model="data.business" ng-value="">
            </div>
            <div class="col-sm-6">
                <label>VAT #</label>
                <input type="text" class="form-control" maxlength="20" ng-model="data.vat" ng-value="">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <label>Number</label>
                <input type="text" class="form-control" maxlength="100" ng-model="data.number" ng-value="">
            </div>
            <div class="col-sm-6">
                <label>Fax</label>
                <input type="text" class="form-control" maxlength="20" ng-model="data.fax" ng-value="">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <label>Billing Address</label>
                <textarea class="form-control" rows="4" maxlength="500" ng-model="data.billing_address" ng-value=""></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <label>City</label>
                <input type="text" class="form-control" required maxlength="100" ng-model="data.city" ng-value="">
            </div>
            <div class="col-sm-3">
                <label>Postal Code</label>
                <input type="text" class="form-control" maxlength="20" ng-model="data.postal_code" ng-value="">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
        <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" title="Insert Client"><span class="fa fa-save"></span></button>
    </div>
</form>