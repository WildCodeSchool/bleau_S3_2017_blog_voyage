<?php

namespace BLOGBundle\Controller;


use BLOGBundle\Entity\Comments;
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
