define(['jquery', 'select2'], function ($, select2) {
    $(document).ready(function(){
        var $tags = $('.tag-search');
        $tags.select2({
            tags: $tags.data('options').split(','),
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
