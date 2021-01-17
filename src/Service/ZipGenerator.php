<?php

namespace App\Service;

use App\Entity\DTO\GenerateDTO;
use App\Entity\DTO\GenerateImageVersionDTO;
use ZipArchive;

class ZipGenerator
{
    /**
     * @param GenerateDTO $requestObject
     * @return string
     */
    public function generateArchive(GenerateDTO $requestObject): string
    {
        $zipFilePath = stream_get_meta_data(tmpfile())['uri'];

        $zip = new ZipArchive();
        $zip->open($zipFilePath, ZipArchive::CREATE);
        $zip->addFromString('docker-compose.yml', $requestObject->getDockerComposeText());

        /** @var GenerateImageVersionDTO $imageVersion */
        foreach ($requestObject->getImageVersions() as $imageVersion) {
            if ($imageVersion->getDockerfileLocation()) {
                $zip->addFromString($imageVersion->getDockerfileLocation() . 'Dockerfile', $imageVersion->getDockerfileText());
            }
        }
        $zip->close();
        return $zipFilePath;
    }
}