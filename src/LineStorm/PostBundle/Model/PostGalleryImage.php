<?php

namespace LineStorm\PostBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use LineStorm\MediaBundle\Model\Media;

class PostGalleryImage
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var integer
     */
    protected $order;

    /**
     * @var PostGallery[]
     */
    protected $gallery;

    /**
     * @var Media
     */
    protected $image;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set gallery
     *
     * @param PostGallery $gallery
     * @return PostGalleryImage
     */
    public function setGallery(PostGallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return PostGallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Media $image
     */
    public function setImage(Media $image)
    {
        $this->image = $image;
    }

    /**
     * @return Media
     */
    public function getImage()
    {
        return $this->image;
    }


    
    
}
