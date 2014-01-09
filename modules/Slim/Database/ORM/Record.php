<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.26.
 * Time: 0:33
 */

namespace Slim\Database\ORM;


class Record {

    protected $schema;
    protected $fields = array();
    protected $relations = array();

    public function __construct(AbstractSchema &$schema) {
        $this->schema = $schema;
        $fields = $this->schema->getFieldNames();
        foreach($fields as $field)
            $this->fields[$field] = null;
    }

    public function set($field, $value) {
        if(array_key_exists($field, $this->fields))
            $this->fields[$field] = $value;
    }

    public function get($field) {
        if(array_key_exists($field, $this->fields))
            return $this->fields[$field];

        if($this->schema->hasRelation($field)) {

        }
    }

    public function add($field_alias, Record $record) {
        if($this->schema->hasRelation($field_alias)) {
            if(!array_key_exists($field_alias, $this->relations))
                $this->relations[$field_alias] = array();

            $this->relations[$field_alias][] = $record;
        }
    }

    public function store() {
        $this->schema->store($this->fields, $this->relations);
    }

    public function delete() {
    }
} 