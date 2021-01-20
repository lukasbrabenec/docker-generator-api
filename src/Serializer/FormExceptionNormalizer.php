<?php

namespace App\Serializer;

use App\Exception\FormException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

class FormExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param Throwable $exception
     *
     * @return array
     */
    public function normalize($exception, string $format = null, array $context = []): array
    {
        return $this->getErrorsFromForm($exception->getForm());
    }

    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface && $childErrors = $this->getErrorsFromForm($childForm)) {
                $errors[$childForm->getName()] = $childErrors;
            }
        }

        return $errors;
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof FormException;
    }
}
