<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.22.
 * Time: 19:32
 */

namespace Slim\Service;


/**
 * Interface IService
 * @package Slim\Service
 */
interface IService {
    /**
     * @param $event_args array
     * @return EventSignal
     */
    public function onCreate($event_args);

    /**
     * @param $event_args array
     * @return EventSignal
     */
    public function onInitialize($event_args);

    /**
     * @param $event_args array
     * @return EventSignal
     */
    public function onDestroy($event_args);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return ServiceManager
     */
    public function &getServiceManager();
} 