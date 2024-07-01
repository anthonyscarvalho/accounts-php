<div class="m-app-loading" ng-animate-children></div>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Search</label>
                <input class="form-control" ng-model="searchKeyword.$" type="text" ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 500, 'blur': 0 } }">
            </div>

            <div class="form-group text-center">
                <button ng-click="reset()" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-refresh"></span></button>
                <button type="button" ng-click="add()" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span></button>
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
    <div class="col-md-6">
        <table class="table table-condensed table-hover table-striped click">
            <thead>
                <tr>
                    <th>ID</th>
                    <th custom-sort order="'name'" sort="sort">Name</th>
                    <th custom-sort order="'created'" sort="sort">Created</th>
                    <th custom-sort order="'credit'" sort="sort">Credit</th>
                    <th style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | filter:searchKeyword:strict || undefined | itemsPerPage: totalItems">
                    <td>{{res.id}}</td>
                    <td>{{res.name}}</td>
                    <td>{{res.created}}</td>
                    <td>{{res.credit}}</td>
                    <td>
                        <span ng-if="userRoles.edit == 'true'">
                            <a class="btn btn-info btn-xs" href="#/campaigns/edit/{{res.id}}"><span class="fa fa-pencil"></span></a>
                        </span>
                        <span ng-if="userRoles.delete == 'true'">
                            <a class="btn btn-warning btn-xs" ng-click="delete( res.id )"><span class="fa fa-trash"></span></a>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
