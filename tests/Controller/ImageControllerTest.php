<?php

namespace App\Tests\Controller;

use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageControllerTest extends WebTestCase
{
    public function testListGet()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/images');

        /** @var stdClass $responseBodyObject */
        $responseBodyObject = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertObjectHasAttribute('message', $responseBodyObject);
        $this->assertObjectHasAttribute('data', $responseBodyObject);
        $this->assertGreaterThan(0, count($responseBodyObject->data));

        foreach ($responseBodyObject->data as $image) {
            $this->assertObjectHasAttribute('id', $image);
            $this->assertObjectHasAttribute('name', $image);
            $this->assertObjectHasAttribute('code', $image);
        }
    }

    public function testDetailGet()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/images/1');

        $responseBodyObject = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertObjectHasAttribute('message', $responseBodyObject);
        $this->assertObjectHasAttribute('data', $responseBodyObject);
        $this->assertEquals(1, count($responseBodyObject->data));

        foreach ($responseBodyObject->data as $image) {
            $this->assertObjectHasAttribute('id', $image);
            $this->assertObjectHasAttribute('name', $image);
            $this->assertObjectHasAttribute('code', $image);
            $this->assertObjectHasAttribute('imageVersions', $image);
            foreach ($image->imageVersions as $imageVersion) {
                $this->assertObjectHasAttribute('id', $imageVersion);
                $this->assertObjectHasAttribute('version', $imageVersion);
                $this->assertObjectHasAttribute('extensions', $imageVersion);
                if (count($imageVersion->extensions)) {
                    foreach ($imageVersion->extensions as $extension) {
                        $this->assertObjectHasAttribute('id', $extension);
                        $this->assertObjectHasAttribute('name', $extension);
                        $this->assertObjectHasAttribute('special', $extension);
                    }
                }
                if (count($imageVersion->environments)) {
                    foreach ($imageVersion->environments as $environment) {
                        $this->assertObjectHasAttribute('id', $environment);
                        $this->assertObjectHasAttribute('code', $environment);
                        $this->assertObjectHasAttribute('defaultValue', $environment);
                        $this->assertObjectHasAttribute('required', $environment);
                        $this->assertObjectHasAttribute('hidden', $environment);
                    }
                }
                if (count($imageVersion->volumes)) {
                    foreach ($imageVersion->volumes as $volume) {
                        $this->assertObjectHasAttribute('id', $volume);
                        $this->assertObjectHasAttribute('hostPath', $volume);
                        $this->assertObjectHasAttribute('containerPath', $volume);
                        $this->assertObjectHasAttribute('active', $volume);
                    }
                }
                if (count($imageVersion->ports)) {
                    foreach ($imageVersion->ports as $port) {
                        $this->assertObjectHasAttribute('id', $port);
                        $this->assertObjectHasAttribute('inward', $port);
                        $this->assertObjectHasAttribute('outward', $port);
                        $this->assertObjectHasAttribute('exposedToHost', $port);
                        $this->assertObjectHasAttribute('exposedToContainers', $port);
                    }
                }
            }
        }
    }

    public function testDetailGetNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/images/-1');

        $responseBodyObject = json_decode($client->getResponse()->getContent());

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertObjectHasAttribute('message', $responseBodyObject);
        $this->assertEquals('Not Found', $responseBodyObject->message);
        $this->assertObjectHasAttribute('data', $responseBodyObject);
        $this->assertObjectHasAttribute('errors', $responseBodyObject);
        $this->assertEquals(0, count((array) $responseBodyObject->data));
    }
}
