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

		        if($request->request->get('category'))
		        {
                    foreach ($request->request->get('category') as $category)
                    {
                        if ($category !== "") // Si catégorie est vide (y compris si select est laissé sur 'choisir une catégorie')
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
                        } else { // On gère le set automatique à "Autres" si aucune catégorie indiquée
                            // Deux cas de figure ->
                            // La catégorie "Autres" n'existe pas en bdd. Il faut la créer.
                            // Elle existe en bdd -> on associe l'article à "Autres"
                            $i = 0;
                            foreach ($keyword as $key) {

                                if ($key->getCategory() == "Autres") {
                                    $key->addArticle($article);
                                    $article->addCategory($key);
                                    $i++;
                                }

                                if($i==0){
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

//			  mise en place de l'envoi swiftmailer
//            if()checkbox coché on récupère le premier content, la premiere image et la premiere catégorie

//            recupérer directement le dernier article
         $article_mail = $article;

         $object_cat = $article_mail->getCategory();
         $subject = $object_cat[0]->getCategory();
         $image_mail = $article_mail->getImage();
         $image_mails = $image_mail[0]->getSrc();

         //recuperer chaque addresse mail a qui envoyer l'article
//            $em = $this->getDoctrine()->getManager();
//           $email = $em->getRepository("BLOGBundle:NewsLetter")->findAll();
//
//            foreach($email as $emails)
//            {
//                $lien[] = $emails->getLien();
//            }


//            Pas besoin de faire un persite sur $category car cascade définie dans ArticleORM


            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
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
                            'article_mail' => $article_mail)
                    ),
                    'text/html'
                );
            ;

           $this->get('mailer')->send($message);

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

    public function commentValidationAction($id)
    {
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
    public function newsletterAction()
    {
        $em = $this->getDoctrine()->getManager();
        $email = $em->getRepository("BLOGBundle:NewsLetter")->findAll();


        return $this->render('@BLOG/Admin/newsletter.html.twig', array(
            'email' => $email
        ));
    }

}
