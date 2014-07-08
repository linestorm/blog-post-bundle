require(['jquery', 'bttrlazyloading', 'highlightjs'], function($, lzy, hjs){
    hljs.initHighlightingOnLoad();
    $(document).ready(function(){
        $('pre code').each(function(i, e) {hljs.highlightBlock(e);});
        $("img.article-image").bttrlazyloading({
            delay: 500,
            container: document.body,
            animation: null,
            retina: false
        });
    });
});
