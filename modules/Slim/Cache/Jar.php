<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.24.
 * Time: 23:43
 */

namespace Slim\Cache;


class Jar {
    protected $meta;
    protected $content;

    public function __construct($meta, $content) {
        $this->meta = $meta;
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

}