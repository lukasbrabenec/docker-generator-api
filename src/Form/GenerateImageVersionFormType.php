<?php

namespace App\Form;

use App\Entity\DTO\GenerateImageVersionDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateImageVersionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageVersionId', IntegerType::class)
            ->add('environments', CollectionType::class, [
                'entry_type' => GenerateEnvironmentFormType::class,
                'allow_add' => true,
                'error_bubbling' => false
            ])
            ->add('ports', CollectionType::class, [
                'entry_type' => GeneratePortFormType::class,
                'allow_add' => true,
                'error_bubbling' => false
            ])
            ->add('extensions', CollectionType::class, [
                'entry_type' => GenerateExtensionFormType::class,
                'allow_add' => true,
                'error_bubbling' => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenerateImageVersionDTO::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'imageVersions';
    }
}