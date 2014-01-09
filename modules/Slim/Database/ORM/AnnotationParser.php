<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2014.01.04.
 * Time: 22:47
 */

namespace Slim\Database\ORM;


class AnnotationParser {
    static $regexp_barebone = "[name]\((?P<arguments>.*)\)";

    static $tags = array(
        "Field" => array("type" => "string", "default_value" => null, "index" => false, "auto_increment" => false),
        "OneToMany" => array("foreign_schema", "foreign_key" => 'id', "local_key", "foreign_alias" => null),
        "ManyToOne" => array("foreign_schema", "foreign_key" => 'id', "local_key", "foreign_alias" => null),
        "OneToOne" => array("foreign_schema", "foreign_key" => 'id', "local_key"),
        "ManyToMany" => array("foreign_schema", "foreign_key" => 'id', "local_key", "foreign_alias" => null),
        "Table" => array("name"),
    );

    public static function parse($docblock) {
        $metas = array();
        foreach(self::$tags as $tag => $property_list) {
            $regexp = "/\@".str_replace("[name]", $tag, self::$regexp_barebone)."/";
            //echo $regexp."\n";
            preg_match($regexp, $docblock, $matches);
            //echo $docblock."<br>";
            if(count($matches)) {
                $args = array();
                $arguments = str_replace("\\", "\\\\", $matches['arguments']);
                if($matches['arguments'][0] == '{')
                    $args = json_decode($arguments, true);
                else {
                    if(strpos($arguments, ":") === FALSE) {
                        $helper_args = json_decode("[".$arguments."]", true);
                        $i = 0;
                        array_walk($property_list, function($v, $k) use($helper_args, &$args, &$i) {
                            if(array_key_exists($i, $helper_args)) {
                                $temp = $helper_args[$i];
                                if(is_int($k)) {
                                    $args[$v] = $temp;
                                } else {
                                    $args[$k] = $temp;
                                }
                            }
                            $i++;
                        });
                    }
                }

                array_walk($property_list, function($v, $k) use(&$args) {
                    if(is_int($k)) {
                        if(!array_key_exists($v, $args))
                            $args[$v] = $k;
                    } else {
                        if(!array_key_exists($k, $args)) {
                            $args[$k] = $v;
                        }
                    }
                });

                $metas[$tag] = $args;
            }
        }
        return $metas;
    }

} 