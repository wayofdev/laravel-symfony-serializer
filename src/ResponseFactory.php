<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Illuminate\Http\Response as IlluminateResponse;
use Symfony\Component\HttpFoundation\Response;
use WayOfDev\Serializer\Contracts\SerializerInterface;

final class ResponseFactory
{
    /**
     * @property SerializerInterface $serializer
     */
    private SerializerInterface $serializer;

    private array $context = [];

    private int $status = Response::HTTP_OK;

    public function __construct(SerializerManager $serializer)
    {
        $this->serializer = $serializer->getSerializer('json');
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
        $content = $this->serializer->normalize(
            data: $response,
            context: $this->context
        );

        return $this->respondWithJson(
            $this->serializer->serialize($content),
            $this->status
        );
    }

    public function fromArray(array $response): Response
    {
        $content = $this->serializer->normalize(
            data: $response,
            context: $this->context
        );

        return $this->respondWithJson(
            $this->serializer->serialize($content),
            $this->status
        );
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
