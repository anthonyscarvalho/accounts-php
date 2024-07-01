<form method="post" ng-submit="add()">
    <div class="modal-header">Add Ad <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group" ng-if="!addAll">
                    <label>Client #:</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control input-sm" maxlength="250" ng-model="data.clients" value="" disabled="disabled">
                    </div>
                </div>
                <div class="form-group" ng-if="addAll">
                    <label>Client</label>
                    <select class="form-control input-sm" id="clients" required chosen options="clients" ng-model="data.clients">
                        <option value="">Please select one</option>
                        <option ng-repeat="res in clients" ng-value="{{res.id}}">{{res.business}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Date</label>
                    <input type="text" class="form-control input-sm" name="date" ng-model="data.date" required data-date-format="yyyy-mm-dd" data-provide="datepicker">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Credit</label>
                    <input type="text" class="form-control input-sm" maxlength="250" name="credit" ng-model="data.credit" value="">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Debit</label>
                    <input type="text" class="form-control input-sm" maxlength="250" name="debit" ng-model="data.debit" value="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Comment</label>
                    <textarea class="form-control" name="comment" rows="4" ng-model="data.comment"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
    <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success"><span class="fa fa-save"></span></button>
</div>
</form>