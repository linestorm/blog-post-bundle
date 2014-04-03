var contentCounts = {
    article: {count: 0 },
    gallery: {count: 0 },
    gallery_images: {}
};

// setup dropzone
Dropzone.autoDiscover = false;

function parseError(e, p){
    if(p === undefined){
        p = 'error';
    }
    var errors = {}, childErrors;
    for(var i in e){
        if(i === 'errors'){
            errors[p] = e[i];
        } else if ("string" === typeof e[i] || e[i] instanceof Array){
            errors[i] = e[i];
        } else {
            childErrors = parseError(e[i], i);
            for (var attrname in childErrors) { errors[attrname] = childErrors[attrname]; }
        }
    }

    return errors;
}

// add a new form to the page from a prototype
function addForm($collectionHolder, prototype, indexer, name) {
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
    newForm = prototype.replace(rgx, indexer.count);
    indexer.count++;

    newContainer = $collectionHolder.data('prototype').replace(/__widget__/, newForm);

    $elementHtml = $(newContainer);
    $collectionHolder.append($elementHtml);

    return $elementHtml;
}


function setupDropzone(placeholder){

    var $p = $(placeholder),
        localCount;

    contentCounts.gallery_images[contentCounts.gallery.count] = {count: $p.find('.dz-preview').length || 0};
    localCount = contentCounts.gallery_images[contentCounts.gallery.count];

    // bind the remove button as it won't be set by dropzone on init
    $p.find('.dz-remove').on('click', function(){
        $(this).closest('.dz-preview').remove();
        --localCount.count;
    });

    new Dropzone(placeholder, {
        url: window.lineStormTags.mediaBank.upload,
        acceptedFiles: 'image/*',
        init: function(){
            this.on("success", function(file, response) {
                if(file.xhr.status == 200){
                    alert('An identical file already exists and has been returned.');
                }
                var dzForm = $(file.previewElement).find('.dz-image-form'),
                    idx = localCount.count
                    ;
                var $form = addForm(dzForm, $(placeholder).data('prototype'), localCount, '__img_name__');

                $form.find('input[name*="[hash]"]').val(response.hash);
                $form.find('input[name*="[src]"]').val(response.src);

                $form.find('input[name*="[title]"]').val(response.title);
                $form.find('input[name*="[description]"]').val(response.description);
                $form.find('input[name*="[alt]"]').val(response.alt);
                $form.find('input[name*="[seo]"]').val(response.seo);

                $form.find('input[name*="[order]"]').val(idx);
            });
            this.on("error", function(file, response) {
                this.removeFile(file);
                alert("Cannot add file: "+response);
            });
            this.on("removedfile", function(file){
                --localCount.count;
            });
        },
        previewTemplate: $p.data('preview')
    });
}


$(document).ready(function(){
    $('form.api-save').on('submit', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('#FormErrors').slideUp(function(){ $(this).html(''); });
        window.lineStorm.api.saveForm($(this), function(on, status, xhr){
            if(xhr.status === 200){
                alert('updated!');
            } else if(xhr.status === 201) {
                alert('created!');
            } else {
                alert('saved ('+xhr.status+')!');
            }
        }, function(e, status, ex){
            if(e.status === 400){
                if(e.responseJSON){
                    var errors = parseError(e.responseJSON.errors);
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

    var $articleCollectionHolder, $addArticleLink, $addGalleryLink;

    $articleCollectionHolder = $('.post-components');
    $addArticleLink = $('a.post-component-new.article-new');

    $addArticleLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // Get the data-prototype explained earlier
        var prototype = $(this).data('prototype');

        // add a new tag form (see next code block)
        var $el = addForm($articleCollectionHolder, prototype, contentCounts.article);
        $el.find('input[type="hidden"]').val(contentCounts.gallery.count);
        $el.find('[data-role="textarea"],textarea').ckeditor().focus();
    });

    $addGalleryLink = $('a.post-component-new.gallery-new');
    $addGalleryLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // Get the data-prototype explained earlier
        var prototype = $(this).data('prototype');

        // add a new tag form (see next code block)
        var $el = addForm($articleCollectionHolder, prototype, contentCounts.gallery);
        $el.find('input[type="hidden"]').val(contentCounts.gallery.count);
        $el.find('[data-role="textarea"],textarea').ckeditor().focus();

        setupDropzone($el.find('.dropzone')[0]);
    });

    // add ckeditor to all the pre-loaded articles
    $articleCollectionHolder.find('textarea.ckeditor-textarea').ckeditor();
    $articleCollectionHolder.find('.item-gallery').each(function(){
        setupDropzone($(this).find('.dropzone')[0]);
        $(this).find('textarea.gallery-body').ckeditor();
    });

    // set up the sortable content
    $articleCollectionHolder.sortable({
        handle: '.item-reorder',
        axis: 'y',
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
            $articleCollectionHolder.children('li').each(function(i, li){
                var $li = $(li);
                var $order = $li.find('input[name*="[order]"]');
                $order.val(i);
            });
        }
    });

    // configure remove button
    $articleCollectionHolder.on('click', 'button.item-remove', function(){
        if(confirm('Are you sure you want to remove this item?\n\nNOTE: IT CANNOT BE UNDONE ONCE SAVED')){
            var i = $(this).data('count');
            $(this).closest('.post-component-item').parent().remove();
            --contentCounts[i];
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


        window.lineStorm.api.call(this.href, {
            'success': function(o){
                if(o.form){
                    var $modal = $(window.lineStorm.modalContainer.replace(/__title__/gim, 'New Category').replace(/__widget__/gim, o.form));
                    var $form = $modal.find('form');

                    $modal.find('button.modal-save').on('click', function(){
                       $form.submit();
                    });
                    $form.on('submit', function(){
                        window.lineStorm.api.saveForm($form, function(o){
                            $categorySelect.append('<option>'+o.name+'</option>').val(o.name);
                            $modal.modal('hide')
                            alert('saved!');
                        },function(xhr, state){
                            alert('failed: '+state);
                        });
                        return false;
                    });

                    $modal.modal({}).appendTo(document.body);
                }
            }
        });

        return false;
    });
});

