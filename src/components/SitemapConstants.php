<?php
/**
 * Created by PhpStorm.
 * Author: Oleksii Danylevskyi <aleksey@danilevsky.com>
 * Date: 30.11.2016
 * Time: 19:25
 */

namespace odanylevskyi\sitemap\components;


abstract class SitemapConstants
{
    const EXT = '.xml';
    const SCHEMA = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    const DEFAULT_PRIORITY = 0.5;
    const ITEM_PER_SITEMAP = 50000;
    const SEPERATOR = '-';

    const CHANGEFREQ_ALWAYS  = 'always';
    const CHANGEFREQ_HOURLY  = 'hourly';
    const CHANGEFREQ_DAILY   = 'daily';
    const CHANGEFREQ_WEEKLY  = 'weekly';
    const CHANGEFREQ_MONTHLY = 'monthly';
    const CHANGEFREQ_YEARLY  = 'yearly';
    const CHANGEFREQ_NEVER   = 'never';
}