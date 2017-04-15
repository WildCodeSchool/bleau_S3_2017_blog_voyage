<?php

namespace BLOGBundle\Controller;

use BLOGBundle\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UserController extends Controller
{
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager()->getRepository('BLOGBundle:Article');
		$articles = $em->myFindAll();
		
		if(count($articles) == 0)
		{
			return new Response("Page en cours de construction. Revenez plus tard :)");
		}
		
        return $this->render('BLOGBundle:User:index.html.twig', array(
			'articles'=> $articles,
			));
    }

    public function viewAction($id, Request $request)
    {
        $comment = new Comments();

        $form = $this->createForm('BLOGBundle\Form\CommentsType', $comment);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('BLOGBundle:Article');
        $article = $article->myFindOne($id);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($article as $articles) {
                $comment->setArticle($articles);
            }
            foreach($comment as $comments) {
                $article->addComment($comments);
            }
            $em->persist($comment);
            $em->flush();
        }

        return $this->render('BLOGBundle:User:view.html.twig', array(
            'articles'=>$article,
            'form' => $form->createView()
        ));
    }

    public function presentationAction()
	{
		$em = $this->getDoctrine()->getManager();
		$presentation = $em->getRepository('BLOGBundle:Presentation')->findAll();
		
		if(count($presentation) == 0)
		{
			return new Response("Page en cours de construction. Revenez plus tard :)");
		}
		
		return $this->render('BLOGBundle:User:profil.html.twig', array(
			'presentation' => $presentation
		));
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
        // On récupères les dates de début et de fin saisie par l'utilisateur
        $start = $request->request->get('start');
        $end = $request->request->get('end');

        // On met des heures et minutes pour correspondre au format datetime...de la bdd
        $start_conv = $start.' '.'00:00:00';
        $end_conv = $end.' '.'23:59:59';

        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('BLOGBundle:Article');

		if(count($articles) == 0)
		{
			return new Response("Page en cours de construction. Revenez plus tard :)");
		}
		
        // On donne les arguments à la méthode de recherche par date du Repository
        $articles = $articles->myFindByDateRange($start_conv, $end_conv);

        // http://symfony.com/doc/current/forms.html
        $form = $this->createFormBuilder()->getForm();

        return $this->render('BLOGBundle:User:dates.html.twig', array(
            'articles' => $articles,
            'form' => $form->createView(),
            // On renvoi les dates saisies par l'utilisateur et on les remet dans le
            // formulaire pour qu'il se rappelle des dates saisies
            'start' => $start,
            'end' => $end,
        ));
    }

    public function contactAction()
    {
        return $this->render('BLOGBundle:User:contact.html.twig');
    }
}
