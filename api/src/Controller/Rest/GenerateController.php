<?php

namespace App\Controller\Rest;

use App\Form\RequestFormType;
use App\Service\DockerfileGenerator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenerateController extends AbstractFOSRestController
{
    /** @var DockerfileGenerator */
    private $dockerfileGenerator;

    /**
     * @param DockerfileGenerator $dockerfileGenerator
     */
    public function __construct(DockerfileGenerator $dockerfileGenerator)
    {
        $this->dockerfileGenerator = $dockerfileGenerator;
    }


    /**
     * @Rest\Post("/generate")
     * @param Request $request
     * @param DockerfileGenerator $dockerfileGenerator
     * @return BinaryFileResponse
     */
    public function generate(Request $request, DockerfileGenerator $dockerfileGenerator)
    {
        $form = $this->createForm(RequestFormType::class);
        $form->submit($request->request->get($form->getName()));
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
        } else {
            dump($form->getErrors());
        }
        die;

        //$fileContent = $dockerfileGenerator->generate();

        return (new BinaryFileResponse('../../../files/test.zip'));
    }
}
