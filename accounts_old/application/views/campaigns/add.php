<form ng-submit="add()" method="post" >
    <div class="modal-header">Add New Adword Campaign <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
    <div class="modal-body">
        <div class="form-group">
            <label>Campaign Name</label>
            <input type="text" class="form-control input-sm" required maxlength="300" ng-model="data.name" ng-value="">
        </div>
        <div class="form-group">
            <label>Signed Up</label>
            <input type="text" class="form-control input-sm" ng-model="data.created" value="" required data-date-format="yyyy-mm-dd" data-provide="datepicker">
        </div>
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12 text-center">
                <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
                <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success"><span class="fa fa-save"></span></button>
            </div>
        </div>
    </div>
</form>
