<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ZMQPoll
{
    private $entries;

    public function __construct()
    {
        $this->entries = array();
    }

    public function add($entry, $type)
    {
        if (!in_array($type, array(ZMQ::POLL_IN, ZMQ::POLL_OUT))) {
            throw new ZMQPollException();
        }

        return array_push($this->entries, array(
            'entry' => $this->entry,
            'type' => $type
        ));
    }

    public function clear()
    {
        $this->entries = array();

        return $this;
    }

    public function count()
    {
        return count($this->entries);
    }

    public function getLastErrors()
    {
        return array();
    }

    public function poll(array &$readable, array &$writable, $timeout = -1)
    {
        // throw new ZMQPollException();
    }

    public function remove($item)
    {
        if (isset($this->entries[$item])) {
            unset($this->entries[$item]);

            return true;
        }

        return false;
    }
}
