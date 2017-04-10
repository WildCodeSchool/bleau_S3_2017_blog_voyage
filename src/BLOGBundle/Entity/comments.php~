<?php

namespace BLOGBundle\Entity;

/**
 * comments
 */
class Comments
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $comment;

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
     * Set author
     *
     * @param string $author
     *
     * @return comments
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return comments
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    public function __construct()
    {
        $this->date = new \ Datetime();
        $this->publication = 0;
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
     * @var \BLOGBundle\Entity\Article
     */
    private $article;


    /**
     * Set article
     *
     * @param \BLOGBundle\Entity\Article $article
     *
     * @return Comments
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
     * @var boolean
     */
    private $publication;


    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Comments
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get publication
     *
     * @return boolean
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * Set publication
     *
     * @return boolean
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    }
}
