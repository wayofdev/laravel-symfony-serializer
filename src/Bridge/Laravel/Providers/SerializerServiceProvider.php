<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Bridge\Laravel\Providers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;
use Symfony\Component\Yaml\Dumper;
use WayOfDev\Serializer\Config;
use WayOfDev\Serializer\Contracts\ConfigRepository;
use WayOfDev\Serializer\Contracts\EncodersRegistryInterface;
use WayOfDev\Serializer\Contracts\NormalizersRegistryInterface;
use WayOfDev\Serializer\Contracts\SerializerRegistryInterface;
use WayOfDev\Serializer\EncodersRegistry;
use WayOfDev\Serializer\NormalizersRegistry;
use WayOfDev\Serializer\Serializer;
use WayOfDev\Serializer\SerializerManager;
use WayOfDev\Serializer\SerializerRegistry;

use function array_map;
use function class_exists;

final class SerializerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../../../config/serializer.php' => config_path('serializer.php'),
            ], 'config');
        }
    }

    public function register(): void
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__ . '/../../../../config/serializer.php', 'serializer');

        $this->registerConfig();
        $this->registerNormalizersRegistry();
        $this->registerEncodersRegistry();
        $this->registerSerializerRegistry();
        $this->registerLoader();
        $this->registerSerializerManager();
        $this->registerSymfonySerializer();
    }

    private function registerConfig(): void
    {
        $this->app->singleton(ConfigRepository::class, function (Application $app) {
            /** @var Repository $config */
            $config = $app['config'];

            return Config::fromArray([
                'default' => $config->get('serializer.default'),
                'normalizers' => $config->get('serializer.normalizers'),
                'encoders' => $config->get('serializer.encoders'),
                'metadata_loader' => $config->get('serializer.metadata_loader'),
            ]);
        });
    }

    private function registerNormalizersRegistry(): void
    {
        $this->app->singleton(NormalizersRegistryInterface::class, function (Application $app): NormalizersRegistryInterface {
            $config = $app->make(ConfigRepository::class);

            $normalizers = array_map(fn (mixed $normalizer) => match (true) {
                $normalizer instanceof NormalizerInterface, $normalizer instanceof DenormalizerInterface => $normalizer,
                default => $this->app->get($normalizer)
            }, $config->normalizers());

            return new NormalizersRegistry(
                $app->get(LoaderInterface::class),
                true,
                $normalizers
            );
        });
    }

    private function registerEncodersRegistry(): void
    {
        $this->app->singleton(EncodersRegistryInterface::class, function (Application $app): EncodersRegistryInterface {
            $config = $app->make(ConfigRepository::class);

            return new EncodersRegistry(
                array_map(
                    fn (string|EncoderInterface $encoder) => $encoder instanceof EncoderInterface ? $encoder : $app->get($encoder),
                    $config->encoders()
                )
            );
        });
    }

    private function registerSerializerRegistry(): void
    {
        $this->app->singleton(SerializerRegistryInterface::class, function (Application $app): SerializerRegistryInterface {
            // $config = $app->make(ConfigRepository::class);
            $serializer = $app->make(SymfonySerializerInterface::class);

            $serializers = [
                'json' => new Serializer($serializer, 'json'),
                'csv' => new Serializer($serializer, 'csv'),
                'xml' => new Serializer($serializer, 'xml'),
            ];

            if (class_exists(Dumper::class)) {
                $serializers['symfony-yaml'] = new Serializer($serializer, 'yaml');
            }

            return new SerializerRegistry($serializers);
        });
    }

    private function registerLoader(): void
    {
        $this->app->singleton(LoaderInterface::class, function (Application $app): LoaderInterface {
            $config = $app->make(ConfigRepository::class);

            return $config->metadataLoader();
        });
    }

    private function registerSerializerManager(): void
    {
        $this->app->singleton(SerializerManager::class, function (Application $app): SerializerManager {
            /** @var Config $config */
            $config = $app->make(ConfigRepository::class);
            $serializers = $app->make(SerializerRegistryInterface::class);

            return new SerializerManager($serializers, $config->defaultSerializer());
        });

        $this->app->alias(SerializerManager::class, SerializerInterface::class);
    }

    private function registerSymfonySerializer(): void
    {
        $this->app->singleton(SymfonySerializerInterface::class, function (Application $app): SymfonySerializer {
            $normalizers = $app->make(NormalizersRegistryInterface::class);
            $encoders = $app->make(EncodersRegistryInterface::class);

            return new SymfonySerializer($normalizers->all(), $encoders->all());
        });

        $this->app->singleton(Serializer::class, SerializerInterface::class);
    }
}
