<?php
/**
 * Created by PhpStorm.
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
    private $path;
    private $filename = 'sitemap';
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
    private function setWriter(XMLWriter $writer) {
        $this->writer = $writer;
    }
    /**
     * Returns path of sitemaps
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
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

    private function setFullpath($fullpath) {
        $this->fullpath = $fullpath;
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
}