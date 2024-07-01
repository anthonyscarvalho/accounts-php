<div class="modal-header">Create Invoice <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <form method="post" ng-submit="create( )" id="form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" ng-if="!addAll">
                    <label>Client #:</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control input-sm" maxlength="250" ng-model="data.clients" value="" disabled="disabled">
                    </div>
                </div>
                <div class="form-group" ng-if="addAll">
                    <label>Client</label>
                    <select class="form-control input-sm" id="clients" required chosen options="clients" ng-model="data.clients" ng-change="updateSubject()">
                        <option ng-repeat="res in clients" ng-value="{{res.id}}" data-client="{{res.business}}">{{res.business}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Company</label>
                    <select class="form-control input-sm" ng-model="data.company">
                        <option ng-value="" value="" selected="">Select one</option>
                        <option ng-repeat="res in companies" ng-value="'{{res.id}}'" value="{{res.id}}">{{res.company}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date:</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control input-sm" maxlength="250" ng-model="data.prodDate" data-date-format="yyyy-mm-dd" data-provide="datepicker">
                        <span class="input-group-btn"><button ng-click="load()" class="btn btn-sm btn-default" type="button"><span class="fa fa-search"></span></button></span>
                        <span class="input-group-btn"><button ng-click="clear()" class="btn btn-sm btn-default" type="button"><span class="fa fa-times"></span></button></span>
                    </div>
                </div>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody ng-repeat="res in products" ng-click="addToSelected(res);" ng-class="getClass(res);">
                        <tr>
                            <td>{{res.categoryName}}</td>
                            <td>{{res.description}}</td>
                            <td>{{res.price}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Send Email:</label>
                            <div class="btn-group input-group-sm">
                                <label class="btn btn-default" ng-model="data.sendMail" uib-btn-radio="'true'">Yes</label>
                                <label class="btn btn-default" ng-model="data.sendMail" uib-btn-radio="'false'">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Invoice Type:</label>
                            <div class="btn-group input-group-sm">
                                <label class="btn btn-default" ng-model="data.invoiceType" uib-btn-radio="'due'" ng-click="updateDate()">Due</label>
                                <label class="btn btn-default" ng-model="data.invoiceType" uib-btn-radio="'now'" ng-click="updateDate()">Now</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="well well-sm">
                    <legend>Email</legend>
                    <label>Subject</label>
                    <input type="text" class="form-control"  name="subject" ng-model="data.emailsubject">
                    <label>Body Of Email</label>
                    <textarea ng-model="data.emailbody" ui-tinymce="" id="tinymce_content" class="form-control" style="height:300px" name="tinymce_content">{{data.emailbody}}</textarea>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
    <button ng-if="!submitted" type="submit" name="submitbtn" value="insert" class="btn btn-success" form="form"><span class="fa fa-save"></span></button>
</div>
