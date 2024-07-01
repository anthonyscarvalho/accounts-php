
<div class="modal-header">Add New Product <button type="button" ng-click="close()" class="close">&times;</button></div>
<div class="modal-body">
    <form method="post" ng-submit="add()" id="form">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <legend>Product Details</legend>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Client #:</label>
                                <input type="text" class="form-control input-sm" maxlength="250" ng-model="data.clients" value="" disabled="disabled" ng-if="!addAll">
                                <select class="form-control" id="clients" chosen options="clients" ng-model="data.clients" ng-if="addAll">
                                    <option value="">Please select one</option>
                                    <option ng-repeat="res in clients" ng-value="{{res.id}}">{{res.business}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Item</label>
                                <select class="form-control" ng-model="data.categories" id="categories" chosen name="categories" options="categories" ng-change="updatePrice()">
                                    <option value="">Please select one</option>
                                    <option ng-repeat="res in categories" value="{{res.id}}" data-price="{{res.price}}">{{res.category}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control" maxlength="250" ng-model="data.date" value="" data-date-format="yyyy-mm-dd" data-provide="datepicker">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="text" class="form-control" maxlength="250" ng-model="data.price" id="price" requried value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" maxlength="40" ng-model="data.description" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Company</label>
                                <div class="btn-group btn-group-sm" data-toggle="buttons">
                                    <label class="btn btn-default" ng-repeat="res in companies" ng-model="data.companies" uib-btn-radio="res.id" name="company" uncheckable>{{res.company}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="well well-sm">
                        <div class="form-group">
                            <label>Renewable</label>
                            <div class="btn-group btn-group-sm" data-toggle="buttons">
                                <label class="btn btn-default" ng-repeat="res in renewable" ng-model="data.renewable" uib-btn-radio="res.id" name="renewable" uncheckable>{{res.name}}</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Period</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" ng-model="data.period" value="">
                                        <span class="input-group-addon">months</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Canceled</label>
                                    <div class="btn-group btn-group-sm" data-toggle="buttons">
                                        <label class="btn btn-default disabled" ng-class="{'active': data.canceled == 'true'}" ng-model="data.canceled" uib-btn-radio="'true'" uncheckable>True</label>
                                        <label class="btn btn-default disabled" ng-class="{'active': data.canceled == 'false'}" ng-model="data.canceled" uib-btn-radio="'false'" uncheckable>False</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Canceled Date</label>
                                    <input type="text" class="form-control" maxlength="250" ng-model="data.canceled_date" disabled value="">
                                </div>
                            </div>
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

