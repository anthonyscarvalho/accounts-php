<div class="m-app-loading" ng-animate-children></div>
<ol class="breadcrumb">
    <li><a href="#/reports/income">Income</a></li>
    <li><a href="#/reports/expense">Expense</a></li>
    <li class="active"><a href="#/reports/adwords">Adwords</a></li>
</ol>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Year</label>
                <input type="text" class="form-control" maxlength="250" ng-model="exp.year" value="" required data-date-format="yyyy" data-provide="datepicker" data-date-min-view-mode="2" data-date-max-view-mode="2">
            </div>
            <div class="form-group">
                <div class="text-center">
                    <button type="button" ng-click="load()" class="btn btn-default"><span class="fa fa-search"></span></button>
                    <button ng-click="reset()" class="btn btn-default"><span class="fa fa-times"></span></button>
                </div>
            </div>
            <p>Showing: {{(results|filter:searchKeyword).length}} of {{( results ).length}} records</p>
        </div>
    </div>
    <div class="col-md-6">
        <table class="table table-condensed table-hover table-striped click" id="incomeOverview">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Payments</th>
                    <th>Expense</th>
                    <th>Commission</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="res in results">
                    <td>{{res.clientName}}</td>
                    <td>{{res.income| currency:'R '}}</td>
                    <td>{{res.income - res.commission| currency:'R '}}</td>
                    <td>{{res.commission| currency:'R '}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
