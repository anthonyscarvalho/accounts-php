<div class="m-app-loading" ng-animate-children></div>
<?php
include("../nav_main.php");
?>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Search</label>
                <input class="form-control" ng-model="searchKeyword.$" type="text" ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 500, 'blur': 0 } }">
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
                    <th>ID</th>
                    <th custom-sort order="'date'" sort="sort">Date</th>
                    <th custom-sort order="'userName'" sort="sort">User</th>
                    <th custom-sort order="'action'" sort="sort">Action</th>
                    <th custom-sort order="'affected_table'" sort="sort">Table</th>
                    <th style="width: 30px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | filter:searchKeyword:strict || undefined | itemsPerPage: totalItems">
                    <td>{{res.id}}</td>
                    <td>{{res.date}}</td>
                    <td>{{res.userName}}</td>
                    <td>{{res.action}}</td>
                    <td>{{res.affected_table}}</td>
                    <td>
                        <button type="button" ng-click="preview( res.id )" class="btn btn-info btn-xs"><span class="fa fa-search"></span></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
