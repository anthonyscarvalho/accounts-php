<ol class="breadcrumb">
    <!--clients-->
    <li ng-if="userAccess.clients === 'true'" ng-class="{'active': SetClient}"><a href="#/clients/edit/{{parent}}">Client</a></li>

    <!--contacts-->
    <li ng-if="userAccess.contacts === 'true'" ng-class="{'active': SetContacts}"><a href="#/contacts/search/{{parent}}">Contacts</a></li>

    <!--products-->
    <li ng-if="userAccess.products === 'true'" ng-class="{'active': SetProducts}"><a href="#/products/search/{{parent}}">Products</a></li>

    <!--invoices-->
    <li ng-if="userAccess.invoices === 'true'" ng-class="{'active': SetInvoices}"><a href="#/invoices/search/{{parent}}">Invoices</a></li>

    <!--attachments-->
    <li ng-class="{'active': SetAttachments}"><a href="#/attachments/search/{{parent}}">Attachments</a></li>

    <!--google ads-->
    <li ng-if="userAccess.transactions === 'true'" ng-class="{'active': SetTransactions}"><a href="#/transactions/search/{{parent}}">Transactions</a></li>

    <!--email log-->
    <li ng-if="userAccess.email_log === 'true'" ng-class="{'active': SetEmailLog}"><a href="#/email_log/search/{{parent}}">Email Log</a></li>

    <!--quotations-->
    <li ng-if="userAccess.quotations === 'true'" ng-class="{'active': SetQuotations}"><a href="#/quotations/search/{{parent}}">Quotations</a></li>

    <!--client log-->
    <li ng-if="userAccess.logs === 'true'" ng-class="{'active': SetClientLog}"><a href="#/logs/search/{{parent}}">Log</a></li>
</ol>
