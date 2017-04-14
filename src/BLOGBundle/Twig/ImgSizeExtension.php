<?php

namespace BLOGBundle\Twig;

class ImgSizeExtension extends \Twig_Extension
{
    protected $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('imgsize', array($this, 'imgWidth'))
        );
    }

    public function imgWidth($img)
    {
        $data = getimagesize($this->param . '/' . $img);
        $size = ['width' => $data[0], 'height' => $data[1]];
        return $size;
    }

    public function getName()
    {
        return 'imgsize';
    }
}