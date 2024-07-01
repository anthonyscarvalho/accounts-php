<div class="modal-header">Edit Product #{{results.id}} <button type="button" ng-click="close()" class="close">&times;</button></div>
<div class="modal-body">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <legend>Product Details</legend>
                <form method="post" ng-submit="save()" id="form">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Client #:</label>
                                <input type="text" class="form-control input-sm" maxlength="250" ng-model="results.clients" value="" disabled="disabled">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Item</label>
                                <input type="text" class="form-control input-sm" disabled ng-model="category.category" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control" maxlength="250" ng-model="results.date" value="" ng-disabled="userRoles.id != '2'" required data-date-format="yyyy-mm-dd" data-provide="datepicker">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="text" class="form-control" maxlength="250" ng-model="results.price" id="price" required value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" maxlength="40" ng-model="results.description" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Company</label>
                                <div class="btn-group btn-group-sm" data-toggle="buttons">
                                    <label ng-disabled="userRoles.id != '2'" class="btn btn-default" ng-repeat="res in companies" ng-model="results.companies" uib-btn-radio="res.id" ng-class="{'disabled':userRoles.id != '2'}">{{res.company}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="well well-sm">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Renewable</label>
                                    <div class="btn-group btn-group-sm" data-toggle="buttons">
                                        <label ng-disabled="userRoles.id != '2'" class="btn btn-default" ng-repeat="res in renewable" ng-model="results.renewable" uib-btn-radio="res.id" required>{{res.name}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Period</label>
                                    <div class="input-group input-group-sm">
                                        <input disabled type="text" class="form-control" ng-model="results.period" value="">
                                        <span class="input-group-addon">months</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Canceled Date</label>
                                    <input type="text" class="form-control" maxlength="250" ng-model="results.canceled_date" disabled value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div class="well well-sm">
                    <legend>Invoice History</legend>
                    <table class="table table-condensed table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="res in invoices_items">
                                <td>{{res.date}}</td>
                                <td>{{res.price}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
    <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" form="form"><span class="fa fa-save"></span></button>

</div>

