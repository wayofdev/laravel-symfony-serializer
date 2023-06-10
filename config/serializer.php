<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

return [
    'default_serializer' => 'json',

    'serializers' => [
        // 'json' => \Symfony\Component\Serializer\Serializer::class,
    ],

    'normalizers' => [
        // \Symfony\Component\Serializer\Normalizer\ObjectNormalizer::class,
    ],

    'encoders' => [
        // \Symfony\Component\Serializer\Encoder\JsonEncoder::class,
        // \Symfony\Component\Serializer\Encoder\CsvEncoder::class,
        // \Symfony\Component\Serializer\Encoder\XmlEncoder::class,
        // \Symfony\Component\Serializer\Encoder\YamlEncoder::class,
    ],

    'metadata_loader' => new AnnotationLoader(new AnnotationReader()),
];
