<?php

/*
 * This file is part of the Yii Boot package.
 *
 * (c) niqingyang <niqy@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yiiboot\EventDispatcher\Attribute;

/**
 * autoconfigure event listeners.
 *
 * @author niqingyang<niqy@qq.com>
 * @date 2022/11/27 17:18
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class AsEventListener
{
    public array $group = [];

    public array $env = [];

    /**
     * @param string|null $event
     * @param string|null $method
     * @param array|string|null $group the definitionEnvironment 'web' or 'console'
     * @param array|string|null $env
     * @param int $priority
     */
    public function __construct(
        public ?string    $event = null,
        public ?string    $method = null,
        array|string|null $group = null,
        array|string|null $env = null,
        public int        $priority = 0,
    )
    {
        if (isset($group)) {
            $this->group = (array) $group;
        }
        if (isset($env)) {
            $this->group = (array) $env;
        }
    }
}
