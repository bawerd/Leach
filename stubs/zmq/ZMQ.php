<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ZMQ
{
    const SOCKET_PAIR = 0;
    const SOCKET_PUB = 1;
    const SOCKET_SUB = 2;
    const SOCKET_REQ = 3;
    const SOCKET_REP = 4;
    const SOCKET_XREQ = 5;
    const SOCKET_XREP = 6;
    const SOCKET_PUSH = 7;
    const SOCKET_PULL = 8;
    const SOCKET_ROUTER = 5;
    const SOCKET_DEALER = 6;
    const SOCKOPT_HWM = 1;
    const SOCKOPT_SNDHWM = 23;
    const SOCKOPT_RCVHWM = 24;
    const SOCKOPT_AFFINITY = 4;
    const SOCKOPT_IDENTITY = 5;
    const SOCKOPT_SUBSCRIBE = 6;
    const SOCKOPT_UNSUBSCRIBE = 7;
    const SOCKOPT_RATE = 8;
    const SOCKOPT_RECOVERY_IVL = 9;
    const SOCKOPT_MCAST_LOOP = 10;
    const SOCKOPT_SNDBUF = 11;
    const SOCKOPT_RCVBUF = 12;
    const SOCKOPT_RCVMORE = 13;
    const SOCKOPT_TYPE = 16;
    const SOCKOPT_LINGER = 17;
    const SOCKOPT_BACKLOG = 19;
    const SOCKOPT_MAXMSGSIZE = 22;
    const SOCKOPT_SNDTIMEO = 23;
    const SOCKOPT_RCVTIMEO = 24;
    const POLL_IN = 1;
    const POLL_OUT = 2;
    const MODE_NOBLOCK = 1;
    const MODE_DONTWAIT = 1;
    const MODE_SNDMORE = 2;
    const ERR_INTERNAL = -99;
    const ERR_EAGAIN = 11;
    const ERR_ENOTSUP = 95;
    const ERR_EFSM = 156384763;
    const ERR_ETERM = 156384765;

    private function __construct()
    {
    }
}
