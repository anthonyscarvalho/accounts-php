
<div class="modal-header">Edit Email Template  #{{results.id}} <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <form ng-submit="save()" method="post" id="form">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Template Name</label>
                        <input type="text" class="form-control" name="name" ng-model="results.name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Template Subject</label>
                        <input type="text" class="form-control" name="subject" ng-model="results.subject">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Template Email</label>
                        <textarea ng-model="results.body" ui-tinymce="" id="tinymce_content" class="form-control" style="height:300px" name="tinymce_content"></textarea>
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
