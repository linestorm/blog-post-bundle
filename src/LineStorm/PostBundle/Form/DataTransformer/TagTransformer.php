<?php

namespace LineStorm\PostBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use LineStorm\PostBundle\Model\Tag;
use Symfony\Component\Form\DataTransformerInterface;

class TagTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * FQNS of the data class
     *
     * @var string
     */
    private $dataClass;

    /**
     * @param ObjectManager $em
     * @param string $dataClass
     */
    public function __construct(ObjectManager $em, $dataClass)
    {
        $this->em        = $em;
        $this->dataClass = $dataClass;
    }

    /**
     * Transforms the Document's value to a value for the form field
     */
    public function transform($tags)
    {
        if (!$tags) {
            $tags = array(); // default value
        }

        $tagNames = array();
        foreach ($tags as $tag) {
            $tagNames[] = $tag->getName();
        }

        return implode(',', $tagNames); // concatenate the tag names to one string
    }

    /**
     * Transforms the value the users has typed to a value that suits the field in the Document
     */
    public function reverseTransform($tags)
    {
        $tagEntities = new ArrayCollection();

        if (!$tags) {
            return $tagEntities;
        }

        $parsedTags = array_filter(array_map('trim', explode(',', $tags)));

        foreach ($parsedTags as $tagName) {
            /** @var Tag $tag */
            $tag = $this->em->getRepository($this->dataClass)->findOneBy(array('name' => $tagName));
            if (!($tag instanceof $this->dataClass)) {
                $tag = new $this->dataClass();
                $tag->setName($tagName);
            }
            $tagEntities[] = $tag;
        }

        return $tagEntities;
    }
}
