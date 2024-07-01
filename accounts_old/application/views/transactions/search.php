<div class="m-app-loading" ng-animate-children></div>
<?php
include("../nav_main.php");
?>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group text-center">
                <button ng-click="addTrans( parent )" class="btn btn-default"><span class="fa fa-plus"></span></button>
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
                    <th custom-sort order="'description'" sort="sort">Description</th>
                    <th custom-sort order="'credit'" sort="sort">Credit</th>
                    <th custom-sort order="'debit'" sort="sort">Debit</th>
                    <th style="width: 60px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | itemsPerPage: totalItems">
                    <td>{{res.id}}</td>
                    <td>{{res.date}}</td>
                    <td>{{res.description}}</td>
                    <td>{{res.credit}}</td>
                    <td>{{res.debit}}</td>
                    <td>
                        <div ng-show="res.credit">
                            <span>
                                <button  class="btn btn-info btn-xs" ng-click="edit( res.id )"><span class="fa fa-pencil"></span></button>
                            </span>
                            <span>
                                <button type="button" class="btn btn-warning btn-xs" ng-click="delete( res.id )"><span class="fa fa-trash"></span></button>
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
