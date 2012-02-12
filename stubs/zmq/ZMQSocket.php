<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ZMQSocket
{
    private $context;
    private $type;
    private $persistent_id;
    private $on_new_socket;
    private $options;

    public function __construct(ZMQContext $context, $type, $persistent_id = null, $on_new_socket = null)
    {
        if (!in_array($type, array(ZMQ::SOCKET_PAIR, ZMQ::SOCKET_PUB, ZMQ::SOCKET_SUB, ZMQ::SOCKET_REQ, ZMQ::SOCKET_REP, ZMQ::SOCKET_XREQ, ZMQ::SOCKET_XREP, ZMQ::SOCKET_PUSH, ZMQ::SOCKET_PULL, ZMQ::SOCKET_ROUTER, ZMQ::SOCKET_DEALER,))) {
            throw new ZMQSocketException();
        }

        $this->context = $context;
        $this->type = $type;
        $this->persistent_id = $persistent_id;
        $this->on_new_socket = $on_new_socket;
        $this->options = array();
    }

    public function bind($dsn, $force = false)
    {
        // throw new ZMQSocketException();
    }

    public function connect($dsn, $force = false)
    {
        // throw new ZMQSocketException();
    }

    public function getEndpoints()
    {
        return array();
    }

    public function getPersistentId()
    {
        return $this->persistent_id;
    }

    public function getSocketType()
    {
        return $this->type;
    }

    public function getSockOpt($key)
    {
        if (!in_array($key, array(ZMQ::SOCKOPT_HWM, ZMQ::SOCKOPT_SNDHWM, ZMQ::SOCKOPT_RCVHWM, ZMQ::SOCKOPT_AFFINITY, ZMQ::SOCKOPT_IDENTITY, ZMQ::SOCKOPT_SUBSCRIBE, ZMQ::SOCKOPT_UNSUBSCRIBE, ZMQ::SOCKOPT_RATE, ZMQ::SOCKOPT_RECOVERY_IVL, ZMQ::SOCKOPT_MCAST_LOOP, ZMQ::SOCKOPT_SNDBUF, ZMQ::SOCKOPT_RCVBUF, ZMQ::SOCKOPT_RCVMORE, ZMQ::SOCKOPT_TYPE, ZMQ::SOCKOPT_LINGER, ZMQ::SOCKOPT_BACKLOG, ZMQ::SOCKOPT_MAXMSGSIZE, ZMQ::SOCKOPT_SNDTIMEO, ZMQ::SOCKOPT_RCVTIMEO,))) {
            throw new ZMQSocketException();
        }

        if (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }

        // throw new ZMQSocketException();
    }

    public function isPersistent()
    {
        return $this->context->isPersistent();
    }

    public function recv($mode = 0)
    {
        if (!in_array($mode, array(ZMQ::MODE_NOBLOCK, ZMQ::MODE_DONTWAIT, ZMQ::MODE_SNDMORE,))) {
            throw new ZMQSocketException();
        }

        // on error, if NOBLOCK, return false instead of ZMQSocketException
    }

    public function recvMulti($mode = 0)
    {
        if (!in_array($mode, array(ZMQ::MODE_NOBLOCK, ZMQ::MODE_DONTWAIT, ZMQ::MODE_SNDMORE,))) {
            throw new ZMQSocketException();
        }

        // on error, if NOBLOCK, return false instead of ZMQSocketException
    }

    public function send($message, $mode = 0)
    {
        if (!in_array($mode, array(ZMQ::MODE_NOBLOCK, ZMQ::MODE_DONTWAIT, ZMQ::MODE_SNDMORE,))) {
            throw new ZMQSocketException();
        }

        // on error, if NOBLOCK, return false instead of ZMQSocketException

        return $this;
    }

    public function sendMulti(array $message, $mode = 0)
    {
        if (!in_array($mode, array(ZMQ::MODE_NOBLOCK, ZMQ::MODE_DONTWAIT, ZMQ::MODE_SNDMORE,))) {
            throw new ZMQSocketException();
        }

        // on error, if NOBLOCK, return false instead of ZMQSocketException

        return $this;
    }

    public function setSockOpt($key, $value)
    {
        if (!in_array($key, array(ZMQ::SOCKOPT_HWM, ZMQ::SOCKOPT_SNDHWM, ZMQ::SOCKOPT_RCVHWM, ZMQ::SOCKOPT_AFFINITY, ZMQ::SOCKOPT_IDENTITY, ZMQ::SOCKOPT_SUBSCRIBE, ZMQ::SOCKOPT_UNSUBSCRIBE, ZMQ::SOCKOPT_RATE, ZMQ::SOCKOPT_RECOVERY_IVL, ZMQ::SOCKOPT_MCAST_LOOP, ZMQ::SOCKOPT_SNDBUF, ZMQ::SOCKOPT_RCVBUF, ZMQ::SOCKOPT_RCVMORE, ZMQ::SOCKOPT_TYPE, ZMQ::SOCKOPT_LINGER, ZMQ::SOCKOPT_BACKLOG, ZMQ::SOCKOPT_MAXMSGSIZE, ZMQ::SOCKOPT_SNDTIMEO, ZMQ::SOCKOPT_RCVTIMEO,))) {
            throw new ZMQSocketException();
        }

        $this->options[$key] = $value;

        return $this;
    }
}
