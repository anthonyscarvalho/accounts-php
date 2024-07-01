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
            <div class="form-group text-center">
                <button type="button" ng-click="reset()" class="btn btn-default"><span class="fa fa-times"></span></button>
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
    <div class="col-md-8">
        <table class="table table-condensed table-hover table-striped click">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Accepted</th>
                    <th>Accepted Date</th>
                    <th style="width: 40px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | filter:searchKeyword:strict || undefined | itemsPerPage: totalItems">
                    <td>{{res.id}}</td>
                    <td>{{res.date}}</td>
                    <td>{{res.description}}</td>
                    <td>{{res.accepted}}</td>
                    <td>{{res.accepted_date}}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-xs" ng-click="previewAtt( res.id )"><span class="fa fa-search"></span></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
