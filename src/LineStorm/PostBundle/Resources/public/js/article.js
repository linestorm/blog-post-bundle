define(['jquery', 'jckeditor'], function ($, ckeditor) {

    $(document).on('widget-init', '.item-articles', function(){

        $(this).find('input[type="hidden"]').val(contentCounts['articles'].count);
        $(this).find('textarea.ckeditor-textarea').ckeditor().focus();

    });


    // add ckeditor to all the pre-loaded articles
    $('.post-component-item.item-articles').each(function(){
        $(this).trigger('widget-init');
    });
});
