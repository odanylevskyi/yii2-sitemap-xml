<?php
/**
 * Author: Oleksii Danylevskyi <aleksey@danilevsky.com>
 * Date: 30.11.2016
 * Time: 18:45
 */

namespace odanylevskyi\sitemap\components;

use yii\helpers\Url;

class SitemapHelper
{
    /*
     * Return selected attributes in url.
     * This function should be used just to avoid getting all the information from database
     *
     * @param array $urls  - contains url and sitemap spec information
     * @param string $tableName - model table name
     *
     * @return array - list of attributes that should be selected from database
     */
    public static function getSelect($urls, $tableName) {
        $select = [];
        foreach ($urls as $url) {
            $url = count($url) > 1 && isset($url['path']) ? $url['path'] : $url;
            $select = array_merge($select, self::getAttributes($url, $tableName));
        }
        return array_unique($select);
    }

    /*
     * Return selected attributes in url.
     *
     * @param array $url  - contains only url information
     * @param string $tableName - model table name
     *
     * @return array - list of attributes that should be selected from database for specific url
     */
    public static function getAttributes($url, $tableName) {
        $attributes = [];
        foreach ($url as $key => $attribute) {
            if ($key !== 0 && strcmp($key, 'url') != 0 && strpos($attribute, ":") !== false) {
                $attributes[] = $tableName.'.'.trim($attribute, ':');
            }
        }
        return $attributes;
    }

    /*
     * Build sitemap url
     *
     * @param mixed - url information
     * @param object - model object
     *
     * @return string - url for sitemap.xml
     */
    public static function buildUrl($url, $model = null) {
        if(is_array($url)) {
            $url[0] = "/{$url[0]}";
            if ($model) {
                foreach($url as $key => $value) {
                    if ($key !== 0 && $key != 'url' && !is_int($value)) {
                        $url[$key] = $model->{trim($value, ':')};
                    } else {
                        $url[$key] = $value;
                    }
                }
            }
            return Url::to($url, true);
        }
        return Url::to(["/{$url}"], true);
    }
}
