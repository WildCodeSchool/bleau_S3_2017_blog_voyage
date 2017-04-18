<?php

namespace BLOGBundle\Entity;

/**
 * Content
 */
class Content
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;


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
     * Set content
     *
     * @param string $content
     *
     * @return Content
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
     * @var \BLOGBundle\Entity\Article
     */
    private $article;


    /**
     * Set article
     *
     * @param \BLOGBundle\Entity\Article $article
     *
     * @return Content
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
     * @var string
     */
    private $contentES;


    /**
     * Set contentES
     *
     * @param string $contentES
     *
     * @return Content
     */
    public function setContentES($contentES)
    {
        $this->contentES = $contentES;

        return $this;
    }

    /**
     * Get contentES
     *
     * @return string
     */
    public function getContentES()
    {
        return $this->contentES;
    }
}
