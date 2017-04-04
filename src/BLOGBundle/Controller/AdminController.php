<?php

namespace BLOGBundle\Controller;

use BLOGBundle\Entity\Article;
use BLOGBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Article controller
 *
 */
class AdminController extends Controller
{
    /**
     * Lists all Admin entities.
     *
     */
    public function indexAction()
    {
		
        // On se connecte à la bdd
        $em = $this->getDoctrine()->getManager();
       
	   // On récupère tous les éléments de la table Article
        $articles = $em->getRepository('BLOGBundle:Article'); 

		
		$articles = $articles->findAll();
		// $articles = $articles->myFindByDateRange('2014-01-01', '2015-12-31');
		// $articles = $articles->myFindByTitle('Hello', '2014');
        
		
	   // On récupère tous les éléments de la table Category associés à un Admin
		
       
	   // On envoit le résultat à la vue
        return $this->render('@BLOG/Admin/index.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * Creates a new Admin entity.
     *
     */
    public function addAction(Request $request)
    {
		$category = new Category(); // J'instancie un nouvel objet category 
		$category->setCategory('bonjour'); // Je récupère le texte saisi par l'utilisateur

        $category2 = new Category(); // J'instancie un nouvel objet category
        $category2->setCategory('bonjour2'); // Je récupère le texte saisi par l'utilisateur


		$article = new Article(); // J'instancie un nouvel objet Admin
		$article->addCategory($category); // J'appelle sa méthode pour ajouter les catégories
        $article->addCategory($category2);
        // Si suppression de la ligne juste au dessus -> Alors le champ n'apparaît plus dans le formulaire
		
		$category->addArticle($article); // J'appelle la méthode qui permet de lier la catégorie à l'Admin fraîchement créé

        $form = $this->createForm('BLOGBundle\Form\ArticleType', $article); // Créer le formulaire selon modèle ArticleType
																			// puis le prérempli avec les éléments de $Admin;
        $form->handleRequest($request); // Traite le formulaire

        if ($form->isSubmitted() && $form->isValid()) {

			$em = $this->getDoctrine()->getManager();
            $em->persist($article); // Pas besoin de faire un persite sur $category car cascade définie dans ArticleORM
            $em->flush();

            return $this->redirectToRoute('admin_show', array('id' => $article->getId()));
        }

        return $this->render('@BLOG/Admin/add.html.twig', array(
            'admin' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Admin entity.
     *
     */
    public function showAction(Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);

        return $this->render('@BLOG/Admin/show.html.twig', array(
            'Admin' => $article,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Admin entity.
     *
     */
    public function editAction(Request $request, Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm('BLOGBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_edit', array('id' => $article->getId()));
        }

        return $this->render('@BLOG/Admin/edit.html.twig', array(
            'Admin' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Admin entity.
     *
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush($article);
        }

        return $this->redirectToRoute('admin_index');
    }

    /**
     * Creates a form to delete a Admin entity.
     *
     * @param Article $article The Admin entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
