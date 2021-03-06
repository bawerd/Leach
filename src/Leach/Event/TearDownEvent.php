<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Leach\Container\ContainerInterface;
use Leach\Events;

/**
 * A 'leach.teardown' event.
 *
 * @see Events::TEARDOWN
 */
class TearDownEvent extends SetUpEvent
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     * @param Request $request A Request instance
     * @param Response $response A Response instance
     */
    public function __construct(ContainerInterface $container, Request $request, Response $response)
    {
        parent::__construct($container, $request);

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Returns a Request instance.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns a Response instance.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
