<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests;

use Symfony\Component\Serializer\SerializerInterface;
use WayOfDev\Serializer\App\Entity;
use WayOfDev\Serializer\App\Item;
use WayOfDev\Serializer\App\Response;
use WayOfDev\Serializer\ResponseFactory;

final class ResponseFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_response(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerInterface::class));
        $response = $responseFactory->create(new Entity());

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{"items":[]}', $response->getContent());
    }

    /**
     * @test
     */
    public function it_creates_from_array_iterator(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerInterface::class));
        $response = $responseFactory->create(Response::create([new Item()]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('[{"key":"magic_number","value":12}]', $response->getContent());
    }

    /**
     * @test
     */
    public function it_creates_response_from_array(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerInterface::class));
        $response = $responseFactory->fromArray(require __DIR__ . '/../app/stub_array.php');

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals(
            '{"random_object":{"person":{"first_name":"Valery Albertovich","last_name":"Zhmyshenko","birthdate":"01.01.1976","birth_place":"Chuguev","nationality":"ukrainian"}}}',
            $response->getContent()
        );
    }

    /**
     * @test
     */
    public function it_sets_non_default_status_code(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerInterface::class));
        $responseFactory->withStatusCode(404);
        $responseFactory->withContext(['groups' => 'default']);
        $response = $responseFactory->create(new Entity());

        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals('{"items":[],"amount":777,"text":"Some String"}', $response->getContent());
    }

    /**
     * @test
     */
    public function it_uses_given_context(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerInterface::class));
        $responseFactory->withContext(['groups' => 'default']);

        $response = $responseFactory->create(new Entity());

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{"items":[],"amount":777,"text":"Some String"}', $response->getContent());
    }
}
