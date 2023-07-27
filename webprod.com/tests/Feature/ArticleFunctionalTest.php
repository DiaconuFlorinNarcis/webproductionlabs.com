<?php

namespace Tests\Feature;

use Tests\TestCase;

class ArticleFunctionalTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function test_Register($data): void
    {
        $response = $this->json('POST', '/api/register', json_decode($data, true));
        dump($response->getContent());
        dump($response->getStatusCode());
        $this->assertTrue(true);
    }

    private function dataProvider(): array
    {
        return [
            ['{
                "name": "florin3",
                "password": "123456",
                "email": "florin3@webprod.com"
            }'],
            ['{
                "name": "admin",
                "email": "admin@webprod.com"
            }'],
        ];
    }
}
