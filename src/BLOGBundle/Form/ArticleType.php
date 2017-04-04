<?php

namespace BLOGBundle\Form;

use BLOGBundle\Entity\Article; // Ajouté
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
                ->add('date')
                ->add('image', CollectionType::class, array(
                    'entry_type' => ImageType::class
                ))
                ->add('content', CollectionType::class, array(
                    'entry_type' => ContentType::class
                ))
				->add('category', CollectionType::class, array(
                    'entry_type' => CategoryType::class
				));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BLOGBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_article';
    }


}
