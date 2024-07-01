<div class="modal-header">Edit Invoice #{{data.id}} <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <form method="post" ng-submit="save()" id="form">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="well well-sm">
                        <legend>Invoice Details</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Company</label>
                                    <input type="text" class="form-control" disabled="disabled" name="creation_date" ng-model="data.companyName">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Creation</label>
                                    <input type="text" class="form-control" disabled="disabled" name="creation_date" ng-model="data.creation_date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Due</label>
                                    <input type="text" class="form-control" disabled="disabled" name="due_date" ng-model="data.due_date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea name="notes" class="form-control" rows="2" maxlength="500" cols="50" ng-model="data.notes"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Paid</label>
                                    <input type="text" class="form-control" name="paid_date" ng-model="data.paid_date" disabled="disabled">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Canceled</label>
                                    <input type="text" class="form-control" name="canceled_date" ng-model="data.canceled_date" disabled="disabled">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="well well-sm">
                        <legend>Available Credit</legend>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-4">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" readonly="readonly"  name="credit" ng-model="credit">
                                    <span class="input-group-btn">
                                        <button ng-click="addTrans( data.id, data.clients )" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="well well-sm">
                        <legend>Invoice Items</legend>
                        <table class="table table-condensed table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Date</td>
                                    <td>Item</td>
                                    <td>Description</td>
                                    <td>Price</td>
                                    <td style="width: 80px;"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="res in invoices_items">
                                    <td>{{res.id}}</td>
                                    <td>{{res.date}}</td>
                                    <td>{{res.categoryName}}</td>
                                    <td>{{res.description}}</td>
                                    <td>{{res.price}}</td>
                                    <td>
                                        <a ng-if="userRoles.edit" class="btn btn-info btn-xs" ng-click="editItem( res.id )"><span class="fa fa-pencil"></span></a>
                                        <button ng-if="userRoles.delete" class="btn btn-warning btn-xs" ng-click="deleteItem( res.id )" confirm="delete item from invoice?"><span class="fa fa-trash"></span></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-2 col-md-offset-2">Total:</div>
                                    <div class="col-md-8">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon">R</span>
                                            <input type="text" id="invoice_total" readonly="readonly" name="invoice_total" class="form-control" value="{{data.invoice_total}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="well well-sm">
                        <legend>Invoice Transactions</legend>
                        <table class="table table-condensed table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Date</td>
                                    <td>Credit</td>
                                    <td>Debit</td>
                                    <td style="width: 40px;"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="res in transactions">
                                    <td>{{res.id}}</td>
                                    <td>{{res.date}}</td>
                                    <td>{{res.credit}}</td>
                                    <td>{{res.debit}}</td>
                                    <td>
                                        <a ng-if="userRoles.delete" class="btn btn-warning btn-xs" ng-click="deleteTrans( res.id, data.id )"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="well well-sm">
                        <legend>Invoice Emails</legend>
                        <table class="table table-condensed table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <td>Date</td>
                                    <td>Type</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="res in invoices_emails">
                                    <td>{{res.date}}</td>
                                    <td>{{res.email_type}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-info" ng-if="submitted"><span class="fa fa-refresh fa-spin"></span></button>
    <button type="submit" class="btn btn-success" ng-if="!submitted" name="submitbtn" form="form"><span class="fa fa-save"></span></button>
    <button class="btn btn-default" type="button" ng-click="load()"><span class="fa fa-refresh fa-lg"></span></button>
    <span class="pull-right">
        <button ng-click="previewInvoice()" class="btn btn-warning"><span class="fa fa-print"></span></button>
        <button class="btn btn-success" ng-click="creditInvoice( data.id )"  type="button" ><span class="fa fa-dollar"></span></button>
        <button class="btn btn-primary" ng-click="sendInvoice( data.id )" type="button" ><span class="fa fa-envelope"></span></button>
    </span>
</div>
