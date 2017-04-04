<?php

namespace BLOGBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('BLOGBundle:User:index.html.twig');
    }

    public function viewAction($id)
    {
        return $this->render('BLOGBundle:User:view.html.twig', array(
            'id'=>$id
        ));
    }

    public function viewCategoryAction($category)
    {
        return $this->render('BLOGBundle:User:viewCategory.html.twig', array(
            'category'=>$category
        ));
    }

    public function categoryAction()
    {
        return $this->render('BLOGBundle:User:category.html.twig');
    }

    public function datesAction()
    {
        return $this->render('BLOGBundle:User:dates.html.twig');
    }

    public function contactAction()
    {
        return $this->render('BLOGBundle:User:contact.html.twig');
    }
}
