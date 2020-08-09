<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class ZipGenerator
{
    public function generateArchive($files)
    {
        $fileContent = $files['php'];

        $filesystem = new Filesystem();
        $filesystem->dumpFile('../../files/Dockerfile', $fileContent);

        $zip = new \ZipArchive();

        $zip->open('../../../files/test.zip', \ZipArchive::CREATE);
        $zip->addFile('../../../files/Dockerfile', 'Dockerfile');
        $zip->close();
    }
}