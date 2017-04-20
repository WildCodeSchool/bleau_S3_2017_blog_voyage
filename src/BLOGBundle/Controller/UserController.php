<?php

namespace BLOGBundle\Controller;


use BLOGBundle\Entity\Comments;
use BLOGBundle\Entity\Contact;
use BLOGBundle\Entity\NewsLetter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{	
    public function indexAction(Request $request)
    {		
		$em = $this->getDoctrine()->getManager()->getRepository('BLOGBundle:Article');
		
		if($request->getLocale() == 'fr' || $request->getLocale() == 'es')
		{	
			$articles = $em->myFindAll();
		}
		
		if(count($articles) == 0)
		{
			return new Response("Page en cours de construction. Revenez plus tard :)");
		}

        return $this->render('BLOGBundle:User:index.html.twig', array(
            'articles' => $articles
        ));
    }

    public function viewAction(Request $request, $id)
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

    public function presentationAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$presentation = $em->getRepository('BLOGBundle:Presentation')->findAll();
		
		if(count($presentation) == 0 && $request->getLocale() == 'fr')
		{
			return new Response("Page en cours de construction. Revenez plus tard :)");
		}
		if(count($presentation) == 0 && $request->getLocale() == 'es')
		{
			return new Response(
			"<p style='font-size: 2em; font-weight: bold;'>Página web en elaboración </p>
			Volver a la página principal : <a href='/'> Haga click aquí </a>"
			);
		}
		
		return $this->render('BLOGBundle:User:presentation.html.twig', array(
			'presentation' => $presentation
		));
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
                'form_cat' => $form->createView()
            ));
        }
        return $this->render('BLOGBundle:User:category.html.twig', array(
           'form_cat' => $form->createView(),
            'categ' => $categ
        ));
    }
	
    public function viewCategoryAction(Request $request, $category)
    {
		dump($request->request->get('blogbundle_category')['categories']);die();
	
        $em = $this->getDoctrine()->getManager();
        
		$articles = $em->getRepository('BLOGBundle:Article')->findAll();
        $categ = $em->getRepository('BLOGBundle:Category')->findAll();
		
		if($request->getLocale() == 'fr'){	
			$lineCateg = $em->getRepository('BLOGBundle:Category')->findOneByCategory($category);
		}
		
		if($request->getLocale() == 'es'){	
			$lineCateg = $em->getRepository('BLOGBundle:Category')->findOneBy(array('CategoryEs' => $category));
		}
        		
		foreach ($categ as $donnees)
        {
            $cat[] = $donnees->getCategory();
        }
		
        // On récupère les articles pour les envoyer sur la vue et en haut avoir les choix de catégories possible
        $form = $this->createForm('BLOGBundle\Form\CategoryType', $cat);
        $form->handleRequest($request);

        return $this->render('@BLOG/User/viewCategory.html.twig', array(
			'chosencat' => $category,
			'articles'=> $articles,
			'form_cat' => $form->createView(),
			'category' => $lineCateg
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

	
    public function newsLetterAction(Request $request)
    {
        $emnews = $this->getDoctrine()->getManager();

        $NewsLetter = new NewsLetter();

        $form = $this->createForm('BLOGBundle\Form\NewsLetterType', $NewsLetter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $emnews->persist($NewsLetter);
            $emnews->flush();

            return $this->redirectToRoute('blog_homepage');
        }
        return $this->render('BLOGBundle:User:newsLetter.html.twig', array(
            'form_news' => $form->createView()
        ));
    }
	
	
    public function newsLetterDeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $NewsLetter = $em->getRepository('BLOGBundle:NewsLetter')->findOneById($id);

        $em->remove($NewsLetter);
        $em->flush();

        return $this->render('@BLOG/User/newsLetterDel.html.twig');


    }

    public function contactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $Contact = new Contact();

        $form = $this->createForm('BLOGBundle\Form\ContactType', $Contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($Contact);
            $em->flush();
            $sujet = $Contact->getsujet();
            $message = \Swift_Message::newInstance()
                ->setSubject($sujet)
                ->setFrom('vincentchristophe177@gmail.com')
                ->setTo('vincentchristophe177@gmail.com');


            $message->setBody(
//
                $this->renderView(
                    '@BLOG/User/formulaire_contact.html.twig',
                    array(
                        'form' => $Contact)
                ),
                'text/html'
            );
            ;

            $this->get('mailer')->send($message);

            return $this->redirectToRoute('blog_homepage');
        }

        return $this->render('@BLOG/User/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
