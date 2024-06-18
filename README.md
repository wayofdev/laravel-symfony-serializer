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

## 📄 About

This package integrates the Symfony Serializer component into Laravel, providing a powerful tool for serializing and deserializing objects into various formats such as JSON, XML, CSV, and YAML.

Detailed documentation on the Symfony serializer can be found on their [official page](https://symfony.com/doc/current/components/serializer.html).

### → Purpose

This package brings the power of the Symfony Serializer component to Laravel. While Laravel does not have a built-in serializer and typically relies on array or JSON transformations, this package provides more advanced serialization capabilities. This includes object normalization, handling of circular references, property grouping, and format-specific encoders.

If you are building a REST API, working with queues, or have complex serialization needs, this package will be especially useful. It allows you to use objects as payload instead of simple arrays, and supports various formats such as JSON, XML, CSV, and YAML. This documentation will guide you through the installation process and provide examples of how to use the package to serialize and deserialize your objects.

<br>

🙏 If you find this repository useful, please consider giving it a ⭐️. Thank you!

<br>

## 💿 Installation

Require as dependency:

```bash
composer req wayofdev/laravel-symfony-serializer
```

You can publish the config file with:

```bash
$ php artisan vendor:publish \
  --provider="WayOfDev\Serializer\Bridge\Laravel\Providers\SerializerServiceProvider" \
  --tag="config"
```

<br>

## 💻 Usage

The package provides a list of serializers that can be used to serialize and deserialize objects.

The serializers available in this package are: `symfony-json`, `symfony-csv`, `symfony-xml`, `symfony-yaml`.

> **Warning**
> The `yaml` encoder requires the `symfony/yaml` package and is disabled when the package is not installed.
> Install the `symfony/yaml` package and the encoder will be automatically enabled.

We will use this example DTO for serialization purposes:

```php
<?php

namespace Application\DTO;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class MyDTO
{
    #[Groups(['public'])]
    #[SerializedName('id')]
    private int $id;

    #[Groups(['public'])]
    #[SerializedName('name')]
    private string $name;

    #[Groups(['private', 'public'])]
    #[SerializedName('email')]
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

### → Using SerializerManager in your Service Classes

```php
<?php

namespace Application\Services;

use WayOfDev\Serializer\SerializerManager;
use Application\DTO\MyDTO;

class MyService
{
    public function __construct(
        private readonly SerializerManager $serializer,
    ) {
    }

    public function someMethod(): void
    {
        $serializer = $serializer->getSerializer('json');
        $dto = new MyDTO(1, 'John Doe', 'john@example.com');

        $content = $serializer->normalize(
            data: $dto,
            context: ['groups' => ['private']]
        );

        $serialized = $serializer->serialize($content);
    }
}
```

### → Using ResponseFactory in Laravel Controllers

Here's an example of how you can use the `ResponseFactory` in a Laravel controller:

**Example Controller:**

```php
<?php

namespace Laravel\Http\Controllers;

use Application\DTO\MyDTO;
use Illuminate\Http\Request;
use WayOfDev\Serializer\HttpCode;
use WayOfDev\Serializer\ResponseFactory;

class MyController extends Controller
{
    private ResponseFactory $response;

    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function index()
    {
        $dto = new MyDTO(1, 'John Doe', 'john@example.com');

        $this->response->withContext(['groups' => ['private']]);
        $this->response->withStatusCode(HttpCode::HTTP_OK);
      
        return $this->response->create($dto);
    }
}
```

<br>

## 🔒 Security Policy

This project has a [security policy](.github/SECURITY.md).

<br>

## 🙌 Want to Contribute?

Thank you for considering contributing to the wayofdev community! We are open to all kinds of contributions. If you want to:

- 🤔 [Suggest a feature](https://github.com/wayofdev/laravel-symfony-serializer/issues/new?assignees=&labels=type%3A+enhancement&projects=&template=2-feature-request.yml&title=%5BFeature%5D%3A+)
- 🐛 [Report an issue](https://github.com/wayofdev/laravel-symfony-serializer/issues/new?assignees=&labels=type%3A+documentation%2Ctype%3A+maintenance&projects=&template=1-bug-report.yml&title=%5BBug%5D%3A+)
- 📖 [Improve documentation](https://github.com/wayofdev/laravel-symfony-serializer/issues/new?assignees=&labels=type%3A+documentation%2Ctype%3A+maintenance&projects=&template=4-docs-bug-report.yml&title=%5BDocs%5D%3A+)
- 👨‍💻 Contribute to the code

You are more than welcome. Before contributing, kindly check our [contribution guidelines](.github/CONTRIBUTING.md).

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

## ⚖️ License

[![Licence](https://img.shields.io/github/license/wayofdev/laravel-symfony-serializer?style=for-the-badge&color=blue)](./LICENSE.md)

<br>

## 🧱 Credits and Useful Resources

This repository is inspired by the following projects:

- [spiral/serializer](https://github.com/spiral/serializer)
- [spiral-packages/symfony-serializer](https://github.com/spiral-packages/symfony-serializer)
- [jeromegamez/ramsey-uuid-normalizer](https://github.com/jeromegamez/ramsey-uuid-normalizer)
- [wayofdev/laravel-jms-serializer](https://github.com/wayofdev/laravel-jms-serializer)
- [symfony/serializer](https://github.com/symfony/serializer)
