<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach\Container;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Leach\Events;

/**
 * @codeCoverageIgnore
 */
interface ContainerInterface extends HttpKernelInterface
{
    /**
     * Returns a EventDispatcherInterface instance.
     *
     * @return EventDispatcherInterface
     */
    function getEventDispatcher();

    /**
     * Returns a ParameterBag instance.
     *
     * @return ParameterBag
     */
    function getOptions();

    /**
     * Registers a 'leach.setup' event listener.
     *
     * @param mixed $callback
     * @param integer $priority
     *
     * @return void
     *
     * @see Events::SETUP
     * @see EventDispatcherInterface::addListener
     */
    function setUp($callback, $priority = 0);

    /**
     * Registers a 'leach.teardown' event listener.
     *
     * @param mixed $callback
     * @param integer $priority
     *
     * @return void
     *
     * @see Events::TEARDOWN
     * @see EventDispatcherInterface::addListener
     */
    function tearDown($callback, $priority = 0);
}
