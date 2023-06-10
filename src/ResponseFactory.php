<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Illuminate\Http\Response as IlluminateResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ResponseFactory
{
    private SerializerInterface $serializer;

    private array $context = [];

    private int $status = Response::HTTP_OK;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function withStatusCode(int $code): void
    {
        $this->status = $code;
    }

    public function withContext(array $context): void
    {
        $this->context = $context;
    }

    public function create(object $response): Response
    {
        $content = $this->serializer->serialize(
            $response,
            'json',
            $this->context
        );

        return $this->respondWithJson($content, $this->status);
    }

    public function fromArray(array $response): Response
    {
        $content = $this->serializer->serialize(
            $response,
            'json',
            $this->context
        );

        return $this->respondWithJson($content, $this->status);
    }

    private function respondWithJson($content, int $status): IlluminateResponse
    {
        return new IlluminateResponse(
            $content,
            $status,
            ['Content-Type' => 'application/json']
        );
    }
}
