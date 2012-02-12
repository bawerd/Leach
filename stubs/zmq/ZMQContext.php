<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ZMQContext
{
    private $io_threads;
    private $is_persistent;

    public function __construct($io_threads = 1, $is_persistent = true)
    {
        $this->io_threads = $io_threads;
        $this->is_persistent = $is_persistent;

        // throw new ZMQContextException();
    }

    public function getSocket($type, string $persistent_id = null, $on_new_socket = null)
    {
        if (!in_array($type, array(ZMQ::SOCKET_PAIR, ZMQ::SOCKET_PUB, ZMQ::SOCKET_SUB, ZMQ::SOCKET_REQ, ZMQ::SOCKET_REP, ZMQ::SOCKET_XREQ, ZMQ::SOCKET_XREP, ZMQ::SOCKET_PUSH, ZMQ::SOCKET_PULL, ZMQ::SOCKET_ROUTER, ZMQ::SOCKET_DEALER,))) {
            throw new ZMQContextException();
        }

        return new ZMQSocket($this, $type, $persistent_id, $on_new_socket);
    }

    public function isPersistent()
    {
        return $this->is_persistent;
    }
}
