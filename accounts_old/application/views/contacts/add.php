<div class="modal-header">Add New Contact <button class="close" ng-click="close()" type="button"><span class="fa fa-times"></span></button></div>
<div class="modal-body">
   <form method="post" ng-submit="add()" id="form">
      <div class="container">
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
                  <select class="form-control" required chosen options="clients" ng-model="data.clients">
                     <option value="">Please select one</option>
                     <option ng-repeat="res in clients" ng-value="{{res.id}}">{{res.business}}</option>
                  </select>
               </div>
               <div class="form-group">
                  <label>Name</label>
                  <input type="text" class="form-control" maxlength="250" required ng-model="data.name" value="">
               </div>
               <div class="from-group">
                  <label>Surname</label>
                  <input type="text" class="form-control" maxlength="250" ng-model="data.surname" value="">
               </div>
               <div class="form-group">
                  <label>Number 1</label>
                  <input type="phone" class="form-control" maxlength="250" ng-model="data.contact_number_1" value="">
               </div>
               <div class="form-group">
                  <label>Number 2</label>
                  <input type="phone" class="form-control" maxlength="250" ng-model="data.contact_number_2" value="">
               </div>
               <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control" maxlength="250" required ng-model="data.email" value="">
               </div>
            </div>
            <div class="col-md-6">
               <div class="well well-sm">
                  <legend>Notifications</legend>
                  <div class="row">
                     <div class="form-group col-md-4">
                        <label>Payments</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                           <label class="btn btn-default" ng-model="data.payment" uib-btn-radio="'true'" required>True</label>
                           <label class="btn btn-default" ng-model="data.payment" uib-btn-radio="'false'" required>False</label>
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label>Invoices</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                           <label class="btn btn-default" ng-model="data.invoice" uib-btn-radio="'true'" required>True</label>
                           <label class="btn btn-default" ng-model="data.invoice" uib-btn-radio="'false'" required>False</label>
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label>Receipts</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                           <label class="btn btn-default" ng-model="data.receipt" uib-btn-radio="'true'" required>True</label>
                           <label class="btn btn-default" ng-model="data.receipt" uib-btn-radio="'false'" required>False</label>
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label>Suspensions</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                           <label class="btn btn-default" ng-model="data.suspension" uib-btn-radio="'true'" required>True</label>
                           <label class="btn btn-default" ng-model="data.suspension" uib-btn-radio="'false'" required>False</label>
                        </div>
                     </div>
                     <div class="form-group col-md-4">
                        <label>Adwords</label>
                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                           <label class="btn btn-default" ng-model="data.adwords" uib-btn-radio="'true'" required>True</label>
                           <label class="btn btn-default" ng-model="data.adwords" uib-btn-radio="'false'" required>False</label>
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
