<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.26.
 * Time: 0:33
 */

namespace Slim\Database\ORM;


class Relation {
    protected $name;
    protected $foreignSchema;
    protected $localKey;
    protected $foreignKey;
    protected $callbacks = array();

    public function __construct($name, $foreignSchema, $local_key = 'id', $foreign_key = 'id') {
        $this->name = $name;
        $this->foreignSchema = $foreignSchema;
        $this->localKey = $local_key;
        $this->foreignKey = $foreign_key;
        $this->callbacks = array(AbstractSchema::ON_ADD => array(), AbstractSchema::ON_DELETE => array(), AbstractSchema::ON_EDIT => array());

    }

    /**
     * @return mixed
     */
    public function getForeignSchema()
    {
        return $this->foreignSchema;
    }

    /**
     * @return string
     */
    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    /**
     * @return string
     */
    public function getLocalKey()
    {
        return $this->localKey;
    }


    protected function onAdd(\Closure $callback) {
        $this->callbacks[self::ON_ADD][] = $callback;
    }

    protected  function onDelete(\Closure $callback) {
        $this->callbacks[self::ON_DELETE][] = $callback;
    }

    protected function onEdit(\Closure $callback) {
        $this->callbacks[self::ON_EDIT][] = $callback;
    }

    protected function getCallbacks($type) {
        return $this->callbacks[$type];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

} 