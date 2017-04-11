<?php

namespace BLOGBundle\Controller;

use BLOGBundle\Entity\Article;
use BLOGBundle\Entity\Category;
use BLOGBundle\Entity\Content;
use BLOGBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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
		$articles = $articles->myFindAll();

		$comments = $em->getRepository('BLOGBundle:Comments');
		$comments = $comments->myFindAll();

	   // On envoit le résultat à la vue
        return $this->render('@BLOG/Admin/index.html.twig', array(
            'articles' => $articles,
            'comments' => $comments
        ));
    }

    /**
     * Creates a new Admin entity.
     *
     */
    public function addAction(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		
		// On récupère les mots-clefs 
		$keyword = $em->getRepository('BLOGBundle:Category')->findAll();
		
        $article = new Article();

        $form = $this->createForm('BLOGBundle\Form\ArticleType', $article);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
				// Si l'input catégorie a été généré
		        if($request->request->get('category'))
		        {
                    foreach ($request->request->get('category') as $category)
                    {
						// Si l'input a été généré et qu'il n'est pas laissé vide !!!!!!!! (par oubli de le remplir par exemple)
                        if (strlen($category)>0) 
                        {
                            if ($keyword) {
								
                                $i = 0;
                                $j = 0;
                                $endLoop = false;
                                $loopIndex = 0;
                                $loopLength = count($keyword);
                                foreach ($keyword as $key) {
                                    // Si le nouveau mot-clef n'existe pas en bdd, alors on incrémente le compteur pour avoir une trace
                                    if ($category !== $key->getCategory()) {
                                        $j++;
                                    }
                                    // Si le mot-clef renseigné existe déjà, alors on l'associe simplement à l'article créé
                                    if ($category == $key->getCategory()) {
										//echo "mon premier article avec catégorie";die();
										
                                        $key->addArticle($article);
                                        $article->addCategory($key);
                                        $i++;
                                    }

                                    // Si le mot-clef renseigné n'existe pas encore, alors on l'ajoute en bdd et on fait l'association

                                    $loopIndex++; // On ajoute un tour de boucle

                                    if ($loopLength == $loopIndex) {
                                        $endLoop = true; // Si on a parcouru, alors fin des boucles
                                    }
                                }
                                // Puisque j est supérieur à 0, alors on doit créer une catégorie.
                                if ($j > 0 AND $i == 0 AND $endLoop == true) {
									// echo "Noix de cajou";die();
									
                                    $categ = new Category();
                                    $categ->setCategory($category);
                                    $categ->addArticle($article);
                                    $article->addCategory($categ);
                                }
                            } else // Si la base est vide, je crée ma première catégorie
                            {
								echo 'Soleil';die();
                                $categ = new Category();
                                $categ->setCategory($category);
                                $categ->addArticle($article);
                                $article->addCategory($categ);
                            }
						// Si l'input categorie a été généré mais qu'il est laissé vide !!!!!!!!	
                        } else { 
							
							// Y'a-t-il déjà des éléments en bdd?
							
							// Si bdd vide, on créé une catégorie "Autres"
							if(!$keyword){
								echo 'Gaz';die();
								$categ = new Category();
								$categ->setCategory("Autres");
								$categ->addArticle($article);
								$article->addCategory($categ);
							}	
							
							// Si la bdd n'est pas vide, on associe catégorie "Autres" à cet article
							else{
								$i=0;
								foreach ($keyword as $key) {
									if ($key->getCategory() == "Autres") {
										// echo 'Glou';die();
										$key->addArticle($article);
										$article->addCategory($key);
										$i++;
									}
								}
								if($i==0){
									echo 'Hey';die();
									$categ = new Category();
									$categ->setCategory("Autres");
									$categ->addArticle($article);
									$article->addCategory($categ);
								}
							}
                        }
                    }
                }
				else
                {
                   echo 'Au revoir';die();
                    $categ = new Category();
                    $categ->setCategory("Autres");
                    $categ->addArticle($article);
                    $article->addCategory($categ);

                }

			// On vérifie qu'il y a au moins une image envoyée
			if(!empty($request->files))
			{
                foreach($request->files->get('src') as $src)
				{
                    if(!empty($src)) { // On vérifie qu'aucun des formulaires envoyés n'est vide
                        $fileName = uniqid() . '.' . $src->guessExtension();
                        $src->move($this->getParameter('image_directory'), $fileName);

                        $image = new Image();
                        $image->setSrc($fileName);
                        $image->setAlt($article->getTitle());
                        $image->setArticle($article);
                        $article->addImage($image);
                    }
				}
			}

			foreach($request->request->get('content') as $content)
			{
				if(!empty($content)){
					$cont = new Content();
					$cont->setContent($content);
					$cont->setArticle($article);
					$article->addContent($cont);
				}
			}

			// On récupère le nombre d'images créées
            // On récupère le nombre de textes créées
            // Est-ce cohérent ?
            // Si texte en trop, alors images correspondantes doivent être vides
            // Si images en trop, alors textes correspondants doivent être vides

            $nbImages = count($request->files->get('src'));
            $nbTexts = count($request->request->get('content'));

            // Si leurs quantités n'est pas similaires, on rentre dans la condition
            if($nbImages !== $nbTexts)
            {
                if($nbImages > $nbTexts){
                    $deltaTexts = $nbImages - $nbTexts;
                    echo "Le delta de texte(s) est de : " .$deltaTexts . " texte(s).
                        <br /> Il faut donc créer " . $deltaTexts . " texte(s) vides";
                    for($i=0; $i<$deltaTexts; $i++){
                        $cont = new Content();
                        $cont->setContent("");
                        $cont->setArticle($article);
                        $article->addContent($cont);
                    }
                }
                if($nbTexts > $nbImages)
                {
                    $deltaImages = $nbTexts - $nbImages;
                    echo "Le delta d'image(s) est de ". $deltaImages .
                        "<br /> Il faut donc créer " . $deltaImages . " image(s) vides";

                    for($i=0; $i<$deltaImages; $i++)
                    {
                        $image = new Image();
                        $image->setSrc("");
                        $image->setAlt("");
                        $image->setArticle($article);
                        $article->addImage($image);

                    }
                }
            }

			$em->persist($article);
			$em->flush();

             // Pas besoin de faire un persite sur $category car cascade définie dans ArticleORM


            return $this->redirectToRoute('admin_add');
        }

        return $this->render('@BLOG/Admin/add.html.twig', array(
            'admin' => $article,
			'keyword' => $keyword,
            'form' => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Admin entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('BLOGBundle:Article')->findOneById($id);

        $editForm = $this->createForm('BLOGBundle\Form\ArticleType', $article);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager();
            var_dump($request->request);
            die();
            // On récupères les images de l'article édité
            foreach($article->getImage() as $image)
            {
                // On vérifie si ça ne matche pas avec les infos en bdd
                // Alors, cela signifie que l'image a été supprimée
                // Il faudra donc l'enlever de la bdd
                $i=0;
                $img = $image;
                $src= $img->getSrc();
                // On récupère les src des images envoyées via le formulaire d'édition
                foreach($request->request->get('src') as $src2)
                {
                    if($src2 == $src) // Si le src de l'image envoyée ne correspond pas à
                    {                // une image en bdd, c'est que l'image en bdd doit être supp
                        $i++;       // Le compteur est alors égal à 0
                    }
                }

                if($i==0)
                {
                    var_dump(src2);
                    // $em->remove($img);
                }
             }


            $em->flush();
            echo $i;
            die();



            return $this->redirectToRoute('admin_edit', array('id' => $article->getId()));
        }

        return $this->render('@BLOG/Admin/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Admin entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('BLOGBundle:Article')->findOneById($id);
            $em->remove($article);
            $em->flush($article);


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

    public function commentAction(){
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('BLOGBundle:Comments');
        $comments = $comments->myFindAll();

        return $this->render('@BLOG/Admin/comment.html.twig', array(
            'comments' => $comments
        ));
    }

    public function commentValidationAction($id){
        $em = $this->getDoctrine()->getManager();

        $comment = $em->getRepository("BLOGBundle:Comments")->findOneById($id);

        $comment->setPublication('1');

        $em->persist($comment);
        $em->flush();

        $comment = $em->getRepository("BLOGBundle:Comments")->myFindAll();

        return $this->render('@BLOG/Admin/comment.html.twig', array(
            'comments' => $comment
        ));

    }
}
