<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use WayOfDev\App\Item;
use WayOfDev\App\Object\Post;
use WayOfDev\App\Response;
use WayOfDev\Serializer\ResponseFactory;
use WayOfDev\Serializer\SerializerManager;

final class ResponseFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_response(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerManager::class));
        $response = $responseFactory->create(new Post(
            id: 1,
            text: 'Some text',
            active: true,
            views: 777,
        ));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{"id":1,"text":"Some text","active":true,"views":777}', $response->getContent());
    }

    /**
     * @test
     */
    public function it_creates_from_array_iterator(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerManager::class));
        $responseFactory->withContext(['groups' => ['default']]);
        $response = $responseFactory->create(Response::create([new Item()]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('[{"id":"0cd74c72-8920-4e4e-86c3-19fdd5103514","key":"magic_number","value":12}]', $response->getContent());
    }

    /**
     * @test
     */
    public function it_creates_response_from_array(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerManager::class));
        $response = $responseFactory->fromArray(require __DIR__ . '/../../app/array.php');

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals(
            '{"random_object":{"person":{"first_name":"John Doe","last_name":"Zhmyshenko","birthdate":"01.01.1976","birth_place":"Chuguev","nationality":"ukrainian"}}}',
            $response->getContent()
        );
    }

    /**
     * @test
     */
    public function it_sets_non_default_status_code(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerManager::class));
        $responseFactory->withStatusCode(404);
        $responseFactory->withContext(['groups' => 'default']);
        $response = $responseFactory->create(new Item());

        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals('{"id":"0cd74c72-8920-4e4e-86c3-19fdd5103514","key":"magic_number","value":12}', $response->getContent());
    }

    /**
     * @test
     */
    public function it_uses_given_context(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerManager::class));
        $responseFactory->withContext(['groups' => 'private']);

        $response = $responseFactory->create(new Item());

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{"id":"0cd74c72-8920-4e4e-86c3-19fdd5103514","onlyForAdmin":"secret"}', $response->getContent());
    }
}
