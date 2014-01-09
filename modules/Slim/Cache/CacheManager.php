<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.24.
 * Time: 23:09
 */

namespace Slim\Cache;

use Slim\Event\EventSignal;
use Slim\Service\AbstractService;

define('CACHE_ARRAY', 0x01);
define('CACHE_OBJECT', 0x02);
define('CACHE_PLAIN_TEXT', 0x03);

class CacheManager extends AbstractService {

    protected $dir;

    public function onCreate($event_args) {
        $this->dir = \Environment::get('cache_dir');
        return new EventSignal();
    }

    public function get($id) {
        $id = md5(strtolower($id));

        if(file_exists($this->dir.substr($id, 0, 2)."/".substr($id, 2, 2)."/".substr($id, 4))) {
            $cacheItem = unserialize(file_get_contents($this->dir.substr($id, 0, 2)."/".substr($id, 2, 2)."/".substr($id, 4)));
            $meta = $cacheItem->getMeta();
            $content = $cacheItem->getContent();
            switch($meta) {
                case CACHE_ARRAY:
                case CACHE_OBJECT:
                    return unserialize($content);
                break;
                case CACHE_PLAIN_TEXT:
                    return $content;
                break;
            }
        }
        return null;
    }

    public function store($id, $content) {
        $meta = null;
        if(is_array($content)) {
            $meta = CACHE_ARRAY;
            $content = serialize($content);
        } else
        if(is_object($content)) {
            $meta = CACHE_OBJECT;
            $content = serialize($content);
        } else {
            $meta = CACHE_PLAIN_TEXT;
        }
        $dir = $this->dir.substr($id, 0, 2)."/".substr($id, 2, 2);
        $file = $this->dir.substr($id, 0, 2)."/".substr($id, 2, 2)."/".substr($id, 4);
        @mkdir($dir, 0777, true);
        file_put_contents($file, new Jar($meta, $content));
    }
} 