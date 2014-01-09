<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.22.
 * Time: 19:35
 */

namespace Slim\Service;

use Slim\Event\EventSignal;

/**
 * Class AbstractService
 * @package Slim\Service
 */
class AbstractService implements IService {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param array $event_args
     * @return EventSignal
     */
    public function onCreate($event_args)
    {
        $this->serviceManager = &$event_args[0];
        return new EventSignal();
    }

    /**
     * @param array $event_args
     * @return EventSignal
     */
    public function onInitialize($event_args)
    {
        return new EventSignal();
    }

    /**
     * @param array $event_args
     * @return EventSignal
     */
    public function onDestroy($event_args)
    {
        return new EventSignal();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return strtolower(substr(get_class($this), strrpos(get_class($this), '\\')+1));
    }

    /**
     * @return ServiceManager
     */
    public function &getServiceManager()
    {
        return $this->serviceManager;
    }

} 