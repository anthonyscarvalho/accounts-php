/**
 * plugin.js
 *
 * Copyright, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/*jshint unused:false */
/*global tinymce:true */

/**
 * Example plugin that adds a toolbar button and menu item.
 */
tinymce.PluginManager.add( 'filemanager', function ( editor, url ) {
// Add a button that opens a window

    editor.addButton( 'filemanager', {
        title: 'Insert Image',
        text: 'Insert Image',
        stateSelector: 'img:not([data-mce-object],[data-mce-placeholder])',
        icon: false,
        onclick: function () {
            // Open window
            editor.windowManager.open( {
                title: 'File Manager',
                text: 'Insert image',
                width: 1100,
                height: 800,
                resizable: 1,
                url: 'http://control.local/admin/#media/link',
                buttons: [
                    {
                        text: 'Insert',
                        onclick: function () {

                            var win = editor.windowManager.getWindows()[0];
                            var image_src = win.getContentWindow().document.getElementById( 'image_src' ).value + "/";
                            var image_name = win.getContentWindow().document.getElementById( 'image_name' ).value;
                            var image_alt = 'alt="' + win.getContentWindow().document.getElementById( 'alt' ).value + '"';
                            var image_title = 'alt="' + win.getContentWindow().document.getElementById( 'title' ).value + '"';
                            var image_class = 'class="' + win.getContentWindow().document.getElementById( 'class' ).value + '"';
                            var image_height = win.getContentWindow().document.getElementById( 'height' ).value;
                            var image_width = win.getContentWindow().document.getElementById( 'width' ).value;

                            if ( win.getContentWindow().document.getElementById( 'image_link_yes' ).checked )
                            {
                                editor.insertContent( '<a href="' + image_src + 'large/' + image_name + '" rel="gal" class="gal"><img src="' + image_src + image_name + '" ' + image_alt + image_title + image_class + ' height="' + image_height + '" width="' + image_width + '" /></a>' );
                            }
                            else if ( win.getContentWindow().document.getElementById( 'image_link_no' ).checked )
                            {
                                editor.insertContent( '<img src="' + image_src + '" ' + image_alt + image_title + image_class + ' height="' + image_height + '" width="' + image_width + '" />' );
                            }
                            // Close the window
                            win.close();
                        }
                    },
                    { text: 'Close', onclick: 'close' }
                ],
                onsubmit: function ( e ) {
                    // Insert content when the window form is submitted
                    //editor.insertContent ('Title: ' + e.data.title);

                    editor.insertContent( '<img src="' + e.data.title + '" alt="" />' );
                }
            } );
        }
    } );
    editor.addMenuItem( 'filemanager', {
        title: 'Insert Image',
        text: 'Insert Image',
        icon: false,
        onclick: function () {
            // Open window
            editor.windowManager.open( {
                title: 'File Manager',
                text: 'Insert image',
                width: 1100,
                height: 800,
                resizable: 1,
                url: 'http://control.local/admin/#media/link',
                buttons: [
                    {
                        text: 'Insert',
                        onclick: function () {

                            var win = editor.windowManager.getWindows()[0];
                            var image_src = win.getContentWindow().document.getElementById( 'image_src' ).value + "/" + win.getContentWindow().document.getElementById( 'image_name' ).value;
                            var image_alt = 'alt="' + win.getContentWindow().document.getElementById( 'alt' ).value + '"';
                            var image_title = 'alt="' + win.getContentWindow().document.getElementById( 'title' ).value + '"';
                            var image_class = 'class="' + win.getContentWindow().document.getElementById( 'class' ).value + '"';
                            var image_height = win.getContentWindow().document.getElementById( 'height' ).value;
                            var image_width = win.getContentWindow().document.getElementById( 'width' ).value;

                            editor.insertContent( '<img src="' + image_src + '" ' + image_alt + image_title + image_class + ' height="' + image_height + '" width="' + image_width + '" />' );
                            // Close the window
                            win.close();
                        }
                    },
                    { text: 'Close', onclick: 'close' }
                ],
                onsubmit: function ( e ) {
                    // Insert content when the window form is submitted
                    //editor.insertContent ('Title: ' + e.data.title);

                    editor.insertContent( '<img src="' + e.data.title + '" alt="" />' );
                }
            } );
        }
    } );
} );