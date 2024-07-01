<div class="m-app-loading" ng-animate-children></div>

<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Client</label>
                <select class="form-control" required chosen options="clients" ng-model="data.clients">
                    <option ng-repeat="res in clients" ng-value="{{res.id}}">{{res.business}}</option>
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control input-sm" ng-model="data.startDate" value="" data-date-format="yyyy-mm-dd" data-provide="datepicker" placeholder="Start date">
                    <span class="input-group-addon"> - </span>
                    <input type="text" class="form-control input-sm" ng-model="data.endDate" value="" data-date-format="yyyy-mm-dd" data-provide="datepicker" placeholder="End date">
                </div>
            </div>
            <div class="form-group text-center">
                <button ng-click="load()" class="btn btn-sm btn-info"><span class="fa fa-search"></span></button>
                <button ng-click="emailStatement( client.id )" ng-show="data.clients!=''" class="btn btn-sm btn-primary"><span class="fa fa-envelope"></span></button>
                <button ng-click="previewStatement()" ng-show="data.clients!=''" class="btn btn-sm btn-warning"><span class="fa fa-print"></span></button>
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
                <tr ng-repeat="res in data.statements | orderBy:sort.sortType:sort.sortReverse">
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
