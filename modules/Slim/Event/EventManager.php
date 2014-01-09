<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.22.
 * Time: 19:28
 */

namespace Slim\Event;

use Slim\Service\ServiceLocator;
use Slim\Service\ServiceManager;

/**
 *
 */
define('EVENT_ON_INITIALIZE', 'global_event_on_initialize');
/**
 *
 */
define('EVENT_ON_DESTROY', 'global_event_on_destroy');

/**
 * Class EventManager
 * @package Slim\Event
 */
class EventManager {
    /**
     * @var array
     */
    static $eventListeners = array();
    /**
     * @var ServiceManager
     */
    static $serviceManager;

    /**
     * @param ServiceManager $sm
     */
    public static function setServiceManager(ServiceManager &$sm) {
        self::$serviceManager = $sm;
    }

    /**
     * @param $event string
     * @param $callback Callable
     * @param int $priority
     * @return void
     */
    public static function addListener($event, $callback, $priority = 1000) {
        if(!array_key_exists($event, self::$eventListeners))
            self::$eventListeners[$event] = array();
        if(!array_key_exists($priority, self::$eventListeners[$event]))
            self::$eventListeners[$event][$priority] = array();
        self::$eventListeners[$event][$priority][] = $callback;
        //ksort(self::$eventListeners[$event]);
    }

    /**
     * @param $event string
     * @param array $event_args
     * @return SignalCollection
     */
    public static function emit($event, $event_args = array()) {
        if(!array_key_exists($event, self::$eventListeners))
            return new SignalCollection(array(new EventSignal()));

        $signals = array();
        $service_locator = new ServiceLocator(self::$serviceManager);

        foreach(self::$eventListeners[$event] as $priority) {
            foreach($priority as &$callback) {
                $signal = call_user_func_array($callback, array(&$service_locator));
                if($signal) {
                    if($signal->getSignals() & EVENT_SIGNAL_TERMINATE)
                        break 2;
                    $signals[] = $signal;
                } else {
                    $signal[] = new EventSignal();
                }
            }
        }
        return new SignalCollection($signals);
    }
} 