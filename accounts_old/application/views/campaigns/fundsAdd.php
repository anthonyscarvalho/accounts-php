<div class="modal-header">Add Transaction <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <form method="post" ng-submit="add()" id="form">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Client</label>
                        <select class="form-control input-sm" id="clients" chosen options="clients" ng-model="data.clients">
                            <option value="">Please select one</option>
                            <option ng-repeat="res in clients" ng-value="{{res.id}}">{{res.business}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" class="form-control input-sm" name="date" ng-model="data.date" required data-date-format="yyyy-mm-dd" data-provide="datepicker">
                    </div>
                </div>
            </div>
            <div class="row">
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
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Commission</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                            <label class="btn btn-default" ng-model="data.commission" uib-btn-radio="'true'">True</label>
                            <label class="btn btn-default" ng-model="data.commission" uib-btn-radio="'false'">False</label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Email</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                            <label class="btn btn-default" ng-model="data.email" uib-btn-radio="'true'" uncheckable>True</label>
                            <label class="btn btn-default" ng-model="data.email" uib-btn-radio="'false'" uncheckable>False</label>
                        </div>
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
    </form>
</div>
<div class="modal-footer">
    <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
    <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" form="form"><span class="fa fa-save"></span></button>
</div>
