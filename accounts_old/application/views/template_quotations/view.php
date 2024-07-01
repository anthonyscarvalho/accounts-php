<div class="m-app-loading" ng-animate-children></div>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Search</label>
                <input class="form-control input-sm" ng-model="searchKeyword.$" type="text" ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 500, 'blur': 0 } }">
            </div>
            <div class="form-group">
                <label>Company</label>
                <select class="form-control input-sm" ng-model="searchKeyword.companies">
                    <option selected="selected" value>All</option>
                    <option ng-repeat="res in companies" value="{{res.id}}">{{res.company}}</option>
                </select>
            </div>
            <div class="form-group text-center">
                <button ng-click="reset()" class="btn btn-default"><span class="fa fa-times"></span></button>
                <button type="button" ng-click="add()" class="btn btn-primary"><span class="fa fa-plus"></span></button>
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
    <div class="col-md-4">
        <table class="table table-condensed table-hover table-striped click">
            <thead>
                <tr>
                    <th>ID</th>
                    <th custom-sort order="'name'" sort="sort">Name</th>
                    <th custom-sort order="'date_created'" sort="sort">Created</th>
                    <th style="width: 40px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | filter:searchKeyword:strict || undefined | itemsPerPage: totalItems">
                    <td>{{res.id}}</td>
                    <td>{{res.name}}</td>
                    <td>{{res.date_created}}</td>
                    <td>
                        <span ng-if="userRoles.edit == 'true'">
                            <a class="btn btn-info btn-xs" ng-click="edit( res.id )"><span class="fa fa-pencil"></span></a>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
