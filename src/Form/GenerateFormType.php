<?php

namespace App\Form;

use App\Entity\DTO\GenerateDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projectName', TextType::class)
            ->add('dockerVersionId', IntegerType::class)
            ->add('imageVersions', CollectionType::class, [
                'entry_type' => GenerateImageVersionFormType::class,
                'allow_add' => true
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenerateDTO::class,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix()
    {
        return 'generate';
    }
}