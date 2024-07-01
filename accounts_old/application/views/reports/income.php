<div class="m-app-loading" ng-animate-children></div>
<ol class="breadcrumb">
    <li class="active"><a href="#/reports/income">Income</a></li>
    <li><a href="#/reports/expense">Expense</a></li>
    <li><a href="#/reports/adwords">Adwords</a></li>
</ol>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Year</label>
                <input type="text" class="form-control input-sm" ng-model="exp.year" data-date-format="yyyy" data-provide="datepicker" data-date-min-view-mode="2" data-date-max-view-mode="2">
            </div>
            <div class="form-group">
                <label>Company</label>
                <select ng-model="exp.companies" class="form-control input-sm">
                    <option ng-value="0" value="0">All</option>
                    <option ng-repeat="res in companies" ng-value="{{res.id}}">{{res.company}}</option>
                </select>
            </div>
            <div class="form-group">
                <label>Summary Type</label>
                <div class="btn-group" role="group" data-toggle="buttons">
                    <label class="btn btn-default" ng-model="exp.summary" uib-btn-radio="'grouped'">Grouped</label>
                    <label class="btn btn-default" ng-model="exp.summary" uib-btn-radio="'full'">Full</label>
                </div>
            </div>
            <div class="form-group text-center">
                <button type="button" ng-click="load()" class="btn btn-default"><span class="fa fa-search"></span></button>
                <button ng-click="reset()" class="btn btn-default"><span class="fa fa-times"></span></button>
                <button type="button" ng-click="exportToExcel( '#overview' )" class="pull-right btn btn-primary"><span class="fa fa-download"></span></button>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-condensed table-hover table-striped" id="overview">
            <thead>
                <tr>
                    <th></th>
                    <th>Date</th>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody ng-repeat="res in results" ng-init="val = updateTotal( res.data )">
                <tr>
                    <td>{{res.date}}</td>
                    <td colspan="5"></td>
                </tr>
                <tr ng-repeat="ress in res.data">
                    <td></td>
                    <td>{{ress.date}}</td>
                    <td>{{ress.id}}</td>
                    <td>{{ress.client}}</td>
                    <td>{{ress.description}}</td>
                    <td>{{ress.credit}}</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-right">Total</td>
                    <td>{{val| currency:'R '}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
