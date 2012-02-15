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

use Leach\Container\ContainerInterface;
use Leach\Events;

/**
 * A 'leach.request' event.
 *
 * @see Events::REQUEST
 */
class FilterRequestEvent extends Event
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     * @param Request $request A Request instance
     */
    public function __construct(ContainerInterface $container, Request $request)
    {
        parent::__construct($container);

        $this->request = $request;
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
}
