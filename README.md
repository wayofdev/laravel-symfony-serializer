<br>

<div align="center">
<img width="456" src="https://raw.githubusercontent.com/wayofdev/laravel-symfony-serializer/master/assets/logo.gh-light-mode-only.png#gh-light-mode-only">
<img width="456" src="https://raw.githubusercontent.com/wayofdev/laravel-symfony-serializer/master/assets/logo.gh-dark-mode-only.png#gh-dark-mode-only">
</div>


<br>

<br>

<div align="center">
<a href="https://github.com/wayofdev/laravel-symfony-serializer/actions"><img alt="Build Status" src="https://img.shields.io/endpoint.svg?url=https%3A%2F%2Factions-badge.atrox.dev%2Fwayofdev%2Flaravel-symfony-serializer%2Fbadge&style=flat-square"/></a>
<a href="https://packagist.org/packages/wayofdev/laravel-symfony-serializer"><img src="https://img.shields.io/packagist/dt/wayofdev/laravel-symfony-serializer?&style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/wayofdev/laravel-symfony-serializer"><img src="https://img.shields.io/packagist/v/wayofdev/laravel-symfony-serializer?&style=flat-square" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/wayofdev/laravel-symfony-serializer"><img src="https://img.shields.io/packagist/l/wayofdev/laravel-symfony-serializer?style=flat-square&color=blue" alt="Software License"/></a>
<a href="https://packagist.org/packages/wayofdev/laravel-symfony-serializer"><img alt="Commits since latest release" src="https://img.shields.io/github/commits-since/wayofdev/laravel-symfony-serializer/latest?style=flat-square"></a>
</div>
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
$ composer req wayofdev/laravel-symfony-serializer
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
        $this->response->withStatusCode(200);
      
        return $this->response->create($dto);
    }
}
```

<br>

## 🧪 Running Tests

### → PHPUnit tests

To run phpunit and pest tests, run the following command:

```bash
$ make test
```

### → Static Analysis

Code quality using PHPStan:

```bash
$ make lint-stan
```

### → Coding Standards Fixing

Fix code using The PHP Coding Standards Fixer (PHP CS Fixer) to follow our standards:

```bash
$ make lint-php
```

<br>

## 🤝 License

[![Licence](https://img.shields.io/github/license/wayofdev/laravel-symfony-serializer?style=for-the-badge&color=blue)](./LICENSE)

<br>

## 🙆🏼‍♂️ Author Information

Created in **2023** by [lotyp / wayofdev](https://github.com/wayofdev)

<br>

## 🙌 Want to Contribute?

Thank you for considering contributing to the wayofdev community! We are open to all kinds of contributions. If you want to:

- 🤔 Suggest a feature
- 🐛 Report an issue
- 📖 Improve documentation
- 👨‍💻 Contribute to the code

<br>

## 🧱 Credits and Useful Resources

This repository is based on code from following repositories:

* [spiral/serializer](https://github.com/spiral/serializer)
* [spiral-packages/symfony-serializer](https://github.com/spiral-packages/symfony-serializer)
* [jeromegamez/ramsey-uuid-normalizer](https://github.com/jeromegamez/ramsey-uuid-normalizer)
* [wayofdev/laravel-jms-serializer](https://github.com/wayofdev/laravel-jms-serializer)

