<div class="m-app-loading" ng-animate-children></div>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Load:</label>
                <div class="btn-group" role="group" data-toggle="buttons">
                    <label class="btn btn-default" ng-class="{'active': loaded == ''}"><input type="radio" name="state" ng-click="load( '' )" />All</label>
                    <label class ="btn btn-default" ng-class="{'active': loaded == 'pending'}"><input type="radio" name="state" ng-click="load( 'pending' )" />Pending</label>
                    <label class ="btn btn-default" ng-class="{'active': loaded == 'false'}"><input type="radio" name="state" ng-click="load( 'false' )" />Accepted</label>
                    <label class="btn btn-default" ng-class="{'active': loaded == 'true'}"><input type="radio" name="state" ng-click="load( 'true' )" />Canceled</label>
                </div>
            </div>
            <div class="form-group">
                <label>Search</label>
                <input class="form-control" ng-model="searchKeyword.$" type="text" ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 500, 'blur': 0 } }">
            </div>
            <div class="form-group text-center">
                <button ng-click="reset()" class="btn btn-sm btn-default" data-toggle="tooltip" title="Reset Filter">Reset</button>
                <button type="button" ng-click="add()" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Add Client"><span class="glyphicon glyphicon-plus"></span></button>
            </div>
            <div class="form-group">
                <label>Showing</label>
                <select ng-model="totalItems">
                    <option ng-repeat="res in pagnation.dropdown" ng-value="{{res.number}}">{{res.name}}</option>
                </select> of {{(results|filter:searchKeyword).length}} records
            </div>
            <dir-pagination-controls max-size="pagnation.maxsize" direction-links="true" boundary-links="true" ></dir-pagination-controls>
        </div>
    </div>
    <div class="col-md-9">
        <table class="table table-condensed table-hover table-striped click">
            <thead>
                <tr>
                    <th>ID</th>
                    <th custom-sort order="'clientName'" sort="sort">Client</th>
                    <th custom-sort order="'creation_date'" sort="sort">Created</th>
                    <th custom-sort order="'accepted_date'" sort="sort">Accepted</th>
                    <th custom-sort order="'canceled_date'" sort="sort">Canceled</th>
                    <th style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | filter:searchKeyword:strict || undefined | itemsPerPage: totalItems">
                    <td>{{res.id}}</td>
                    <td>{{res.clientName}}</td>
                    <td>{{res.creation_date}}</td>
                    <td>{{res.canceled_date}}</td>
                    <td>{{res.accepted_date}}</td>
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
    </div>
</div>
