<?php

namespace App\Tests\Functional;

use App\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class EndpointTest extends ApiTestCase
{
    public function testGetDateOvertime(): void
    {
        $response = $this->request('GET', '/time/1/2022-01-01/2022-12-31');
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertIsArray($content);
        $this->assertGreaterThan(0, count($content));
    }
}
