<form ng-submit="save()" method="post" >
    <div class="modal-header">Add New User <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" maxlength="300" ng-model="results.name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Surname</label>
                    <input type="text" class="form-control" maxlength="300" ng-model="results.surname">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" maxlength="100" ng-model="results.username">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="text" class="form-control" maxlength="100" ng-model="results.password">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="text" class="form-control" maxlength="20" ng-model="results.email_address">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>User Role</label><br>
                    <div class="btn-group" role="group" data-toggle="buttons">
                        <label class="btn btn-default" ng-repeat="res in roles" ng-model="results.roles" uib-btn-radio="res.id">{{res.role}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
        <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" title="Insert Client"><span class="fa fa-save"></span></button>
    </div>
</form>
