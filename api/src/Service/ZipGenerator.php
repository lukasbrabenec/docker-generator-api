<?php

namespace App\Service;

use App\Entity\DTO\Request;
use App\Entity\DTO\RequestImageVersion;
use Symfony\Component\Filesystem\Filesystem;

class ZipGenerator
{
    /**
     * @param Request $requestObject
     */
    public function generateArchive(Request $requestObject)
    {
        $zip = new \ZipArchive();
        $zip->open('../files/test.zip', \ZipArchive::CREATE);
        $zip->addFromString('docker-compose.yml', $requestObject->getDockerComposeText());

        /** @var RequestImageVersion $imageVersion */
        foreach ($requestObject->getImageVersions() as $imageVersion) {
            if ($imageVersion->getDockerfileLocation()) {
                $zip->addFromString($imageVersion->getDockerfileLocation() . 'Dockerfile', $imageVersion->getDockerfileText());
            }
        }
        $zip->close();
        return $zip;
    }
}