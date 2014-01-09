<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.23.
 * Time: 2:27
 */

namespace Slim\Mvc;

use Slim\Event\EventManager;
use Slim\Service\AbstractService;

define(__NAMESPACE__.'\EVENT_ON_DISPATCH', 'slim_mvc_event_on_dispatch');

class Router extends AbstractService{

    protected $routes = array();

    public function onCreate($s) {
        parent::onCreate($s);
        EventManager::addListener(EVENT_ON_DISPATCH, function(&$sl) {
            $mvc = $sl->get('mvc');
            $mvc->dispatch();
        });
    }
} // /hirek[/:hir/:id]
  // /felhasznalok[/:action[/:uid]]
  // /cikk[_:cikknev[-:id]]