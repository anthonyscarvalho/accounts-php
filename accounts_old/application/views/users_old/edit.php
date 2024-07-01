 <form method="post" ng-submit="save()">
    <div class="modal-header">Edit User #{{data.id}} <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
    <div class="modal-body">
        <fieldset>
            <legend>User Info</legend>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control input-sm" maxlength="300" ng-model="data.name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Surname</label>
                        <input type="text" class="form-control input-sm" maxlength="300" ng-model="data.surname">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control input-sm" maxlength="100" ng-model="data.username">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="text" class="form-control input-sm" maxlength="100" ng-model="data.newpassword">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Repeat Password</label>
                        <input type="text" class="form-control input-sm" maxlength="100" ng-model="data.repeatpassword">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>Email Address</label>
                    <input type="text" class="form-control input-sm" maxlength="20" ng-model="data.email_address">
                </div>
            </div>
        </fieldset>
    </div>
    <div class="modal-footer">
        <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
        <button ng-if="!submitted" type="submit" name="submit" value="save" class="btn btn-success"><span class="fa fa-save"></span></button>
         <button type="button" name="submit" value="insert" class="btn btn-info" ng-click="permissions(data.access_list)"><span class="fa fa-key"></span></button>
    </div>
</form>