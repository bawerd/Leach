<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ZMQDevice
{
    private $frontend;
    private $backend;
    private $cb_func;
    private $timeout;

    public function __construct(ZMQSocket $frontend ,ZMQSocket $backend)
    {
        $this->frontend = $frontend;
        $this->backend = $backend;

        // throw new ZMQDeviceException();
    }

    public function run()
    {
        // throw new ZMQDeviceException();
    }

    public function setIdleCallback($cb_func)
    {
        $this->cb_func = $cb_func;

        return $this;
    }

    public function setIdleTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }
}
