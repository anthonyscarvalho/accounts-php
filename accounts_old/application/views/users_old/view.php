<div class="m-app-loading" ng-animate-children></div>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Search</label>
                <input class="form-control input-sm" ng-model="searchKeyword.$" type="text" ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 500, 'blur': 0 } }">
            </div>
            <div class="form-group">
                <div class="text-center">
                    <button ng-click="reset()" class="btn btn-default"><span class="fa fa-times"></span></button>
                    <button type="button" ng-click="add()" class="btn btn-primary"><span class="fa fa-plus"></span></button>
                </div>
            </div>

            <p>Showing: {{(results|filter:searchKeyword).length}} of {{( results ).length}} records</p>
            <div class="form-group">
                <label>Show items per page</label>
                <select ng-model="totalItems">
                    <option ng-repeat="res in pagnation.dropdown" ng-value="{{res.number}}">{{res.name}}</option>
                </select>
            </div>
        </div>
        <dir-pagination-controls max-size="pagnation.maxsize" direction-links="true" boundary-links="true" ></dir-pagination-controls>
    </div>
    <div class="col-md-6">
        <table class="table table-condensed table-hover table-striped dataTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th custom-sort order="'name'" sort="sort">Name</th>
                    <th custom-sort order="'surname'" sort="sort">Surname</th>
                    <th>Username</th>
                    <th style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | filter:searchKeyword:strict || undefined | itemsPerPage: totalItems">
                    <td>{{res.id}}</td>
                    <td>{{res.name}}</td>
                    <td>{{res.surname}}</td>
                    <td>{{res.username}}</td>
                    <td>
                        <span ng-if="userRoles.edit == 'true'">
                        <a class="btn btn-info btn-xs" ng-click="edit(res.id)"><span class="fa fa-pencil"></span></a>
                        </span>
                        <span ng-if="userRoles.cancel == 'true'">
                            <a class="btn btn-danger btn-xs" ng-click="enable( res.id )" ng-show="res.canceled == 'true'"><span class="fa fa-times"></span></a>
                            <a class="btn btn-success btn-xs" ng-click="cancel( res.id )" ng-show="res.canceled == 'false'"><span class="fa fa-check"></span></a>
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