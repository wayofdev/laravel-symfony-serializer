<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional\Bridge\Laravel\Http;

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\Test;
use WayOfDev\App\Objects\Post;
use WayOfDev\App\Response\Item;
use WayOfDev\App\Response\Response;
use WayOfDev\Serializer\Bridge\Laravel\Http\ResponseFactory;
use WayOfDev\Serializer\Manager\SerializerManager;
use WayOfDev\Tests\Functional\TestCase;

use function sprintf;

final class ResponseFactoryTest extends TestCase
{
    #[Test]
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

    #[Test]
    public function it_creates_from_array_iterator(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerManager::class));
        $responseFactory->withContext(['groups' => ['default']]);
        $response = $responseFactory->create(Response::create([new Item()]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals(
            '[{"id":"0cd74c72-8920-4e4e-86c3-19fdd5103514","key":"magic_number","value":12,"dateTime":null}]',
            $response->getContent()
        );
    }

    #[Test]
    public function it_creates_response_from_array(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerManager::class));
        $response = $responseFactory->fromArray(require __DIR__ . '/../../../../../app/Response/array.php');

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals(
            '{"random_object":{"person":{"first_name":"John Doe","last_name":"Deer","birthdate":"01.01.1976","birth_place":"SomePlace","nationality":"alien"}}}',
            $response->getContent()
        );
    }

    #[Test]
    public function it_sets_non_default_status_code(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerManager::class));
        $responseFactory->withStatusCode(404);
        $responseFactory->withContext(['groups' => 'default']);
        $response = $responseFactory->create(new Item());

        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals(
            '{"id":"0cd74c72-8920-4e4e-86c3-19fdd5103514","key":"magic_number","value":12,"dateTime":null}',
            $response->getContent()
        );
    }

    #[Test]
    public function it_uses_given_context(): void
    {
        $responseFactory = new ResponseFactory(app(SerializerManager::class));
        $responseFactory->withContext(['groups' => 'private']);

        $dateTime = new DateTimeImmutable();

        $response = $responseFactory->create(new Item($dateTime));

        $formattedDateTime = $dateTime->format(DateTimeInterface::ATOM);
        $expectedResponse = sprintf(
            '{"id":"0cd74c72-8920-4e4e-86c3-19fdd5103514","onlyForAdmin":"secret","dateTime":"%s"}',
            $formattedDateTime
        );

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals($expectedResponse, $response->getContent());
    }
}
