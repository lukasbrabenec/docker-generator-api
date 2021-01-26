<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GenerateControllerTest extends WebTestCase
{
    public function testListGet()
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/generate', [], [], ['CONTENT_TYPE' => 'application/json'], '{"generate":{"dockerVersionID":14,"imageVersions":[{"id":5,"version":"7-alpine","environments":[],"volumes":[{"id":5,"hostPath":"./php","containerPath":"/var/www/html","active":true}],"ports":[{"id":5,"inward":80,"outward":80,"exposedToHost":false,"exposedToContainers":true}],"dependsOn":[],"imageName":"PHP","restartType":{"id":1,"type":"no"}},{"id":1,"version":"5.6-alpine","environments":[],"volumes":[{"id":1,"hostPath":"./php","containerPath":"/var/www/html","active":true}],"ports":[{"id":1,"inward":8010,"outward":80,"exposedToHost":true,"exposedToContainers":true}],"dependsOn":[],"imageName":"imageName","restartType":{"id":1,"type":"no"},"extensions":[{"id":80,"name":"composer","special":false},{"id":79,"name":"git","special":false},{"id":11,"name":"exif","special":true},{"id":8,"name":"dba","special":true}]},{"id":45,"version":"5.6","environments":[{"id":70,"value":"root"}],"volumes":[{"id":44,"hostPath":"./mysql/data","containerPath":"/var/lib/mysql","active":true}],"ports":[{"id":46,"inward":3306,"outward":3306,"exposedToHost":true,"exposedToContainers":true}],"dependsOn":[1],"imageName":"imageMysql","restartType":{"id":1,"type":"no"}}],"projectName":"test"}}'); // @codeCoverageIgnore

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/zip');
        $this->assertResponseHeaderSame('Content-Disposition', 'attachment; filename=test.zip');
    }
}
