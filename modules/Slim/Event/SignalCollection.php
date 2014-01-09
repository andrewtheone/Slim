<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.23.
 * Time: 0:12
 */

namespace Slim\Event;


class SignalCollection extends \ArrayObject {

    public function getLast() {
        return $this->offsetGet($this->count()-1);
    }

    public function getFirst() {
        return $this->offsetGet(0);
    }
} 