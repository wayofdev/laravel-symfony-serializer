<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Bridge\Laravel\Providers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;
use Symfony\Component\Yaml\Dumper;
use WayOfDev\Serializer\Config;
use WayOfDev\Serializer\Contracts\ConfigRepository;
use WayOfDev\Serializer\Contracts\EncoderRegistrationStrategy;
use WayOfDev\Serializer\Contracts\EncoderRegistryInterface;
use WayOfDev\Serializer\Contracts\NormalizerRegistrationStrategy;
use WayOfDev\Serializer\Contracts\NormalizerRegistryInterface;
use WayOfDev\Serializer\Contracts\SerializerRegistryInterface;
use WayOfDev\Serializer\EncoderRegistry;
use WayOfDev\Serializer\Manager\Serializer;
use WayOfDev\Serializer\Manager\SerializerManager;
use WayOfDev\Serializer\Manager\SerializerRegistry;
use WayOfDev\Serializer\NormalizerRegistry;

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
        $this->registerLoader();
        $this->registerNormalizerRegistry();
        $this->registerEncoderRegistry();
        $this->registerSerializerRegistry();
        $this->registerSerializerManager();
        $this->registerSymfonySerializer();
    }

    private function registerConfig(): void
    {
        $this->app->singleton(ConfigRepository::class, static function (Application $app) {
            /** @var Repository $config */
            $config = $app->get(Repository::class);

            return Config::fromArray([
                'default' => $config->get('serializer.default'),
                'debug' => $config->get('serializer.debug'),
                'normalizerRegistrationStrategy' => $config->get('serializer.normalizerRegistrationStrategy'),
                'encoderRegistrationStrategy' => $config->get('serializer.encoderRegistrationStrategy'),
                'metadataLoader' => $config->get('serializer.metadataLoader'),
            ]);
        });
    }

    private function registerLoader(): void
    {
        $this->app->singleton(LoaderInterface::class, static function (Application $app): LoaderInterface {
            /** @var Config $config */
            $config = $app->make(ConfigRepository::class);

            return $config->metadataLoader();
        });
    }

    private function registerNormalizerRegistry(): void
    {
        $this->app->singleton(NormalizerRegistrationStrategy::class, static function (Application $app): NormalizerRegistrationStrategy {
            /** @var Config $config */
            $config = $app->make(ConfigRepository::class);

            /** @var LoaderInterface $loader */
            $loader = $app->get(LoaderInterface::class);

            $strategyFQCN = $config->normalizerRegistrationStrategy();

            return $app->make($strategyFQCN, [
                'loader' => $loader,
                'debugMode' => $config->debug(),
            ]);
        });

        $this->app->singleton(NormalizerRegistryInterface::class, static function (Application $app): NormalizerRegistryInterface {
            $strategy = $app->get(NormalizerRegistrationStrategy::class);

            return new NormalizerRegistry($strategy);
        });
    }

    private function registerEncoderRegistry(): void
    {
        $this->app->singleton(EncoderRegistrationStrategy::class, static function (Application $app): EncoderRegistrationStrategy {
            /** @var Config $config */
            $config = $app->make(ConfigRepository::class);

            $strategyFQCN = $config->encoderRegistrationStrategy();

            return $app->make($strategyFQCN);
        });

        $this->app->singleton(EncoderRegistryInterface::class, static function (Application $app): EncoderRegistryInterface {
            $strategy = $app->get(EncoderRegistrationStrategy::class);

            return new EncoderRegistry($strategy);
        });
    }

    private function registerSerializerRegistry(): void
    {
        $this->app->singleton(SerializerRegistryInterface::class, static function (Application $app): SerializerRegistryInterface {
            /** @var SymfonySerializer $serializer */
            $serializer = $app->make(SymfonySerializerInterface::class);

            $serializers = [
                'symfony-json' => new Serializer($serializer, 'json'),
                'symfony-csv' => new Serializer($serializer, 'csv'),
                'symfony-xml' => new Serializer($serializer, 'xml'),
            ];

            if (class_exists(Dumper::class)) {
                $serializers['symfony-yaml'] = new Serializer($serializer, 'yaml');
            }

            return new SerializerRegistry($serializers);
        });
    }

    private function registerSymfonySerializer(): void
    {
        $this->app->singleton(SymfonySerializerInterface::class, static function (Application $app): SymfonySerializer {
            /** @var NormalizerRegistryInterface $normalizers */
            $normalizers = $app->make(NormalizerRegistryInterface::class);

            /** @var EncoderRegistryInterface $encoders */
            $encoders = $app->make(EncoderRegistryInterface::class);

            return new SymfonySerializer(
                $normalizers->all(),
                $encoders->all()
            );
        });

        $this->app->singleton(SymfonySerializer::class, SymfonySerializerInterface::class);
    }

    private function registerSerializerManager(): void
    {
        $this->app->singleton(SerializerManager::class, static function (Application $app): SerializerManager {
            /** @var Config $config */
            $config = $app->make(ConfigRepository::class);

            /** @var SerializerRegistry $serializers */
            $serializers = $app->make(SerializerRegistryInterface::class);

            return new SerializerManager($serializers, $config->defaultSerializer());
        });

        $this->app->alias(SerializerManager::class, 'serializer.manager');
    }
}
