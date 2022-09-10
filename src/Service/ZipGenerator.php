<?php

namespace App\Service;

use App\Entity\DTO\GenerateDTO;
use App\Entity\DTO\ImageVersionDTO;
use ZipArchive;

class ZipGenerator
{
    public function generateArchive(GenerateDTO $generateDTO): string
    {
        $zipFilePath = \stream_get_meta_data(\tmpfile())['uri'];

        $zip = new ZipArchive();
        $zip->open($zipFilePath, ZipArchive::CREATE);
        $zip->addFromString('docker-compose.yml', $generateDTO->getDockerComposeText());

        /** @var ImageVersionDTO $imageVersionDTO */
        foreach ($generateDTO->getImageVersions() as $imageVersionDTO) {
            if ($imageVersionDTO->getDockerfileLocation()) {
                $zip->addFromString(
                    $imageVersionDTO->getDockerfileLocation() . 'Dockerfile',
                    $imageVersionDTO->getDockerfileText()
                );
            }
        }

        $zip->close();

        return $zipFilePath;
    }
}
