<?php

namespace App\Form;

use App\Entity\ComposeFormatVersion;
use App\Entity\DTO\GenerateDTO;
use App\Entity\DTO\ImageVersionDTO;
use App\Entity\Environment;
use App\Entity\Image;
use App\Entity\ImageVersion;
use App\Entity\ImageVersionExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateFormType extends AbstractType
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('projectName', TextType::class)
            ->add('dockerVersionID', IntegerType::class)
            ->add('imageVersions', CollectionType::class, [
                'entry_type' => ImageVersionFormType::class,
                'allow_add' => true,
            ])
            ;

        $builder->addEventListener(
            FormEvents::SUBMIT,
            fn (FormEvent $formEvent) => $this->applyDefaultValues($formEvent->getData())
        );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            fn (FormEvent $formEvent) => $this->resolveDependencies($formEvent->getData())
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GenerateDTO::class,
            'allow_extra_fields' => true,
        ]);
    }

    private function applyDefaultValues(GenerateDTO $generateDTO): void
    {
        if ($generateDTO->getDockerVersionID() !== null) {
            $composeFormatVersion = $this->em->getRepository(ComposeFormatVersion::class)
                ->find($generateDTO->getDockerVersionID());

            if ($composeFormatVersion !== null) {
                $generateDTO->setDockerComposeVersion($composeFormatVersion->getComposeVersion());
            }
        }

        $allImageIDs = [];

        /** @var ImageVersionDTO $imageVersionDTO */
        foreach ($generateDTO->getImageVersions() as $imageVersionDTO) {
            $imageVersion = $this->em->getRepository(ImageVersion::class)
                ->find($imageVersionDTO->getId());

            if ($imageVersion === null) {
                continue;
            }

            $allImageIDs[] = $imageVersion->getImage()->getId();
            $this->applyImageVersionDefaults($imageVersionDTO, $imageVersion);
            $this->applyExtensionDefaults($imageVersionDTO, $imageVersion);
            $this->applyEnvironmentDefaults($imageVersionDTO);
        }

        /** @var ImageVersionDTO $imageVersionDTO */
        foreach ($generateDTO->getImageVersions() as $imageVersionDTO) {
            // set otherImageNamesForGeneration to all imageVersions for easier validation
            $imageVersion = $this->em->getRepository(ImageVersion::class)
                ->find($imageVersionDTO->getId());

            if ($imageVersion !== null) {
                $imageVersionDTO->setOtherImageIDsForGeneration(
                    \array_diff($allImageIDs, [$imageVersion->getId()])
                );
            }
        }
    }

    private function applyImageVersionDefaults(ImageVersionDTO $imageVersionDTO, ImageVersion $imageVersion): void
    {
        if ($imageVersionDTO->getImageName() === null) {
            $imageVersionDTO->setImageName($imageVersion->getImage()->getName());
        } else {
            $imageVersionDTO->setImageName(\str_replace(' ', '_', $imageVersionDTO->getImageName()));
        }

        $imageVersionDTO->setVersion($imageVersion->getVersion());
        $imageVersionDTO->setImageCode($imageVersion->getImage()->getCode());
        $imageVersionDTO->setDockerfileLocation($imageVersion->getImage()->getDockerfileLocation());
    }

    private function applyExtensionDefaults(ImageVersionDTO $imageVersionDTO, ImageVersion $imageVersion): void
    {
        foreach ($imageVersionDTO->getExtensions() as $extensionDTO) {
            $imageVersionExtension = $this->em
                ->getRepository(ImageVersionExtension::class)->findOneBy(
                    [
                    'extension' => $extensionDTO->getId(),
                    'imageVersion' => $imageVersion,
                    ]
                );
            $extensionDTO->setName($imageVersionExtension->getExtension()->getName());
            $extensionDTO->setConfig($imageVersionExtension->getConfig());
            $extensionDTO->setSpecial($imageVersionExtension->getExtension()->isSpecial());
            $extensionDTO->setCustomCommand($imageVersionExtension->getExtension()->getCustomCommand());
        }
    }

    private function applyEnvironmentDefaults(ImageVersionDTO $imageVersionDTO): void
    {
        foreach ($imageVersionDTO->getEnvironments() as $environmentDTO) {
            if ($environmentDTO->getId() === null) {
                continue;
            }

            $environment = $this->em->getRepository(Environment::class)
                ->find($environmentDTO->getId());
            $environmentDTO->setCode($environment->getCode());
            $environmentDTO->setValue($environment->getDefaultValue());
        }
    }

    private function resolveDependencies(GenerateDTO $generateDTO): void
    {
        $dependencyMap = [];

        /** @var ImageVersionDTO $imageVersionDTO */
        foreach ($generateDTO->getImageVersions() as $imageVersionDTO) {
            $imageVersion = $this->em->getRepository(ImageVersion::class)
                ->find($imageVersionDTO->getId());

            if ($imageVersion !== null) {
                $dependencyMap[$imageVersion->getImage()->getName()] = $imageVersionDTO->getImageName();
            }
        }

        foreach ($generateDTO->getImageVersions() as $imageVersionDTO) {
            $dependencies = [];

            foreach ($imageVersionDTO->getDependsOn() as $dependency) {
                $image = $this->em->getRepository(Image::class)->find($dependency);

                if ($image !== null) {
                    $dependencies[] = $dependencyMap[$image->getName()] ?? $image->getName();
                }
            }

            $imageVersionDTO->setDependsOn($dependencies);
        }
    }
}
