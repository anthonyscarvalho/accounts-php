<div class="m-app-loading" ng-animate-children></div>
<?php
include("../nav_main.php");
?>
<div class="well well-sm">
    <div class="row">
        <div class="col-sm-12">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">Search</span>
                <input class="form-control" ng-model="searchKeyword.$" type="text" ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 500, 'blur': 0 } }">
                <span class="input-group-addon">Status</span>
                <select class="form-control" ng-model="searchKeyword.canceled">
                    <option selected="selected" value>All</option>
                    <option value="true">Canceled</option>
                    <option value="false">Active</option>
                </select>
                <span class="input-group-btn">
                    <button ng-click="reset()" class="btn btn-sm btn-default" data-toggle="tooltip" title="Reset Filter">Reset</button>
                </span>
                <span class="input-group-btn">
                    <button type="button" ng-click="add( parent )" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Add Client"><span class="glyphicon glyphicon-plus"></span></button>
                </span>
            </div>
        </div>
    </div>
</div>
<table class="table table-condensed table-hover table-striped click">
    <thead>
        <tr>
            <th >ID</th>
            <th custom-sort order="'name'" sort="sort">Name</th>
            <th custom-sort order="'surname'" sort="sort">Surname</th>
            <th custom-sort order="'contact_number_1'" sort="sort">Number 1</th>
            <th custom-sort order="'contact_number_2'" sort="sort">Number 2</th>
            <th custom-sort order="'email'" sort="sort">Email</th>
            <th>Canceled</th>
            <th style="width: 100px;"></th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="res in results | orderBy:sortType:sortReverse | filter:searchKeyword:strict||undefined">
            <td>{{res.id}}</td>
            <td>{{res.name}}</td>
            <td>{{res.surname}}</td>
            <td>{{res.contact_number_1}}</td>
            <td>{{res.contact_number_2}}</td>
            <td>{{res.email}}</td>
            <td>{{res.canceled}}</td>
            <td>
                <span ng-if="userRoles.edit == 'true'">
                    <a class="btn btn-info btn-xs" ng-click="edit( res.id )"><span class="fa fa-pencil"></span></a>
                </span>
                <span ng-if="userRoles.cancel == 'true'">
                    <a class="btn btn-danger btn-xs" ng-click="enable( res.id, loaded )" ng-show="res.canceled == 'true'"><span class="fa fa-times"></span></a>
                    <a class="btn btn-success btn-xs" ng-click="cancel( res.id, loaded )" ng-show="res.canceled == 'false'"><span class="fa fa-check"></span></a>
                </span>
                <span ng-if="userRoles.delete == 'true'">
                    <a class="btn btn-warning btn-xs" ng-click="delete( res.id )"><span class="fa fa-trash"></span></a>
                </span>
            </td>
        </tr>
    </tbody>
</table>
