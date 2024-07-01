<form ng-submit="add()" method="post" >
    <div class="modal-header">Add New Email Tempalte <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Template Name</label>
                    <input type="text" class="form-control" name="name" ng-model="data.name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Template Subject</label>
                    <input type="text" class="form-control" name="subject" ng-model="data.subject">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Template Email</label>
                    <textarea ng-model="data.body" ui-tinymce="" id="tinymce_content" class="form-control" style="height:300px" name="tinymce_content">{{data.body}}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
        <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" title="Insert Client"><span class="fa fa-save"></span></button>
    </div>
</form>