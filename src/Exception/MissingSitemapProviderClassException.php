<?php

namespace Sitemap\Exception;

use Cake\Core\Exception\Exception;

class MissingSitemapProviderClassException extends Exception
{

    protected $_messageTemplate = 'Sitemap provider class %s could not be found.';
}
