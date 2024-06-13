<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\Attributes\DataProvider;
use Traversable;
use WayOfDev\App\NestedObjects\City;
use WayOfDev\App\NestedObjects\Country;
use WayOfDev\App\Object\Post;
use WayOfDev\App\Object\Product;
use WayOfDev\App\Object\User;
use WayOfDev\Serializer\Serializer;
use WayOfDev\Serializer\SerializerManager;

use function preg_replace;

final class SerializerTest extends TestCase
{
    public static function serializeDataProvider(): Traversable
    {
        yield ['{"id":1,"text":"some","active":false,"views":3}', new Post(1, 'some', false, 3), 'json'];
        yield ['id,text,active,views1,some,1,5', new Post(1, 'some', true, 5), 'csv'];
        yield ['{id:1,text:some,active:true,views:5}', new Post(1, 'some', true, 5), 'symfony-yaml'];
        yield [
            '<?xmlversion="1.0"?><response><id>1</id><text>some</text><active>1</active><views>5</views></response>',
            new Post(1, 'some', true, 5),
            'xml',
        ];
        yield [
            '{"id":1,"title":"Someproduct","price":100.0,"active":false,"product_views":5}',
            new Product(1, 'Some product', 100, false, 5),
            'json',
        ];
        yield [
            '{"name":"USA","cities":[{"name":"Chicago","timezone":"America\/Chicago"},{"name":"NewYork","timezone":"America\/New_York"}]}',
            new Country('USA', [
                new City('Chicago', new DateTimeZone('America/Chicago')),
                new City('New York', new DateTimeZone('America/New_York')),
            ]),
            'json',
        ];
        yield [
            'name,cities.0.name,cities.0.timezone,cities.1.name,cities.1.timezoneUSA,Chicago,America/Chicago,"NewYork",America/New_York',
            new Country('USA', [
                new City('Chicago', new DateTimeZone('America/Chicago')),
                new City('New York', new DateTimeZone('America/New_York')),
            ]),
            'csv',
        ];
        yield [
            '<?xmlversion="1.0"?><response><name>USA</name><cities><name>Chicago</name><timezone>America/Chicago</timezone></cities><cities><name>NewYork</name><timezone>America/New_York</timezone></cities></response>',
            new Country('USA', [
                new City('Chicago', new DateTimeZone('America/Chicago')),
                new City('New York', new DateTimeZone('America/New_York')),
            ]),
            'xml',
        ];
        yield [
            '{"id":3,"registeredAt":"2023-06-05T22:12:55+00:00"}',
            new User(3, new DateTimeImmutable('2023-06-05T22:12:55+00:00')),
            'json',
        ];
        yield [
            'id,registeredAt3,2023-06-05T22:12:55+00:00',
            new User(3, new DateTimeImmutable('2023-06-05T22:12:55+00:00')),
            'csv',
        ];
        yield [
            '<?xmlversion="1.0"?><response><id>3</id><registeredAt>2023-06-05T22:12:55+00:00</registeredAt></response>',
            new User(3, new DateTimeImmutable('2023-06-05T22:12:55+00:00')),
            'xml',
        ];
    }

    /**
     * @test
     */
    #[DataProvider('serializeDataProvider')]
    public function serialize(string $expected, mixed $payload, string $format): void
    {
        $manager = $this->app->get(SerializerManager::class);

        self::assertSame($expected, preg_replace('/\s+/', '', $manager->serialize($payload, $format)));
    }

    /**
     * @test
     */
    public function unserialize(): void
    {
        $manager = $this->app->get(SerializerManager::class);

        $result = $manager->unserialize('{"id":1,"text":"some","active":false,"views":3}', Post::class, 'json');
        self::assertInstanceOf(Post::class, $result);
        self::assertSame(1, $result->id);
        self::assertSame('some', $result->text);
        self::assertFalse($result->active);

        $result = $manager->unserialize(
            '{"id":1,"text":"some","active":false,"views":3}',
            new Post(2, '', true, 1),
            'json'
        );
        self::assertInstanceOf(Post::class, $result);
        self::assertSame(1, $result->id);
        self::assertSame('some', $result->text);
        self::assertFalse($result->active);
    }

    /**
     * @test
     */
    public function unserialize_with_attributes(): void
    {
        $manager = $this->app->get(SerializerManager::class);

        $result = $manager->unserialize(
            '{"id":1,"title":"Some product","price":100,"active":false,"product_views":5}',
            Product::class,
            'json'
        );
        self::assertInstanceOf(Product::class, $result);
        self::assertSame(1, $result->id);
        self::assertSame('Some product', $result->title);
        self::assertSame(100.0, $result->price);
        self::assertFalse($result->active);
        self::assertSame(5, $result->views);
    }

    /**
     * @test
     */
    public function unserialize_nested_objects(): void
    {
        $manager = $this->app->get(SerializerManager::class);

        $result = $manager->unserialize(
            '{"name":"USA","cities":[{"name":"Chicago","timezone":"America\/Chicago"},{"name":"NewYork","timezone":"America\/New_York"}]}',
            Country::class,
            'json'
        );

        self::assertInstanceOf(Country::class, $result);
        self::assertSame('USA', $result->name);
        self::assertSame('Chicago', $result->cities[0]->name);
        self::assertSame('NewYork', $result->cities[1]->name);
    }

    /**
     * @test
     */
    public function unserialize_date_time_interface(): void
    {
        $manager = $this->app->get(SerializerManager::class);

        $result = $manager->unserialize(
            '{"id":3,"registeredAt":"2023-06-05T22:12:55+00:00"}',
            User::class,
            'json'
        );

        self::assertInstanceOf(User::class, $result);
        self::assertSame(3, $result->id);
        self::assertInstanceOf(DateTimeImmutable::class, $result->registeredAt);
        self::assertSame('2023-06-05T22:12:55+00:00', $result->registeredAt->format('c'));
    }

    /**
     * @test
     */
    public function group_normalize(): void
    {
        // dd($this->app->make(SerializerManager::class));

        $manager = $this->app->get(SerializerManager::class);
        /** @var Serializer $serializer */
        $serializer = $manager->getSerializer('json');

        $product = new Product(1, 'Some product', 100, false, 5);

        self::assertSame(
            ['id' => 1, 'title' => 'Some product'],
            $serializer->normalize($product, null, ['groups' => 'group1'])
        );
        self::assertSame(
            ['price' => 100.0, 'active' => false],
            $serializer->normalize($product, null, ['groups' => 'group2'])
        );
        self::assertSame(['product_views' => 5], $serializer->normalize($product, null, ['groups' => 'group3']));
    }
}
