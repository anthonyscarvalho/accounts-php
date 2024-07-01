<div class="modal-header">Edit Transaction #{{results.id}} <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <form method="post" ng-submit="save()" id="form">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-offset-3">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" class="form-control input-sm" maxlength="250" ng-model="results.date" value="" required data-date-format="yyyy-mm-dd" data-provide="datepicker">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control input-sm" required maxlength="250" ng-model="results.description" value="">
                    </div>
                    <div class="form-group">
                        <label>Credit</label>
                        <input type="text" class="form-control input-sm" required ng-model="results.credit" value="">
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                            <label class="btn btn-default" ng-repeat="res in companies" ng-model="results.companies" uib-btn-radio="res.id" required>{{res.company}}</label>
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
