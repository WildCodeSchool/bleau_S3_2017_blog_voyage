<?php

namespace BLOGBundle\Controller;

use BLOGBundle\Entity\Article;
use BLOGBundle\Entity\Category;
use BLOGBundle\Entity\Content;
use BLOGBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Finder\SplFileInfo;


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
				
				$nbCategory = count($request->request->get('category')); // Combien de catégories définies?
				
				$checkbox = $request->request->get('checked'); // Faut-il envoyer la newsletter?
				if($checkbox == "on"){
					$article->setNewsletter('1');
				}
				else{
					$article->setNewsletter('0');
				}
						
				$check = $request->request->get('category');	

				// if $check car doit au moins avoir un élément pour comparer
				if($check){
					// On transforme toutes les entrées en minuscules, qu'on stocke dans un nouveau tableau
					$str;
					foreach($check as $tab){
						$str[] = strtolower($tab); 
					}
					
					foreach(array_count_values($str) as $key => $value)
					{
						if($value > 1){
							echo "doublon trouvé"; die();
							$request->getSession()->getFlashBag()->add("notice", "Vous avez déclaré des mots clefs en double");
							return $this->redirectToRoute('admin_add'); 
						}
					}
				}
				
		        if($request->request->get('category'))
		        {
                    foreach ($request->request->get('category') as $category)
                    {
                        if (strlen($category)>0) // Si texte n'est pas "";
                        {
                            if($keyword){
                                $i = 0;
                                $j = 0;
                                $endLoop = false;
                                $loopIndex = 0;
                                $loopLength = count($keyword);
                                foreach ($keyword as $key) {
                                    // Si le nouveau mot-clef n'existe pas en bdd, alors on incrémente le compteur pour avoir une trace
                                    if (strtolower($category) !== strtolower($key->getCategory())) {
                                        $j++;
                                    }
                                    // Si le mot-clef renseigné existe déjà, alors on l'associe simplement à l'article créé
                                    if (strtolower($category) == strtolower($key->getCategory())) {
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
                                $categ = new Category();
                                $categ->setCategory($category);
                                $categ->addArticle($article);
                                $article->addCategory($categ);
                            }
				
                        } 
						else{
							
							if($nbCategory == 1)
							{
								$i=0;
								foreach ($keyword as $key) 
								{
                                    if ($key->getCategory() == "Autres") 
									{	
                                        $key->addArticle($article);
                                        $article->addCategory($key);	
										$i++;
                                    }
								}	
								if($i==0) // Autre n'existe pas encore en bdd, donc on le créé
								{
									$categ = new Category();
									$categ->setCategory("Autres");
									$categ->addArticle($article);
									$article->addCategory($categ);
								}
							}
						}
                    }
                }
				else // Valable ici uniquement que pour le premier article. Ne peut être utilisé qu'une fois
					// Si les auteurs ont créé leur premier article sans définir de catégorie
                {                
                    $categ = new Category();
                    $categ->setCategory("Autres");
                    $categ->addArticle($article);
                    $article->addCategory($categ);
                }

			// Images
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
                    else{
                        echo "bonjour";die();
                    }
				}

			foreach($request->request->get('content') as $content)
			{
				$cont = new Content();
				$cont->setContent($content);
				$cont->setArticle($article);
				$article->addContent($cont);
			}

			$em->persist($article);
			$em->flush();

             // Pas besoin de faire un persite sur $category car cascade définie dans ArticleORM


            return $this->redirectToRoute('admin_index');
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
		
		$nbImage = count($article->getImage()); // On compte le nombre d'éléments envoyés dans le formulaire d'édition.
        
		$editForm = $this->createForm('BLOGBundle\Form\ArticleType', $article);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager();
			
			$nbOfAncientBlocksReceived = count($request->request->get('src')); 
            $nbOfNewBlocksReceived = count($request->files->get('src'));
			$nbBlocksReceived = $nbOfAncientBlocksReceived + $nbOfNewBlocksReceived;
			
			// On récupère toutes les images (anciennes (src) + nouvelles (files));
			$tabImgReceived;
			if($request->request->get('src')){
				foreach($request->request->get('src') as $src){
					$tabImgReceived[] = $src;
				}
			}
			
			if($request->files->get('src')){
				foreach($request->files->get('src') as $src){
					$tabImgReceived[] = $src;
				}
			}
			
			// On récupère tous les textes envoyés
			$tabText;
			foreach($request->request->get('content') as $text){
				$tabText[] = $text;
			}
			
			// On récupère toutes les src de la bdd 
			$tabBdd;
			foreach($article->getImage() as $Image){
				$tabBdd[] = $Image->getSrc();
			}		
            
			// Cas 1 : le nombre d'images est supérieur ou égal à "avant l'édition"
			if($nbBlocksReceived >= $nbImage){
				// On met éventuellement à jour les photos et/ou textes sur les lignes présentes en bdd
				for($i=0; $i<$nbImage; $i++){
					// Si les images coïncident (pas de suppression lors de l'édition)
					if($tabImgReceived[$i] == $tabBdd[$i])
					{
						// On met à jour le texte uniquement
						
						$article->getContent()[$i]->setContent($tabText[$i]);

						
					}
					// Attention, si les auteurs suppriment un bloc compris entre 1 et n-1, cela décale tout et donc 
					// on rentre nécessairement dans le else
					else{
						// Si image pas correspondante, alors il faut remplacer l'ancienne.
						// Puis associer le nouveau texte
						// echo "Image à supprimer " . $tabBdd[$i];					
						
						if(is_uploaded_file($tabImgReceived[$i])){
						
							// Pas besoin de faire new Image si on ne fait que mettre à jour le src de l'image i
							
							$newFileName = uniqid() . '.' .$tabImgReceived[$i]->guessExtension();
							
							$article->getImage()[$i]->setSrc($newFileName);
							$article->getContent()[$i]->setContent('' . $tabText[$i] . '');
							
							$tabImgReceived[$i]->move($this->getParameter('image_directory'), $newFileName);							
						}
						
						else{
							$ancientFileName = $tabBdd[$i];
							$path = $this->getParameter('image_directory')."/".$ancientFileName;
							
							$article->getImage()[$i]->setSrc(''.$tabImgReceived[$i].'');
							$article->getContent()[$i]->setContent('' . $tabText[$i] . '');
						}
						
						// C'est ici qu'on doit éventuellement supprimer des images (car y'a forcément un décalage)
						if(in_array('' . $tabBdd[$i] . '', $tabImgReceived)==0)
						{
									
							$ancientFileNameNotFound = $tabBdd[$i];
							$path = $this->getParameter('image_directory')."/".$ancientFileNameNotFound;
									
							if(file_exists($path))
							{
								unlink($path);
							}
						}			
					}
				}	
				

				
			}
			
			// Cas 2 : le nombre d'images est inférieur à "avant l'édition"
			else{
				echo "no";
			}
			
			
			// On récupères les images de l'article édité
            
			$em->persist($article);
            $em->flush();
			
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
			
			
			
			foreach($article->getImage() as $image)
			{
				$fileName = $image->getSrc();
				$path = $this->getParameter('image_directory')."/".$fileName;
				if(file_exists($path))
				{
					unlink($path);
					
				}
			}
		
			
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

        return $this->redirectToRoute('admin_comments');

    }
}
