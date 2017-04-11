<?php

namespace BLOGBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BLOGBundle\Entity\Category;
use BLOGBundle\Repository\CategoryRepository;

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
        $categ = $em->findAll();
        foreach ($categ as $category)
        {
            $cat[] = $category->getCategory();
        }
        // On récupère les articles pour les envoyer sur la vue et en haut avoir les choix de catégories possible

        $form = $this->createForm('BLOGBundle\Form\CategoryType', $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $data = $data['categories']->getCategory();



            return $this->render('@BLOG/User/viewCategory.html.twig', array(
                'chosencat' => $data,
                'articles'=> $articles,
                'form' => $form->createView()
            ));
        }
        return $this->render('BLOGBundle:User:category.html.twig', array(

            'articles'=>$articles,
            'categories'=>$cat,
           'form' => $form->createView()

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
