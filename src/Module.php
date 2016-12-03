<?php

namespace odanylevskyi\sitemap;

/**
 * 
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = '\odanylevskyi\sitemap\controllers';
    public $items = [];
    public $expire = 30*24*3600;
    public $useIndex = true;
}
