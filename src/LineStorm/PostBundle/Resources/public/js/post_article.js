

define(['jquery', 'jckeditor'], function ($, ckeditor) {

    CKEDITOR.config.customConfig = window.lineStormTags.assets.path+'/bundles/linestormpost/js/ckeditor/config.js';

    CKEDITOR.plugins.addExternal( 'pbckcode', window.lineStormTags.assets.path+'/vendor/PBCKCode/' );
    CKEDITOR.plugins.addExternal( 'linestorm-media', window.lineStormTags.assets.path+'/bundles/linestormpost/js/ckeditor/plugins/linestorm-media/' );

    CKEDITOR.plugins.addExternal( 'carousel',   window.lineStormTags.assets.path+'/bundles/linestormpost/js/ckeditor/plugins/carousel/' );
    CKEDITOR.plugins.addExternal( 'featurette', window.lineStormTags.assets.path+'/bundles/linestormpost/js/ckeditor/plugins/featurette/' );
    CKEDITOR.plugins.addExternal( 'jumbotron',  window.lineStormTags.assets.path+'/bundles/linestormpost/js/ckeditor/plugins/jumbotron/' );
    CKEDITOR.plugins.addExternal( 'trifold',    window.lineStormTags.assets.path+'/bundles/linestormpost/js/ckeditor/plugins/trifold/' );
    CKEDITOR.plugins.addExternal( 'listgroup',  window.lineStormTags.assets.path+'/bundles/linestormpost/js/ckeditor/plugins/listgroup/' );
    CKEDITOR.plugins.addExternal( 'badge',      window.lineStormTags.assets.path+'/bundles/linestormpost/js/ckeditor/plugins/badge/' );

    $(document).on('widget-init', '.item-articles', function(){

        $(this).find('input[name$="[order]"]').filter(function(){ return this.name.match(/\[articles\]\[\d+\]\[order\]$/); }).val(contentCounts.components);
        $(this).find('textarea.ckeditor-textarea').ckeditor().focus();

    });

    // add ckeditor to all the pre-loaded articles
    $('.post-component-item.item-articles').each(function(){
        $(this).trigger('widget-init');
    });
});
