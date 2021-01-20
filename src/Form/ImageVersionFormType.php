<?php

namespace App\Form;

use App\Entity\DTO\ImageVersionDTO;
use App\Entity\RestartType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageVersionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageVersionId', IntegerType::class)
            ->add('environments', CollectionType::class, [
                'entry_type' => EnvironmentFormType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
            ->add('ports', CollectionType::class, [
                'entry_type' => PortFormType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
            ->add('extensions', CollectionType::class, [
                'entry_type' => ExtensionFormType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
            ->add('volumes', CollectionType::class, [
                'entry_type' => VolumeFormType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
            ->add('restartType', EntityType::class, [
                'class' => RestartType::class,
                'choice_label' => 'type',
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImageVersionDTO::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'imageVersions';
    }
}
