<?php

namespace BLOGBundle\Controller;

use BLOGBundle\Entity\Article;
use BLOGBundle\Entity\Category;
use BLOGBundle\Entity\Content;
use BLOGBundle\Entity\Image;
use BLOGBundle\Entity\Presentation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
		$articles = $articles->myFindAll();

		$comments = $em->getRepository('BLOGBundle:Comments');
		$comments = $comments->myFindAllNotPublicated();
		
		$profil = $em->getRepository('BLOGBundle:Presentation');
		$profil = $profil->findAll();

	   // On envoit le résultat à la vue
        return $this->render('@BLOG/Admin/index.html.twig', array(
            'articles' => $articles,
            'comments' => $comments, 
			'profil' => $profil
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

		if ($form->isSubmitted() && $form->isValid()) 
		{
			$checkFr = $request->request->get('categoryFr');	
			$rowEs = $request->request->get('categoryEs'); // Création de nouveaux mots-clef en espagnol
			$checkEs = []; // Est forcément nul si sélection de mot clef en français, ou pas d'ailleurs...
			
			$nbCategory = count($checkFr);
			$nbCategoryEs = count($checkEs);
			$nbRowEs = count($rowEs);
			
			$deltaFrEs = $nbCategory - $nbRowEs;
			
			// On met tous les mots français de la base dans un tableau
			$tabKeyWordBdd = [];
			foreach($keyword as $k)
			{
				$tabKeyWordBdd[] = $k->getCategory();
			}
			
			// Si réception mot(s)-clef(s) en français mais pas en espagnol (sélection uniquement, pas création)
			$nbAssociations = 0;
			$check = false;
			
			if($checkFr && $nbCategoryEs == 0) 
			{
				$l=0;
				foreach($checkFr as $categ)
				{
					if($l < $deltaFrEs)
					{
						$key = array_search($categ, $tabKeyWordBdd);
						$checkEs[] = $keyword[$key]->getCategoryEs();
						$nbAssociations++;
						$l++;
						$check = true;
					}	
				}	
			}
			
			if($nbAssociations > 0 && $rowEs)
			{
				foreach($rowEs as $cat)
				{
					$checkEs[] = $cat;
				}
			}
			
			// Si on n'a que des nouveaux mots-clef (aucune sélection de mot(s)-clef(s) français)
			if(isset($check) AND $check == false AND $rowEs)
			{
				foreach($rowEs as $cat)
				{
					$checkEs[] = $cat;
				}
			}
		
			if($checkFr && $checkEs)
			{
				// On transforme toutes les entrées en minuscules, qu'on stocke dans un nouveau tableau
				$strFrMin = [];
				$strFr = [];
				foreach($checkFr as $tab)
				{
					$strFrMin[] = strtolower($tab);  
					$strFr[] = $tab;  
				}
				
				$strEsMin = [];
				$strEs = [];
				foreach($checkEs as $tab)
				{
					$strEsMin[] = strtolower($tab); 
					$strEs[] = $tab; 
				}
				
				foreach(array_count_values($strFrMin) as $key => $value)
				{
					if($value > 1)
					{
						$request->getSession()->getFlashBag()->add("notice", "Vous avez déclaré des mots clefs français en double. L'article n'a donc pas été publié. Veuillez recommencer.");
						return $this->redirectToRoute('admin_add'); 
					}
				}
				
				foreach(array_count_values($strEsMin) as $key => $value)
				{
					if($value > 1)
					{
						$request->getSession()->getFlashBag()->add("notice", "Vous avez déclaré des mots clefs espagnol en double. L'article n'a donc pas été publié. Veuillez recommencer.");
						return $this->redirectToRoute('admin_add'); 
					}
				}
			}
				
			if($checkFr && $checkEs)
			{
				foreach($checkFr as $key=>$category)
				{					
					if(strlen($category)>0) // Si texte n'est pas "";
					{						
						if($keyword)
						{
							$i = 0;
							$j = 0;
							$endLoop = false;
							$loopIndex = 0;
							$loopLength = count($keyword);
							
							foreach ($keyword as $k=>$val) 
							{								
								// Si les nouveaux mots-clefs n'existe pas en bdd, alors on incrémente le compteur pour avoir une trace
								if(strtolower($category) !== strtolower($val->getCategory()) && 
								   strtolower($strEs[$key]) !== strtolower($val->getCategoryEs())) 
								{
									$j++;
								}
								
								// Si les mot-clefs renseignés existent déjà, alors on les associe simplement à l'article créé
								if(strtolower($category) == strtolower($val->getCategory()) &&
								   strtolower($strEs[$key]) == strtolower($val->getCategoryEs())) 
								{			
									$val->addArticle($article);
									$article->addCategory($val);
									$i++;
								}
								// Si le mot-clef renseigné n'existe pas encore, 
								// Alors on l'ajoute en bdd et on fait l'association
								// On ajoute un tour de boucle
								$loopIndex++; 
								if ($loopLength == $loopIndex) 
								{
									$endLoop = true; // Si on a parcouru, alors fin des boucles
								}
							}
							// Puisque j est supérieur à 0, alors on doit créer une catégorie.
							if ($j > 0 AND $i == 0 AND $endLoop == true) 
							{								
								$categ = new Category();
								$categ->setCategory(ucfirst($category));
								$categ->setCategoryEs(ucfirst($strEs[$key]));
								$categ->addArticle($article);
								$article->addCategory($categ);
							}
						} 
						else // Si la base est vide, je crée ma première catégorie
						{
							$categ = new Category();
							$categ->setCategory(ucfirst($category));
							$categ->setCategoryEs(ucfirst($strEs[$key]));
							$categ->addArticle($article);
							$article->addCategory($categ);
						}
					} 
					else
					{
						if($nbCategory == 1)
						{
							$i=0;
							foreach($keyword as $k) 
							{
								if($k->getCategory() == "Autres" && $k->getCategoryEs() == "Otros") 
								{	
									$k->addArticle($article);
									$article->addCategory($k);	
									$i++;
								}
							}	
							if($i==0) // Autre n'existe pas encore en bdd, donc on le créé
							{
								$categ = new Category();
								$categ->setCategory("Autres");
								$categ->setCategoryEs("Otros");
								$categ->addArticle($article);
								$article->addCategory($categ);
							}
						}
					}
				}
			}
			else // Valable ici uniquement que pour le premier article. Ne peut être utilisé qu'une fois 
			{   // Si les auteurs ont créé leur premier article sans définir de catégorie         
				$categ = new Category();
				$categ->setCategory("Autres");
				$categ->setCategoryEs("Otros");
				$categ->addArticle($article);
				$article->addCategory($categ);
			}

			// Images
			foreach($request->files->get('src') as $src)
			{
				if(!empty($src)) 
				{   // On vérifie qu'aucun des formulaires envoyés n'est vide
					$fileName = uniqid() . '.' . $src->guessExtension();
					$src->move($this->getParameter('image_directory'), $fileName);

					$image = new Image();
					$image->setSrc($fileName);
					$image->setAlt($article->getTitleFr());
					$image->setArticle($article);
					$article->addImage($image);
				}
			}
			
			foreach($request->request->get('contentFr') as $content)
			{
				$contentFr[] = $content;
			}
			
			foreach($request->request->get('contentEs') as $content)
			{
				$contentEs[] = $content;
			}
			
			foreach($contentFr as $key=>$content)
			{
				$cont = new Content();
				$cont->setContent($content);
				$cont->setContentEs($contentEs[$key]);
				$cont->setArticle($article);
				$article->addContent($cont);
			}
			
			// Faut-il envoyer la newsletter?
			$checkbox = $request->request->get('checked'); 
			if($checkbox == "on")
			{
				$article->setNewsletter('1');
			}
			else
			{
				$article->setNewsletter('0');
			}
			
			$em->persist($article);
			$em->flush();

            /*
            * Une fois les infos enregistrées, on envoie la newsletter
            * On récupère les infos de la bdd

            * mise en place de l'envoi swiftmailer
            * if()checkbox coché on récupère le premier content, la premiere image et la premiere catégorie*/
            if ($checkbox == "on") {
//            recupérer directement le dernier article
                $article_mail = $article;
                $image_mail = $article_mail->getImage();
                $image_mails = $image_mail[0]->getSrc();

                //recuperer chaque addresse mail a qui envoyer l'article
//          
                $em = $this->getDoctrine()->getManager();

//           $email = $em->getRepository("BLOGBundle:NewsLetter")->findAll();
                $email = $em->getRepository("BLOGBundle:NewsLetter")->findAll();
                foreach ($email as $emails) {
//
//            Pas besoin de faire un persite sur $category car cascade définie dans ArticleORM
                    $message = \Swift_Message::newInstance()
                        ->setSubject('blog de Raquelita et Pierre')
                        ->setFrom('vincentchristophe177@gmail.com')
                        ->setTo('vincentchristophe177@gmail.com');
//                ->setTo('$email')
//ici j'ajoute des images a ma vue
                    $img = $message->embed(\Swift_Image::fromPath('../web/bundles/images/' . $image_mails));

                    $message->setBody(
//
                        $this->renderView(
                            '@BLOG/Admin/emailType.html.twig',
                            array('img' => $img,
                                'article_mail' => $article_mail,
                                'email' => $emails)
                        ),
                        'text/html'
                    );

                    $this->get('mailer')->send($message);
                }
                // Pas besoin de faire un persite sur $category car cascade définie dans ArticleORM
            }
            $request->getSession()->getFlashBag()->add("notice", "L'article a bien été créé.");
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
		
		// On compte le nombre de catégories envoyées dans le form pour l'article d'id $id		
		$nbCatArticleBdd = count($article->getCategory());        
		
		$editForm = $this->createForm('BLOGBundle\Form\ArticleType', $article);
        $editForm->handleRequest($request);
		
        if ($editForm->isSubmitted() && $editForm->isValid()) 
		{
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
			$tabImgReceived =[];
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
			$tabTextFr = [];
			if($request->request->get('contentFr'))
			{
				foreach($request->request->get('contentFr') as $text)
				{
					$tabTextFr[] = $text;
				}
			}
			
			// Si on reçoit de nouveau mot-clef 
			
			$tabTextEs = [];
			if($request->request->get('contentEs'))
			{
				foreach($request->request->get('contentEs') as $text)
				{
					$tabTextEs[] = $text;
				}
			}
			
			// On récupère les catégories envoyées et la quantité de catégories
			$checkFr = $request->request->get('categoryFr');	
			$checkEs = $request->request->get('categoryEs');	
			$nbCategories = count($checkFr);
			$nbCategoriesEs = count($checkEs);	
			
			// On récupère toutes les catégories en bdd			
			$allKeyWordsInBddFr = [];
			foreach($categoryBdd as $categ)
			{
				$allKeyWordsInBddFr[] = $categ->getCategory();
			}
			
			$allKeyWordsInBddEs = [];
			foreach($categoryBdd as $categ)
			{
				$allKeyWordsInBddEs[] = $categ->getCategoryEs();
			}
			
			$tabKeyWordFr = [];
			// Si les auteurs retournent un formulaire d'édition avec au moins un mot-clef
			// Si rien reçu, on crée une catégorie "Autres" dans la bdd (si pas existante) : Voir "GESTION DES 
			// CATEGORIES"
			if($checkFr)
			{
				foreach($checkFr as $tab)
				{
					$tabKeyWordFr[] = ucfirst($tab);
				}
			}
			
			if($checkEs)
			{
				foreach($checkEs as $tab)
				{
					$tabKeyWordEs[] = ucfirst($tab);
				}
			}
			
			// On vérifie qu'il n'y a pas de doublon dans les catégories envoyées.
			// if $check car doit au moins avoir un élément pour comparer sinon ça pète
			if($checkFr && $checkEs)
			{
				// On transforme toutes les entrées en minuscules, qu'on stocke dans un nouveau tableau
				$strFr = [];
				foreach($checkFr as $tab)
				{
					$strFr[] = strtolower($tab); 
				}
				
				$strEs = [];
				foreach($checkEs as $tab)
				{
					$strEs[] = strtolower($tab); 
				}
				
				// Si valeur > 1, alors on a un doublon et on renvoie vers la page d'édition
				foreach(array_count_values($strFr) as $key => $value)
				{
					if($value > 1)
					{
						$request->getSession()->getFlashBag()->add("notice", "Vous avez déclaré des mots clefs français en double. L'article n'a donc pas été édité. Veuillez recommencer.");
						return $this->redirectToRoute('admin_edit', array(
							"id" => $id
						)); 
					}
				}
				
				foreach(array_count_values($strEs) as $key => $value)
				{
					if($value > 1)
					{
						$request->getSession()->getFlashBag()->add("notice", "Vous avez déclaré des mots clefs espagnol en double. L'article n'a donc pas été édité. Veuillez recommencer.");
						return $this->redirectToRoute('admin_edit', array(
							"id" => $id
						)); 
					}
				}				
			}

			// On récupère toutes les src de la bdd
			$tabBdd = [];
			foreach($article->getImage() as $Image)
			{
				$tabBdd[] = $Image->getSrc();
			}	

			// On récupères tous les mots-clefs en français associées à l'article
			$keyWordArticleFr = [];
			foreach($article->getCategory() as $categ)
			{
				$keyWordArticleFr[] = $categ->getCategory();
			}
			
			// On récupères tous les mots-clefs en espagnol associées à l'article
			$keyWordArticleEs = [];
			foreach($article->getCategory() as $categ)
			{
				$keyWordArticleEs[] = $categ->getCategoryEs();
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
						// On met à jour le texte uniquement (Fr et Es...)
						$article->getContent()[$i]->setContent($tabTextFr[$i]);						
						$article->getContent()[$i]->setContentEs($tabTextEs[$i]);						
					}
					
					// Attention, si les auteurs suppriment un bloc compris entre 1 et n-1, cela décale tout
					// On rentre donc nécessairement dans le else
					else
					{
						// Si image pas correspondante, alors il faut remplacer l'ancienne.
						// Puis associer le nouveau texte
						if(is_uploaded_file($tabImgReceived[$i]))
						{
							// Pas besoin de faire new Image si on ne fait que mettre à jour le src de l'image..
							$newFileName = uniqid() . '.' .$tabImgReceived[$i]->guessExtension();
							
							$article->getImage()[$i]->setSrc($newFileName);
							$article->getContent()[$i]->setContent('' . $tabTextFr[$i] . '');
							$article->getContent()[$i]->setContentEs('' . $tabTextEs[$i] . '');
							
							$tabImgReceived[$i]->move($this->getParameter('image_directory'), $newFileName);	
						}
						else
						{
							$ancientFileName = $tabBdd[$i];
							$path = $this->getParameter('image_directory')."/".$ancientFileName;
							
							$article->getImage()[$i]->setSrc(''.$tabImgReceived[$i].'');
							$article->getContent()[$i]->setContent('' . $tabTextFr[$i] . '');
							$article->getContent()[$i]->setContentEs('' . $tabTextEs[$i] . '');
						}
						
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
				
				// Tout le surplus d'images vs nb de lignes en bdd est nécessairement de l'uploaded
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
							$image->setAlt($article->getTitleFr());
							$image->setArticle($article);
							$article->addImage($image);
							
							$cont = new Content();
							$cont->setContent('' . $tabTextFr[$i] . '');
							$cont->setContentEs('' . $tabTextEs[$i] . '');
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
					if($nbOfNewBlocksReceived && $nbOfAncientBlocksReceived)
					{
						if(array_key_exists($i, $tabImgReceived))
						{
							if(is_uploaded_file($tabImgReceived[$i]))
							{
								$newfileName = uniqid() . '.' . $tabImgReceived[$i]->guessExtension();
								$tabImgReceived[$i]->move($this->getParameter('image_directory'), $newfileName);
								
								$article->getImage()[$i]->setSrc($newfileName);
								$article->getContent()[$i]->setContent('' . $tabTextFr[$i] . '');
								$article->getContent()[$i]->setContentEs('' . $tabTextEs[$i] . '');
							}
							else
							{
								$article->getContent()[$i]->setContent('' . $tabTextFr[$i] . '');
								$article->getContent()[$i]->setContentEs('' . $tabTextEs[$i] . '');
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
					
					if($nbOfAncientBlocksReceived && $foo == false)
					{
						if(array_key_exists($i, $tabImgReceived))
						{
							$article->getContent()[$i]->setContent('' . $tabTextFr[$i] . '');
							$article->getContent()[$i]->setContentEs('' . $tabTextEs[$i] . '');
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
				
					if($nbOfNewBlocksReceived && $foo == false)
					{
						if(array_key_exists($i, $tabImgReceived))
						{
							$newfileName = uniqid() . '.' . $tabImgReceived[$i]->guessExtension();
							$tabImgReceived[$i]->move($this->getParameter('image_directory'), $newfileName);
								
							$article->getImage()[$i]->setSrc($newfileName);
							$article->getContent()[$i]->setContent('' . $tabTextFr[$i] . '');
							$article->getContent()[$i]->setContentEs('' . $tabTextEs[$i] . '');
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
				$tabKeyWordFr[] = "Autres";
				$tabKeyWordEs[] = "Otros";
			}
			
			if($nbCategories >= $nbCatArticleBdd)
			{
				for($i=0; $i<$nbCatArticleBdd; $i++)
				{
					if(array_key_exists($i, $tabKeyWordFr))	
					{
						// Le mot-clef existe-t-il déjà en bdd et est-il déjà associé à l'article?
						//dump($tabKeyWordFr[$i]);
						//echo"x"; die();
						// Cas 1 :  existe en bdd 
						if(in_array($tabKeyWordFr[$i], $allKeyWordsInBddFr))
						{	
							// Si n'est pas déjà associé à article, on l'associe
							if(in_array($tabKeyWordFr[$i], $keyWordArticleFr)==0)
							{
								$key = array_search($tabKeyWordFr[$i], $allKeyWordsInBddFr);
								$categoryBdd[$key]->addArticle($article);
								$article->addCategory($categoryBdd[$key]);
							}
						}
						
						if(in_array($tabKeyWordFr[$i], $allKeyWordsInBddFr)==0)  
						{
							$categ = new Category();
							$categ->setCategory('' . ucfirst($tabKeyWordFr[$i]) . '');
							$categ->setCategoryEs('' . ucfirst($tabKeyWordEs[$i]) . '');
							$categ->addArticle($article);
							$article->addCategory($categ);
						}
						
						if(in_array($keyWordArticleFr[$i], $tabKeyWordFr)==0)
						{
							$key = array_search($keyWordArticleFr[$i], $allKeyWordsInBddFr);
							$article->removeCategory($categoryBdd[$key]);
							$categoryBdd[$key]->removeArticle($article);
						}
					}	
				}
				
				for($i=$nbCatArticleBdd; $i<$nbCategories; $i++)
				{
					if(in_array($tabKeyWordFr[$i], $allKeyWordsInBddFr))
					{	
						if(in_array($tabKeyWordFr[$i], $keyWordArticleFr)==0)
						{
							$key = array_search($tabKeyWordFr[$i], $allKeyWordsInBddFr);
							$categoryBdd[$key]->addArticle($article);
							$article->addCategory($categoryBdd[$key]);
						}
					}
					
					if(in_array($tabKeyWordFr[$i], $allKeyWordsInBddFr)==0)  
					{
						$categ = new Category();
						$categ->setCategory('' . ucfirst($tabKeyWordFr[$i]) . '');
						$categ->setCategoryEs('' . ucfirst($tabKeyWordEs[$i]) . '');
						$categ->addArticle($article);
						$article->addCategory($categ);
					}
					
					if(array_key_exists($i, $keyWordArticleFr))
					{	
						if(in_array($keyWordArticleFr[$i], $tabKeyWordFr)==0)
						{	
							$key = array_search($keyWordArticleFr[$i], $allKeyWordsInBddFr);
							$article->removeCategory($categoryBdd[$key]);
							$categoryBdd[$key]->removeArticle($article);
						}
						else
						{
							$key = array_search($keyWordArticleFr[$i], $allKeyWordsInBddFr);
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
					if(array_key_exists($i, $tabKeyWordFr))
					{
						if(in_array($tabKeyWordFr[$i], $allKeyWordsInBddFr))
						{	
							if(in_array($tabKeyWordFr[$i], $keyWordArticleFr)==0)
							{
								// On récupère la clef de l'objet en bdd et on l'associe à l'article
								$key = array_search($tabKeyWordFr[$i], $allKeyWordsInBddFr);
								$categoryBdd[$key]->addArticle($article);
								$article->addCategory($categoryBdd[$key]);
							}
						}
						
						if(in_array($tabKeyWordFr[$i], $allKeyWordsInBddFr)==0)  
						{
							$categ = new Category();
							$categ->setCategory('' . ucfirst($tabKeyWordFr[$i]) . '');
							$categ->setCategoryEs('' . ucfirst($tabKeyWordEs[$i]) . '');
							$categ->addArticle($article);
							$article->addCategory($categ);
						}
						
						if(in_array($keyWordArticleFr[$i], $tabKeyWordFr)==0)
						{
							$key = array_search($keyWordArticleFr[$i], $allKeyWordsInBddFr);
							$article->removeCategory($categoryBdd[$key]);
							$categoryBdd[$key]->removeArticle($article);
						}
					}
					else
					{
						if(in_array($keyWordArticleFr[$i], $tabKeyWordFr)==0)
						{
							$key = array_search($keyWordArticleFr[$i], $allKeyWordsInBddFr);
							$article->removeCategory($categoryBdd[$key]);
							$categoryBdd[$key]->removeArticle($article);
						}
					}
				}				
			}

            $checkbox = $request->request->get('checked');
            if($checkbox == "on")
            {
                $article->setNewsletter('1');
            }
            else
			{
                $article->setNewsletter('0');
            }
            
			$em->persist($article);
            $em->flush();
			
			$request->getSession()->getFlashBag()->add("notice", "L'article a bien été modifié.");
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
        $commentsNotPublicated = $em->getRepository('BLOGBundle:Comments');
        $commentsNotPublicated = $commentsNotPublicated->myFindAllNotPublicated();

        $commentsPublicated = $em->getRepository('BLOGBundle:Comments');
        $commentsPublicated = $commentsPublicated->myFindAllPublicated();

        return $this->render('@BLOG/Admin/comment.html.twig', array(
            'commentsNotPublicated' => $commentsNotPublicated,
            'commentsPublicated' => $commentsPublicated
        ));
    }

    public function commentValidationAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $comment = $em->getRepository("BLOGBundle:Comments")->findOneById($id);
        $comment->setPublication('1');

        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('admin_comments');
    }

    public function commentUnvalidationAction($id){
        $em = $this->getDoctrine()->getManager();

        $comment = $em->getRepository("BLOGBundle:Comments")->findOneById($id);
        $comment->setPublication('0');

        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('admin_comments');
    }
	
	public function commentDeleteAction($id){
        $em = $this->getDoctrine()->getManager();

        $comment = $em->getRepository("BLOGBundle:Comments")->findOneById($id);

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('admin_comments');
    }

    public function profilAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $presentation = new presentation();

        $form = $this->createForm('BLOGBundle\Form\PresentationType', $presentation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            foreach($request->files->get('blogbundle_presentation') as $image)
            {
                $fileName = uniqId() . '.' . $image->guessExtension();
                $image->move($this->getParameter('image_directory'), $fileName);
                $presentation->setImage($fileName);
            }

            $em->persist($presentation);
            $em->flush();

			$request->getSession()->getFlashBag()->add("notice", "Votre profil a bien été créé.
			Vous pouvez l'éditer à tout moment en cliquant sur \"Editer le profil\".");
            return $this->redirectToRoute('admin_index');
        }

        return $this->render('BLOGBundle:Admin:profil.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function newsletterAction()
    {
        $em = $this->getDoctrine()->getManager();
        $email = $em->getRepository("BLOGBundle:NewsLetter")->findAll();


        return $this->render('@BLOG/Admin/newsletter.html.twig', array(
            'emails' => $email));
    }

    public function profilEditAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $presentation = $em->getRepository('BLOGBundle:Presentation')->myFindOne();

        $form = $this->createForm(FormType::class, $presentation)
            ->add('presentation')
            ->add('contributors');

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $image = $request->files->get('image');

            if(count($image) !== 0)
            {
                $ancientFileName = $presentation->getImage();
                $path = $this->getParameter('image_directory')."/".$ancientFileName;
                if(file_exists($path))
                {
                    unlink($path);
                }

                $fileName = uniqId() . '.' . $image->guessExtension();
                $image->move($this->getParameter('image_directory'), $fileName);
                $presentation->setImage($fileName);
            }

            $em->persist($presentation);
            $em->flush();

			$request->getSession()->getFlashBag()->add("notice", "Votre profil a bien été modifié.");
            return $this->redirectToRoute('admin_profil_edit');
        }
		
		if(count($presentation) == 0)
		{
			return $this->redirectToRoute('admin_profil');
		}

        return $this->render('BLOGBundle:Admin:profil.edit.html.twig', array(
            'form' => $form->createView(),
            'presentation' => $presentation
        ));
    }

}
