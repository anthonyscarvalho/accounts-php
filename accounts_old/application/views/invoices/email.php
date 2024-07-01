<div class="modal-header">Email Invoice <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <form method="post" ng-submit="sendEmail( )" id="form">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Invoice #:</label>
                        <input type="text" class="form-control input-sm" maxlength="250" ng-model="invoiceNumber" value="" disabled="disabled">
                    </div>
                    <div class="form-group">
                        <label>Email Type</label>
                        <div class="btn-group btn-group-sm btn-group-vertical" data-toggle="buttons">
                            <label class="btn btn-default" ng-model="email_type" uib-btn-radio="'email'" ng-click="load( email_type )">First</label>
                            <label class="btn btn-default" ng-model="email_type" uib-btn-radio="'reminder'" ng-click="load( email_type )">Second</label>
                            <label class="btn btn-default" ng-model="email_type" uib-btn-radio="'suspend'" ng-click="load( email_type )">Suspension</label>
                            <label class="btn btn-default" ng-model="email_type" uib-btn-radio="'termination'" ng-click="load( email_type )">Termination</label>
                            <label class="btn btn-default" ng-model="email_type" uib-btn-radio="'paid'" ng-click="load( email_type )">Paid</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <label>Email Subject</label>
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
    <button ng-if="!submitted" type="submit" name="submitbtn" class="btn btn-success" form="form"><span class="fa fa-envelope"></span></button>
</div>
