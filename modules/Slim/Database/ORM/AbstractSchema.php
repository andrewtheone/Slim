<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.26.
 * Time: 0:33
 */

namespace Slim\Database\ORM;


class AbstractSchema {

    CONST ON_ADD = 0x01;
    CONST ON_DELETE = 0x02;
    CONST ON_EDIT = 0x03;

    protected $fieldSet = array();
    protected $relations = array();
    protected $callbacks = array();
    protected $tableName;
    /**
     * @var SchemaFactory
     */
    protected $schemaFactory;

    public function __construct() {
        $this->fieldSet[] = new Field('id', Field::TYPE_INTEGER, null, Field::FLAG_PRIMARYKEY | Field::FLAG_AUTOINCREMENT);
        $this->tableName = strtolower(substr(get_class($this), strrpos(get_class($this), '\\')+1));
        $this->callbacks = array(
            self::ON_ADD => array(), self::ON_DELETE => array(), self::ON_EDIT => array()
        );
    }

    public function setTableName($name) {
        $this->tableName = $name;
    }

    public function setSchemaFactory(SchemaFactory &$sf) {
        $this->schemaFactory = $sf;
    }

    public function addField(Field $field) {
        $this->fieldSet[] = $field;
    }

    public function addRelation($field_alias, Relation $relation) {
        $this->relations[$field_alias] = $relation;

        $foreignSchema = $this->schemaFactory->getSchema($relation->getForeignSchema());
        $localKey = $relation->getLocalKey();
        $foreignKey = $relation->getForeignKey();

        if($this->hasField($localKey)) {
            //no-need for helper table
        } else {
            //we need to fabricate helper table
            if(!$this->schemaFactory->shemaExists($this->getTableName()."_".$foreignSchema->getTableName()."_".$relation->getName())) {
                $helperSchema = new self;
                $helperSchema->setTableName($this->getTableName()."_".$foreignSchema->getTableName()."_".$relation->getName());
                $helperSchema->addField(new Field($this->getTableName().'_id', Field::TYPE_INTEGER));
                $helperSchema->addField(new Field($foreignSchema->getTableName().'_id', Field::TYPE_INTEGER));
                $this->schemaFactory->addSchema($helperSchema);
            }
        }
    }

    public function onAdd(\Closure $callback) {
        $this->callbacks[self::ON_ADD][] = $callback;
    }

    public  function onDelete(\Closure $callback) {
        $this->callbacks[self::ON_DELETE][] = $callback;
    }

    public function onEdit(\Closure $callback) {
        $this->callbacks[self::ON_EDIT][] = $callback;
    }

    public function getFieldNames() {
        $fields = array();
        foreach($this->fieldSet as $f)
            $fields[] = $f->getName();
        return $fields;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function hasField($field) {
        if(array_key_exists($field, $this->getFieldNames()))
            return true;
        return false;
    }

    public function hasRelation($field_alias) {
        if(array_key_exists($field_alias, $this->relations))
            return true;
        return false;
    }

    public function getSchemaName() {
        if((strpos(get_class($this), "AbstractSchema") !== FALSE)) {
            return $this->tableName;
        } else {
            return get_class($this);
        }
    }

    public function store($fields, $relations) {
        //store relations
        if(count($relations)) {
            foreach($relations as $relation_name => $relation_record) {
                $relation = $this->relations[$relation_name];
                $foreignSchema = $this->schemaFactory->getSchema($relation->getForeignSchema());
                $localKey = $relation->getLocalKey();
                $foreignKey = $relation->getForeignKey();

                $relation_record->store();
                if($this->hasField($localKey)) {
                    $fields[$localKey] = $relation_record->get($foreignKey);
                } else {
                    //storing relation with to helper schema
                    $helperSchema = $this->schemaFactory->getSchema($this->getTableName()."_".$foreignSchema->getTableName()."_".$relation->getName());
                    $helperRecord = new Record($helperSchema);
                    //TODO: find out if its a new record, if it is then store field first and after it use it...
                    $helperRecord->set($this->getTableName().'_id', $fields[$localKey]);
                    $helperRecord->set($foreignSchema->getTableName().'_id', $relation_record[$foreignKey]);
                    $helperRecord->store();
                }
            }
        }

        //store fields...
    }
} 