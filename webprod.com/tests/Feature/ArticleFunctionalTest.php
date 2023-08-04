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
                "name": "ionut",
                "password": "123456",
                "email": "ionut@webprod.com"
            }'],
            ['{
                "name": "admin-master",
                "password": "123456",
                "email": "admin-master@webprod.com"
            }'],
        ];
    }
}
