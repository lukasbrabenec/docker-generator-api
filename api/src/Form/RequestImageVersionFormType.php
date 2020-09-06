<?php

namespace App\Form;

use App\Entity\DTO\RequestImageVersion;
use App\Validator\Constraints\ImageVersion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestImageVersionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageVersionId', IntegerType::class)
            ->add('environments', CollectionType::class, [
                'entry_type' => RequestEnvironmentFormType::class,
                'allow_add' => true
            ])
            ->add('ports', CollectionType::class, [
                'entry_type' => RequestPortFormType::class,
                'allow_add' => true
            ])
            ->add('installExtensions', CollectionType::class, [
                'entry_type' => IntegerType::class,
                'allow_add' => true
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RequestImageVersion::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'imageVersions';
    }
}