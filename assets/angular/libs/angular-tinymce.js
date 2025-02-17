/*! Binds a TinyMCE widget to <textarea> elements. */
angular.module( 'ui.tinymce', [] ).value( 'uiTinymceConfig', {} ).directive( 'uiTinymce', [ '$rootScope', '$compile', '$timeout', '$window', '$sce', 'uiTinymceConfig', function( $rootScope, $compile, $timeout, $window, $sce, uiTinymceConfig )
    {
        uiTinymceConfig = uiTinymceConfig || {};
        var generatedIds = 0;
        var ID_ATTR = 'tinymce_content';
        if ( uiTinymceConfig.baseUrl ) {
            tinymce.baseURL = uiTinymceConfig.baseUrl;
        }
        return {
            require: [ 'ngModel', '^?form' ],
            link: function( scope, element, attrs, ctrls ) {
                if ( !$window.tinymce ) {
                    return;
                }
                var ngModel = ctrls[ 0 ],
                    form = ctrls[ 1 ] || null;
                var expression, options, tinyInstance,
                    updateView = function( editor ) {
                        var content = editor.getContent( { format: options.format } ).trim();
                        content = $sce.trustAsHtml( content );
                        ngModel.$setViewValue( content );
                        if ( !$rootScope.$$phase ) {
                            scope.$apply();
                        }
                    };

                function toggleDisable( disabled ) {
                    if ( disabled ) {
                        ensureInstance();
                        if ( tinyInstance ) {
                            tinyInstance.getBody().setAttribute( 'contenteditable', false );
                        }
                    } else {
                        ensureInstance();
                        if ( tinyInstance ) {
                            tinyInstance.getBody().setAttribute( 'contenteditable', true );
                        }
                    }
                }
                // generate an ID
                //attrs.$set( 'id', ID_ATTR );
                attrs.$set( 'id', ID_ATTR + '-' + generatedIds++ );
                expression = {};
                angular.extend( expression, scope.$eval( attrs.uiTinymce ) );
                options = {
                    // Update model when calling setContent
                    // (such as from the source editor popup)
                    setup: function( ed ) {
                        ed.on( 'init', function() {
                            ngModel.$render();
                            ngModel.$setPristine();
                            if ( form ) {
                                form.$setPristine();
                            }
                        } );
                        // Update model on button click
                        ed.on( 'ExecCommand', function() {
                            ed.save();
                            updateView( ed );
                        } );
                        // Update model on change
                        ed.on( 'change', function( e ) {
                            ed.save();
                            updateView( ed );
                        } );
                        ed.on( 'blur', function() {
                            element[ 0 ].blur();
                        } );
                        // Update model when an object has been resized (table, image)
                        ed.on( 'ObjectResized', function( ed ) {
                            ed.save();
                            updateView( ed );
                        } );
                        ed.on( 'remove', function() {
                            element.remove();
                        } );
                        if ( expression.setup ) {
                            expression.setup( ed, {
                                updateView: updateView
                            } );
                        }
                    },
                    format: 'raw',
                    selector: '#' + attrs.id,
                    convert_urls: false,
                    relative_urls: true,
                    fix_list_elements: true,
                    fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
                    force_p_newlines: true,
                    force_hex_style_colors: true,
                    invalid_elements: "font span",
                    keep_styles: false,
                    visual: true,
                    plugins: [
                        "lists link table",
                        "wordcount code",
                        "directionality"
                    ],
                    toolbar1: "pastetext removeformat | undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify styleselect | bullist numlist outdent indent | link code",
                    image_advtab: true,
                    target_list: [ {
                        title: 'Same page',
                        value: '_self'
                        }, {
                        title: 'New page',
                        value: '_blank'
                        } ],
                    style_formats: [ {
                        title: 'H1',
                        block: 'h1'
                    }, {
                        title: 'H2',
                        block: 'h2'
                    }, {
                        title: 'P.h2',
                        block: 'p',
                        inline: 'p',
                        classes: 'h2'
                    }, {
                        title: 'H3',
                        block: 'h3'
                    }, {
                        title: 'P.h3',
                        block: 'p',
                        inline: 'p',
                        classes: 'h3'
                    }, {
                        title: 'H4',
                        block: 'h4'
                    }, {
                        title: 'P.h4',
                        block: 'p',
                        inline: 'p',
                        classes: 'h4'
                    } ],
                    paste_as_text: true,
                    menubar: false,
                    /* menu: {
                     //file: {title: 'File', items: 'newdocument'},
                     //edit: { title: 'Edit', items: 'undo redo' },
                     //insert: {title: 'Insert', items: 'link media | template hr'},
                     //view: { title: 'View', items: 'visualaid' },
                     //format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat' },
                     //table: { title: 'Table', items: 'inserttable tableprops deletetable | cell row column' }
                     //tools: {title: 'Tools', items: 'spellchecker code'}
                     },*/
                    browser_spellcheck: true,
                    dialog_type: "modal"
                };
                // extend options with initial uiTinymceConfig and
                // options from directive attribute value
                angular.extend( options, uiTinymceConfig, expression );
                // Wrapped in $timeout due to $tinymce:refresh implementation, requires
                // element to be present in DOM before instantiating editor when
                // re-rendering directive
                $timeout( function() {
                    tinymce.init( options );
                    toggleDisable( scope.$eval( attrs.ngDisabled ) );
                } );
                ngModel.$formatters.unshift( function( modelValue ) {
                    return modelValue ? $sce.trustAsHtml( modelValue ) : '';
                } );
                ngModel.$parsers.unshift( function( viewValue ) {
                    return viewValue ? $sce.getTrustedHtml( viewValue ) : '';
                } );
                ngModel.$render = function() {
                    ensureInstance();
                    var viewValue = ngModel.$viewValue ? $sce.getTrustedHtml( ngModel.$viewValue ) : '';
                    // instance.getDoc() check is a guard against null value
                    // when destruction & recreation of instances happen
                    if ( tinyInstance && tinyInstance.getDoc() ) {
                        tinyInstance.setContent( viewValue );
                        // Triggering change event due to TinyMCE not firing event &
                        // becoming out of sync for change callbacks
                        tinyInstance.fire( 'change' );
                    }
                };
                attrs.$observe( 'disabled', toggleDisable );
                // This block is because of TinyMCE not playing well with removal and
                // recreation of instances, requiring instances to have different
                // selectors in order to render new instances properly
                scope.$on( '$tinymce:refresh', function( e, id ) {
                    var eid = attrs.id;
                    if ( angular.isUndefined( id ) || id === eid ) {
                        var parentElement = element.parent();
                        var clonedElement = element.clone();
                        clonedElement.removeAttr( 'id' );
                        clonedElement.removeAttr( 'style' );
                        clonedElement.removeAttr( 'aria-hidden' );
                        tinymce.execCommand( 'mceRemoveEditor', false, eid );
                        parentElement.append( $compile( clonedElement )( scope ) );
                    }
                } );
                scope.$on( '$destroy', function() {
                    ensureInstance();
                    if ( tinyInstance ) {
                        tinyInstance.remove();
                        tinyInstance = null;
                    }
                } );

                function ensureInstance() {
                    if ( !tinyInstance ) {
                        tinyInstance = tinymce.get( attrs.id );
                    }
                }
            }
        };
    }
] );
