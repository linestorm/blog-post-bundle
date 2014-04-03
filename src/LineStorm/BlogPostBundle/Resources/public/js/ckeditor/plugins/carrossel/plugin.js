/**
 * Created by athorne1016 on 04/03/14.
 */
CKEDITOR.plugins.add( 'carrossel', {
    icons: 'carrossel',
    init: function( editor ) {

        editor.addCommand( 'carrosselDialog', new CKEDITOR.dialogCommand( 'carrosselDialog' ) );

        editor.ui.addButton( 'Carrossel', {
            label: 'Insert an image carrossel',
            command: 'carrosselDialog',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add( 'carrosselDialog', this.path + 'dialogs/carrossel.js' );
    }
});
