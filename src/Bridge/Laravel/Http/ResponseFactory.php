<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Bridge\Laravel\Http;

use Illuminate\Http\Response as IlluminateResponse;
use Stringable;
use WayOfDev\Serializer\Contracts\SerializerInterface;
use WayOfDev\Serializer\Manager\SerializerManager;

final class ResponseFactory
{
    private readonly SerializerInterface $serializer;

    /**
     * @var array<string, mixed>
     */
    private array $context = [];

    private int $status = HttpCode::HTTP_OK;

    public function __construct(SerializerManager $serializer)
    {
        $this->serializer = $serializer->serializer('symfony-json');
    }

    public function withStatusCode(int $code): self
    {
        $this->status = $code;

        return $this;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function withContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function create(object $response): IlluminateResponse
    {
        $content = $this->serializeResponse($response);

        return $this->respondWithJson($content, $this->status);
    }

    /**
     * @param array<string, mixed> $response
     */
    public function fromArray(array $response): IlluminateResponse
    {
        $content = $this->serializeResponse($response);

        return $this->respondWithJson($content, $this->status);
    }

    public function empty(): IlluminateResponse
    {
        return $this->respondWithJson('', HttpCode::HTTP_NO_CONTENT);
    }

    /**
     * @param array<string, mixed>|object $response
     */
    private function serializeResponse(array|object $response): string|Stringable
    {
        return $this->serializer->serialize(
            payload: $response,
            context: $this->context
        );
    }

    private function respondWithJson(mixed $content, int $status): IlluminateResponse
    {
        return new IlluminateResponse(
            $content,
            $status,
            ['Content-Type' => 'application/json']
        );
    }
}
