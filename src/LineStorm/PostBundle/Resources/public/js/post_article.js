define(['jquery', 'jckeditor'], function ($, ckeditor) {

    $(document).on('widget-init', '.item-articles', function(){

        $(this).find('input[name$="[order]"]').filter(function(){ return this.name.match(/\[articles\]\[\d+\]\[order\]$/) }).val(contentCounts.components);
        $(this).find('textarea.ckeditor-textarea').ckeditor().focus();

    });


    // add ckeditor to all the pre-loaded articles
    $('.post-component-item.item-articles').each(function(){
        $(this).trigger('widget-init');
    });
});
