<?php

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
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
}