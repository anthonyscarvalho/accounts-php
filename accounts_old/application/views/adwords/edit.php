<form method="post" ng-submit="save()">
    <div class="modal-header">Edit Ad #{{results.id}}</div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Date</label>
                    <input type="text" class="form-control input-sm" name="date" ng-model="results.date" required data-date-format="yyyy-mm-dd" data-provide="datepicker">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Credit</label>
                    <input type="text" class="form-control input-sm" maxlength="250" name="credit" ng-model="results.credit" value="">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Debit</label>
                    <input type="text" class="form-control input-sm" maxlength="250" name="debit" ng-model="results.debit" value="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Comment</label>
                    <textarea class="form-control" name="comment" rows="4" ng-model="results.comment"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" name="close" class="btn btn-default" ng-click="close()"><span class="fa fa-times"></span></button>
        <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
        <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" title="Insert Contact"><span class="fa fa-save"></span></button>
    </div>
</form>
