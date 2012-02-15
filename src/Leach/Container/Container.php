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

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

use Leach\Events;

abstract class Container implements ContainerInterface
{
    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ParameterBag
     */
    private $options;

    /**
     * Constructor.
     *
     * @param array $options (optional)
     * @param EventDispatcherInterface $dispatcher A EventDispatcherInterface instance
     *
     * @return void
     */
    public function __construct(array $options = array(), EventDispatcherInterface $dispatcher = null)
    {
        $this->dispatcher = $dispatcher;
        if (null === $this->dispatcher) {
            $this->dispatcher = new EventDispatcher();
        }

        $this->options = new ParameterBag(array(
            'expose_leach' => false,
            'max_requests' => 500,
        ));
        $this->options->add($this->defaults);
        $this->options->add($options);
    }

    /**
     * {@inheritDoc}
     */
    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritDoc}
     */
    public function setUp($callback, $priority = 0)
    {
        $this->dispatcher->addListener(Events::SETUP, $callback, $priority);
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown($callback, $priority = 0)
    {
        $this->dispatcher->addListener(Events::TEARDOWN, $callback, $priority);
    }
}
