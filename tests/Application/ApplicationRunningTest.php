<?php

namespace App\Tests\Application;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ApplicationRunningTest extends ApiTestCase
{
    public function testApplicationRunning(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api');
        $this->assertResponseIsSuccessful();
    }
}
