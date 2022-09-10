<?php

namespace App\Serializer\Normalizer;

use App\Exception\FormException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

class FormExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param Throwable $exception
     */
    public function normalize(mixed $exception, ?string $format = null, array $context = []): array
    {
        return $this->getErrorsFromForm($exception->getForm());
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof FormException;
    }

    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                $errors[$childForm->getName()] = $this->getErrorsFromForm($childForm);
            }
        }

        return $errors;
    }
}
