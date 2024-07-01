<div class="modal-header">
    Are you sure you want to cancel this record?
    <button class="close" ng-click="close()">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="input-group">
                <span class="input-group-addon">Reason</span>
                <textarea name="cancel_reason" rows="2" class="form-control"></textarea>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="row">
        <div class="col-md-4 text-center">
            <button type="button" ng-click="cancel()" class="btn btn-success"><span class="fa fa-check"></span></button>
        </div>
        <div class="col-md-4 text-center">
            <?php //<a class="btn" href="/profile" data-toggle="tooltip" title="Edit Profile"><span class="fa fa-user fa-2x"></span></a>    ?>
        </div>
        <div class="col-md-4 text-center">
            <button type="button" ng-click="close()" class="btn btn-default"><span class="fa fa-times"></span></button>
        </div>
    </div>
</div>