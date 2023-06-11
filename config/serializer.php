<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

return [
    /*
     * Name (format) of the default serializer that will be registered in SerializerManager.
     */
    'default' => 'json',

    /*
     * Supported serializers: json, csv, xml, yaml
     * Set serializer to false to disable it.
     */
    'serializers' => [
        'json' => true,
        'csv' => false,
        'xml' => false,
        'yaml' => false,
    ],

    /*
     * Register your custom normalizers here.
     * Default normalizers are registered in src/NormalizersRegistry.php
     */
    'normalizers' => [
        // Symfony\Component\Messenger\Transport\Serialization\Normalizer\FlattenExceptionNormalizer
    ],

    /*
     * Register your custom encoders here.
     * Default encoders are registered in src/EncodersRegistry.php
     *
     * Default encoders are:
     * - Symfony\Component\Serializer\Encoder\JsonEncoder
     * - Symfony\Component\Serializer\Encoder\CsvEncoder
     * - Symfony\Component\Serializer\Encoder\XmlEncoder
     * - Symfony\Component\Serializer\Encoder\YamlEncoder
     */
    'encoders' => [
        // Symfony\Component\Serializer\Encoder\JsonEncoder
    ],

    'metadata_loader' => new AnnotationLoader(new AnnotationReader()),
];
