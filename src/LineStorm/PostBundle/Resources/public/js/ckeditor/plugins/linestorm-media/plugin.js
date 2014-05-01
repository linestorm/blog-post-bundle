CKEDITOR.plugins.add( 'linestorm-media',
{
    icons: 'source',
    init: function( editor )
    {
        editor.addCommand( 'linestormMediaDialog', new CKEDITOR.dialogCommand( 'linestormMediaDialog' ) );
        editor.ui.addButton( 'Code',
        {
            label: 'Insert Media',
            command: 'linestormMediaDialog',
            toolbar: 'insert'
        });

        editor.config.allowedContent = true;

        CKEDITOR.dialog.add( 'linestormMediaDialog', this.path + 'dialogs/linestorm-media.js' );
    }

});
