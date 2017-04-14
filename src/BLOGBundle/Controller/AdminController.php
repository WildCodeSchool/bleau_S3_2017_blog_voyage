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
							$request->getSession()->getFlashBag()->add("notice", "Vous avez déclaré des mots clefs en double. L'article n'a donc pas été publié. Veuillez recommencer.");
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
                                    if(strtolower($category) !== strtolower($key->getCategory())) 
									{
                                        $j++;
                                    }
                                    // Si le mot-clef renseigné existe déjà, alors on l'associe simplement à l'article créé
                                    if(strtolower($category) == strtolower($key->getCategory())) 
									{
                                        $key->addArticle($article);
                                        $article->addCategory($key);
                                        $i++;
                                    }
                                    // Si le mot-clef renseigné n'existe pas encore, 
									// Alors on l'ajoute en bdd et on fait l'association
									// On ajoute un tour de boucle
                                    $loopIndex++; 
                                    if ($loopLength == $loopIndex) {
                                        $endLoop = true; // Si on a parcouru, alors fin des boucles
                                    }
                                }
                                // Puisque j est supérieur à 0, alors on doit créer une catégorie.
                                if ($j > 0 AND $i == 0 AND $endLoop == true) {
									// echo "Noix de cajou";die();
                                    $categ = new Category();
                                    $categ->setCategory(ucfirst($category));
                                    $categ->addArticle($article);
                                    $article->addCategory($categ);
                                }
                            } 
							else // Si la base est vide, je crée ma première catégorie
                            {
                                $categ = new Category();
                                $categ->setCategory(ucfirst($category));
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
			
			$checkbox = $request->request->get('checked'); // Faut-il envoyer la newsletter?
			if($checkbox == "on"){
				$article->setNewsletter('1');
			}
			else{
				$article->setNewsletter('0');
			}

			
			$em->persist($article);
			$em->flush();
			
			// Une fois les infos enregistrées, on envoie la newsletter avec Swiftmailer
			// On récupère les infos de la bdd
			/*
			if($checkbox == "on"){
				swiftmailer
				
				$this->render(........twig, array
					'image' => $src[0];
					'texte' => $cont[0];
				)
				array(
				
				)
			}
			*/

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
        $imageBdd = $em->getRepository('BLOGBundle:Image')->findBy(array('article' => $article));
        $contentBdd = $em->getRepository('BLOGBundle:Content')->findBy(array('article' => $article));
        $categoryBdd = $em->getRepository('BLOGBundle:Category')->myFindAll();
        
		// On compte le nombre de blocs Img + Text(texte vide ou pas) envoyés dans le form
		$nbImage = count($article->getImage()); 
		
		// On compte le nombre de catégories envoyées dans le form		
		$nbCatArticleBdd = count($article->getCategory());        
		
		$editForm = $this->createForm('BLOGBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);
		
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
			$this->getDoctrine()->getManager();
			
			/****************************************** 
			*******************************************
			
				GESTION DES BLOCS IMAGES + TEXTES
			
			*******************************************
			******************************************/
			
			// On compte le nombre d'anciens blocs IMAGE + TEXTE envoyés (si !== 0, : UPDATE)
			$nbOfAncientBlocksReceived = count($request->request->get('src')); 
			
			// On compte le nombre de nouveaux blocs IMAGE + TEXTE envoyés (si !== 0, : CREATE)
            $nbOfNewBlocksReceived = count($request->files->get('src'));
			
			// On additionne ancien(s) + nouveau(x)
			$nbBlocksReceived = $nbOfAncientBlocksReceived + $nbOfNewBlocksReceived;
				
			// On récupère toutes les images (ANCIENNES (src) + NOUVELLES (files));
			
			// Anciens fichiers que l'utilisateur a conservés (src)
			$tabImgReceived;
			if($request->request->get('src'))
			{
				foreach($request->request->get('src') as $src)
				{
					$tabImgReceived[] = $src;
				}
			}
			
			// Nouveaux fichiers ajoutés (files)
			if($request->files->get('src'))
			{
				foreach($request->files->get('src') as $src)
				{
					$tabImgReceived[] = $src;
				}
			}
			
			// On récupère tous les textes envoyés
			$tabText;
			if($request->request->get('content'))
			{
				foreach($request->request->get('content') as $text)
				{
					$tabText[] = $text;
				}
			}
			
			// On récupère les catégories envoyées et la quantité de catégories
			$check = $request->request->get('category');	
			$nbCategories = count($check);
			
			// On récupère toutes les catégories en bdd
			$allKeyWordsInBdd;
			foreach($categoryBdd as $categ)
			{
				$allKeyWordsInBdd[] = $categ->getCategory();
			}
			
			$tabKeyWord;
			// Si les auteurs retournent un formulaire d'édition avec au moins un mot-clef
			// Si rien reçu, on crée une catégorie "Autres" dans la bdd (si pas existante) :
			   // Voir "GESTION DES CATEGORIES"
			if($check)
			{
				foreach($check as $tab)
				{
					$tabKeyWord[] = $tab;
				}
			}
			
			// On vérifie qu'il n'y a pas de doublon dans les catégories envoyées.
			// if $check car doit au moins avoir un élément pour comparer sinon ça pète
			if($check)
			{
			// On transforme toutes les entrées en minuscules, qu'on stocke dans un nouveau tableau
				$str;
				foreach($check as $tab)
				{
					$str[] = strtolower($tab); 
				}
				
				// Si valeur > 1, alors on a un doublon et on renvoie vers la page d'édition
				foreach(array_count_values($str) as $key => $value)
				{
					if($value > 1)
					{
						$request->getSession()->getFlashBag()->add("notice", "Vous avez déclaré des mots clefs en double. L'article n'a donc pas été édité. Veuillez recommencer.");
						return $this->redirectToRoute('admin_edit', array(
							"id" => $id
						)); 
					}
				}
				
				for($i=0; $i<count($str); $i++)
				{
					// On compare mot-clef envoyé passé en miniscule avec tous les mots clefs de la bdd passés
					// en minuscules
					$CatBddLowercase = array_map('strtolower', $allKeyWordsInBdd);
					if(in_array($str[$i], $CatBddLowercase))
					{
						$keySimilarCategoryInBdd = array_search($str[$i], $CatBddLowercase); 
						$tabKeyWord[$i] = $allKeyWordsInBdd[$keySimilarCategoryInBdd]; 
					}
				}		
			}

			// On récupère toutes les src de la bdd 
			$tabBdd;
			foreach($article->getImage() as $Image)
			{
				$tabBdd[] = $Image->getSrc();
			}	

			// On récupères toutes les catégories associées à l'article
			$keyWordArticle;
			foreach($article->getCategory() as $categ)
			{
				$keyWordArticle[] = $categ->getCategory();
			}
			
			// Cas 1 : le nombre d'images est supérieur ou égal à "avant l'édition"
			if($nbBlocksReceived >= $nbImage)
			{
				// On met éventuellement à jour les photos et/ou textes sur les lignes présentes en bdd
				for($i=0; $i<$nbImage; $i++)
				{
					// Si les images coïncident (pas de suppression lors de l'édition)
					if($tabImgReceived[$i] == $tabBdd[$i])
					{
						// On met à jour le texte uniquement
						$article->getContent()[$i]->setContent($tabText[$i]);						
					}
					// Attention, si les auteurs suppriment un bloc compris entre 1 et n-1, cela décale tout
					// On rentre donc nécessairement dans le else
					else{
						// Si image pas correspondante, alors il faut remplacer l'ancienne.
						// Puis associer le nouveau texte
						if(is_uploaded_file($tabImgReceived[$i]))
						{
							// Pas besoin de faire new Image si on ne fait que mettre à jour le src de l'image i
							$newFileName = uniqid() . '.' .$tabImgReceived[$i]->guessExtension();
							
							$article->getImage()[$i]->setSrc($newFileName);
							$article->getContent()[$i]->setContent('' . $tabText[$i] . '');
							
							$tabImgReceived[$i]->move($this->getParameter('image_directory'), $newFileName);	
						}
						else
						{
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
				
				// Tout le surplus de fichier contenu vs nb de ligne en bdd est nécessairement de l'uploaded
				// On doit donc créer de nouvelles lignes
				// Doit être strictement supérieur
				if($nbBlocksReceived > $nbImage)
				{ 
					for($i=$nbImage; $i<$nbBlocksReceived; $i++)
					{
						if($i>=$nbImage)
						{	
							$fileName = uniqid() . '.' . $tabImgReceived[$i]->guessExtension();
							$tabImgReceived[$i]->move($this->getParameter('image_directory'), $fileName);

							$image = new Image();
							$image->setSrc($fileName);
							$image->setAlt($article->getTitle());
							$image->setArticle($article);
							$article->addImage($image);
							
							$cont = new Content();
							$cont->setContent('' . $tabText[$i] . '');
							$cont->setArticle($article);
							$article->addContent($cont);
						}
					}
				}	
			}
			
			// Cas 2 : le nombre d'images est inférieur à "avant l'édition"
			else
			{
				for($i=0; $i<$nbImage; $i++)
				{
					$foo = false;
					if($nbOfAncientBlocksReceived && $nbOfAncientBlocksReceived)
					{
						if(array_key_exists($i, $tabImgReceived))
						{
							if(is_uploaded_file($tabImgReceived[$i]))
							{
								$newfileName = uniqid() . '.' . $tabImgReceived[$i]->guessExtension();
								$tabImgReceived[$i]->move($this->getParameter('image_directory'), $newfileName);
								
								$article->getImage()[$i]->setSrc($newfileName);
								$article->getContent()[$i]->setContent('' . $tabText[$i] . '');
							}
							else
							{
								$article->getContent()[$i]->setContent('' . $tabText[$i] . '');
								$article->getImage()[$i]->setSrc($tabImgReceived[$i]);								
							}
						}
						else
						{
							$em->remove($imageBdd[$i]);
							$em->remove($contentBdd[$i]);
							$article->removeContent($contentBdd[$i]);
							$article->removeImage($imageBdd[$i]);	
						}
						
						if(in_array($tabBdd[$i], $tabImgReceived)==0)
						{	
							$ancientFileToBeRemoved = $tabBdd[$i];
							$path = $this->getParameter('image_directory')."/".$ancientFileToBeRemoved;
							
							if(file_exists($path))
							{
								unlink($path);
							}
						}
						$foo = true;
					}
					
					elseif($nbOfAncientBlocksReceived && $foo == false)
					{
						if(array_key_exists($i, $tabImgReceived))
						{
							$article->getContent()[$i]->setContent('' . $tabText[$i] . '');
							$article->getImage()[$i]->setSrc($tabImgReceived[$i]);
						}
						else
						{	
							$em->remove($imageBdd[$i]);
							$em->remove($contentBdd[$i]);
							$article->removeContent($contentBdd[$i]);
							$article->removeImage($imageBdd[$i]);		
						}	
						
						if(in_array($tabBdd[$i], $tabImgReceived)==0)
						{
							$ancientFileToBeRemoved = $tabBdd[$i];
							$path = $this->getParameter('image_directory')."/".$ancientFileToBeRemoved;
							
							if(file_exists($path))
							{
								unlink($path);
							}
						}
					}
				
					elseif($nbOfNewBlocksReceived && $foo == false)
					{
						if(array_key_exists($i, $tabImgReceived))
						{
							$article->getContent()[$i]->setContent('' . $tabText[$i] . '');
							$article->getImage()[$i]->setSrc($tabImgReceived[$i]);
						}
						else
						{
							$em->remove($imageBdd[$i]);
							$em->remove($contentBdd[$i]);
							$article->removeContent($contentBdd[$i]);
							$article->removeImage($imageBdd[$i]);	
							
							$ancientFileToBeRemoved = $tabBdd[$i];
							$path = $this->getParameter('image_directory')."/".$ancientFileToBeRemoved;
							
							if(file_exists($path))
							{
								unlink($path);
							}
						}
					}
				}
			}
			
			/****************************************** 
			*******************************************
			
					GESTION DES CATEGORIES 
			
			*******************************************
			******************************************/
			
			// Si aucun mot-clef reçu, on associe l'article à "Autres"
			if($nbCategories == 0)
			{
				$tabKeyWord[] = "Autres";
			}
			
			if($nbCategories >= $nbCatArticleBdd)
			{
				for($i=0; $i<$nbCatArticleBdd; $i++)
				{
					if(array_key_exists($i, $tabKeyWord))	
					{
						// Le mot-clef existe-t-il déjà en bdd et est-il déjà associé à l'article?
						// Cas 1 :  existe en bdd mais pas dans article -> On associe et on empêche le passage dans la boucle
						if(in_array($tabKeyWord[$i], $allKeyWordsInBdd))
						{	
							if(in_array($tabKeyWord[$i], $keyWordArticle)==0)
							{
								$key = array_search($tabKeyWord[$i], $allKeyWordsInBdd);
								$categoryBdd[$key]->addArticle($article);
								$article->addCategory($categoryBdd[$key]);
							}
						}
						
						if(in_array($tabKeyWord[$i], $allKeyWordsInBdd)==0)  
						{
							$categ = new Category();
							$categ->setCategory('' . ucfirst($tabKeyWord[$i]) . '');
							$categ->addArticle($article);
							$article->addCategory($categ);
						}
						
						if(in_array($keyWordArticle[$i], $tabKeyWord)==0)
						{
							$key = array_search($keyWordArticle[$i], $allKeyWordsInBdd);
							$article->removeCategory($categoryBdd[$key]);
							$categoryBdd[$key]->removeArticle($article);
						}
					}	
				}
				
				for($i=$nbCatArticleBdd; $i<$nbCategories; $i++)
				{
					if(in_array($tabKeyWord[$i], $allKeyWordsInBdd))
					{	
						if(in_array($tabKeyWord[$i], $keyWordArticle)==0)
						{
							$key = array_search($tabKeyWord[$i], $allKeyWordsInBdd);
							$categoryBdd[$key]->addArticle($article);
							$article->addCategory($categoryBdd[$key]);
						}
					}
					
					if(in_array($tabKeyWord[$i], $allKeyWordsInBdd)==0)  
					{
						$categ = new Category();
						$categ->setCategory('' . ucfirst($tabKeyWord[$i]) . '');
						$categ->addArticle($article);
						$article->addCategory($categ);
					}
					
					if(array_key_exists($i, $keyWordArticle))
					{	
						if(in_array($keyWordArticle[$i], $tabKeyWord)==0)
						{	
							$key = array_search($keyWordArticle[$i], $allKeyWordsInBdd);
							$article->removeCategory($categoryBdd[$key]);
							$categoryBdd[$key]->removeArticle($article);
						}
						else
						{
							$key = array_search($keyWordArticle[$i], $allKeyWordsInBdd);
							$article->removeCategory($categoryBdd[$key]);
							$categoryBdd[$key]->removeArticle($article);	
						}
					}
				}					
			}
			else
			{
				for($i=0; $i<$nbCatArticleBdd; $i++)
				{
					// Si l'index existe dans le tableau de mots-clefs renvoyé (car on a un nombre inférieur)
					if(array_key_exists($i, $tabKeyWord))
					{
						// Si le mot-clef existe en bdd, 
						// on recherche la clef pour faire l'association d'objets
						if(in_array($tabKeyWord[$i], $allKeyWordsInBdd))
						{	
							// Si le mot-clef n'est pas déjà associé à l'article
							if(in_array($tabKeyWord[$i], $keyWordArticle)==0)
							{
								// On récupère la clef de l'objet en bdd et on l'associe à l'article
								$key = array_search($tabKeyWord[$i], $allKeyWordsInBdd);
								$categoryBdd[$key]->addArticle($article);
								$article->addCategory($categoryBdd[$key]);
							}
						}
						
						if(in_array($tabKeyWord[$i], $allKeyWordsInBdd)==0)  
						{
							$categ = new Category();
							$categ->setCategory('' . ucfirst($tabKeyWord[$i]) . '');
							$categ->addArticle($article);
							$article->addCategory($categ);
						}
						
						if(in_array($keyWordArticle[$i], $tabKeyWord)==0)
						{
							$key = array_search($keyWordArticle[$i], $allKeyWordsInBdd);
							$article->removeCategory($categoryBdd[$key]);
							$categoryBdd[$key]->removeArticle($article);
						}
					}
					else
					{
						if(in_array($keyWordArticle[$i], $tabKeyWord)==0)
						{
							$key = array_search($keyWordArticle[$i], $allKeyWordsInBdd);
							$article->removeCategory($categoryBdd[$key]);
							$categoryBdd[$key]->removeArticle($article);
						}
					}
				}				
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
