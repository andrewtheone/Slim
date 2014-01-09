<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.22.
 * Time: 19:34
 */

namespace Slim\Service;


/**
 * Class ServiceLocator
 * @package Slim\Service
 */
class ServiceLocator {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param ServiceManager $sm
     * @return void
     */
    public function __construct(ServiceManager &$sm)
    {
        $this->serviceManager = $sm;
    }

    /**
     * @param $service_name
     * @return IService
     */
    public function &get($service_name) {
        return $this->serviceManager->get($service_name);
    }
} 