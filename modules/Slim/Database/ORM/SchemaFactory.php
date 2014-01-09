<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.26.
 * Time: 0:55
 */

namespace Slim\Database\ORM;


use I18n\Model\Locale;
use I18n\Model\TranslationKey;
use I18n\Model\TranslationText;

class SchemaFactory {

    /**
     * @var AbstractSchema[]
     */
    protected $schemas;

    public static function generateSchemas() {

        $schema_names = array();

        //get schema class names
        $dirs = array(APPLICATION_DIR, SLIM_DIR.'modules');
        foreach($dirs as $dir) {
            $dir_itr = new \RecursiveDirectoryIterator($dir);
            while($dir_itr->valid()) {

                $module_name = $dir_itr->getPath()."/".$dir_itr->getFilename();
                if(!($module_name == '.' || $module_name == '..')) {
                    if(is_dir($module_name."/Model")) {
                        $module_schemas_itr = new \RecursiveDirectoryIterator($module_name."/Model");

                        while($module_schemas_itr->valid()) {
                            $schema_file = $module_schemas_itr->getFilename();
                            if(!($schema_file == "." || $schema_file == "..")) {
                                $schema_name = str_replace(array($dir_itr->getPath()."/", "/", ".php"),
                                    array("", "\\", ""),
                                    $module_schemas_itr->getPath()."/".$module_schemas_itr->getFilename());

                                $schema_names[] = $schema_name;
                            }
                            $module_schemas_itr->next();
                        }
                    }
                }

                $dir_itr->next();
            }
        }

        $schema_reflectionClasses = array();
        array_walk($schema_names, function($value) use(&$schema_reflectionClasses) {
            $schema_reflectionClasses[] = new \ReflectionClass($value);
        });

        $meta_data = array();
        array_walk($schema_reflectionClasses, function /** @var $schema_reflectionClass \ReflectionClass */ ($schema_reflectionClass) use(&$meta_data) {
            $fields_reflectionProperties = $schema_reflectionClass->getProperties();
            array_walk($fields_reflectionProperties, function /** @var $field_reflectionProperty \ReflectionProperty */ ($field_reflectionProperty) use (&$meta_data) {
                $meta_data[$field_reflectionProperty->getDeclaringClass()->getName()][$field_reflectionProperty->getName()] = AnnotationParser::parse($field_reflectionProperty->getDocComment());
            });
        });

        $schemaFactory = null;//new self;
        array_walk($meta_data, function ($fields, $schema_class_name) use(&$schemaFactory) {
            echo $schema_class_name."\n";
            $schema = new AbstractSchema($schema_class_name);
            array_walk($fields, function($field_metas, $field) use (&$schema) {
                echo "\t".$field."\n";
                array_walk($field_metas, function($options, $type) use (&$schema) {
                    echo "\t\t".$type."\n";
                });
            });
        });

        print_r($meta_data);
        print_r($schema_reflectionClasses);

    }

    public function __construct() {
        $this->schemas = array(
            'I18n\Model\Locale' => new Locale(),
            'I18n\Model\TranslationText' => new TranslationText(),
            'I18n\Model\TranslationKey' => new TranslationKey()
        );
        $this->reload();
        foreach($this->schemas as $s)
            $s->schema();
    }

    public function reload() {
        foreach($this->schemas as $schema)
            $schema->setSchemaFactory($this);
    }

    /**
     * @param $schema
     * @return AbstractSchema
     */
    public function &getSchema($schema_name) {
        return $this->schemas[$schema_name];
    }

    public function addSchema($schema) {
        $this->schemas[$schema->getSchemaName()] = $schema;
    }

    public function shemaExists($schema_name) {
        foreach($this->schemas as $schema)
            if($schema->getSchemaName() == $schema_name)
                return true;
        return false;
    }
}