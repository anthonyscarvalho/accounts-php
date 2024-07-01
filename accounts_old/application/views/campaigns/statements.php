<div class="m-app-loading" ng-animate-children></div>
<ol class="breadcrumb">
    <li><a href="#/campaigns/edit/{{parent}}">Campaign</a></li>
    <li><a href="#/campaigns/clients/{{parent}}">Clients</a></li>
    <li><a href="#/campaigns/funds/{{parent}}">Funds</a></li>
    <li><a href="#/campaigns/emails/{{parent}}">Emails</a></li>
    <li><a href="#/campaigns/logs/{{parent}}">Log</a></li>
</ol>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Date</label>
                <input type="text" class="form-control input-sm" ng-model="date" value="" data-date-format="yyyy-mm-dd" data-provide="datepicker" placeholder="Start date">
            </div>
            <div class="form-group text-center">
                <button ng-click="load()" class="btn btn-sm btn-info"><span class="fa fa-search"></span></button>
                <button ng-click="emailStatement( client.id )" ng-show="results!=''" class="btn btn-sm btn-primary"><span class="fa fa-envelope"></span></button>
                <button ng-click="previewStatement()" ng-show="results!=''" class="btn btn-sm btn-warning"><span class="fa fa-print"></span></button>
                <button ng-click="clear()" class="btn btn-sm btn-default"><span class="fa fa-times"></span></button>
            </div>
            <p>Showing: {{( data.statements ).length}} records</p>
        </div>
    </div>
    <div class="col-md-9">
        <table class="table table-condensed table-hover table-striped click" at-table at-paginated at-list="list" at-config="config">
            <thead>
                <tr>
                    <th>ID</th>
                    <th custom-sort order="'date'" sort="sort">Date</th>
                    <th custom-sort order="'comment'" sort="sort">Description</th>
                    <th custom-sort order="'credit'" sort="sort">Credit</th>
                    <th custom-sort order="'debit'" sort="sort">Debit</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="res in results | orderBy:sort.sortType:sort.sortReverse">
                    <td>{{res.id}}</td>
                    <td>{{res.date}}</td>
                    <td>{{res.description}}</td>
                    <td>{{res.credit| currency:'R '}}</td>
                    <td>{{res.debit| currency:'R '}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
