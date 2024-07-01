<div class="m-app-loading" ng-animate-children></div>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Load:</label>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-default" ng-model="data.state" uib-btn-radio="''" ng-click="load()">All</label>
                    <label class="btn btn-default" ng-model="data.state" uib-btn-radio="'false'" ng-click="load()">Active</label>
                    <label class="btn btn-default" ng-model="data.state" uib-btn-radio="'true'" ng-click="load()">Canceled</label>
                    <label class="btn btn-default" ng-model="data.state" uib-btn-radio="'due'" ng-click="load()">Due</label>
                </div>
            </div>
            <div class="form-group">
                <label>Search</label>
                <input class="form-control input-sm" ng-model="searchKeyword.$" type="text" ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 500, 'blur': 0 } }">
            </div>
            <div class="form-group">
                <label>Company</label>
                <select class="form-control input-sm" ng-model="searchKeyword.companies">
                    <option selected="selected" value>All</option>
                    <option value="1">ZAWebs</option>
                    <option value="2">ZAWebDesigns</option>
                    <option value="3">ZAWebPortals</option>
                </select>
            </div>
            <div class="form-group text-center">
                <button type="button" ng-click="reset()" class="btn btn-default"><span class="fa fa-times"></span></button>
                <button type="button" ng-click="add()" class="btn btn-primary"><span class="fa fa-plus"></span></button>
            </div>
            <div ng-show="data.state == 'due'">
                <div class="form-group">
                    <label>Date for due products:</label>
                    <input type="text" class="form-control input-sm" ng-model="data.date" data-date-format="yyyy-mm-dd" data-provide="datepicker">
                </div>
                <div class="form-group text-center">
                    <button type="button" ng-click="load()" class="btn btn-primary"><span class="fa fa-search"></span></button>
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
    <div class="col-md-9">
        <table class="table table-condensed table-hover table-striped click">
            <thead>
                <tr>
                    <th custom-sort order="'date'" sort="sort">Date</th>
                    <th custom-sort order="'clientName'" sort="sort">Client</th>
                    <th custom-sort order="'categoryName'" sort="sort">Category</th>
                    <th custom-sort order="'description'" sort="sort">Description</th>
                    <th custom-sort order="'price'" sort="sort">Price</th>
                    <th custom-sort order="'renewable'" sort="sort">Renewable</th>
                    <th custom-sort order="'last_invoice'" sort="sort">Last Invoice</th>
                    <th style="width: 120px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | filter:searchKeyword:strict || undefined | itemsPerPage: totalItems">
                    <td>{{res.date}}</td>
                    <td>{{res.clientName}}</td>
                    <td>{{res.categoryName}}</td>
                    <td class="short-description">{{res.description}}</td>
                    <td>{{res.price}}</td>
                    <td>{{res.renewable}}</td>
                    <td>{{res.lastInvoice}}</td>
                    <td>
                        <span ng-if="userRoles.edit == 'true'">
                            <a class="btn btn-info btn-xs" ng-click="edit( res.id )"><span class="fa fa-pencil"></span></a>
                        </span>
                        <span ng-if="userRoles.enable == 'true'">
                            <a class="btn btn-danger btn-xs" ng-click="update( res.id, 'enable' )" ng-show="res.canceled == 'true'"><span class="fa fa-square-o"></span></a>
                        </span>
                        <span ng-if="userRoles.cancel == 'true'">
                            <a class="btn btn-success btn-xs" ng-click="update( res.id, 'cancel' )" ng-show="res.canceled == 'false'"><span class="fa fa-square"></span></a>
                        </span>
                        <span ng-if="userRoles.delete == 'true'">
                            <a class="btn btn-warning btn-xs" ng-click="delete( res.id )"><span class="fa fa-trash"></span></a>
                        </span>
                        <a class="btn btn-primary btn-xs" href="/#products/search/{{res.clients}}"><span class="fa fa-user"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
