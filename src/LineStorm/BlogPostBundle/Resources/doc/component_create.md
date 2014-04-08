Creating a Component
====================

A Component adds funtionality or features to a post. Creating a Component is relativly simple.

For this example, will create a YouTube video component.

1. Component Types
2. Creating the Component class
3. Registering the Component
4. Using the Component

Step 1: Component Types
-----------------------
There are 4 types of component:

Type    | Example
--------|------------
HEAD    | Assets that need to be included
META    | Content Tags, keywords
BODY    | Articles, Galleries, Polls
FOOTER  | End of page javascript like Google Analytics

Each type describes where is should appear on the page and what it's context is.

Conveniently, all you have to do after choosing what best suits your needs is to extend the revelant class in:
```php
LineStorm\BlogPostBundle\Module\Component\Abstract[TYPE]Component
``

Step 2: Creating the Component class
------------------------------------

As a YouTube video is content, we will be extending `LineStorm\BlogPostBundle\Module\Component\AbstractBodyComponent`.
This intern extends `LineStorm\BlogPostBundle\Module\Component\AbstractComponent`, which gives us a few common and
default methods.

So, lets crate the class:

```php
<?php

namespace Acme\DemoBundle\Component;

use LineStorm\BlogPostBundle\Module\Component\AbstractBodyComponent;
use LineStorm\BlogPostBundle\Module\Component\ComponentInterface;
use LineStorm\BlogPostBundle\Module\Component\View\ComponentView;

class YouTubeComponent extends AbstractBodyComponent implements ComponentInterface
{
    protected $name = 'YouTube';
    protected $id = 'youtubevideos';

    public function isSupported($entity)
    {
        return ($entity instanceof PostYouTubeVideo);
    }
}
```

TODO: FINISH THIS ARTICLE!
