<?php
namespace Sitemap\View;

use Cake\Network\Exception\NotFoundException;
use Cake\View\View;

class SitemapXmlView extends View
{
    public function render($view = null, $layout = null)
    {
        $this->viewPath = 'Sitemap';
        $this->subDir = 'xml';

        //$sitemap = $this->get('locations');
        //if (!$sitemap) {
        //    throw new NotFoundException('Sitemap not initalized');
        //}
        return parent::render($view, false);
    }
} 