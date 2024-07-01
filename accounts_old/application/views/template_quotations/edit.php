<div class="modal-header">Edit Quotation Template #{{data.id}} <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <form ng-submit="save()" method="post" id="form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Template Name</label>
                    <input type="text" class="form-control input-sm" name="name" ng-model="data.name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Company</label>
                    <div class="btn-group btn-group-sm" data-toggle="buttons">
                        <label class="btn btn-default" ng-repeat="res in companies" ng-model="data.companies" uib-btn-radio="res.id" required>{{res.company}}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="well well-sm">
                    <legend>Content</legend>
                    <textarea ng-model="data.content" ui-tinymce="" class="form-control" style="height:250px">{{data.content}}</textarea>
                </div>
                <div class="well well-sm">
                    <legend>Included</legend>
                    <textarea ng-model="data.inclusions" ui-tinymce="" class="form-control" style="height:250px">{{data.inclusions}}</textarea>
                </div>
                <div class="well well-sm">
                    <legend>Warranty</legend>
                    <textarea ng-model="data.warranty" ui-tinymce="" class="form-control" style="height:250px">{{data.warranty}}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="well well-sm">
                    <legend>Extras</legend>
                    <textarea ng-model="data.extras" ui-tinymce="" class="form-control" style="height:250px">{{data.extras}}</textarea>
                </div>
                 <div class="well well-sm">
                    <legend>Excluded</legend>
                    <textarea ng-model="data.exclusions" ui-tinymce="" class="form-control" style="height:250px">{{data.exclusions}}</textarea>
                </div>
                <div class="well well-sm">
                    <legend>Signature</legend>
                    <textarea ng-model="data.signature" ui-tinymce="" class="form-control" style="height:250px">{{data.signature}}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="well well-sm">
                    <legend>Annexure A</legend>
                    <textarea ng-model="data.annexure_a" ui-tinymce="" class="form-control" style="height:250px">{{data.annexure_a}}</textarea>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
    <button ng-if="!submitted" type="submit" name="submitbtn" value="insert" class="btn btn-success" form="form"><span class="fa fa-save"></span></button>
</div>
