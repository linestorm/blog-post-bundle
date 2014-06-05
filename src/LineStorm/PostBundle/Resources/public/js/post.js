
var contentCounts = contentCounts || {};

window.onbeforeunload = function(){
    return 'Are you sure you want to leave?';
};

define(['jquery', 'jqueryui', 'typeahead', 'cms_api', 'cms_media_dropzone', 'cms_media_treebrowser'], function ($, $ui, th, api, mDz, mTree) {

    // add a new form to the page from a prototype
    window.addForm = function($collectionHolder, prototype, indexer, name) {
        var newForm, newContainer, $elementHtml;

        if(indexer === undefined){
            console.log('addForm: indexer must be defined');
            return;
        }

        if(name === undefined)
            name = '__name__';

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have#
        var rgx = new RegExp(name, 'g');
        if(indexer){
            newForm = prototype.replace(rgx, indexer.count);
            indexer.count++;
        } else {
            newForm = prototype.replace(rgx, '');
        }


        newContainer = $collectionHolder.data('prototype').replace(/__widget__/, newForm);

        $elementHtml = $(newContainer);
        $collectionHolder.append($elementHtml);

        ++window.contentCounts.components;

        return $elementHtml;
    };


    $(document).ready(function(){
        $('form.api-save').on('submit', function(e){
            e.preventDefault();
            e.stopPropagation();
            $('#FormErrors').slideUp(function(){ $(this).html(''); });
            api.saveForm($(this), function(on, status, xhr){
                if(xhr.status === 200){
                } else if(xhr.status === 201) {
                    window.location = on.location;
                } else {
                }
            }, function(e, status, ex){
                if(e.status === 400){
                    if(e.responseJSON){
                        var errors = api.parseError(e.responseJSON.errors);
                        var str = '';
                        for(var i in errors){
                            if(errors[i].length)
                                str += "<p class=''><strong style='text-transform:capitalize;'>"+i+":</strong> "+errors[i].join(', ')+"</p>";
                        }
                        $('#FormErrors').html(str).slideDown();
                    } else {
                        alert(status);
                    }
                }
            });

            return false;
        });

        $('.post-form-delete').on('click', function(){
            if(confirm("Are you sure you want to permanently delete this post?")){
                api.call($(this).data('url'), {
                    method: 'DELETE',
                    success: function(o){
                        alert(o.message);
                        window.location = o.location;
                    }
                });
            }
        });

        var $postBodyHolder;

        $postBodyHolder = $('.post-components');

        $('a.post-component-new').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Get the data-prototype explained earlier
            var id = $(this).data('id');
            var prototype = $(this).data('prototype');

            // add a new tag form (see next code block)
            var $el = addForm($postBodyHolder, prototype, contentCounts[id]);

            $el.find('.post-component-item').addClass('item-'+id).trigger('widget-init');

            return false;
        });

        // set up the cover image dropzone
        var $coverImageform = $('#linestorm_cms_form_post_coverImage');
        var $coverImageformPreview = $('.image-preview-container > img');
        $('.new-media').on('click', function(){
            var $button = $(this);
            api.call($button.data('url'), {
                dataType: 'json',
                'success': function(o){
                    if(o.form){
                        var $modal = $(window.lineStorm.modalContainer.replace(/__title__/gim, 'New Media').replace(/__widget__/gim, o.form));
                        var $form = $modal.find('form');

                        $modal.find('button.modal-save').on('click', function(){
                            $form.submit();
                        });
                        $form.on('submit', function(){
                            api.saveForm($form, function(o){
                                if(0 in o){
                                    var m = o[0];
                                    $coverImageform.val(m.id);
                                    $coverImageformPreview.attr('src', m.src);
                                }
                                $modal.modal('hide');
                            },function(xhr, state){
                            });
                            return false;
                        });

                        $modal.modal({}).appendTo(document.body);

                        $modal.on('shown.bs.modal', function(){
                            // build the dropzone and trees
                            mTree.mediaTree($modal.find('.media-tree'));
                            mDz.dropzone($modal.find('.dropzone'), {
                                maxFiles: 1
                            });
                        });

                    }
                }
            });
        });

        // set up the sortable content
        $postBodyHolder.sortable({
            handle: '.item-reorder',
            axis: 'y',
            create: function( event, ui ) {
                var $ul = $(this);
                $ul.children('li').sort(function(a,b) {
                    return a.dataset.order > b.dataset.order;
                }).appendTo($ul);
            },
            start: function(e, ui){

                $(e.target).children().addClass('fade-overlay');
                $(this).sortable('refreshPositions');

                // save the ckeditor state and destroy it else is breaks on sorting stop
                var tarea = ui.item.find('textarea.ckeditor-textarea');
                if(tarea.length){
                    tarea.data('value', tarea.val()).val('Moving...');
                    var ck = tarea.ckeditorGet();
                    ck.destroy();
                }
            },
            stop:function(e,ui){

                $(e.target).children().removeClass('fade-overlay');
                $(this).sortable('refreshPositions');

                // rebuild ckeditor
                var tarea = ui.item.find('textarea.ckeditor-textarea');
                if(tarea.length){
                    tarea.val(tarea.data('value'));
                    tarea.ckeditor();
                }

                // update the order
                $postBodyHolder.children('li').each(function(i, li){
                    var $li = $(li);
                    var $order = $li.find('input[name*="[order]"]');
                    $order.val(i);
                });
            }
        });

        // configure remove button
        $postBodyHolder.on('click', 'button.item-remove', function(){
            if(confirm('Are you sure you want to remove this item?\n\nNOTE: IT CANNOT BE UNDONE ONCE SAVED')){
                var i = $(this).data('count');
                $(this).closest('.post-component-item').parent().remove();
            }
        });

        $(document).on('click', '.options-toggle', function(){
            $(this).next('.'+$(this).data('toggle')).slideToggle();
            return false;
        });

        var $categorySelect = $('.category-select');
        $('.add-category').on('click', function(e){

            e.preventDefault();
            e.stopPropagation();


            api.call(this.href, {
                'success': function(o){
                    if(o.form){
                        var $modal = $(window.lineStorm.modalContainer.replace(/__title__/gim, 'New Category').replace(/__widget__/gim, o.form));
                        var $form = $modal.find('form');

                        $modal.find('button.modal-save').on('click', function(){
                           $form.submit();
                        });
                        $form.on('submit', function(){
                            api.saveForm($form, function(o){
                                $categorySelect.append('<option value="'+o.id+'">'+o.name+'</option>').val(o.id);
                                $modal.modal('hide');
                            },function(xhr, state){
                            });
                            return false;
                        });

                        $modal.modal({}).appendTo(document.body);
                    }
                }
            });

            return false;
        });

        // auto fill in the slug until it is changed
        var hasSlugChanged = false;
        var $slugInput  = $('input.post-form-slug');
        var $titleInput = $('input.post-form-title');

        $titleInput.on('keyup', function(){
            if(!hasSlugChanged){
                $slugInput.val(this.value.replace(/[^\w\d\s-]/g, '').replace(/\s+/g, '-').toLowerCase());
            }
        });
        $slugInput.on('keyup', function(){
            hasSlugChanged = true;
        });

        var mediaSearch = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: lineStormTags.mediaBank.search+'?q=%QUERY'
        });

        mediaSearch.initialize();

        $('input.media-search').typeahead({
            hint: true,
            highlight: true,
            minLength: 2
        },{
            source: mediaSearch.ttAdapter(),
            templates: {
                suggestion: function(data){
                    return '<div class="media" data-id="'+data.id+'" data-src="'+data.src+'">' +
                        '<span class="pull-left">' +
                        '   <img class="media-object" src="'+data.src+'" alt="'+data.alt+'">' +
                        '</span>' +
                        '<div class="media-body">' +
                        '   <h4 class="media-heading">'+data.title+'</h4>' +
                        '</div>' +
                    '</div>';
                }
            }
        }).on('typeahead:selected', function(e,s,o){
            $coverImageform.val(s.id);
            $coverImageformPreview.attr('src', s.src);
        });

    });

});
