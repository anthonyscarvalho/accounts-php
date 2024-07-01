<div class="m-app-loading" ng-animate-children></div>
<div class="row">
	<div class="col-md-3">
		<div class="well well-sm">
			<div class="form-group">
				<label>Year</label>
				<input type="text" class="form-control input-sm" maxlength="250" ng-model="ads.year" value="" required data-date-format="yyyy" data-provide="datepicker" data-date-min-view-mode="2" data-date-max-view-mode="2">
			</div>
			<div class="form-group">
				<label>Month</label>
				<input type="text" class="form-control input-sm" maxlength="250" ng-model="ads.month" value="" required data-date-format="mm" data-provide="datepicker" data-date-min-view-mode="1" data-date-max-view-mode="1">
			</div>
			<div class=" form-group text-center">
				<button ng-click="load()" class="btn btn-default"><span class="fa fa-search"></span></button>
				<button ng-click="add()" class="btn btn-primary"><span class="fa fa-plus"></span></button>
			</div>
			<div class="form-group">
				<ul class="list-group">
					<li class="list-group-item">Credit:<span class="badge">{{totalCredit| currency:'R '}}</span></li>
					<li class="list-group-item">Debit:<span class="badge">{{totalDebit| currency:'R '}}</span></li>
					<li class="list-group-item">Remaining:<span class="badge">{{totalCredit - totalDebit| currency:'R '}}</span></li>
				</ul>
			</div>
			<p>Showing: {{(results|filter:searchKeyword).length}} of {{( results ).length}} records</p>
		</div>
	</div>
	<div class="col-md-6">
		<table class="table table-condensed table-hover table-striped click" at-table at-paginated at-list="list" at-config="config">
			<thead>
				<tr>
					<th custom-sort order="'date'" sort="sort">Date</th>
					<th custom-sort order="'clientName'" sort="sort">Client</th>
					<th custom-sort order="'comment'" sort="sort">Comment</th>
					<th custom-sort order="'credit'" sort="sort">Credit</th>
					<th custom-sort order="'debit'" sort="sort">Debit</th>
					<th></th>
					<th style="width: 60px;"></th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="res in results | orderBy:sort.sortType:sort.sortReverse " ng-init="setTotals( res ); val = updateTotal( res )">
					<td>{{res.date}}</td>
					<td>{{res.clientName}}</td>
					<td>{{res.comment}}</td>
					<td>{{res.credit| currency:'R '}}</td>
					<td>{{res.debit| currency:'R '}}</td>
					<td>{{val| currency:'R '}}</td>
					<td>
						<div ng-show="res.clientName != ''">
							<span ng-if="userRoles.edit == 'true'">
								<button type="button" class="btn btn-info btn-xs" ng-click="edit( res.id )"><span class="fa fa-pencil"></span></button>
							</span>
							<span ng-if="userRoles.cancel == 'true'">
								<a class="btn btn-danger btn-xs" ng-click="enable( res.id, loaded )" ng-show="res.canceled == 'true'"><span class="fa fa-times"></span></a>
								<a class="btn btn-success btn-xs" ng-click="cancel( res.id, loaded )" ng-show="res.canceled == 'false'"><span class="fa fa-check"></span></a>
							</span>
							<span>
								<a class="btn btn-warning btn-xs" ng-click="delete( res.id, loaded )"><span class="fa fa-trash"></span></a>
							</span>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-3">
		<div class="well well-sm">
			<label>Credit Overview</label>
			<table class="table table-condensed table-hover table-striped categories">
				<thead>
					<tr>
						<th>Client</th>
						<th>Credit</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="res in credit">
						<td>{{res.clientName}}</td>
						<td>{{res.adsCredit| currency:'R '}}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
