<?php

namespace BLOGBundle\Form;

use BLOGBundle\Entity\Category; // Ajouté
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];

        if ($locale == 'fr'){
            $builder->add('categories', EntityType::class,
                array(
                    'class' => Category::class,
                    'choice_label' => function($category) {
                        return $category->getCategory();
                    },
                    'expanded'=> false,
                    'multiple'=> false
                ));
        }
        else{
            $builder->add('categories', EntityType::class,
                array(
                    'class' => Category::class,
                    'choice_label' => function($category) {
                        return $category->getCategoryEs();
                    },
                    'expanded'=> false,
                    'multiple'=> false
                ));
        }

    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'locale' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_category';
    }


}
