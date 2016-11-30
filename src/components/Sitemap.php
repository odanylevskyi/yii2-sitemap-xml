<?php

namespace odanylevskyi\sitemap\components;

use XMLWriter;
use Yii;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
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

    public $index;

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
        $this->setWriter(new XMLWriter());

        if ($this->getCurrentSitemap()) {
            $this->setFullpath($this->getPath() . $this->getFilename() . SitemapConstants::SEPERATOR . $this->getCurrentSitemap() . SitemapConstants::EXT);
        } else {
            $this->setFullpath($this->getPath() . $this->getFilename() . SitemapConstants::EXT);
        }
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
            if ($this->getWriter() instanceof XMLWriter) {
                $this->endSitemap();
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
    private function endSitemap() {
        if (!$this->getWriter()) {
            $this->startSitemap();
        }
        $this->getWriter()->endElement();
        $this->getWriter()->endDocument();
    }

    public function save() {
        $this->endSitemap();
    }

    public function output($enableGzip = false) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'application/xml');
        return file_get_contents($this->fullpath);
    }

    public function createIndex() {
        $this->index = new SitemapIndex();
        for ($index = 0; $index < $this->getCurrentSitemap(); $index++) {
            $this->index->addItem(Url::base(true), $index);
        }
        $this->index->save();
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
