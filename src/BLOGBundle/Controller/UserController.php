<?php

namespace BLOGBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UserController extends Controller
{
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager()->getRepository('BLOGBundle:Article');
		$articles = $em->myFindAll();
		
        return $this->render('BLOGBundle:User:index.html.twig', array(
			'articles'=> $articles,
			));
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager()->getRepository('BLOGBundle:Article');
        $article = $em->myFindOne($id);

        return $this->render('BLOGBundle:User:view.html.twig', array(
            'articles'=>$article
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

    public function datesAction(Request $request)
    {

        $start = $request->request->get('start');
        $end = $request->request->get('end');
        $start = $start.' '.'00:00:00';
        $end = $end.' '.'23:59:59';

        $em = $this->getDoctrine()->getManager();
        $date = $em->getRepository('BLOGBundle:Article');

        $date = $date->myFindByDateRange($start,$end);

        // http://symfony.com/doc/current/forms.html
        $form = $this->createFormBuilder()->getForm();

        return $this->render('BLOGBundle:User:dates.html.twig', array(
            'dates' => $date,
            'form' => $form->createView()
        ));
    }

    public function contactAction()
    {
        return $this->render('BLOGBundle:User:contact.html.twig');
    }
}
