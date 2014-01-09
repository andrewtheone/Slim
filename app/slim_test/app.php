<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.22.
 * Time: 12:20
 */

define('APPLICATION_DIR', __DIR__."/");
define('SLIM_DIR', APPLICATION_DIR.'../Slim/');

require SLIM_DIR."Startup.php";

$applicationModel = array(
    "base_url" => "http://127.0.0.1/slim_test/",
    "cache_dir" => APPLICATION_DIR."cache/",
    "database" => array(
        "host" => "127.0.0.1",
        "port" => 3306,
        "username" => "root",
        "password" => "",
        "database" => "db"
    ),
    "modules" => array(
        "Admin", /*"Pages", "Seo", "I18n", "News", "Gallery"*/
    ),
    "callbacks" => array(
        'ON_CREATE' => function() {},
        'ON_INITIALIZE' => function() {},
        'ON_DESTROY' => function() {},
        'ERROR_404' => function() {},
        'ON_EXCEPTION' => function() {}
    )
);

$startup = new Startup($applicationModel);