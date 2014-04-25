CKEDITOR.plugins.add( 'at-code',
{
    icons: 'source',
    init: function( editor )
    {
        editor.ui.addButton( 'Code',
        {
            label: 'Insert Code',
            command: 'insertCode',
            toolbar: 'insert'
        });

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
        });
    }

});
