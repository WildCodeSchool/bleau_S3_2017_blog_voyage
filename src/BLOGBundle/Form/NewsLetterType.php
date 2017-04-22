<?php

namespace BLOGBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsLetterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locale = $options['locale'];

        if ($locale == 'fr') {
            $builder->add('lien', EmailType::class,
                array('attr' => array('placeholder' => 'votre@email.com')))
                ->add('Souscrire', SubmitType::class
                );
        }
        else
        {
            $builder->add('lien', EmailType::class,
                array('attr' => array('placeholder' => 'vuesto@email.com')))
                ->add('Souscribir', SubmitType::class
                );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BLOGBundle\Entity\NewsLetter',
            'locale' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_newsletter';
    }


}
