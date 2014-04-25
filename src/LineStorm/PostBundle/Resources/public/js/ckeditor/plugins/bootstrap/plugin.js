CKEDITOR.plugins.add( 'bootstrap',
{
    icons: 'source',
    init: function( editor )
    {
        editor.addCommand( 'bootstrapRow', new CKEDITOR.dialogCommand( 'bootstrapRow' ) );
        editor.ui.addButton( 'Bootstrap',
        {
            label: 'Insert Bootstrap Row',
            command: 'bootstrapRow',
            toolbar: 'insert'
        });

        editor.config.allowedContent = true;

        CKEDITOR.dialog.add( 'bootstrapRow', this.path + 'dialogs/bootstrap.js' );

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
