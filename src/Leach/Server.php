<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Leach\Container\ContainerInterface;
use Leach\Event\SetUpEvent;
use Leach\Event\TearDownEvent;

class Server
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Transport
     */
    protected $transport;

    /**
     * @var Boolean
     */
    protected $running;

    /**
     * @var integer
     */
    protected $requests;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     * @param Transport $transport A Transport instance
     */
    public function __construct(ContainerInterface $container, Transport $transport)
    {
        $this->container = $container;
        $this->transport = $transport;
        $this->requests = 0;

        // @codeCoverageIgnoreStart
        if (false === gc_enabled()) {
            gc_enable();
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Returns the Transport instance.
     *
     * @return Transport
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Returns the ContainerInterface instance.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Starts the server.
     *
     * @return void
     *
     * @see Transport::connect
     * @see Server::isRunning
     * @see Server::runOnce
     */
    public function start()
    {
        $this->transport->connect();
        $this->running = true;
        while ($this->isRunning()) {
            $this->runOnce();
        }
    }

    /**
     * Stops the server.
     *
     * @return void
     *
     * @see Transport::disconnect
     */
    public function stop()
    {
        $this->running = false;
        $this->transport->disconnect();
    }

    /**
     * Whether the server is running.
     *
     * @return Boolean
     *
     * @see Server::stop
     */
    protected function isRunning()
    {
        if ($this->requests++ >= $this->container->getOptions()->get('max_requests', 500)) {
            $this->stop();
        }

        return $this->running;
    }

    /**
     * Runs the server once.
     *
     * @return void
     *
     * @see HttpKernelInterface::handle
     * @see Transport::recv
     * @see Transport::send
     */
    protected function runOnce()
    {
        $this->setUp();

        $request = $this->transport->recv();
        $response = $this->container->handle($request);
        $this->transport->send($request, $response);

        $this->tearDown($request, $response);

        gc_collect_cycles();
    }

    /**
     * Dispatches a 'leach.setup' event.
     *
     * @return void
     *
     * @see Events::SETUP
     * @see SetUpEvent::__construct
     * @see EventDispatcherInterface::dispatch
     */
    protected function setUp()
    {
        $event = new SetUpEvent($this->container);
        $this->container->getEventDispatcher()->dispatch(Events::SETUP, $event);
    }

    /**
     * Dispatches a 'leach.teardown' event.
     *
     * @param Request $request A Request instance
     * @param Response $response A Response instance
     *
     * @return void
     *
     * @see Events::TEARDOWN
     * @see TearDownEvent::__construct
     * @see EventDispatcherInterface::dispatch
     */
    protected function tearDown(Request $request, Response $response)
    {
        $event = new TearDownEvent($this->container, $request, $response);
        $this->container->getEventDispatcher()->dispatch(Events::TEARDOWN, $event);
    }
}
