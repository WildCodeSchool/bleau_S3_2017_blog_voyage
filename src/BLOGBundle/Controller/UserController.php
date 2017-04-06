<?php

namespace BLOGBundle\Controller;

use BLOGBundle\Entity\Article;
use BLOGBundle\Entity\Category;
use BLOGBundle\Entity\Content;
use BLOGBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager()->getRepository('BLOGBundle:Article');
		$articles = $em->findAll();
		
        return $this->render('BLOGBundle:User:index.html.twig', array(
			'articles'=> $articles,
			));
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
