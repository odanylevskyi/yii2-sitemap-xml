<?php
/**
 * Author: Oleksii Danylevskyi <aleksey@danilevsky.com>
 * Date: 30.11.2016
 * Time: 19:24
 */

namespace odanylevskyi\sitemap\components;

use XMLWriter;

trait SitemapTrait
{
    /**
     *
     * @var XMLWriter
     */
    private $writer;
    //Path to the directory where sitemap files will be located
    private $path = '@app/web/sitemap/';
    private $filename = 'sitemap';
    //Full path of sitemap file including the filename
    private $fullpath;

    /**
     * Returns XMLWriter object instance
     *
     * @return XMLWriter
     */
    public function getWriter() {
        return $this->writer;
    }
    /**
     * Assigns XMLWriter object instance
     *
     * @param XMLWriter $writer
     */
    private function setWriter(\XMLWriter $writer) {
        $this->writer = $writer;
    }
    /*
     * Set full path of sitemap file
     * Check if sitemap-index file exists then set fullpath to the sitemap-index path
     * otherwise set fullpath as sitemap file
     *
     */
    public function setOutputFile() {
        $file = $this->getPath().$this->getFilename().SitemapConstants::SEPERATOR;
        if (file_exists($file.SitemapConstants::INDEX_SUFFIX.SitemapConstants::EXT)) {
            $this->setFullpath($file.SitemapConstants::INDEX_SUFFIX.SitemapConstants::EXT);
        } else {
            $this->setFullpath($file.$this->getCurrentSitemap() . SitemapConstants::EXT);
        }
    }
    /**
     * Returns path of sitemaps
     *
     * @return string
     */
    public function getPath() {
        return \Yii::getAlias($this->path);
    }
    /**
     * Sets paths of sitemaps
     *
     * @param string $path
     * @return Sitemap
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }
    /**
     * Returns filename of sitemap file
     *
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }
    /**
     * Sets filename of sitemap file
     *
     * @param string $filename
     * @return Sitemap
     */
    public function setFilename($filename) {
        $this->filename = $filename;
        return $this;
    }
    /**
     * Sets full path of sitemap file
     *
     * @param string $filename
     */
    public function setFullpath($fullpath) {
        $this->fullpath = $fullpath;
    }
    /**
     * Returns full path of sitemap file
     *
     * @return string
     */
    public function getFullpath() {
        return $this->fullpath;
    }

    /**
     * Prepares given date for sitemap
     *
     * @param string $date Unix timestamp or any English textual datetime description
     * @return string Year-Month-Day formatted date.
     */
    private function getLastModifiedDate($date) {
        if (ctype_digit($date)) {
            return date('Y-m-d', $date);
        } else {
            $date = strtotime($date);
            return date('Y-m-d', $date);
        }
    }

    /**
     * Check file expiration date
     *
     * @param integer $expire - time in seconds
     * @return boolean true if file time is expired, false otherwise
     */
    public function isFileDateExpired($expire = 0) {
        $file = $this->getPath().$this->getFilename().SitemapConstants::SEPERATOR.SitemapConstants::INDEX_SUFFIX.SitemapConstants::EXT;
        if (file_exists($file) && !is_dir($file)) {
            return time() > filemtime($file) + $expire + 0;
        }
        $file = $this->getPath().$this->getFilename().'-0.xml';
        if (file_exists($file) && !is_dir($file)) {
            return time() > filemtime($file) + $expire + 0;
        }
        return true;
    }
}
