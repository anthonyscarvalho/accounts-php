<form method="post" ng-submit="send()">
	<div class="modal-header">Adwords Reminder</div>
	<div class="modal-body">
		<div class="row">
		<div class="col-md-4">
				<label>Subject</label>
				<input type="text" class="form-control" ng-model="data.emailsubject">
				<label>Date</label>
				<input type="text" class="form-control" ng-model="data.date" data-date-format="yyyy-mm-dd" data-provide="datepicker">
				<label>Date Due</label>
				<input type="text" class="form-control" ng-model="data.due_date" data-date-format="yyyy-mm-dd" data-provide="datepicker">
				<label>Month</label>
				<input type="text" class="form-control input-sm" maxlength="250" ng-model="data.month" value="" required data-date-format="MM" data-provide="datepicker" data-date-min-view-mode="1" data-date-max-view-mode="1">
				<label>Amount</label>
				<input type="text" class="form-control" ng-model="data.amount" required>
			</div>
			<div class="col-md-8">
				<textarea ng-model="data.emailbody" ui-tinymce="" class="form-control" style="height:250px">{{data.emailbody}}</textarea>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" name="close" class="btn btn-default" ng-click="close()"><span class="fa fa-times"></span></button>
		<a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
		<button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" title="Insert Contact"><span class="fa fa-save"></span></button>
	</div>
</form>
