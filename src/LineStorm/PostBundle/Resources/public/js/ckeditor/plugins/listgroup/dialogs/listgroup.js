// Note: This automatic widget to dialog window binding (the fact that every field is set up from the widget
// and is committed to the widget) is only possible when the dialog is opened by the Widgets System
// (i.e. the widgetDef.dialog property is set).
// When you are opening the dialog window by yourself, you need to take care of this by yourself too.

CKEDITOR.dialog.add('listgroup', function (editor) {
    var maxItems = 20;

    var lang = editor.lang.listgroup,
        commonLang = editor.lang.common;

    var selectItems = [];
    for(var i = 1 ; i<=maxItems ; ++i){
        selectItems.push([i, i]);
    }

    var dialog = {
        title: 'Edit List Group',
        minWidth: 600,
        minHeight: 300,
        contents: [
            {
                id: 'info',
                label: lang.infoTab,
                accessKey: 'I',
                elements: [
                    {
                        id: 'items',
                        type: 'select',
                        label: lang.items,
                        items: selectItems,
                        setup: function (widget) {
                            this.setValue(widget.data.items);
                        },
                        commit: function (widget) {
                            widget.setData('items', parseInt(this.getValue()));
                        }
                    },
                    {
                        id: 'badge',
                        type: 'checkbox',
                        label: lang.label,
                        setup: function (widget) {
                            this.setValue(widget.data.badge);
                        },
                        commit: function (widget) {
                            widget.setData('badge', this.getValue());
                        }
                    }
                ]
            }
        ]
    };

    return dialog;
});
