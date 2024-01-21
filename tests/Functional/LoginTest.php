<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\UserFactory;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class LoginTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    /**
     * @dataProvider provideLoginOption
     * @param array $option
     * @param int $statusCode
     */
    public function testLogin(array $option, int $statusCode): void
    {

        $user = UserFactory::createOne([
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response = self::createClient()->request('POST', '/login', $option);
        $this->assertResponseStatusCodeSame($statusCode);

        if ($statusCode === Response::HTTP_NO_CONTENT) {
            $this->assertArrayHasKey('location', $response->getHeaders());
            $this->assertEquals(
                ['/api/users/'. $user->getId()],
                $response->getHeaders()['location']
            );
        }
    }

    public function provideLoginOption(): iterable
    {
        yield [
            [
                'json' => [
                    'email' => 'test@example.com',
                    'password' => 'password',
                ]
            ],
            Response::HTTP_NO_CONTENT
        ];
        yield [
            [
                'body' => [
                    'email' => 'test@example.com',
                    'password' => 'password',
                ]
            ],
            Response::HTTP_UNAUTHORIZED
        ];
    }
}