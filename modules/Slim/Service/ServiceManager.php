<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.22.
 * Time: 19:34
 */

namespace Slim\Service;


use Slim\Event\EventManager;

/**
 * Class ServiceManager
 * @package Slim\Service
 */
class ServiceManager {

    /**
     * @var IService[]
     */
    protected $services = array();

    public function __construct() {
        EventManager::setServiceManager($this);
    }

    /**
     * @param IService $service
     */
    public function registerService(IService &$service) {
        $this->services[$service->getName()] = $service;
        EventManager::addListener(EVENT_ON_INITIALIZE, array(&$this->services[$service->getName()], 'onInitialize'));
        EventManager::addListener(EVENT_ON_DESTROY, array(&$this->services[$service->getName()], 'onDestroy'));
        $this->services[$service->getName()]->onCreate(array(&$this));
    }

    /**
     * @param $service_name string
     * @return IService
     * @throws \Exception
     */
    public function &get($service_name) {
        if(!array_key_exists($service_name, $this->services))
            throw new \Exception("No service was loaded with name: \"{$service_name}\"!");
        return $this->services[$service_name];
    }
} 