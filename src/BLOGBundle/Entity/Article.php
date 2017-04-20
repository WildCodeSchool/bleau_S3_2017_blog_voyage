<?php

namespace BLOGBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Article
 */
class Article
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $titleFr;

    /**
     * @var string
     */
    private $titleEs;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var boolean
     */
    private $newsletter;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $image;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $comment;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $content;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->image = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->content = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
		$this->date = new \ DateTime();
    }

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
     * Set titleFr
     *
     * @param string $titleFr
     *
     * @return Article
     */
    public function setTitleFr($titleFr)
    {
        $this->titleFr = $titleFr;

        return $this;
    }

    /**
     * Get titleFr
     *
     * @return string
     */
    public function getTitleFr()
    {
        return $this->titleFr;
    }

    /**
     * Set titleEs
     *
     * @param string $titleEs
     *
     * @return Article
     */
    public function setTitleEs($titleEs)
    {
        $this->titleEs = $titleEs;

        return $this;
    }

    /**
     * Get titleEs
     *
     * @return string
     */
    public function getTitleEs()
    {
        return $this->titleEs;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set newsletter
     *
     * @param boolean $newsletter
     *
     * @return Article
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Add image
     *
     * @param \BLOGBundle\Entity\Image $image
     *
     * @return Article
     */
    public function addImage(\BLOGBundle\Entity\Image $image)
    {
        $this->image[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \BLOGBundle\Entity\Image $image
     */
    public function removeImage(\BLOGBundle\Entity\Image $image)
    {
        $this->image->removeElement($image);
    }

    /**
     * Get image
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add comment
     *
     * @param \BLOGBundle\Entity\Comments $comment
     *
     * @return Article
     */
    public function addComment(\BLOGBundle\Entity\Comments $comment)
    {
        $this->comment[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \BLOGBundle\Entity\Comments $comment
     */
    public function removeComment(\BLOGBundle\Entity\Comments $comment)
    {
        $this->comment->removeElement($comment);
    }

    /**
     * Get comment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Add content
     *
     * @param \BLOGBundle\Entity\Content $content
     *
     * @return Article
     */
    public function addContent(\BLOGBundle\Entity\Content $content)
    {
        $this->content[] = $content;

        return $this;
    }

    /**
     * Remove content
     *
     * @param \BLOGBundle\Entity\Content $content
     */
    public function removeContent(\BLOGBundle\Entity\Content $content)
    {
        $this->content->removeElement($content);
    }

    /**
     * Get content
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add category
     *
     * @param \BLOGBundle\Entity\Category $category
     *
     * @return Article
     */
    public function addCategory(\BLOGBundle\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \BLOGBundle\Entity\Category $category
     */
    public function removeCategory(\BLOGBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @var integer
     */
    private $longitude;

    /**
     * @var integer
     */
    private $latitude;


    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Set longitude
     *
     * @param integer $longitude
     *
     * @return Article
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return integer
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param integer $latitude
     *
     * @return Article
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return integer
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
}
