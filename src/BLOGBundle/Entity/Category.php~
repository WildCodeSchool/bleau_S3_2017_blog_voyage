<?php

namespace BLOGBundle\Entity;

/**
 * Category
 */
class Category
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $category;


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
     * Set category
     *
     * @param string $category
     *
     * @return Category
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @var \BLOGBundle\Entity\Article
     */
    private $id_article;


    /**
     * Set idArticle
     *
     * @param \BLOGBundle\Entity\Article $idArticle
     *
     * @return Category
     */
    public function setIdArticle(\BLOGBundle\Entity\Article $idArticle = null)
    {
        $this->id_article = $idArticle;

        return $this;
    }

    /**
     * Get idArticle
     *
     * @return \BLOGBundle\Entity\Article
     */
    public function getIdArticle()
    {
        return $this->id_article;
    }
    /**
     * @var \BLOGBundle\Entity\Article
     */
    private $article;


    /**
     * Set article
     *
     * @param \BLOGBundle\Entity\Article $article
     *
     * @return Category
     */
    public function setArticle(\BLOGBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \BLOGBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->article = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add article
     *
     * @param \BLOGBundle\Entity\Article $article
     *
     * @return Category
     */
    public function addArticle(\BLOGBundle\Entity\Article $article)
    {
        $this->article[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \BLOGBundle\Entity\Article $article
     */
    public function removeArticle(\BLOGBundle\Entity\Article $article)
    {
        $this->article->removeElement($article);
    }
}
