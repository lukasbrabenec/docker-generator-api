<?php

namespace App\Controller\Rest;

use App\Entity\DTO\GenerateDTO;
use App\Exception\FormException;
use App\Form\GenerateFormType;
use App\Service\GeneratorService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GenerateController extends BaseController
{
    #[Route('/generate', requirements: ['version' => 'v1'], methods: ['POST'])]
    public function generate(Request $request, GeneratorService $generatorService): BinaryFileResponse
    {
        $requestObject = $this->processForm($request, GenerateFormType::class);
        $zipFilePath = $generatorService->generate($requestObject);

        $response = new BinaryFileResponse($zipFilePath);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            sprintf('%s.zip', $requestObject->getProjectName())
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->deleteFileAfterSend(true);

        return $response;
    }

    protected function processForm(Request $request, string $formClass): GenerateDTO
    {
        $form = $this->createForm($formClass);
        $form->submit($this->getJSON($request)[$form->getName()]);
        if (!$form->isValid()) {
            throw new FormException($form);
        }

        return $form->getData();
    }
}
