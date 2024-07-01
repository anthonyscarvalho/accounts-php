<!DOCTYPE html>
<html ng-app="invoiceSystem">
    <head>
        <title>ZAWebs - Accounts Application</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <link href="/app/manager/combine/css" rel="stylesheet" type="text/css">
        <link rel="icon" type="image/ico" href="/media/main/favicon.ico">
        <script src="/app/manager/combine/js" type="text/javascript"></script>
        <script src="/assets/tinymce/tinymce.min.js" type="text/javascript"></script>
    </head>
    <body ng-controller="mainApp">
        <div ng-if="showMenu">
            <?php include 'application/views/menu.php';?>
        </div>
        <div ng-view class="view-fade-in"></div>
    </body>
</html>
