<?php

namespace odanylevskyi\sitemap\components;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\helpers\FileHelper;

/**
 * Author: Oleksii Danylevskyi <aleksey@danilevsky.com>
 * Date: 27.11.2016
 * Time: 14:31
 *
 * Sitemap
 *
 */
class Sitemap {
    use SitemapTrait;

    private $domain;
    private $current_item = 0;
    private $current_sitemap = 0;
    private $index = null;

    /**
     *
     * @param string $domain
     */
    public function __construct($domain = '') {
        $this->setDomain($domain);
    }
    /**
     * Prepares sitemap XML document
     *
     */
    public function init() {
        if (!file_exists($this->getPath()) && !is_dir($this->getPath())) {
            FileHelper::createDirectory($this->getPath());
        }
        $this->setWriter(new \XMLWriter());
        $this->setFullpath(
          $this->getPath() .
          $this->getFilename() .
          SitemapConstants::SEPERATOR .
          $this->getCurrentSitemap() .
          SitemapConstants::EXT
        );
        $this->getWriter()->openURI($this->fullpath);

        $this->getWriter()->startDocument('1.0', 'UTF-8');
        $this->getWriter()->setIndent(true);
        $this->getWriter()->startElement('urlset');
        $this->getWriter()->writeAttribute('xmlns', SitemapConstants::SCHEMA);
    }
    /**
     * Adds an item to sitemap
     *
     * @param string $loc URL of the page. This value must be less than 2,048 characters.
     * @param string $priority The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0.
     * @param string $changefreq How frequently the page is likely to change. Valid values are always, hourly, daily, weekly, monthly, yearly and never.
     * @param string|int $lastmod The date of last modification of url. Unix timestamp or any English textual datetime description.
     * @return Sitemap
     */
    public function addItem($loc, $priority = SitemapConstants::DEFAULT_PRIORITY, $changefreq = SitemapConstants::CHANGEFREQ_MONTHLY, $lastmod = NULL) {
        if (($this->getCurrentItem() % SitemapConstants::ITEM_PER_SITEMAP) == 0) {
            if ($this->getWriter() instanceof \XMLWriter) {
                $this->save();
            }
            $this->init();
            $this->incCurrentSitemap();
        }
        $this->incCurrentItem();
        $this->getWriter()->startElement('url');
        $this->getWriter()->writeElement('loc', $this->getDomain() . $loc);
        $this->getWriter()->writeElement('priority', $priority);
        if ($changefreq)
            $this->getWriter()->writeElement('changefreq', $changefreq);
        if ($lastmod)
            $this->getWriter()->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
        $this->getWriter()->endElement();
        return $this;
    }
    /**
     * Finalizes tags of sitemap XML document.
     *
     */
    public function save() {
        if (!$this->getWriter()) {
            $this->init();
        }
        $this->getWriter()->endElement();
        $this->getWriter()->endDocument();
    }

    /*
     * Output sitemap or sitamp-index file to the client
     */
    public function output() {
        if (!$this->fullpath) {
            $this->setOutputFile();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        \Yii::$app->response->headers->add('Content-Type', 'application/xml');
        return file_get_contents($this->index ? $this->index->getFullpath() : $this->getFullpath());
    }

    /*
     * Prepare and create SitemapIndex object for current sitemap files
     */
    public function getIndex() {
        if(!$this->index) {
            $this->index = new SitemapIndex();
        }
        $this->index->setFullpath(
          $this->getPath() .
          $this->getFilename() .
          SitemapConstants::SEPERATOR .
          SitemapConstants::INDEX_SUFFIX .
          SitemapConstants::EXT
        );
        return $this->index;
    }
    
    /*
     * Create sitemap-index file and current sitemap files to it
     */
    public function createIndex() {
        $this->save();
        $this->getIndex()->setPath($this->getPath());
        $this->getIndex()->init();
        for ($index = 0; $index < $this->getCurrentSitemap(); $index++) {
            $this->getIndex()->addItem(Url::base(true).'/sitemap/', $index);
        }
        $this->getIndex()->save();
        return $this->getIndex();
    }

    /*
     * Remove sitemap files from sitemap directory
     */
    public function clear() {
        array_map('unlink', glob("{$this->getPath()}/*.*"));
    }

    /**
     * Sets root path of the website, starting with http:// or https://
     *
     * @param string $domain
     */
    public function setDomain($domain) {
        $this->domain = $domain;
        return $this;
    }
    /**
     * Returns root path of the website
     *
     * @return string
     */
    private function getDomain() {
        return $this->domain;
    }

    /**
     * Returns current item count
     *
     * @return int
     */
    private function getCurrentItem() {
        return $this->current_item;
    }
    /**
     * Increases item counter
     *
     */
    private function incCurrentItem() {
        $this->current_item = $this->current_item + 1;
    }
    /**
     * Returns current sitemap file count
     *
     * @return int
     */
    public function getCurrentSitemap() {
        return $this->current_sitemap;
    }
    /**
     * Increases sitemap file count
     *
     */
    private function incCurrentSitemap() {
        $this->current_sitemap = $this->current_sitemap + 1;
    }
}
