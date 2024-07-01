<div class="m-app-loading" ng-animate-children></div>
<div class="well well-sm">
    <form ng-submit="loadInvoices()">
        <div class="row">
            <div class="col-md-2">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" maxlength="250" ng-model="year" value="" required data-date-format="yyyy" data-provide="datepicker" data-date-min-view-mode="2" data-date-max-view-mode="2">
                    <span class="input-group-btn">
                        <button type="submit"  class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
            </div>
            <div class="col-md-10">
                <div class="btn-group" role="group" data-toggle="buttons">
                    <label class="btn btn-default" ng-repeat="res in companies" ng-model="data.activeCompany" uib-btn-radio="res.id" ng-click="loadInvoices()">{{res.company}}</label>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Invoices</div>
            <div class="panel-body">
                <canvas class="chart chart-line chart-xl" data="invoices.data" labels="invoices.labels" legend="true" series="invoices.series" colours="lineGraph" options="graphoptions"  height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Monthly Income</div>
            <div class="panel-body">
                <canvas class="chart chart-bar chart-xl" data="monthly.data" labels="monthly.labels" legend="true" series="monthly.series" colours="barGraph" options="graphoptions" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">5 Year Annual Income</div>
            <div class="panel-body">
                <canvas class="chart chart-bar chart-xl" data="anual.data" labels="anual.labels" legend="true" series="anual.series" colours="barGraph" options="graphoptions" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Expected vs Actual</div>
            <div class="panel-body">
                <canvas class="chart chart-bar chart-xl" data="monthlyPred.data" labels="monthlyPred.labels" legend="true" series="monthlyPred.series" colours="barGraph" options="graphoptions" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">3 Year Monthly Income</div>
            <div class="panel-body">
                <canvas class="chart chart-bar chart-xl" data="monthlyPrev.data" labels="monthlyPrev.labels" legend="true" series="monthlyPrev.series" colours="barGraph" options="graphoptions" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">5 Year Annual Income vs Expense</div>
            <div class="panel-body">
                <canvas class="chart chart-bar chart-xl" data="annualExp.data" labels="annualExp.labels" legend="true" series="annualExp.series" colours="barGraph" options="graphoptions" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Recent Emails</div>
            <div class="panel-body">
                <table class="table table-condensed table-striped">
                    <tr ng-repeat="res in emails">
                        <td>{{res.id}}</td>
                        <td>{{res.username}}</td>
                        <td>{{res.contactname}}</td>
                        <td>{{res.subject}}</td>
                        <td>{{res.date}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
