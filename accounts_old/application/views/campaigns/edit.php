<div class="m-app-loading" ng-animate-children></div>
<ol class="breadcrumb">
    <li class="active"><a href="#/campaigns/edit/{{parent}}">Campaign</a></li>
    <li><a href="#/campaigns/clients/{{parent}}">Clients</a></li>
    <li><a href="#/campaigns/funds/{{parent}}">Funds</a></li>
    <li><a href="#/campaigns/emails/{{parent}}">Emails</a></li>
    <li><a href="#/campaigns/logs/{{parent}}">Log</a></li>
</ol>

<form method="post" ng-submit="save( )">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="row">
                        <div class="col-md-8">
                            <label>Campaign Name</label>
                            <input type="text" class="form-control" maxlength="300" ng-model="results.name" required>
                        </div>
                        <div class="col-md-4">
                            <label>Signed Up</label>
                            <input type="text" class="form-control input-sm" ng-model="results.created" value="" required data-date-format="yyyy-mm-dd" data-provide="datepicker">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
        <div class="container">
            <ul class="nav navbar-nav">
                <li><a href="#/campaigns/view" data-toggle="tooltip"><span class="fa fa-backward fa-lg"></span></a></li>
                <li ng-if="submitted"><button type="button" class="btn-save"><span class="fa fa-lg fa-refresh fa-spin"></span></button></li>
                <li ng-if="!submitted"><button type="submit" class="btn-save"><span class="fa fa-lg fa-save"></span></button></li>
            </ul>
        </div>
    </nav>
</form>
