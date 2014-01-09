<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.26.
 * Time: 0:37
 */

namespace Slim\Database\ORM;


class Field {

    CONST TYPE_STRING = 'ts';
    CONST TYPE_INTEGER = 'ti';
    CONST TYPE_LONGTEXT = 'tl';
    CONST TYPE_TIMESTAMP = 'tt';

    CONST FLAG_PRIMARYKEY = 0x01;
    CONST FLAG_INDEX = 0x02;
    CONST FLAG_AUTOINCREMENT = 0x04;
    CONST FLAG_RELATION = 0x08;

    protected $name;
    protected $type;
    protected $defaultValue;
    protected $flags;

    public function __construct($name, $type, $defaultValue = null, $flags = 0x00) {
        $this->name = $name;
        $this->type = $type;
        $this->defaultValue = $defaultValue;
        $this->flags = $flags;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return mixed
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


} 