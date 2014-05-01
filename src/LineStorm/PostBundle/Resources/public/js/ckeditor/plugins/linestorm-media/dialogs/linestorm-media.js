CKEDITOR.dialog.add('linestormMediaDialog', function (editor) {

    return {
        title: 'Upload Media',
        minWidth: 400,
        minHeight: 200,

        contents: [
            {
                id: 'info',
                label: 'Basic Settings',
                elements: [

                    {
                        id: 'media',
                        type: 'html',
                        html: '<div class="ckeditor-dropzone dropzone"></div>',

                        onLoad: function (e) {
                            var domId = this.domId;
                            var dialog = this.getDialog();
                            var element = this;

                            require(['jquery', 'dropzone'], function ($, Dropzone) {
                                $('#' + domId).dropzone({
                                    url: window.lineStormTags.mediaBank.upload,
                                    maxFiles: 1,
                                    init: function () {
                                        this.on("success", function (file, response) {
                                            element.setValue(response);
                                            dialog.getContentElement('info', 'src').setValue(response.src);
                                            dialog.getContentElement('info', 'description').setValue(response.description);
                                            dialog.getContentElement('info', 'credits').setValue(response.credits);
                                            dialog.getContentElement('info', 'alt').setValue(response.alt);
                                            this.removeFile(file);
                                        });
                                        this.on("error", function (file, response) {
                                            this.removeFile(file);
                                        });
                                    }
                                });
                            });
                        },
                        commit: function (widget) {
                            widget.setData('media', this.getValue());
                        }
                    },
                    {
                        id: 'src',
                        type: 'text',
                        label: 'Src',
                        setup: function (widget) {
                            this.setValue(widget.data.src);
                        },
                        commit: function (widget) {
                            widget.setData('src', this.getValue());
                        },
                        onLoad: function(e){
                            this.getInputElement().setAttribute('readonly', true)
                        },
                        validate: CKEDITOR.dialog.validate.notEmpty("Src cannot be empty")
                    },
                    {
                        id: 'description',
                        type: 'text',
                        label: 'Description',
                        setup: function (widget) {
                            this.setValue(widget.data.alt);
                        },
                        commit: function (widget) {
                            widget.setData('credits', this.getValue());
                        },
                        onLoad: function(e){
                            this.getInputElement().setAttribute('readonly', true)
                        },
                        validate: CKEDITOR.dialog.validate.notEmpty("Description field cannot be empty")
                    },
                    {
                        id: 'alt',
                        type: 'text',
                        label: 'Alt Text',
                        setup: function (widget) {
                            this.setValue(widget.data.alt);
                        },
                        commit: function (widget) {
                            widget.setData('alt', this.getValue());
                        },
                        onLoad: function(e){
                            this.getInputElement().setAttribute('readonly', true)
                        },
                        validate: CKEDITOR.dialog.validate.notEmpty("Alt field cannot be empty")
                    },
                    {
                        id: 'credits',
                        type: 'text',
                        label: 'Credits',
                        setup: function (widget) {
                            this.setValue(widget.data.alt);
                        },
                        commit: function (widget) {
                            widget.setData('credits', this.getValue());
                        },
                        onLoad: function(e){
                            this.getInputElement().setAttribute('readonly', true)
                        },
                        validate: CKEDITOR.dialog.validate.notEmpty("Credits field cannot be empty")
                    }
                ]
            }
        ],

        onOk: function (e) {
            /*var media = this.getContentElement('info', 'media').getValue();

            media.src = this.getContentElement('info', 'src').getValue();
            media.alt = this.getContentElement('info', 'alt').getValue();
            media.description = this.getContentElement('info', 'description').getValue();
            media.credits = this.getContentElement('info', 'credits').getValue();

            console.log(media);
            window.lineStorm.api.call(media._api.edit, {
                data: media,
                method: 'PUT',
                success: function(o){
                    alert('ok!');
                }
            });*/
        }
    };
});
