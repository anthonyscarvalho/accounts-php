<div class="modal-header">Edit Contact #{{results.id}} <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
   <form method="post" ng-submit="save()" id="form">
      <div class="container">
         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label>Client #:</label>
                  <input type="text" class="form-control input-sm" ng-model="results.clients" value="" disabled="disabled">
               </div>
               <div class="form-group">
                  <label>Name</label>
                  <input type="text" class="form-control input-sm" maxlength="250" required ng-model="results.name" value="">
               </div>
               <div class="form-group">
                  <label>Surname</label>
                  <input type="text" class="form-control input-sm" maxlength="250" ng-model="results.surname" value="">
               </div>
               <div class="form-group">
                  <label>Number 1</label>
                  <input type="phone" class="form-control input-sm" maxlength="250" ng-model="results.contact_number_1" value="">
               </div>
               <div class="form-group">
                  <label>Number 2</label>
                  <input type="phone" class="form-control input-sm" maxlength="250" ng-model="results.contact_number_2" value="">
               </div>
               <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control input-sm" maxlength="250" required ng-model="results.email" value="">
               </div>
            </div>
            <div class="col-md-6">
               <div class="well well-sm">
                  <legend>Notifications</legend>
                  <div class="row">
                     <div class="form-group col-md-4">
                        <label>Payments</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                           <label class="btn btn-default" ng-model="results.payment" uib-btn-radio="'true'" required>True</label>
                           <label class="btn btn-default" ng-model="results.payment" uib-btn-radio="'false'" required>False</label>
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label>Invoices</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                           <label class="btn btn-default" ng-model="results.invoice" uib-btn-radio="'true'" required>True</label>
                           <label class="btn btn-default" ng-model="results.invoice" uib-btn-radio="'false'" required>False</label>
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label>Receipts</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                           <label class="btn btn-default" ng-model="results.receipt" uib-btn-radio="'true'" required>True</label>
                           <label class="btn btn-default" ng-model="results.receipt" uib-btn-radio="'false'" required>False</label>
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label>Suspensions</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                           <label class="btn btn-default" ng-model="results.suspension" uib-btn-radio="'true'" required>True</label>
                           <label class="btn btn-default" ng-model="results.suspension" uib-btn-radio="'false'" required>False</label>
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label>Adwords</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                           <label class="btn btn-default" ng-model="results.adwords" uib-btn-radio="'true'" required>True</label>
                           <label class="btn btn-default" ng-model="results.adwords" uib-btn-radio="'false'" required>False</label>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>
</div>
<div class="modal-footer">
   <a ng-if="submitted" class="btn btn-info"><span class="fa fa-refresh fa-spin"></span></a>
   <button ng-if="!submitted" type="submit" name="submit" value="insert" class="btn btn-success" form="form"><span class="fa fa-save"></span></button>
</div>
