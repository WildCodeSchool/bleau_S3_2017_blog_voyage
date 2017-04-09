<?php

namespace BLOGBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager()->getRepository('BLOGBundle:Article');
		$articles = $em->findAll();
//        dump($articles);die;
		
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

    public function categoryAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getRepository('BLOGBundle:Article');
        $articles = $em->findAll();
        $em = $this->getDoctrine()->getManager()->getRepository('BLOGBundle:Category');
        $categories = $em->findAll();
        // On récupère les articles pour les envoyer sur la vue et en haut avoir les choix de catégories possible

        $categories = new ArrayCollection($categories);

        $form = $this->createForm('BLOGBundle\Form\CategoryType', $categories);
       $form->handleRequest($request);

        return $this->render('BLOGBundle:User:category.html.twig', array(

            'articles'=>$articles,
            'categories'=>$categories,
           'form' => $form->createView()

        ));
    }
	
	public function viewCategoryAction($category)
    {
        $em = $this->getDoctrine()->getManager()->getRepository('BLOGBundle:Category');
        $categories = $em->findAll();
        // On récupère les articles ayant category $category
        //we need a foreach article which contain the category a render of this article


       foreach ($categorie as $categories)
       {
           if($category == $categorie )
               $articles_categ = $categorie->get('article');

       }
        return $this->render('BLOGBundle:User:viewCategory.html.twig', array(

            'articles_categ'=>$articles_categ

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
