<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Illuminate\Http\Response as IlluminateResponse;
use WayOfDev\Serializer\Contracts\SerializerInterface;

final class ResponseFactory
{
    /**
     * @property SerializerInterface $serializer
     */
    private readonly SerializerInterface $serializer;

    private array $context = [];

    private int $status = HttpCode::HTTP_OK;

    public function __construct(SerializerManager $serializer)
    {
        $this->serializer = $serializer->getSerializer('json');
    }

    public function withStatusCode(int $code): self
    {
        $this->status = $code;

        return $this;
    }

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

    public function fromArray(array $response): IlluminateResponse
    {
        $content = $this->serializeResponse($response);

        return $this->respondWithJson($content, $this->status);
    }

    public function empty(): IlluminateResponse
    {
        return $this->respondWithJson('', HttpCode::HTTP_NO_CONTENT);
    }

    private function serializeResponse(array|object $response): string
    {
        $normalizedResponse = $this->serializer->normalize(
            data: $response,
            context: $this->context
        );

        return $this->serializer->serialize($normalizedResponse);
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
