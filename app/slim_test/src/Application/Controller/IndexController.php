<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.22.
 * Time: 17:35
 */

namespace Application\Controller;


class IndexController {

    public function indexAction(&$sl) {
        echo "hello world!";
        $a = $sl->get('database');
        $locale = $a->dispose('I18n\Model\Locale');
        print_r($locale);
    }
} 