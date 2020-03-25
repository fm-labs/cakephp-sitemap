<?php
declare(strict_types=1);

namespace Sitemap\Sitemap;

interface SitemapProviderInterface
{
    /**
     * @return array
     */
    public function getSitemapLocations();
}
