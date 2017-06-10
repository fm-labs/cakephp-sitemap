<?php

namespace Sitemap\Exception;

use Cake\Core\Exception\Exception;

class InvalidSitemapProviderException extends Exception
{

    protected $_messageTemplate = 'Sitemap provider class %s does not implemente the SitemapProviderInterface';
}
