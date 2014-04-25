CKEDITOR.dialog.add( 'bootstrapRow', function ( editor ) {

    var uploadedImage;

    return {
        title: 'Bootstrap Ropw',
        minWidth: 400,
        minHeight: 200,

        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        type: 'text',
                        id: 'columns',
                        label: 'Columns',
                        validate: CKEDITOR.dialog.validate.notEmpty( "Columns field cannot be empty" )
                    }
                ]
            }
        ],

        onLoad: function(){

        },

        onOk: function() {
            var dialog = this;

            var cols = parseInt(dialog.getValueOf( 'tab-basic', 'columns' ));
            var spaces = 12 / cols;

            var html = '<div class="row">';
            for(var i=0 ; i<cols ; ++i){
                html += '<div class="col-md-'+spaces+'">content</div>';
            }
            html += '</div>';

            //abbr.setText( dialog.getValueOf( 'tab-basic', 'caption' ) );

            /*
            var id = dialog.getValueOf( 'tab-adv', 'id' );
            if ( id )
                abbr.setAttribute( 'id', id );
            */

            editor.insertHtml( html );
        }
    };
});
