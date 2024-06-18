<?php

declare(strict_types=1);

use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;

/**
 * @return array{
 *     default: string,
 *     debug: bool,
 *     normalizerRegistrationStrategy: class-string<WayOfDev\Serializer\Contracts\NormalizerRegistrationStrategy>,
 *     encoderRegistrationStrategy: class-string<WayOfDev\Serializer\Contracts\EncoderRegistrationStrategy>,
 *     metadataLoader: class-string<LoaderInterface>|null,
 * }
 */
return [
    /*
     * The 'default' key specifies the name (format) of the default serializer
     * that will be registered in SerializerManager. This can be overridden
     * by setting the SERIALIZER_DEFAULT_FORMAT environment variable.
     */
    'default' => env('SERIALIZER_DEFAULT_FORMAT', 'symfony-json'),

    /*
     * Specifies whether to enable debug mode for ProblemNormalizer.
     */
    'debug' => env('SERIALIZER_DEBUG_MODE', env('APP_DEBUG', false)),

    /*
     * Allows to specify additional, custom serializers that will be registered in SerializerManager.
     */
    'manager' => [
        'serializers' => [
            'json' => '',
            'php' => '',
        ],
    ],

    /*
     * Allows you to specify the strategy class for registering your normalizers.
     * Default is 'WayOfDev\Serializer\DefaultNormalizerRegistrationStrategy'.
     */
    'normalizerRegistrationStrategy' => WayOfDev\Serializer\DefaultNormalizerRegistrationStrategy::class,

    /*
     * Allows you to register your custom encoders.
     * Default encoders are registered in src/DefaultEncoderRegistrationStrategy.php.
     *
     * Default encoders include:
     *      JsonEncoder,
     *      CsvEncoder,
     *      XmlEncoder,
     *      YamlEncoder.
     *
     * You can replace the default encoders with your custom ones by implementing
     * your own registration strategy.
     */
    'encoderRegistrationStrategy' => WayOfDev\Serializer\DefaultEncoderRegistrationStrategy::class,

    /*
     * Allows you to register your custom metadata loader.
     */
    'metadataLoader' => null,
];
