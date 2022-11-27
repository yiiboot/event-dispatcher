<p align="center">
    <a href="https://github.com/yiiboot" target="_blank">
        <img src="https://avatars.githubusercontent.com/u/118281946?s=600&u=b16475d97095b69a8f500ec2f29b8d05c3d02b3a&v=4" height="100px">
    </a>
    <h1 align="center">Yii Boot Event Dispatcher</h1>
    <br>
</p>

[//]: # ([![Latest Stable Version]&#40;https://poser.pugx.org/yiiboot/event-dispatcher/v/stable.png&#41;]&#40;https://packagist.org/packages/yiiboot/event-dispatcher&#41;)

[//]: # ([![Total Downloads]&#40;https://poser.pugx.org/yiiboot/event-dispatcher/downloads.png&#41;]&#40;https://packagist.org/packages/yiiboot/event-dispatcher&#41;)

[//]: # ([![Build status]&#40;https://github.com/yiiboot/event-dispatcher/workflows/build/badge.svg&#41;]&#40;https://github.com/yiiboot/event-dispatcher/actions?query=workflow%3Abuild&#41;)

[//]: # ([![Scrutinizer Code Quality]&#40;https://scrutinizer-ci.com/g/yiiboot/event-dispatcher/badges/quality-score.png?b=master&#41;]&#40;https://scrutinizer-ci.com/g/yiiboot/event-dispatcher/?branch=master&#41;)

[//]: # ([![Code Coverage]&#40;https://scrutinizer-ci.com/g/yiiboot/event-dispatcher/badges/coverage.png?b=master&#41;]&#40;https://scrutinizer-ci.com/g/yiiboot/event-dispatcher/?branch=master&#41;)

[//]: # ([![Mutation testing badge]&#40;https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%yiiboot%2Fevent-dispatcher%2Fmaster&#41;]&#40;https://dashboard.stryker-mutator.io/reports/github.com/yiiboot/event-dispatcher/master&#41;)

[//]: # ([![static analysis]&#40;https://github.com/yiiboot/event-dispatcher/workflows/static%20analysis/badge.svg&#41;]&#40;https://github.com/yiiboot/event-dispatcher/actions?query=workflow%3A%22static+analysis%22&#41;)

[//]: # ([![type-coverage]&#40;https://shepherd.dev/github/yiiboot/event-dispatcher/coverage.svg&#41;]&#40;https://shepherd.dev/github/yiiboot/event-dispatcher&#41;)

An way to define an event listener with the AsEventListener PHP attribute. This allows to configure the listener inside
its class, without having to add any configuration in external files

## Requirements

- PHP 8.1 or higher.

## Installation

The package could be installed with composer:

```shell
composer require yiiboot/event-dispatcher
```

## General usage

the `config/params.php`
```php
return [
    // ...
    'yiiboot/attributed' => [
        'paths' => [
            // ...
            dirname(__DIR__) . '/src/EventListener',
        ]
    ]
];
```

the `src/EventListener/MyMultiListener.php`

```php
namespace App\EventListener;

use Yiiboot\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: CustomEvent::class, method: 'onCustomEvent')]
#[AsEventListener(event: FooEvent::class, priority: 42)]
#[AsEventListener(method: 'onBarEvent', group: ['console', 'web'], env: 'prod')]
final class MyMultiListener
{
    public function onCustomEvent(CustomEvent $event): void
    {
        // ...
    }

    public function onFooEvent(FooEvent $event): void
    {
        // ...
    }

    public function onBarEvent(BarEvent $event): void
    {
        // ...
    }

    #[AsEventListener]
    public function onMethodEvent(BarEvent $event): void
    {
        // ...
    }
}
```

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework with
[Infection Static Analysis Plugin](https://github.com/Roave/infection-static-analysis-plugin). To run it:

```shell
./vendor/bin/roave-infection-static-analysis-plugin
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

### Code style

Use [Rector](https://github.com/rectorphp/rector) to make codebase follow some specific rules or
use either newest or any specific version of PHP:

```shell
./vendor/bin/rector
```

### Dependencies

Use [ComposerRequireChecker](https://github.com/maglnet/ComposerRequireChecker) to detect transitive
[Composer](https://getcomposer.org/) dependencies.

## License

The Yii Boot Event Dispatcher is free software. It is released under the terms of the Apache-2.0 License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Boot](https://github.com/yiiboot).

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiiboot)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Boot-green.svg?style=flat)](https://github.com/yiiboot)

## Inspired && Thanks

- [Yii Software](https://github.com/yiisoft)
- [Symfony](https://github.com/symfony/symfony)
