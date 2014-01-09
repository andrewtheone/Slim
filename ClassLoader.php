<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.22.
 * Time: 12:02
 */

class ClassLoader {
    protected $paths = array();

    public function addPath($path) {
        $this->paths[] = $path;
    }

    public function register() {
        spl_autoload_register(array(&$this, "autoload"));
    }

    public function autoload($class_name) {
        $pearl = str_replace("\\", "_", $class_name);
        $namespace = str_replace("\\", "/", $class_name);

        foreach($this->paths as $path) {
            if(file_exists($path.$pearl.".php")) {
                require $path.$pearl.".php";
                return;
            }
            if(file_exists($path.$namespace.".php")) {
                require $path.$namespace.".php";
                return;
            }
        }

        throw new Exception("Class with the name \"{$class_name}\" could not be loaded because could not be found.");
    }
} 