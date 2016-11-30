<?php
/**
 * Created by PhpStorm.
 * Author: Oleksii Danylevskyi <aleksey@danilevsky.com>
 * Date: 30.11.2016
 * Time: 18:45
 */

namespace odanylevskyi\sitemap\components;

use yii\helpers\Url;

class SitemapHelper
{
    public static function getSelect($urls, $tableName) {
        $select = [];
        foreach ($urls as $url) {
            $select = array_merge($select, self::getAttributes($url, $tableName));
        }
        return array_unique($select);
    }

    public static function getAttributes($url, $tableName) {
        $attributes = [];
        foreach ($url as $key => $attribute) {
            if ($key !== 0 && strcmp($key, 'url') != 0) {
                $attributes[] = $tableName.'.'.trim($attribute, ':');
            }
        }
        return $attributes;
    }

    public static function buildUrl($url, $model = null) {
        if(is_array($url)) {
            $url[0] = "/{$url[0]}";
            if ($model) {
                foreach($url as $key => $value) {
                    if ($key !== 0 && $key != 'url') {
                        $url[$key] = $model->{trim($value, ':')};
                    }
                }
            }
            return Url::to($url, true);
        }
        return Url::to(["/{$url}"], true);
    }
}