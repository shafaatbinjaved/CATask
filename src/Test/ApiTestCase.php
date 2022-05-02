<?php

namespace App\Test;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiTestCase extends WebTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var \Generator
     */
    protected $faker;

    /**
     * @var KernelBrowser
     */
    protected $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient([], []);
        $this->faker = Factory::create();
        $this->getEntityManager()->clear();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return static::getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function request(
        string $method,
        string $uri,
        $content = null,
        array $headers = [],
        $files = []
    ): Response
    {
        // detach all entities before sending the request
        $this->getEntityManager()->clear();

        if (!array_key_exists('accept-language', $headers)) {
            $headers['accept-language'] = 'en-GB';
        }

        $server = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json'
        ];
        foreach ($headers as $key => $value) {
            if (is_string($key) && 'content-type' === strtolower($key)) {
                $server['CONTENT_TYPE'] = $value;
                continue;
            }

            $server['HTTP_'.strtoupper(str_replace('-','_', $key))] = $value;
        }

        if (is_array($content) && 1 === preg_match('/^application\/(?:.+\+)?json$/', $server['CONTENT_TYPE'])) {
            $content = json_encode($content);
        }

        $this->client->request($method, $uri, [], $files, $server, $content);

        return $this->client->getResponse();
    }

    protected function getClientRequest()
    {
        return $this->client->getRequest();
    }
}
