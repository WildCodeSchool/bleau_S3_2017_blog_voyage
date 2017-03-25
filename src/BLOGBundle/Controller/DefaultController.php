<?php

namespace BLOGBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BLOGBundle:Default:index.html.twig');
    }
}
