<?php

namespace Sitemap\Sitemap;


interface SitemapProviderInterface
{
    /**
     * @return array
     */
    public function getSitemapLocations();
}