<p align="center">
    <br>
    <a href="https://wayof.dev" target="_blank">
        <picture>
            <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/wayofdev/.github/master/assets/logo.gh-dark-mode-only.png">
            <img width="400" src="https://raw.githubusercontent.com/wayofdev/.github/master/assets/logo.gh-light-mode-only.png" alt="WayOfDev Logo">
        </picture>
    </a>
    <br>
</p>

<p align="center">
    <strong>Build</strong><br>
    <a href="https://github.com/wayofdev/laravel-symfony-serializer/actions" target="_blank"><img alt="Build Status" src="https://img.shields.io/endpoint.svg?url=https%3A%2F%2Factions-badge.atrox.dev%2Fwayofdev%2Flaravel-symfony-serializer%2Fbadge&style=flat-square&label=github%20actions"/></a>
</p>
<p align="center">
    <strong>Project</strong><br>
    <a href="https://packagist.org/packages/wayofdev/laravel-symfony-serializer" target="_blank"><img src="https://img.shields.io/packagist/dt/wayofdev/laravel-symfony-serializer?&style=flat-square" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/wayofdev/laravel-symfony-serializer" target="_blank"><img src="https://img.shields.io/packagist/v/wayofdev/laravel-symfony-serializer?&style=flat-square" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/wayofdev/laravel-symfony-serializer" target="_blank"><img alt="Commits since latest release" src="https://img.shields.io/github/commits-since/wayofdev/laravel-symfony-serializer/latest?style=flat-square"></a>
    <a href="https://packagist.org/packages/wayofdev/laravel-symfony-serializer" target="_blank"><img alt="PHP Version Require" src="https://poser.pugx.org/wayofdev/laravel-symfony-serializer/require/php?style=flat-square"></a>
</p>
<p align="center">
    <strong>Quality</strong><br>
    <a href="https://app.codecov.io/gh/wayofdev/laravel-symfony-serializer" target="_blank"><img alt="Codecov" src="https://img.shields.io/codecov/c/github/wayofdev/laravel-symfony-serializer?style=flat-square&logo=codecov"></a>
    <a href="https://dashboard.stryker-mutator.io/reports/github.com/wayofdev/laravel-symfony-serializer/master" target="_blank"><img alt="Mutation testing badge" src="https://img.shields.io/endpoint?style=flat-square&label=mutation%20score&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fwayofdev%2Flaravel-symfony-serializer%2Fmaster"></a>
    <a href=""><img src="https://img.shields.io/badge/phpstan%20level-8%20of%209-brightgreen?style=flat-square" alt="PHP Stan Level 6 of 9"></a>
</p>
<p align="center">
    <strong>Community</strong><br>
    <a href="https://discord.gg/CE3TcCC5vr" target="_blank"><img alt="Discord" src="https://img.shields.io/discord/1228506758562058391?style=flat-square&logo=discord&labelColor=7289d9&logoColor=white&color=39456d"></a>
    <a href="https://x.com/intent/follow?screen_name=wayofdev" target="_blank"><img alt="Follow on Twitter (X)" src="https://img.shields.io/badge/-Follow-black?style=flat-square&logo=X"></a>
</p>

<br>

# Laravel Symfony Serializer

This package integrates the Symfony Serializer component into Laravel, providing a powerful tool for serializing and deserializing objects into various formats such as JSON, XML, CSV, and YAML.

