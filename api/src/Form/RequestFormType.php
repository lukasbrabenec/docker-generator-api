<?php

namespace App\Form;

use App\Entity\DTO\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dockerVersionId', IntegerType::class)
            ->add('imageVersions', CollectionType::class, [
                'entry_type' => RequestImageVersionFormType::class,
                'allow_add' => true
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Request::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'request';
    }
}