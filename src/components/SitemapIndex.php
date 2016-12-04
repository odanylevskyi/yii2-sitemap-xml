<?php
/**
 * Author: Oleksii Danylevskyi <aleksey@danilevsky.com>
 * Date: 27.11.2016
 * Time: 18:44
 */

namespace odanylevskyi\sitemap\components;


class SitemapIndex
{
    use SitemapTrait;

    /**
     * Prepares sitemap index XML document
     *
     */
    public function init() {
        $this->writer = new \XMLWriter();
        $this->setFullpath(
          $this->getPath() .
          $this->getFilename() .
          SitemapConstants::SEPERATOR .
          SitemapConstants::INDEX_SUFFIX .
          SitemapConstants::EXT
        );
        $this->writer->openURI($this->fullpath);
        $this->writer->startDocument('1.0', 'UTF-8');
        $this->writer->setIndent(true);
        $this->writer->startElement('sitemapindex');
        $this->writer->writeAttribute('xmlns', SitemapConstants::SCHEMA);
    }

    /**
     * Writes Google/Yandex sitemap index for generated sitemap files
     *
     * @param string $loc Accessible URL path of sitemaps
     * @param integer $index index of sitemap
     * @param string|int $lastmod The date of last modification of sitemap. Unix timestamp or any English textual datetime description.
     *
     */
    public function addItem($loc, $index, $lastmod = 'Today') {
        $this->writer->startElement('sitemap');
        $this->writer->writeElement(
            'loc',
            $loc . $this->getFilename() . SitemapConstants::SEPERATOR . ($index ? $index : 0) . SitemapConstants::EXT
        );
        $this->writer->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
        $this->writer->endElement();
    }

    /**
     * Finalizes tags of sitemap index XML document.
     *
     */
    public function save() {
        $this->writer->endElement();
        $this->writer->endDocument();
    }
}
