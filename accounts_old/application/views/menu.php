<div class="navbar navbar-default navbar-fixed-top navbar-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand">{{title}}</a>
    </div>
    <div class="collapse navbar-collapse" id="collapse">
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#/dashboard"><span class="fa fa-home"></span></a></li>
            <li ng-if="userAccess.clients === 'true'"><a href="#/clients/view">Clients</a></li>
            <li ng-if="userAccess.contacts === 'true'"><a href="#/contacts/view">Contacts</a></li>
            <li ng-if="userAccess.products === 'true'"><a href="#/products/view">Products</a></li>
            <li ng-if="userAccess.invoices === 'true'"><a href="#/invoices/view">Invoices</a></li>
            <li ng-if="userAccess.quotations === 'true'"><a href="#/quotations/view">Quotations</a></li>
            <li ng-if="userAccess.statements === 'true'"><a href="#/statements/view">Statements</a></li>
            <li ng-if="userAccess.logs === 'true'"><a href="#/logs/view">Logs</a></li>
            <li ng-if="userAccess.campaigns === 'true'"><a href="#/campaigns/view">Google Ads</a></li>
            <!--
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="fa fa-search"></span> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <form role="search">
                            <div id="Search">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="client_search" value="" placeholder="search clients" id="search"/>
                                    <span class="input-group-addon" ><img src="<?php // echo BASE_PATH ?>/public/img/loading.gif" height="16" width="16" id="loading"/></span>
                                </div>
                                <ul id="result" style="display:none;"></ul>
                            </div>
                        </form>
                    </li>
                </ul>
            </li>
            -->
            <li><a ng-click="modalOpen()" href=""><span class="fa fa-bars"></span></a></li>
        </ul>
    </div>
</div>
