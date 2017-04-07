<?php

namespace BLOGBundle\Controller;


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

    public function presentationAction()
	{
		return $this->render('BLOGBundle:User:presentation.html.twig');
	}

    public function categoryAction()
    {
        return $this->render('BLOGBundle:User:category.html.twig');
    }
	
	public function viewCategoryAction($category)
    {
        return $this->render('BLOGBundle:User:viewCategory.html.twig', array(
            'category'=>$category
        ));
    }

    public function datesAction($request)
    {
        $start = $request->request->get('start');
        $start = $request->request->get('end');
        $em = $this->getDoctrine()->getManager();
        $date = $em->getRepository('BLOGBundle:Article');

        $date = $date->myfindBYdatrange($start,$end);


        return $this->render('BLOGBundle:User:dates.html.twig', array(
            'date' => $date
        ));
    }

    public function contactAction()
    {
        return $this->render('BLOGBundle:User:contact.html.twig');
    }
}
