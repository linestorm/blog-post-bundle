CKEDITOR.dialog.add( 'linestormMediaDialog', function ( editor ) {

    var uploadedImage;

    return {
        title: 'Upload Media',
        minWidth: 400,
        minHeight: 200,

        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        type: 'text',
                        id: 'caption',
                        label: 'Caption',
                        validate: CKEDITOR.dialog.validate.notEmpty( "Caption field cannot be empty" )
                    },
                    {
                        type: 'html',
                        html: '<div class="ckeditor-dropzone dropzone"></div><input type="text" id="ckeMediaId"><input type="text" id="ckeMediaSrc">'
                    }
                ]
            }
        ],

        onLoad: function(){
            require(['jquery', 'dropzone'], function($, Dropzone){
                $('.ckeditor-dropzone').dropzone({
                    url: window.lineStormTags.mediaBank.upload,
                    maxFiles: 1,
                    init: function(){
                        this.on("success", function(file, response) {
                            $('#caption').val(response.title);
                            $('#ckeMediaId').val(response.id);
                            $('#ckeMediaSrc').val(response.src);
                        });
                    }
                });
            });
        },

        onOk: function() {
            var dialog = this;

            var abbr = editor.document.createElement( 'img' );
            abbr.setAttribute( 'data-id', $('#ckeMediaId').val() );
            abbr.setAttribute( 'data-src', $('#ckeMediaSrc').val() );
            abbr.setAttribute( 'src', $('#ckeMediaSrc').val() );

            //abbr.setText( dialog.getValueOf( 'tab-basic', 'caption' ) );

            /*
            var id = dialog.getValueOf( 'tab-adv', 'id' );
            if ( id )
                abbr.setAttribute( 'id', id );
            */

            editor.insertElement( abbr );
        }
    };
});
