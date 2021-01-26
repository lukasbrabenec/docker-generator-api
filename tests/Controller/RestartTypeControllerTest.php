<?php

namespace App\Tests\Controller;

use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestartTypeControllerTest extends WebTestCase
{
    public function testListGet()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/restart-types');

        /** @var stdClass $responseBodyObject */
        $responseBodyObject = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertObjectHasAttribute('message', $responseBodyObject);
        $this->assertObjectHasAttribute('data', $responseBodyObject);
        $this->assertGreaterThan(0, count($responseBodyObject->data));

        foreach ($responseBodyObject->data as $image) {
            $this->assertObjectHasAttribute('id', $image);
            $this->assertObjectHasAttribute('type', $image);
        }
    }
}
