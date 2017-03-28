<?php

namespace BLOGBundle\Entity;

/**
 * Article
 */
class Article
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

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
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $id_caterogy;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id_caterogy = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add idCaterogy
     *
     * @param \BLOGBundle\Entity\Category $idCaterogy
     *
     * @return Article
     */
    public function addIdCaterogy(\BLOGBundle\Entity\Category $idCaterogy)
    {
        $this->id_caterogy[] = $idCaterogy;

        return $this;
    }

    /**
     * Remove idCaterogy
     *
     * @param \BLOGBundle\Entity\Category $idCaterogy
     */
    public function removeIdCaterogy(\BLOGBundle\Entity\Category $idCaterogy)
    {
        $this->id_caterogy->removeElement($idCaterogy);
    }

    /**
     * Get idCaterogy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdCaterogy()
    {
        return $this->id_caterogy;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $category;


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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $image;


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
}
