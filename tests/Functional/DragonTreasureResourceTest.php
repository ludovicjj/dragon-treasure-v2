<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\UserFactory;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use App\Factory\DragonTreasureFactory;

class DragonTreasureResourceTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testGetCollectionTreasures(): void
    {
        DragonTreasureFactory::createMany(5);

        $response = static::createClient()->request('GET', '/api/treasures');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertEquals(5, $response->toArray()['hydra:totalItems']);

        $this->assertSame(
            ['@id', '@type', 'name', 'description', 'value', 'coolFactor', 'owner', 'shortDescription', 'plunderedAtAgo'],
            array_keys($response->toArray()['hydra:member'][0])
        );
    }

    /**
     * @dataProvider provideTreasureJson
     * @param array $json
     * @param int $statusCode
     * @param array $expectedViolations
     */
    public function testPostCreateTreasure(array $json, int $statusCode, array $expectedViolations = []): void
    {
        $proxy = UserFactory::createOne();
        $user = $proxy->object();

        $response = static::createClient()
            ->loginUser($user)
            ->request('POST', '/api/treasures', [
                'json' => $json,
            ]);

        $this->assertResponseStatusCodeSame($statusCode);

        if ($statusCode === Response::HTTP_UNPROCESSABLE_ENTITY) {
            $responseArray = $response->toArray(false);
            $this->assertArrayHasKey('violations', $responseArray);
            $this->assertCount(count($expectedViolations), $responseArray['violations']);

            $responseViolations = array_map(function($violation) {
                return [
                    'propertyPath' => $violation['propertyPath'],
                    'message' => $violation['message'],
                ];
            }, $responseArray['violations']);


            foreach ($expectedViolations as $expectedViolation) {
                $this->assertContains($expectedViolation, $responseViolations);
            }
        }
    }

    public function provideTreasureJson(): iterable
    {
        yield [
            [],
            422,
            [
                [
                    'propertyPath' => 'description',
                    'message' => 'This value should not be blank.',
                ],
                [
                    'propertyPath' => 'name',
                    'message' => 'This value should not be blank.',
                ]
            ]
        ];
        yield [
            [
                'description' => 'It sparkles when I wave it in the air.',
                'value' => 1000,
                'coolFactor' => 5,
                'owner' => '/api/users/1',
            ],
            422,
            [
                [
                    'propertyPath' => 'name',
                    'message' => 'This value should not be blank.',
                ]
            ]
        ];
        yield [
            [
                'name' => '',
                'description' => 'It sparkles when I wave it in the air.',
                'value' => 1000,
                'coolFactor' => 5,
                'owner' => '/api/users/1',
            ],
            422,
            [
                [
                    'propertyPath' => 'name',
                    'message' => 'This value should not be blank.',
                ],
                [
                    'propertyPath' => 'name',
                    'message' => 'This value is too short. It should have 2 characters or more.',
                ]
            ]
        ];
        yield [
            [
                'name' => 'A shiny thing',
                'description' => '',
                'value' => 1000,
                'coolFactor' => 5,
                'owner' => '/api/users/1',
            ],
            422,
            [
                [
                    'propertyPath' => 'description',
                    'message' => 'This value should not be blank.',
                ]
            ]
        ];
        yield [
            [
                'name' => 'A shiny thing',
                'description' => 'It sparkles when I wave it in the air.',
                'value' => 1000,
                'coolFactor' => 5,
            ],
            201
        ];
        yield [
            [
                'name' => 'A shiny thing',
                'description' => 'It sparkles when I wave it in the air.',
                'value' => 1000,
                'coolFactor' => 5,
                'owner' => '/api/users/1',
            ],
            201
        ];
    }
}
