<div class="modal-header">Preview Email Log <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
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
            </table>
            <div class="form-group" ng-if="results.invoices">
                <button ng-click="previewInv(results.invoices)" class="btn btn-warning">Preview Invoice</button>
            </div>
            <div class="well well-sm">
                <legend>Email status:</legend>
                {{results.status}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="well well-sm">
                <legend>Email Body:</legend>
                <div  ng-bind-html="results.body"></div>
            </div>
        </div>
    </div>
</div>
