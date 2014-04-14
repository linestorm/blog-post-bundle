define(['jquery', 'select2'], function ($, select2) {
    $(document).ready(function(){
        $('.tag-search').select2({
            tags: $('.tag-search').data('options').split(','),
            tokenSeparators: [',', ' ', ';']
        });
    });

    $(document).on('widget-init', '.item-tags', function(){

    });

    // add ckeditor to all the pre-loaded articles
    $('.post-component-item.item-tags').each(function(){
        $(this).trigger('widget-init');
    });
});
