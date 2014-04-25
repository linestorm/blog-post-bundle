// Register the plugin within the editor.
CKEDITOR.plugins.add('badge', {

    lang: 'en',
    requires: 'widget',
    icons: 'badge',

    init: function (editor) {

        editor.ui.addButton('Badge', {
            label: 'Create a badge',
            command: 'badge',
            toolbar: 'bootstrap,5'
        });

        editor.addCommand( 'badge', {
            exec : function( editor )
            {
                editor.insertHtml( '<div class="badge badge-success">badge</div>' );
            }
        });
    }
});
