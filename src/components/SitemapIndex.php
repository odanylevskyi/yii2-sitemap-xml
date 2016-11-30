<?php
/**
 * Created by PhpStorm.
 * Author: Oleksii Danylevskyi <aleksey@danilevsky.com>
 * Date: 27.11.2016
 * Time: 18:44
 */

namespace odanylevskyi\sitemap\components;

use Yii;

class SitemapIndex
{
    use SitemapTrait;

    /**
     * Writes Google sitemap index for generated sitemap files
     *
     * @param string $loc Accessible URL path of sitemaps
     * @param string|int $lastmod The date of last modification of sitemap. Unix timestamp or any English textual datetime description.
     */
    public function __construct() {
        $this->init();
    }

    private function init() {
        $this->writer = new XMLWriter();
        $this->setFullpath($this->getPath() . $this->getFilename() . self::SEPERATOR . self::INDEX_SUFFIX . self::EXT);
        $this->writer->openURI($this->fullpath);
        $this->writer->startDocument('1.0', 'UTF-8');
        $this->writer->setIndent(true);
        $this->writer->startElement('sitemapindex');
        $this->writer->writeAttribute('xmlns', self::SCHEMA);
    }


    public function addItem($loc, $index, $lastmod = 'Today') {
        $this->writer->startElement('sitemap');
        $this->writer->writeElement('loc', $loc .'/sitemap/'. $this->getFilename() . ($index ? self::SEPERATOR . $index : '') . self::EXT);
        $this->writer->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
        $this->writer->endElement();
    }

    public function save() {
        $this->writer->endElement();
        $this->writer->endDocument();
    }
    
    public function output() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'application/xml');
        return file_get_contents($this->fullpath);
    }
}