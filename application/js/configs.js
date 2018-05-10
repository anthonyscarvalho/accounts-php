//Config
app.config(['$compileProvider', function($compileProvider)
{
    // disable debug info
    $compileProvider.debugInfoEnabled(false);
}]);
app.config(function(paginationTemplateProvider)
{
    paginationTemplateProvider.setPath('/application/templates/dir-paginate.htm');
});
