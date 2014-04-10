<?php

namespace LineStorm\PostBundle\Search;

use LineStorm\SearchBundle\Search\Provider\TriGraphSearchProvider;
use LineStorm\SearchBundle\Search\SearchProviderInterface;

class PostTriGraphSearchProvider extends TriGraphSearchProvider implements SearchProviderInterface
{

    public function getName()
    {
        return 'post';
    }

    public function getTriGraph()
    {
        return 'trigraph_post';
    }

    public function getIndexFields()
    {
        return array(
            'title' => null,
            'articles' => array(
                'body'
            ),
            'galleries' => array(
                'body'
            ),
            'polls' => array(
                'body'
            ),
            'category' => array(
                'name'
            ),
            'tags' => array(
                'name'
            )
        );
    }


} 
