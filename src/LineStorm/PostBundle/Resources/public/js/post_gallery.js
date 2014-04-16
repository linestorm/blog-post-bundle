define(['jquery', 'dropzone', 'jckeditor'], function ($, Dropzone, ckeditor) {

    Dropzone.autoDiscover = false;

    function setupDropzone(placeholder){

        var $p = $(placeholder),
            localCount;

        contentCounts.gallery_images[contentCounts.galleries.count] = {count: $p.find('.dz-preview').length || 0};
        localCount = contentCounts.gallery_images[contentCounts.galleries.count];

        // bind the remove button as it won't be set by dropzone on init
        $p.find('.dz-remove').on('click', function(){
            $(this).closest('.dz-preview').remove();
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

                    $form.find('input[name$="[hash]"]').val(response.hash);
                    $form.find('input[name$="[src]"]').val(response.src);

                    $form.find('input[name$="[title]"]').val(response.title);
                    $form.find('textarea[name$="[description]"]').val(response.description);
                    $form.find('input[name$="[credits]"]').val(response.credits);
                    $form.find('input[name$="[alt]"]').val(response.alt);
                    $form.find('input[name$="[seo]"]').val(response.seo);

                    $form.find('input[name$="[order]"]').val(idx);
                });
                this.on("error", function(file, response) {
                    this.removeFile(file);
                    alert("Cannot add file:\n\n"+response.error);
                });
                this.on("removedfile", function(file){
                });
            },
            previewTemplate: $p.data('preview')
        });
    }

    $(document).on('widget-init', '.item-galleries', function(){

        var $el = $(this);

        $el.find('input[name$="[order]"]').filter(function(){ return this.name.match(/\[galleries\]\[\d+\]\[order\]$/); }).val(contentCounts.components);
        $el.find('textarea.ckeditor-textarea').ckeditor().focus();

        setupDropzone($el.find('.dropzone')[0]);
    });


    // add ckeditor to all the pre-loaded articles
    $('.post-component-item.item-galleries').each(function(){
        $(this).trigger('widget-init');
    });
});
