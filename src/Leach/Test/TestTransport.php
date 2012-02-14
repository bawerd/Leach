<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach\Test;

use Leach\Socket;
use Leach\Transport;

/**
 * @codeCoverageIgnore
 */
class TestTransport extends Transport
{
    /**
     * @return Socket
     */
    public function getSendSpec()
    {
        return $this->sendSpec;
    }

    /**
     * @return string
     */
    public function getSendId()
    {
        return $this->sendId;
    }

    /**
     * @return \ZMQSocket
     */
    public function getSend()
    {
        return $this->send;
    }

    /**
     * @param \ZMQSocket $send A ZMQSocket instance
     *
     * @return void
     */
    public function setSend(\ZMQSocket $send)
    {
        $this->send = $send;
    }

    /**
     * @return Socket
     */
    public function getRecvSpec()
    {
        return $this->recvSpec;
    }

    /**
     * @return \ZMQSocket
     */
    public function getRecv()
    {
        return $this->recv;
    }

    /**
     * @param \ZMQSocket $recv A ZMQSocket instance
     *
     * @return void
     */
    public function setRecv(\ZMQSocket $recv)
    {
        $this->recv = $recv;
    }
}
