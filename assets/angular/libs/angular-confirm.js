/*
 * angular-confirm
 * http://schlogen.github.io/angular-confirm/
 * Version: 1.1.0 - 2015-14-07
 * License: Apache
 */
angular.module( 'angular-confirm', [ 'ui.bootstrap' ] ).controller( 'ConfirmModalController', [ '$scope', '$modalInstance', 'data', function ( $scope, $modalInstance, data ) {
        $scope.data = angular.copy( data );

        $scope.ok = function () {
            $modalInstance.close();
        };

        $scope.cancel = function () {
            $modalInstance.dismiss( 'cancel' );
        };
    } ] ).factory( '$confirm', [ '$modal', function ( $modal, $confirmModalDefaults ) {
        return function ( data, settings ) {
            settings = {
                template: '<div class="modal-header">{{data.title}}</div>' +
                        '<div class="modal-body">{{data.text}}</div>' +
                        '<div class="modal-footer">' +
                        '<button class="btn btn-danger" ng-click="ok()">{{data.ok}}</button>' +
                        '<button class="btn btn-default" ng-click="cancel()">{{data.cancel}}</button>' +
                        '</div>',
                controller: 'ConfirmModalController',
                defaultLabels: {
                    title: 'Confirm Action',
                    ok: 'Yes',
                    cancel: 'No'
                },
                animation: false
            };

            data = angular.extend( { }, settings.defaultLabels, data || { } );

            if ( 'templateUrl' in settings && 'template' in settings ) {
                delete settings.template;
            }

            settings.resolve = {
                data: function () {
                    return data;
                }
            };
            return $modal.open( settings ).result;
        };
    } ] ).directive( 'confirm', [ '$confirm', function ( $confirm ) {
        return {
            priority: 1,
            restrict: 'A',
            scope: {
                confirmIf: "=",
                ngClick: '&',
                confirm: '@',
                confirmSettings: "=",
                confirmTitle: '@',
                confirmOk: '@',
                confirmCancel: '@'
            },
            link: function ( scope, element, attrs ) {
                element.unbind( "click" ).bind( "click", function ( $event ) {

                    $event.preventDefault();

                    if ( angular.isUndefined( scope.confirmIf ) || scope.confirmIf ) {

                        var data = { text: scope.confirm };
                        if ( scope.confirmTitle ) {
                            data.title = scope.confirmTitle;
                        }
                        if ( scope.confirmOk ) {
                            data.ok = scope.confirmOk;
                        }
                        if ( scope.confirmCancel ) {
                            data.cancel = scope.confirmCancel;
                        }
                        $confirm( data, scope.confirmSettings || { } ).then( scope.ngClick );
                    } else {

                        scope.$apply( scope.ngClick );
                    }
                } );
            }
        };
    } ] );
