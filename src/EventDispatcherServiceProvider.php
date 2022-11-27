<?php
/*
 * This file is part of the Yii Boot package.
 *
 * (c) niqingyang <niqy@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yiiboot\EventDispatcher;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiiboot\Attributed\AttributedLoader;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\EventDispatcher\Dispatcher\CompositeDispatcher;
use Yiisoft\EventDispatcher\Dispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\Provider;

/**
 *
 * @author niqingyang<niqy@qq.com>
 * @date 2022/11/27 11:31
 */
class EventDispatcherServiceProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        return [];
    }

    public function getExtensions(): array
    {
        return [
            EventDispatcherInterface::class => function (ContainerInterface $container, EventDispatcherInterface $dispatcher) {

                $container->get(AttributedLoader::class)->load();
                $handler = $container->get(AsEventListenerAttributedHandler::class);

                if ($dispatcher instanceof CompositeDispatcher) {
                    $composite = $dispatcher;
                } else {
                    $composite = new CompositeDispatcher();
                    $composite->attach($dispatcher);
                }

                $composite->attach(new Dispatcher(new Provider($handler->getListenerCollection())));

                return $composite;
            }
        ];
    }
}
