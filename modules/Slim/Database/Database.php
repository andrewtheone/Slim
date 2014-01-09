<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.27.
 * Time: 16:33
 */

namespace Slim\Database;


use Slim\Database\ORM\Record;
use Slim\Database\ORM\SchemaFactory;
use Slim\Service\AbstractService;

class Database extends AbstractService {
    protected $sf;

    public function onCreate($event_args) {
    }

    public function onInitialize($event_args) {
        SchemaFactory::generateSchemas();
        $this->sf = new SchemaFactory();
    }

    public function dispose($schema) {
        return new Record($this->sf->getSchema($schema));
    }
} 