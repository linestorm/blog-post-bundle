"use strict"

CKEDITOR.dialog.add('featurette', function (editor) {
    var lang = editor.lang.featurette,
        commonLang = editor.lang.common;

    return {
        title: 'Boostrap Featurette',
        minWidth: 400,
        minHeight: 300,
        contents: [
            {
                id: 'info',
                label: lang.infoTab,
                accessKey: 'I',
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
                                    widget.getDialog().getContentElement('info', 'src').setValue(media.src);
                                    widget.getDialog().getContentElement('info', 'alt').setValue(media.alt || '');
                                });

                            })
                        }
                    },
                    {
                        id: 'src',
                        type: 'text',
                        label: commonLang.url,
                        setup: function (widget) {
                            this.setValue(widget.data.src);
                        },
                        commit: function (widget) {
                            widget.setData('src', this.getValue());
                        },
                        onLoad: function(e){
                            this.getInputElement().setAttribute('readonly', true)
                        },
                        validate: CKEDITOR.dialog.validate.notEmpty(lang.urlMissing)
                    },
                    {
                        id: 'alt',
                        type: 'text',
                        label: lang.alt,
                        setup: function (widget) {
                            this.setValue(widget.data.alt);
                        },
                        commit: function (widget) {
                            widget.setData('alt', this.getValue());
                        },
                        onLoad: function(e){
                            this.getInputElement().setAttribute('readonly', true)
                        }
                    },
                    {
                        id: 'align',
                        type: 'radio',
                        label: lang.alignTitle,
                        items: [
                            [ editor.lang.common.alignLeft, 'left' ],
                            [ editor.lang.common.alignRight, 'right' ]
                        ],
                        setup: function (widget) {
                            this.setValue(widget.data.align);
                        },
                        commit: function (widget) {
                            widget.setData('align', this.getValue());
                        }
                    },
                    {
                        id: 'link',
                        type: 'text',
                        label: lang.linkTitle,
                        style: 'width: 100%',
                        'default': '',
                        setup: function (widget) {
                            this.setValue(widget.data.link);
                        },
                        commit: function (widget) {
                            widget.setData('link', this.getValue());
                        }
                    },
                    {
                        id: 'target',
                        type: 'select',
                        requiredContent: 'a[target]',
                        label: editor.lang.common.target,
                        'default': '_blank',
                        items: [
                            [ editor.lang.common.notSet, '' ],
                            [ editor.lang.common.targetNew, '_blank' ],
                            [ editor.lang.common.targetTop, '_top' ],
                            [ editor.lang.common.targetSelf, '_self' ],
                            [ editor.lang.common.targetParent, '_parent' ]
                        ],
                        setup: function (widget) {
                            this.setValue(widget.data.target);
                        },
                        commit: function (widget) {
                            widget.setData('target', this.getValue());
                        }
                    }
                ]
            }
        ]
    };
});
