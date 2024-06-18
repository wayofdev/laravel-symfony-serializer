<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use Symfony\Component\Serializer\Encoder;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Yaml\Dumper;

use function class_exists;

final class DefaultEncoderRegistrationStrategy implements Contracts\EncoderRegistrationStrategy
{
    /**
     * @return iterable<array{encoder: EncoderInterface|DecoderInterface}>
     */
    public function encoders(): iterable
    {
        yield ['encoder' => new Encoder\JsonEncoder()];
        yield ['encoder' => new Encoder\CsvEncoder()];
        yield ['encoder' => new Encoder\XmlEncoder()];

        if (class_exists(Dumper::class)) {
            yield ['encoder' => new Encoder\YamlEncoder()];
        }
    }
}
