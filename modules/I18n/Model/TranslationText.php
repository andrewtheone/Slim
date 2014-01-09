<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2013.12.26.
 * Time: 1:05
 */

namespace I18n\Model;

class TranslationText {

    /**
     * @Field("LongText")
     */
    protected $text;

    /**
     * @ManyToOne("I18n\Model\TranslationKey", "id", "key_id", "translations")
     */
    protected $key;

    /**
     * @ManyToOne("I18n\Model\Locale", "id", "locale_id")
     */
    protected $locale;
} 