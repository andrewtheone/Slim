<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.23.
 * Time: 2:17
 */

namespace Slim\Mvc;


use Slim\Service\AbstractService;
use Slim\Service\ServiceLocator;

class Mvc extends AbstractService {

    public function dispatch() {
        $controller = new \Application\Controller\IndexController();
        $sl = new ServiceLocator($this->getServiceManager());
        $controller->indexAction($sl);
    }
} 