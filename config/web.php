<?php
/*
 * This file is part of the Yii Boot package.
 *
 * (c) niqingyang <niqy@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Yiiboot\EventDispatcher\AsEventListenerAttributedHandler;
use Yiisoft\Definitions\Reference;
use Yiisoft\Yii\Event\ListenerCollectionFactory;

return [
    AsEventListenerAttributedHandler::class => [
        '__construct()' => [
            'factory' => Reference::to(ListenerCollectionFactory::class),
            'definitionEnvironment' => 'web',
            'env' => $_ENV['YII_ENV'] ?? null,
        ]

    ],
];
