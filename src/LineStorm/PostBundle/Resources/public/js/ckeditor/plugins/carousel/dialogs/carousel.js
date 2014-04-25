"use strict";

(function ($) {

    CKEDITOR.dialog.add('carousel', function (editor) {
        var maxSlides = 5;
        var lang = editor.lang.carousel,
            commonLang = editor.lang.common;

        var dialog = {
            title: 'Edit Carousel',
            minWidth: 600,
            minHeight: 300,
            contents: [
                {
                    id: 'info',
                    label: lang.infoTab,
                    accessKey: 'I',
                    elements: [
                        {
                            id: 'slides',
                            type: 'select',
                            label: lang.slideTitle,
                            items: [],
                            setup: function (widget) {
                                this.setValue(widget.data.slides);
                            },
                            commit: function (widget) {
                                widget.setData('slides', this.getValue());
                            },
                            onChange: function (api) {
                                var dialog = this.getDialog();
                                // Hide and show tabs.
                                for (var i = 1; i <= maxSlides; i++) {
                                    if (i <= this.getValue()) {
                                        dialog.showPage('slide' + i);
                                    }
                                    else {
                                        dialog.hidePage('slide' + i);
                                    }
                                }
                            }
                        },
                        {
                            id: 'interval',
                            type: 'text',
                            label: lang.interval,
                            setup: function (widget) {
                                this.setValue(widget.data.interval);
                            },
                            commit: function (widget) {
                                widget.setData('interval', this.getValue());
                            }
                        },
                        {
                            id: 'pause',
                            type: 'checkbox',
                            label: lang.pause,
                            setup: function (widget) {
                                this.setValue(widget.data.pause == 'hover');
                            },
                            commit: function (widget) {
                                widget.setData('pause', (this.getValue() ? 'hover' : ''));
                            }
                        },
                        {
                            id: 'wrap',
                            type: 'checkbox',
                            label: lang.wrap,
                            setup: function (widget) {
                                this.setValue(widget.data.wrap);
                            },
                            commit: function (widget) {
                                widget.setData('wrap', this.getValue());
                            }
                        },
                        {
                            id: 'navigation',
                            type: 'checkbox',
                            label: lang.navigation,
                            setup: function (widget) {
                                this.setValue(widget.data.navigation);
                            },
                            commit: function (widget) {
                                widget.setData('navigation', this.getValue());
                            }
                        }
                    ]
                }
            ]
        };

        for (var ii = 1; ii <= maxSlides; ii++) {
            (function (i) { // Closure the i variable so that it is available in callbacks
                // Add slide count
                dialog.contents[0].elements[0].items.push([i + ' ' + (i == 1 ? lang.slideSingular : lang.slidePlural), i]);
                // Add tab for each slide
                dialog.contents[i] = {
                    id: 'slide' + i,
                    label: lang.slideSingular + ' ' + i,
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
                                        widget.getDialog().getContentElement('slide' + i, 'src' + i).setValue(media.src);
                                        widget.getDialog().getContentElement('slide' + i, 'alt' + i).setValue(media.alt || '');
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
                            onLoad: function(e){
                                this.getInputElement().setAttribute('readonly', true)
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
                            },
                            onLoad: function(e){
                                this.getInputElement().setAttribute('readonly', true)
                            }
                        }
                    ]
                };
            }(ii));
        }

        return dialog;
    });
})(jQuery);
