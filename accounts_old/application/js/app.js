var app = angular.module( 'invoiceSystem', [
    'ngRoute',
    'ngAnimate',
    'ngResource',
    'ui.bootstrap',
    'chart.js',
    'angular.chosen',
    'angularUtils.directives.dirPagination',
    'ngSanitize',
    'ui.tinymce'
] );
$( document ).on( "click", ".table.click tbody tr", function ( e )
{
    $( this ).toggleClass( 'row_selected' );
} );
//Animations
app.animation( '.mAppLoading', function ()
{
    return {
        enter: function ( element, done )
        {
            element.css(
            {
                opacity: 1
            } ).animate(
            {
                opacity: 0
            }, 1000, done );
        }
    };
} );

function successAlert( $message )
{
    $.notify(
    {
        message: $message
    },
    {
        type: 'success',
        delay: 6000
    } );
}

function warningAlert( $message )
{
    $.notify(
    {
        message: $message
    },
    {
        type: 'warning',
        delay: 10000
    } );
}
