<div class="m-app-loading" ng-animate-children></div>
<ol class="breadcrumb">
    <li><a href="#/campaigns/edit/{{parent}}">Campaign</a></li>
    <li><a href="#/campaigns/clients/{{parent}}">Clients</a></li>
    <li><a href="#/campaigns/funds/{{parent}}">Funds</a></li>
    <li class="active"><a href="#/campaigns/emails/{{parent}}">Emails</a></li>
    <li><a href="#/campaigns/logs/{{parent}}">Log</a></li>
</ol>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Search</label>
                <input class="form-control" ng-model="searchKeyword.$" type="text" ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 500, 'blur': 0 } }">
            </div>
            <div class="form-group text-center">
                <button ng-click="reminder( parent )" class="btn btn-info"><span class="fa fa-envelope"></span></button>
            </div>
            <div class="form-group">
                <label>Showing</label>
                <select ng-model="totalItems">
                    <option ng-repeat="res in pagnation.dropdown" ng-value="{{res.number}}">{{res.name}}</option>
                </select> of {{(results|filter:searchKeyword).length}} records
            </div>
        </div>
        <dir-pagination-controls max-size="pagnation.maxsize" direction-links="true" boundary-links="true" ></dir-pagination-controls>
    </div>
    <div class="col-md-9">
        <table class="table table-condensed table-hover table-striped click">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Contact</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th style="width: 40px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | filter:searchKeyword:strict || undefined | itemsPerPage: totalItems">
                    <td>{{res.userName}}</td>
                    <td>{{res.contactName}}</td>
                    <td>{{res.subject}}</td>
                    <td>{{res.date}}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-xs" ng-click="preview( res.id )"><span class="fa fa-search"></span></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
