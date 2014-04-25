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

        /*
        editor.addCommand( 'insertCode',
        {
            exec : function( editor )
            {
                var div = document.createElement('div');

                $(div).css({
                    height: '100px',
                    width: '100%'
                }).html('hello code!');

                editor.insertElement(div);


            }
        });*/
    }

});
