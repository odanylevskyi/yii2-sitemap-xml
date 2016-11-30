<?php

namespace odanylevskyi\sitemap;

/**
 * This is just an example.
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = '\odanylevskyi\sitemap\controllers';
    public $items = [];
    public $cache = [];
    public $useIndex = true;
    public $enableGzip = false;
    public $path = '@frontend/web/sitemap/';

    public function init()
    {
        parent::init();
        if (!empty($this->cache)) {
            \Yii::configure($this, [
                'components' => [
                    'cache' => $this->cache,
                ]
            ]);
        }
    }
}
