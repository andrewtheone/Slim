<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.22.
 * Time: 12:06
 */

require_once SLIM_DIR."ClassLoader.php";

class Environment {

    static $environmentVars;

    static function get($var) {
        return (array_key_exists($var, self::$environmentVars)?self::$environmentVars[$var]:false);
    }

    static function set($var, $value) {
        if(!is_array(self::$environmentVars))
            self::$environmentVars = array();
        self::$environmentVars[$var] = $value;
    }
}

class Startup {

    private $appModel = array();
    private $serviceManager;
    private $autoloader;

    public function __construct($appModel = array()) {
        $default = array(
            "base_url" => "http://127.0.0.1/",
            "cache_dir" => APPLICATION_DIR."cache/",
            "database" => array(
                "host" => "127.0.0.1",
                "port" => 3306,
                "username" => "root",
                "password" => "",
                "database" => "db"
            ),
            "modules" => array(
            ),
            "callbacks" => array(
                'ERROR_404' => function() {},
                'ON_EXCEPTION' => function() {}
            ),
            "environment" => array(
            )
        );

        $this->appModel = array_merge_recursive($default, $appModel);
        $this->autoloader = new ClassLoader();
        $this->autoloader->addPath(APPLICATION_DIR."modules/");
        $this->autoloader->addPath(APPLICATION_DIR."src/");
        $this->autoloader->addPath(SLIM_DIR."modules/");
        //$this->autoloader->addPath(SLIM_DIR."vendors/");
        $this->autoloader->register();

        Environment::set('cache_dir', $this->appModel['cache_dir']);

        $this->serviceManager = new Slim\Service\ServiceManager();

        $services = array(
            'Slim\Cache\CacheManager', 'Slim\Database\Database', 'Slim\Mvc\Mvc', 'Slim\Mvc\Router'
        );

        foreach($services as $service_name) {
            $service_instance = new $service_name;
            $this->serviceManager->registerService($service_instance);
        }

        foreach($this->appModel['modules'] as $module_name) {
            $module_name = $module_name.'\\'.$module_name;
            $module_instance = new $module_name;
            $this->serviceManager->registerService($module_instance);
        }

        Slim\Event\EventManager::emit(EVENT_ON_INITIALIZE);
        Slim\Event\EventManager::emit(Slim\Mvc\EVENT_ON_DISPATCH);
    }
}