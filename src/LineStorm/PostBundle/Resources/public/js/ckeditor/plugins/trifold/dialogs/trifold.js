// Note: This automatic widget to dialog window binding (the fact that every field is set up from the widget
// and is committed to the widget) is only possible when the dialog is opened by the Widgets System
// (i.e. the widgetDef.dialog property is set).
// When you are opening the dialog window by yourself, you need to take care of this by yourself too.

CKEDITOR.dialog.add('trifold', function (editor) {
    var maxImages = 3;

    var lang = editor.lang.trifold,
        commonLang = editor.lang.common;

    var dialog = {
        title: 'Edit Trifold',
        minWidth: 600,
        minHeight: 300,
        contents: [
            {
                id: 'info',
                label: lang.infoTab,
                accessKey: 'I',
                elements: [
                    {
                        id: 'imageType',
                        type: 'select',
                        label: 'Image Type',
                        items: [
                            [ 'Square', '' ],
                            [ 'Rounded', 'rounded' ],
                            [ 'Circle', 'circle' ],
                            [ 'Thumbnail', 'thumbnail' ]
                        ],
                        // When setting up this field, set its value to the "align" value from widget data.
                        // Note: Align values used in the widget need to be the same as those defined in the "items" array above.
                        setup: function (widget) {
                            this.setValue(widget.data.imageType);
                        },
                        // When committing (saving) this field, set its value to the widget data.
                        commit: function (widget) {
                            widget.setData('imageType', this.getValue());
                        }
                    }
                ]
            }
        ]
    };

    for (var ii = 1; ii <= maxImages; ii++) {
        (function (i) { // Closure the i variable so that it is available in callbacks
            // Add slide count
            dialog.contents[0].elements[0].items.push([i + ' ' + (i == 1 ? lang.slideSingular : lang.slidePlural), i]);
            // Add tab for each slide
            dialog.contents[i] = {
                id: 'image' + i,
                label: lang.image + ' ' + i,
                accessKey: i,
                media: null,
                elements: [
                    {
                        id: 'search-box',
                        type: 'html',
                        html: [
                            '<div class="search-box">',
                            '<input type="text" style="width: 100%" class="cke_dialog_ui_input_text" placeholder="Search Media..." />',
                            '</div>'
                        ].join('\n'),
                        setup: function (widget) {
                            this.media = widget.data.media || null;
                        },
                        commit: function (widget) {
                            widget.setData('media', this.media);
                        },
                        media: null,
                        onLoad: function (e){
                            var widget = this;
                            require(['jquery', 'typeahead'], function($, typeahead){
                                var $widget = $("#"+widget.domId).find('input');
                                $widget.typeahead(
                                    {
                                        minLength: 3,
                                        highlight: true
                                    },
                                    {
                                        name: 'media',
                                        source: function (query, process) {
                                            return $.get(window.lineStormTags.mediaBank.search, { q: query }, function (data) {
                                                return process(data);
                                            });
                                        },
                                        templates: {
                                            suggestion: function(m){
                                                var html = '<img src="'+ m.src+'" /><p class="tt-title">'+ m.title+'</p> <p class="tt-desc">'+m.description+'</p><div class="clearfix"></div>';
                                                console.log(html);
                                                return html;
                                            }
                                        }
                                    }
                                )
                                    .on('typeahead:selected', function(e,media,dataset){
                                        widget.media = media;
                                        widget.getDialog().getContentElement('image' + i, 'src' + i).setValue(media.src);
                                        widget.getDialog().getContentElement('image' + i, 'alt' + i).setValue(media.alt || '');
                                    });
                            })
                        }
                    },
                    {
                        id: 'src' + i,
                        type: 'text',
                        label: lang.imageTitle,
                        setup: function (widget) {
                            this.setValue(widget.data['src' + i]);
                        },
                        commit: function (widget) {
                            widget.setData('src' + i, this.getValue());
                        },
                        validate: CKEDITOR.dialog.validate.notEmpty(lang.urlMissing)
                    },
                    {
                        id: 'alt' + i,
                        type: 'text',
                        label: lang.alt,
                        setup: function (widget) {
                            this.setValue(widget.data['alt' + i]);
                        },
                        commit: function (widget) {
                            widget.setData('alt' + i, this.getValue());
                        }
                    },
                    {
                        id: 'height' + i,
                        type: 'text',
                        label: editor.lang.common.height,
                        setup: function (widget) {
                            this.setValue(widget.data['height' + i]);
                        },
                        commit: function (widget) {
                            widget.setData('height' + i, this.getValue());
                        }
                    },
                    {
                        id: 'width' + i,
                        type: 'text',
                        label: editor.lang.common.width,
                        setup: function (widget) {
                            this.setValue(widget.data['width' + i]);
                        },
                        commit: function (widget) {
                            widget.setData('width' + i, this.getValue());
                        }
                    },
                ],
            };
        }(ii));
    }

    return dialog;
});
