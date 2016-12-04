<?php

namespace odanylevskyi\sitemap;
use yii\base\InvalidConfigException;

/**
 *
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = '\odanylevskyi\sitemap\controllers';
    /*
     * All the configuration that belonds to urls and models should be placed in this attribute
     */
    public $items = [];
    /*
     * Cache period in seconds.
     * Should be an integer.
     * To setup cache period for 30 days you need to put 30 in seconds: 30*24*3600
     * If you put value <= 0 then cache will be disabled
     */
    public $expire = -1;
    /*
     * Boolean - to use sitemap-index file you use true, otherwise false
     * Sitemap-index file will be used automaticaly in case if you have more then one sitema.xml file.
     */
    public $useIndex = false;

    public function init() {
        parent::init();
        if (empty($this->items)) {
            throw new InvalidConfigException();
        }
        if (!is_integer($this->expire)) {
            throw new InvalidConfigException();
        }
    }
}