Detailed documentation on the Symfony Serializer can be found on their [official page](https://symfony.com/doc/current/components/serializer.html).

<br>

## 🗂️ Table of Contents

- [Purpose](#-purpose)
- [Installation](#-installation)
- [Configuration](#-configuration)
  - [Configuration Options](#-configuration-options)
  - [Custom Strategies](#-custom-strategies)
- [Usage](#-usage)
  - [Components](#-components)
  - [Example DTO](#-example-dto)
  - [Using `SerializerManager` in your Service Classes](#-using-serializermanager-in-service-classes)
  - [Using `ResponseFactory` in Laravel Controllers](#-using-responsefactory-in-laravel-controllers)
  - [Using in Laravel Queues](#-using-in-laravel-queues)
- [Security Policy](#-security-policy)
- [Want to Contribute?](#-want-to-contribute)
- [Contributors](#-contributors)
- [Social Links](#-social-links)
- [License](#-license)
- [Credits and Useful Resources](#-credits-and-useful-resources)

<br>

## 🤔 Purpose

This package brings the power of the Symfony Serializer component to Laravel. While Laravel does not have a built-in serializer and typically relies on array or JSON transformations, this package provides more advanced serialization capabilities. These include object normalization, handling of circular references, property grouping, and format-specific encoders.

If you are building a REST API, working with queues, or have complex serialization needs, this package will be especially useful. It allows you to use objects as payloads instead of simple arrays and supports various formats such as JSON, XML, CSV, and YAML. This documentation will guide you through the installation process and provide examples of how to use the package to serialize and deserialize your objects.

<br>

🙏 If you find this repository useful, please consider giving it a ⭐️. Thank you!

<br>

## 💿 Installation

Require the package as a dependency:

```bash
composer require wayofdev/laravel-symfony-serializer
```

You can publish the config file with:

```bash
$ php artisan vendor:publish \
  --provider="WayOfDev\Serializer\Bridge\Laravel\Providers\SerializerServiceProvider" \
  --tag="config"
```

<br>

## 🔧 Configuration

The package configuration file allows you to customize various aspects of the serialization process.

Below is the default configuration provided by the package:

```php
<?php

declare(strict_types=1);

use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use WayOfDev\Serializer\Contracts\EncoderRegistrationStrategy;
use WayOfDev\Serializer\Contracts\NormalizerRegistrationStrategy;
use WayOfDev\Serializer\DefaultEncoderRegistrationStrategy;
use WayOfDev\Serializer\DefaultNormalizerRegistrationStrategy;

/**
 * @return array{
 *     default: string,
 *     debug: bool,
 *     normalizerRegistrationStrategy: class-string<NormalizerRegistrationStrategy>,
 *     encoderRegistrationStrategy: class-string<EncoderRegistrationStrategy>,
 *     metadataLoader: class-string<LoaderInterface>|null,
 * }
 */
return [
    'default' => env('SERIALIZER_DEFAULT_FORMAT', 'symfony-json'),

    'debug' => env('SERIALIZER_DEBUG_MODE', env('APP_DEBUG', false)),

    'normalizerRegistrationStrategy' => DefaultNormalizerRegistrationStrategy::class,

    'encoderRegistrationStrategy' => DefaultEncoderRegistrationStrategy::class,

    'metadataLoader' => null,
];
```

### → Configuration Options

- **`default`**: Specifies the default serializer format. This can be overridden by setting the `SERIALIZER_DEFAULT_FORMAT` environment variable. The default is `symfony-json`.
- **`debug`**: Enables debug mode for `ProblemNormalizer`. This can be set using the `SERIALIZER_DEBUG_MODE` environment variable. It defaults to the `APP_DEBUG` value.
- **`normalizerRegistrationStrategy`**: Specifies the strategy class for registering normalizers. The default strategy is [`WayOfDev\Serializer\DefaultNormalizerRegistrationStrategy`](https://github.com/wayofdev/laravel-symfony-serializer/blob/master/src/DefaultNormalizerRegistrationStrategy.php).
- **`encoderRegistrationStrategy`**: Specifies the strategy class for registering encoders. The default strategy is [`WayOfDev\Serializer\DefaultEncoderRegistrationStrategy`](https://github.com/wayofdev/laravel-symfony-serializer/blob/master/src/DefaultEncoderRegistrationStrategy.php).
- **`metadataLoader`**: Allows registration of a custom metadata loader. By default, `Symfony\Component\Serializer\Mapping\Loader\AttributeLoader` is used.

### → Custom Strategies

[Due to Laravel's caching limitations, where configs cannot instantiate objects](https://elliotderhay.com/blog/caching-laravel-configs-that-use-objects), this package uses strategies to register normalizers and encoders.

You can create custom normalizer or encoder registration strategies by implementing the respective interfaces.

#### Normalizer Registration Strategy

To create a custom normalizer registration strategy:

1. Implement the [`NormalizerRegistrationStrategy`](https://github.com/wayofdev/laravel-symfony-serializer/blob/master/src/Contracts/NormalizerRegistrationStrategy.php) interface:

   ```php
   <?php
   
   declare(strict_types=1);
   
   namespace Infrastructure\Serializer;
   
   use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
   use Symfony\Component\Serializer\Normalizer;
   use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
   use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
   use WayOfDev\Serializer\Contracts\NormalizerRegistrationStrategy;
   // ...
   
   final readonly class CustomNormalizerRegistrationStrategy implements NormalizerRegistrationStrategy
   {
       public function __construct(
           private LoaderInterface $loader,
           private bool $debugMode = false,
       ) {
       }
   
       /**
        * @return iterable<array{normalizer: NormalizerInterface|DenormalizerInterface, priority: int<0, max>}>
        */
       public function normalizers(): iterable
       {
           // ...
       }
   }
   ```

2. Change `serializer.php` config to use your custom strategy:

   ```php
   'normalizerRegistrationStrategy' => CustomNormalizerRegistrationStrategy::class,
   ```

#### Encoder Registration Strategy

To create a custom encoder registration strategy:

1. Implement the [`EncoderRegistrationStrategy`](https://github.com/wayofdev/laravel-symfony-serializer/blob/master/src/Contracts/EncoderRegistrationStrategy.php) interface:

   ```php
   <?php
   
   declare(strict_types=1);
   
   namespace Infrastructure\Serializer;
   
   use Symfony\Component\Serializer\Encoder;
   use Symfony\Component\Serializer\Encoder\DecoderInterface;
   use Symfony\Component\Serializer\Encoder\EncoderInterface;
   use Symfony\Component\Yaml\Dumper;
   
   use function class_exists;
   
   final class CustomEncoderRegistrationStrategy implements Contracts\EncoderRegistrationStrategy
   {
       /**
        * @return iterable<array{encoder: EncoderInterface|DecoderInterface}>
        */
       public function encoders(): iterable
       {
           // Register your encoders here...
         
           yield ['encoder' => new Encoder\JsonEncoder()];
           yield ['encoder' => new Encoder\CsvEncoder()];
           yield ['encoder' => new Encoder\XmlEncoder()];
   
           if (class_exists(Dumper::class)) {
               yield ['encoder' => new Encoder\YamlEncoder()];
           }
       }
   }
   ```

2. Change `serializer.php` config to use your custom strategy:

   ```php
   'encoderRegistrationStrategy' => CustomEncoderRegistrationStrategy::class,
   ```

<br>

## 💻 Usage

The package provides a list of serializers that can be used to serialize and deserialize objects.

The default serializers available in this package are: `symfony-json`, `symfony-csv`, `symfony-xml`, `symfony-yaml`.

> [!WARNING]
> The `yaml` encoder requires the `symfony/yaml` package and is disabled when the package is not installed.
> Install the `symfony/yaml` package, and the encoder will be automatically enabled.

### → Components

#### SerializerManager

The `SerializerManager` handles the different serializers available in this package. It can be used to serialize and deserialize objects.

#### ResponseFactory

The `ResponseFactory` is used to create responses in Laravel controllers, making it easy to include serialized data in HTTP responses.

#### Facades

This package includes two Laravel Facades:

- `Manager` — To access the underlying `SerializerManager`
- `Serializer` — To access the bound and configured original Symfony Serializer instance.

### → Example DTO

We will use this example DTO for serialization purposes:

```php
<?php

namespace Application\User;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class UserDTO
{
    #[Groups(['public'])]
    #[SerializedName('id')]
    private int $id;

    #[Groups(['public'])]
    #[SerializedName('name')]
    private string $name;

    #[Groups(['private', 'public'])]
    #[SerializedName('emailAddress')]
    private string $email;

    public function __construct(int $id, string $name, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }
}
```

### → Using `SerializerManager` in Service Classes

```php
<?php

namespace Application\Services;

use WayOfDev\Serializer\Manager\SerializerManager;
use Application\User\UserDTO;

class ProductService
{
    public function __construct(
        private readonly SerializerManager $serializer,
    ) {
    }

    public function someMethod(): void
    {
        $serializer = $this->serializer->serializer('symfony-json');
        $dto = new UserDTO(1, 'John Doe', 'john@example.com');

        $serialized = $serializer->serialize(
            payload: $dto,
            context: ['groups' => ['private']]
        );
    }
}
```

### → Using `ResponseFactory` in Laravel Controllers

Here's an example of how you can use the `ResponseFactory` in a Laravel Controller:

**Example Controller:**

```php
<?php

namespace Bridge\Laravel\Public\Product\Controllers;

use Application\User\UserDTO;
use Illuminate\Http\Request;
use WayOfDev\Serializer\Bridge\Laravel\Http\HttpCode;
use WayOfDev\Serializer\Bridge\Laravel\Http\ResponseFactory;

class UserController extends Controller
{
    public function __construct(private ResponseFactory $response)
    {
    }

    public function index()
    {
        $dto = new UserDTO(1, 'John Doe', 'john@example.com');

        $this->response->withContext(['groups' => ['private']]);
        $this->response->withStatusCode(HttpCode::HTTP_OK);
      
        return $this->response->create($dto);
    }
}
```

<br>

### → Using in Laravel Queues

To switch from Laravel's default serialization to this implementation in queues, you can override the `__serialize` and `__unserialize` methods in your queue jobs. Here’s an example:

```php
<?php

declare(strict_types=1);

namespace Bridge\Laravel\Public\Product\Jobs;

use Domain\Product\Models\Product;
use Domain\Product\ProductProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WayOfDev\Serializer\Bridge\Laravel\Facades\Manager;

/**
 * This Job class shows how Symfony Serializer can be used with Laravel Queues.
 */
class ProcessProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle(ProductProcessor $processor): void
    {
        $processor->process($this->product);
    }

    public function __serialize(): array
    {
        return [
            'product' => Manager::serialize($this->product),
        ];
    }

    public function __unserialize(array $values): void
    {
        $this->product = Manager::deserialize($values['product'], Product::class);
    }
}
```

<br>

## 🔒 Security Policy

This project has a [security policy](.github/SECURITY.md).

<br>

## 🙌 Want to Contribute?

Thank you for considering contributing to the wayofdev community! We welcome all kinds of contributions. If you want to:

- 🤔 [Suggest a feature](https://github.com/wayofdev/laravel-symfony-serializer/issues/new?assignees=&labels=type%3A+enhancement&projects=&template=2-feature-request.yml&title=%5BFeature%5D%3A+)
- 🐛 [Report an issue](https://github.com/wayofdev/laravel-symfony-serializer/issues/new?assignees=&labels=type%3A+documentation%2Ctype%3A+maintenance&projects=&template=1-bug-report.yml&title=%5BBug%5D%3A+)
- 📖 [Improve documentation](https://github.com/wayofdev/laravel-symfony-serializer/issues/new?assignees=&labels=type%3A+documentation%2Ctype%3A+maintenance&projects=&template=4-docs-bug-report.yml&title=%5BDocs%5D%3A+)
- 👨‍💻 [Contribute to the code](.github/CONTRIBUTING.md)

You are more than welcome. Before contributing, please check our [contribution guidelines](.github/CONTRIBUTING.md).

[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg?style=for-the-badge)](https://conventionalcommits.org)

<br>

## 🫡 Contributors

<p align="left">
<a href="https://github.com/wayofdev/laravel-symfony-serializer/graphs/contributors">
<img align="left" src="https://img.shields.io/github/contributors-anon/wayofdev/laravel-symfony-serializer?style=for-the-badge" alt="Contributors Badge"/>
</a>
<br>
<br>
</p>

## 🌐 Social Links

- **Twitter:** Follow our organization [@wayofdev](https://twitter.com/intent/follow?screen_name=wayofdev) and the author [@wlotyp](https://twitter.com/intent/follow?screen_name=wlotyp).
- **Discord:** Join our community on [Discord](https://discord.gg/CE3TcCC5vr).

<br>

## 📜 License

[![License](https://img.shields.io/github/license/wayofdev/laravel-symfony-serializer?style=for-the-badge&color=blue)](./LICENSE.md)

<br>

## 🧱 Credits and Useful Resources

This repository is inspired by the following projects:

- [spiral/serializer](https://github.com/spiral/serializer)
- [spiral-packages/symfony-serializer](https://github.com/spiral-packages/symfony-serializer)
- [jeromegamez/ramsey-uuid-normalizer](https://github.com/jeromegamez/ramsey-uuid-normalizer)
- [wayofdev/laravel-jms-serializer](https://github.com/wayofdev/laravel-jms-serializer)
- [symfony/serializer](https://github.com/symfony/serializer)
