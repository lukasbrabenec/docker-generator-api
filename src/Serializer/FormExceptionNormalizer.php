<?php

namespace App\Serializer;

use App\Exception\FormException;
use ArrayObject;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param mixed $exception
     * @param string|null $format
     * @param array $context
     *
     * @return array|ArrayObject|bool|float|int|string|void|null
     */
    public function normalize($exception, string $format = null, array $context = []): array
    {
        return $this->getErrorsFromForm($exception->getForm());
    }

    /**
     * @param FormInterface $form
     * @return array
     */
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

    /**
     * @param mixed $data
     * @param string|null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof FormException;
    }
}