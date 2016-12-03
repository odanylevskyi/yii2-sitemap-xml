<?php

namespace odanylevskyi\sitemap\controllers;

use Yii;
use odanylevskyi\sitemap\components\Sitemap;
use odanylevskyi\sitemap\components\SitemapHelper;
use odanylevskyi\sitemap\components\SitemapConstants;

/**
 * Author: Oleksii Danylevskyi <aleksey@danilevsky.com>
 * Date: 27.11.2016
 * Time: 14:19
 */
class DefaultController extends \yii\web\Controller
{
    public function actionIndex() {
        $module = $this->module;
        $sitemap = new Sitemap();
        if (!$sitemap->isFileDateExpired($module->expire)) {
          return $sitemap->output();
        }
        $sitemap->clear();
        $sitemap->init();
        foreach ($module->items as $item) {
            $class = isset($item['class']) ? new \ReflectionClass(Yii::getAlias($item['class'])) : null;
            if (!$class) {
                foreach ($item['urls'] as $url) {
                    $sitemap->addItem(SitemapHelper::buildUrl($url));
                }
            } else {
                $tableName  = $class->getMethod('tableName')->invoke(null);
                $select     = SitemapHelper::getSelect($item['urls'], $tableName);
                $modelQuery = $class->getMethod('find')->invoke(null);
                if (!empty($select)) {
                    $modelQuery->select($select);
                }
                if (isset($item['rules']) && is_callable($item['rules'])) {
                    $rules = $item['rules'];
                    $modelQuery = $rules($modelQuery);
                }
                $models = $modelQuery->all();
                foreach($models as $model) {
                    foreach ($item['urls'] as $url) {
                        $sitemap->addItem(SitemapHelper::buildUrl($url, $model), 0.8, SitemapConstants::CHANGEFREQ_WEEKLY);
                    }
                }
            }
        }
        if ($module->useIndex || $sitemap->getCurrentSitemap() > 1) {
            $sitemap->createIndex();
        }
        $sitemap->save();
        return $sitemap->output();
    }
}
