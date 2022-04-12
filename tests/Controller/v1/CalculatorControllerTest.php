<?php

namespace App\Tests\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CalculatorControllerTest extends WebTestCase
{
    // Application Tests

    public function testGetIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/calculator');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Calculator');

    }

    public function testPostCalculator()
    {
        $response = $this->runPostRequest(['calculation' => '2+2']);

        $this->assertResponseIsSuccessful();
        $this->assertJson('{"status":true, "response" : "4"}', $response);
    }

    public function testPostCalculatorInvalid()
    {
        $response = $this->runPostRequest(['calculation' => '2aaa+222gggvsdf']);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJson('{"status": false, "response": "NAN"}', $response);
    }

    public function testPostCalculatorEmpty()
    {
        $response = $this->runPostRequest(['calculation' => '']);

        $this->assertResponseIsSuccessful();
        $this->assertJson('{"status": true, "response": "0"}', $response);
    }

    public function testPostCalculatorCompletlyEmpty()
    {
        $response = $this->runPostRequest([]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJson('{"status": false, "response": "NAN"}', $response);
    }

    public function testPostCalculatorNotValidField()
    {
        $response = $this->runPostRequest(['something-else' => '2+2']);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJson('{"status": false, "response": "NAN"}', $response);
    }

    private function runPostRequest(array $data): string
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/v1/calculator',
            $data,
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        return $client->getResponse()->getContent();
    }
}
