<div class="m-app-loading" ng-animate-children></div>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Search</label>
                <input class="form-control input-sm" ng-model="searchKeyword.$" type="text" ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 500, 'blur': 0 } }">
            </div>
            <div class="form-group">
                <label>Link</label>
                <select class="form-control input-sm" ng-model="searchKeyword.link">
                    <option selected="selected" value>All</option>
                    <option value="invoice">Invoice</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="form-group">
                <div class="text-center">
                    <button ng-click="reset()" class="btn btn-default"><span class="fa fa-times"></span></button>
                    <button type="button" ng-click="add()" class="btn btn-primary"><span class="fa fa-plus"></span></button>
                </div>
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
                    <th custom-sort order="'category'" sort="sort">Category</th>
                    <th custom-sort order="'price'" sort="sort">Price</th>
                    <th custom-sort order="'link'" sort="sort">Link</th>
                    <th style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | filter:searchKeyword:strict || undefined | itemsPerPage: totalItems">
                    <td>{{res.id}}</td>
                    <td>{{res.category}}</td>
                    <td>{{res.price}}</td>
                    <td>{{res.link}}</td>
                    <td>
                        <span ng-if="userRoles.edit == 'true'">
                            <button type="button" class="btn btn-info btn-xs" ng-click="edit( res.id )"><span class="fa fa-pencil"></span></button>
                        </span>
                        <span ng-if="userRoles.enable == 'true'">
                            <a class="btn btn-danger btn-xs" ng-click="update( res.id, 'enable' )" ng-show="res.canceled == 'true'"><span class="fa fa-square-o"></span></a>
                        </span>
                        <span ng-if="userRoles.cancel == 'true'">
                            <a class="btn btn-success btn-xs" ng-click="update( res.id, 'cancel' )" ng-show="res.canceled == 'false'"><span class="fa fa-square"></span></a>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>
