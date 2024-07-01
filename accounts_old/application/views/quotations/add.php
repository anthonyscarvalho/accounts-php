<form method="post" ng-submit="add()">
    <div class="modal-header">Add New Quote <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group" ng-if="!addAll">
                    <label>Client #:</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control input-sm" maxlength="250" ng-model="data.clients" requried value="" disabled="disabled">
                    </div>
                </div>
                <div class="form-group" ng-if="addAll">
                    <label>Client</label>
                    <select class="form-control" id="clients" required chosen options="clients" ng-model="data.clients">
                        <option value="">Please select one</option>
                        <option ng-repeat="res in clients" ng-value="{{res.id}}">{{res.business}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>Company</label>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-default" ng-repeat="res in companies" ng-model="data.companies" uib-btn-radio="res.id" required>{{res.company}}</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>Quote Template</label>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-default" ng-repeat="res in teplates" ng-model="data.template" uib-btn-radio="res.id" required>{{res.name}}</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <label>Proposed Domain</label>
                <input type="text" class="form-control" maxlength="250" ng-model="data.domain" value="">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <label>Notes</label>
                <textarea ng-model="data.notes" ui-tinymce="" class="form-control" style="height:300px" name="tinymce_content"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
        <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" title="Insert Contact"><span class="fa fa-save"></span></button>
    </div>
</form>