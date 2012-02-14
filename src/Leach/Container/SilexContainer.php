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
use Symfony\Component\HttpFoundation\Request;

use Silex\Application;

class SilexContainer extends Container
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var array
     */
    protected $defaults = array(
        'use_http_cache' => false
    );

    /**
     * Constructor.
     *
     * @param Application $application A Application instance
     * @param array $options (optional)
     * @param EventDispatcherInterface $dispatcher A EventDispatcherInterface instance
     *
     * @see Container::__construct
     */
    public function __construct(Application $application, array $options = array(), EventDispatcherInterface $dispatcher = null)
    {
        if (null === $dispatcher) {
            $dispatcher = $application['dispatcher'];
        }

        parent::__construct($options, $dispatcher);

        $this->application = $application;
    }

    /**
     * Returns a Application instance.
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        if ($this->getOptions()->get('use_http_cache', false)) {
            return $this->application['http_cache']->handle($request, $type, $catch);
        }

        return $this->application->handle($request, $type, $catch);
    }
}
