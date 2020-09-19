<?php

namespace App\Service;

use App\Entity\DTO\Request;
use App\Entity\DTO\RequestImageVersion;
use ZipArchive;

class ZipGenerator
{
    /**
     * @param Request $requestObject
     * @return string
     */
    public function generateArchive(Request $requestObject)
    {
        $zipFilePath = stream_get_meta_data(tmpfile())['uri'];

        $zip = new ZipArchive();
        $zip->open($zipFilePath, ZipArchive::CREATE);
        $zip->addFromString('docker-compose.yml', $requestObject->getDockerComposeText());

        /** @var RequestImageVersion $imageVersion */
        foreach ($requestObject->getImageVersions() as $imageVersion) {
            if ($imageVersion->getDockerfileLocation()) {
                $zip->addFromString($imageVersion->getDockerfileLocation() . 'Dockerfile', $imageVersion->getDockerfileText());
            }
        }
        $zip->close();
        return $zipFilePath;
    }
}