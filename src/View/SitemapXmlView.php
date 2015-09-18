<?php
namespace Sitemap\View;

use Cake\Network\Exception\NotFoundException;
use Cake\View\View;
use Sitemap\Controller\Component\SitemapComponent;

class SitemapXmlView extends View
{
    public function render($view = null, $layout = null)
    {
        $this->viewPath = 'Sitemap';
        $this->subDir = 'xml';

        if ($view === null) {
            $type = $this->get('type', SitemapComponent::TYPE_SITEMAP);
            $view = 'Sitemap.' . $type;
        }

        //$sitemap = $this->get('locations');
        //if (!$sitemap) {
        //    throw new NotFoundException('Sitemap not initalized');
        //}
        return parent::render($view, false);
    }
} 