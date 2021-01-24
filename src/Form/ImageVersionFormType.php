<?php

namespace App\Form;

use App\Entity\DTO\ImageVersionDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageVersionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', IntegerType::class)
            ->add('imageName', TextType::class)
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
            ->add('restartType', RestartTypeFormType::class)
            ->add('dependsOn', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImageVersionDTO::class,
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'imageVersions';
    }
}
