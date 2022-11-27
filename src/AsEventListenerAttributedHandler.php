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

use ReflectionClass;
use Yiiboot\Attributed\AbstractAttributedHandler;
use Yiiboot\Attributed\AttributedClass;
use Yiiboot\Attributed\AttributedMethod;
use Yiiboot\EventDispatcher\Attribute\AsEventListener;
use Yiisoft\Arrays\ArraySorter;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\EventDispatcher\Provider\ListenerCollection;
use Yiisoft\Yii\Event\InvalidEventConfigurationFormatException;
use Yiisoft\Yii\Event\ListenerCollectionFactory;

/**
 * the AsEventListener Attribute handler
 *
 * @author niqingyang<niqy@qq.com>
 * @date 2022/11/23 23:56
 */
class AsEventListenerAttributedHandler extends AbstractAttributedHandler
{
    private array $events = [];

    public function __construct(private ListenerCollectionFactory $factory, protected ?string $definitionEnvironment = null, protected ?string $env = null)
    {

    }

    public function getAttribute(): string
    {
        return AsEventListener::class;
    }

    /**
     * @throws InvalidConfigException
     */
    public function handle(array $attributeds): void
    {
        foreach ($attributeds as $attributed) {

            /* @var $attribute AsEventListener */
            $attribute = $attributed->getAttribute();

            // 检查环境
            if ($attribute->group && !in_array($this->definitionEnvironment, $attribute->group)) {
                continue;
            } else if ($attribute->env && !in_array($this->env, $attribute->env)) {
                continue;
            }

            $class = $attributed->getClass();

            $event = [
                'event' => $attribute->event,
                'class' => $class->getName(),
                'method' => $attribute->method,
                'priority' => $attribute->priority,
                'listener' => null,
            ];

            if ($attributed instanceof AttributedClass) {

                if (!isset($event['event'])) {
                    $event['method'] = $event['method'] ?? '__invoke';
                    $event['event'] = $this->getEventFromTypeDeclaration($class, $event['method']);
                }

                if (!isset($event['method'])) {
                    $event['method'] = 'on' . preg_replace_callback([
                            '/(?<=\b|_)[a-z]/i',
                            '/[^a-z0-9]/i',
                        ], function ($matches) {
                            return strtoupper($matches[0]);
                        }, $this->parseClassName($event['event']));
                    $event['method'] = preg_replace('/[^a-z0-9]/i', '', $event['method']);

                    if (!$class->hasMethod($event['method']) && $class->hasMethod('__invoke')) {
                        $event['method'] = '__invoke';
                    }
                }
            } else if ($attributed instanceof AttributedMethod) {

                $event['method'] = $attributed->getMethod()->getName();

                if (!isset($event['event'])) {
                    $event['event'] = $this->getEventFromTypeDeclaration($class, $event['method']);
                }
            } else {
                continue;
            }

            $event['listener'] = [$class->getName(), $event['method']];

            $this->events[] = $event;
        }
    }

    public function flush(): void
    {
        // 根据 priority 进行排序，值越大越优先处理
        ArraySorter::multisort($this->events, 'priority', SORT_DESC);
    }

    public function getListenerCollection(): ListenerCollection
    {
        $eventListeners = [];

        foreach ($this->events as $event) {
            $eventListeners[$event['event']] ??= [];
            $eventListeners[$event['event']][] = $event['listener'];
        }

        return $this->factory->create($eventListeners);
    }

    private function parseClassName(string $class): string
    {
        $pos = strrpos($class, '\\');
        return false === $pos ? $class : substr($class, $pos + 1);
    }


    private function getEventFromTypeDeclaration(ReflectionClass $class, string $method): string
    {
        if (!$class->hasMethod($method)
            || 1 > ($m = $class->getMethod($method))->getNumberOfParameters()
            || !($type = $m->getParameters()[0]->getType()) instanceof \ReflectionNamedType
            || $type->isBuiltin()
        ) {
            throw new InvalidEventConfigurationFormatException(sprintf('Incorrect event listener format. "%s" must define the "event" attribute on "AsEventListener" Attribute.', $class->getName()));
        }

        return $type->getName();
    }
}
