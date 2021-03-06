<?php

namespace BLOGBundle\Entity;

/**
 * Presentation
 */
class Presentation
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $presentation;

    /**
     * @var string
     */
    private $contributors;


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
     * Set image
     *
     * @param string $image
     *
     * @return Presentation
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set presentation
     *
     * @param string $presentation
     *
     * @return Presentation
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set contributors
     *
     * @param string $contributors
     *
     * @return Presentation
     */
    public function setContributors($contributors)
    {
        $this->contributors = $contributors;

        return $this;
    }

    /**
     * Get contributors
     *
     * @return string
     */
    public function getContributors()
    {
        return $this->contributors;
    }
    /**
     * @var string
     */
    private $presentationEs;

    /**
     * @var string
     */
    private $contributorsEs;


    /**
     * Set presentationEs
     *
     * @param string $presentationEs
     *
     * @return Presentation
     */
    public function setPresentationEs($presentationEs)
    {
        $this->presentationEs = $presentationEs;

        return $this;
    }

    /**
     * Get presentationEs
     *
     * @return string
     */
    public function getPresentationEs()
    {
        return $this->presentationEs;
    }

    /**
     * Set contributorsEs
     *
     * @param string $contributorsEs
     *
     * @return Presentation
     */
    public function setContributorsEs($contributorsEs)
    {
        $this->contributorsEs = $contributorsEs;

        return $this;
    }

    /**
     * Get contributorsEs
     *
     * @return string
     */
    public function getContributorsEs()
    {
        return $this->contributorsEs;
    }
}
