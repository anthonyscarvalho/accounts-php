<form ng-submit="add()" method="post" >
    <div class="modal-header">Add New Company <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" class="form-control" ng-model="data.company">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Bank Details</label>
                    <textarea ng-model="data.account_details"  class="form-control" ui-tinymce=""  style="height:150px">{{data.account_details}}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Invoice Header</label>
                    <textarea ng-model="data.invoice_header" ui-tinymce="" class="form-control" style="height:150px">{{data.invoice_header}}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12 text-center">
                <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
                <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" title="Insert Client"><span class="fa fa-save"></span></button>
            </div>
        </div>
    </div>
</form>