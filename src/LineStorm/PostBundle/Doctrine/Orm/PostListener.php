<?php

namespace LineStorm\PostBundle\Doctrine\Orm;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use LineStorm\PostBundle\Model\Post;

/**
 * Doctrine ORM listener updating the canonical fields and the password.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class PostListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist($args)
    {
        $object = $args->getEntity();

        if ($object instanceof Post)
        {
            if(!($object->getCreatedOn() instanceof \DateTime))
            {
                $object->setCreatedOn(new \DateTime());
            }

            if(!$object->getRealSlug())
            {
                $slug = preg_replace(array('/[^a-zA-Z0-9\s]/', '/\s+/'), array('', '-'), $object->getTitle());
                $object->setSlug($slug);
            }
        }
    }
}
