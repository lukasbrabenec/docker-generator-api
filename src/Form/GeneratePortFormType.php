<?php

namespace App\Form;

use App\Entity\DTO\GeneratePortDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeneratePortFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', IntegerType::class)
            ->add('inward', IntegerType::class)
            ->add('outward', IntegerType::class)
            ->add('exposedToContainers', CheckboxType::class)
            ->add('exposedToHost', CheckboxType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GeneratePortDTO::class
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'ports';
    }
}