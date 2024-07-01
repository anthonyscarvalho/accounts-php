<div class="m-app-loading" ng-animate-children></div>
<div class="row">
    <div class="col-md-3">
        <div class="well well-sm">
            <div class="form-group">
                <label>Load:</label>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-default" ng-model="loaded" uib-btn-radio="''" ng-click="load()">All</label>
                    <label class="btn btn-default" ng-model="loaded" uib-btn-radio="'false'" ng-click="load()">Active</label>
                    <label class="btn btn-default" ng-model="loaded" uib-btn-radio="'true'" ng-click="load()">Canceled</label>
                </div>
            </div>
            <div class="form-group">
                <label>Paid:</label>
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-default" ng-model="paid" uib-btn-radio="''" ng-click="load()">All</label>
                    <label class="btn btn-default" ng-model="paid" uib-btn-radio="'true'" ng-click="load()">True</label>
                    <label class="btn btn-default" ng-model="paid" uib-btn-radio="'false'" ng-click="load()">False</label>
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
                <button type="button" ng-click="reset( )" class="btn btn-default"><span class="fa fa-times"></span></button>
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
    <div class="col-md-9">
        <table class="table table-condensed table-hover table-striped click">
            <thead>
                <tr>
                    <th>ID</th>
                    <th custom-sort order="'clientName'" sort="sort">Client</th>
                    <th custom-sort order="'creation_date'" sort="sort">Creation</th>
                    <th custom-sort order="'due_date'" sort="sort">Due</th>
                    <th custom-sort order="'paid_date'" sort="sort">Paid Date</th>
                    <th custom-sort order="'canceled_date'" sort="sort">Canceled Date</th>
                    <th>Invoice Total</th>
                    <th custom-sort order="'lastInvoice'" sort="sort">Last Invoice</th>
                    <th>Total Items</th>
                    <th style="width: 120px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="res in results | orderBy:sort.sortType:sort.sortReverse | filter:searchKeyword:strict || undefined | itemsPerPage: totalItems">
                    <td>{{res.id}}</td>
                    <td>{{res.clientName}}</td>
                    <td>{{res.creation_date}}</td>
                    <td>{{res.due_date}}</td>
                    <td>{{res.paid_date}}</td>
                    <td>{{res.canceled_date}}</td>
                    <td>{{res.invoice_total}}</td>
                    <td>{{res.lastInvoice}}</td>
                    <td>{{res.totalItems}}</td>
                    <td>
                        <span ng-if="userRoles.edit == 'true'">
                            <a class="btn btn-info btn-xs" ng-click="edit( res.id, res.clients )"><span class="fa fa-pencil"></span></a>
                        </span>
                        <span ng-if="userRoles.cancel == 'true'">
                            <a class="btn btn-danger btn-xs" ng-click="update( res.id, 'enable' )" ng-show="res.canceled == 'true'"><span class="fa fa-times"></span></a>
                            <a class="btn btn-success btn-xs" ng-click="update( res.id, 'cancel' )" ng-show="res.canceled == 'false'"><span class="fa fa-check"></span></a>
                        </span>
                        <span ng-if="userRoles.delete == 'true'">
                            <a class="btn btn-warning btn-xs" ng-click="delete( res.id )"><span class="fa fa-trash"></span></a>
                        </span>
                        <a class="btn btn-primary btn-xs" href="/#invoices/search/{{res.clients}}"><span class="fa fa-user"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
