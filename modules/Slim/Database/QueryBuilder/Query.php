<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.27.
 * Time: 15:25
 */

namespace Slim\Database\QueryBuilder;


use Slim\Database\ORM\AbstractSchema;

class Query {
    CONST SELECT = 0x01;
    CONST INSERT = 0x02;
    CONST UPDATE = 0x03;
    CONST DELETE = 0x04;
    CONST TABLE_CREATE = 0x05;
    CONST TABLE_TRUNCATE = 0x06;
    CONST TABLE_DROP = 0x07;
    CONST TABLE_REFACTOR = 0x08;

    protected $schema;
    protected $type;

    public function __construct(AbstractSchema &$schema, $type) {
        $this->schema = $schema;
        $this->type = $type;
    }


} 