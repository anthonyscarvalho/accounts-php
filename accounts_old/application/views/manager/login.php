<div class="loginWrapper">
    <div class="loginForm">
        <form name="form" ng-submit="requestLogIn()" class="loginForm">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title">Please Sign In</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="fa fa-user"></span></span>
                            <input type="text" class="form-control" placeholder="Username" ng-model="username" id="username" name="username">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="fa fa-key"></span></span>
                            <input type="password" class="form-control" placeholder="Password" ng-model="password" name="password">
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-cust" type="submit" name="login" ng-if="!submitted">Login</button>
                        <a class="btn btn-info" ng-if="submitted">Busy...</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
