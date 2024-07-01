<div class="modal-header">Add New Expense <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <form ng-submit="add()" method="post" id="form" >
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
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
                            <div class="form-group">
                                <label>Date (yyyy-mm-dd)</label>
                                <input type="text" class="form-control" maxlength="250" ng-model="data.date" value="" required data-date-format="yyyy-mm-dd" data-provide="datepicker">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Expense Type</label>
                                <div class="btn-group btn-group-sm" data-toggle="buttons">
                                    <label class="btn btn-default" ng-model="data.type" uib-btn-radio="'Fixed Cost'" required>Fixed Cost</label>
                                    <label class="btn btn-default" ng-model="data.type" uib-btn-radio="'Supplemental'" required>Supplemental</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Category</label>
                                 <select class="form-control" required ng-model="data.categories" chosen options="categories">
                            <option value="">Please select one</option>
                            <option ng-repeat="res in categories" value="{{res.id}}" required>{{res.category}}</option>
                        </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" class="form-control" maxlength="250" ng-model="data.amount" id="amount" required value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" maxlength="250" ng-model="data.description" id="price" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="well well-sm">
                        <legend>Recent Records</legend>
                        <table class="table table-condensed">
                            <tr>
                                <th>Id</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                            <tr ng-repeat="res in recentRecords | orderBy:date:true">
                                <td>{{res.id}}</td>
                                <td>{{res.date}}</td>
                                <td>{{res.amount}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <div class="row">
        <div class="col-md-12 text-center">
            <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
            <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" form="form"><span class="fa fa-save"></span></button>
        </div>
    </div>
</div>
