<div class="modal-header">Add New Category <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <form ng-submit="add()" method="post"  id="form">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" class="form-control" maxlength="300" ng-model="data.category" required="">
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" class="form-control" maxlength="300" ng-model="data.price">
                    </div>

                    <div class="form-group">
                        <label>Link</label>
                        <div class="btn-group btn-group-sm">
                            <label class="btn btn-default"ng-model="data.link" uib-btn-radio="'invoice'" uncheckable>Invoice</label>
                            <label class="btn btn-default" ng-model="data.link" uib-btn-radio="'expense'" uncheckable>Expense</label>
                        </div>
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
