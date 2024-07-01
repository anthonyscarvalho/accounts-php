<div class="modal-header">Preview Email Log <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <table class="table table-condensed table-bordered">
                <tr>
                    <td>Email Date</td>
                    <td>{{results.date}}</td>
                </tr>
                <tr>
                    <td>Email Subject</td>
                    <td>{{results.subject}}</td>
                </tr>
                <tr>
                    <td>Client Contact</td>
                    <td>{{results.contactName}}</td>
                </tr>
                <tr>
                    <td colspan="2">Email Status</td>
                </tr>
            </table>
            <div class="well well-sm">{{results.status}}</div>
            <div>
                <button ng-click="previewInvoice()" class="btn btn-warning"><span class="fa fa-print"></span></button>
            </div>
        </div>
        <div class="col-md-8">
            <p>Email Body:</p>
            <div class="well well-sm" ng-bind-html="results.body"></div>
        </div>
    </div>
</div>
