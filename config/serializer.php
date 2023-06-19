<?php

declare(strict_types=1);

return [
    /*
     * The 'default' key specifies the name (format) of the default serializer
     * that will be registered in SerializerManager. This can be overridden
     * by setting the SERIALIZER_DEFAULT_FORMAT environment variable.
     */
    'default' => env('SERIALIZER_DEFAULT_FORMAT', 'json'),

    /*
     * The 'serializers' key lists the supported serializers: json, csv, xml, yaml.
     * Set a serializer to "false" to disable it. This can be overridden by setting
     * the corresponding SERIALIZER_USE_* environment variable.
     */
    'serializers' => [
        'json' => env('SERIALIZER_USE_JSON', true),
        'csv' => env('SERIALIZER_USE_CSV', false),
        'xml' => env('SERIALIZER_USE_XML', false),
        'yaml' => env('SERIALIZER_USE_YAML', false),
    ],

    /*
     * The 'normalizers' key allows you to register your custom normalizers.
     * Default normalizers are registered in src/NormalizersRegistry.php.
     * Uncomment the line below and replace with your custom normalizer if needed
     * to merge with default ones.
     */
    'normalizers' => [
        // Symfony\Component\Messenger\Transport\Serialization\Normalizer\FlattenExceptionNormalizer
    ],

    /*
     * The 'encoders' key allows you to register your custom encoders.
     * Default encoders are registered in src/EncodersRegistry.php.
     * Default encoders include JsonEncoder, CsvEncoder, XmlEncoder, and YamlEncoder.
     * Uncomment the line below and replace with your custom encoder if needed.
     */
    'encoders' => [
        // Symfony\Component\Serializer\Encoder\JsonEncoder
    ],
];
