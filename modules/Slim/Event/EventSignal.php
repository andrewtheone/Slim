<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.22.
 * Time: 19:29
 */

namespace Slim\Event;

/**
 *
 */
define('EVENT_SIGNAL_TERMINATE', 1);

/**
 * Class EventSignal
 * @package Slim\Event
 */
class EventSignal {

    /**
     * @var array
     */
    protected $data = array();
    /**
     * @var int
     */
    protected $signals = 0;

    /**
     * @param array $data
     * @param int $signals
     */
    public function __construct($data = array(), $signals = 0) {
        $this->data = $data;
        $this->signals = $signals;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getSignals()
    {
        return $this->signals;
    }


} 